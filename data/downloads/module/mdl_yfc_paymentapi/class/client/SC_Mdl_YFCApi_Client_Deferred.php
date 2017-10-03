<?php
/**
 * Copyright(c)2015, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Base_Ex.php');
// }}}
/**
 * 決済モジュール 決済処理: 後払い
 */
class SC_Mdl_YFCApi_Client_Deferred extends SC_Mdl_YFCApi_Client_Base_Ex {

    /**
     * 決済実行を行う.
     * @param array $arrOrder
     * @param array $arrParam
     * @param array $arrPaymentInfo
     * @return bool
     */
    function doPaymentRequest($arrOrder, $arrParam, $arrPaymentInfo)
    {
        //API設定
        $server_url = $this->getApiUrl('KAARA0010APIAction');

        return $this->lfSendOrderRequest(
            $server_url,
            $arrOrder,
            $arrParam,
            $arrPaymentInfo
        );
    }

    /**
     * 注文情報リクエスト
     * @param string $url API URL
     * @param array $arrOrder 注文情報
     * @param array $arrParam その他パラメタ
     * @param array $arrPaymentInfo 支払方法設定
     * @return bool
     */
    function lfSendOrderRequest($url, $arrOrder, $arrParam, $arrPaymentInfo) {
        $arrSendData = $arrParam;
        //リクエスト送信
        $ret = $this->sendRequest($url, $arrSendData);
        if ($ret) {
            $arrParam = (array)$this->getResults();
        }else {
            $arrParam = array();
            $arrParam['error'] = $this->getError();
        }
        //決済情報設定
        $arrParam['order_no'] = $arrSendData['orderNo'];

        if (!SC_Utils_Ex::isBlank($arrParam['result'])) {
            //審査結果
            $arrParam['result_code'] = $arrParam['result'];
        }
        //取引状況
        if (!SC_Utils_Ex::isBlank($arrParam['result']) && $arrParam['result'] == MDL_YFCAPI_DEFERRED_AVAILABLE) {
            $arrParam['action_status'] = MDL_YFCAPI_DEFERRED_AUTH_OK;
        }
        //決済金額総計
        if (!SC_Utils_Ex::isBlank($arrSendData['totalAmount'])) {
            $arrParam['totalAmount'] = $arrSendData['totalAmount'];
        }

        //決済ログの記録
        SC_Util_Mdl_YFCApi_Ex::setOrderPayData($arrOrder, $arrParam);

        //審査結果判定
        if ($arrParam['result'] != '0') {
            //ご利用可以外
            return false;
        }

        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        // 成功時のみ表示用データの構築
        $this->setOrderPaymentViewData($arrOrder, $arrPaymentInfo);
        return true;
    }
}
