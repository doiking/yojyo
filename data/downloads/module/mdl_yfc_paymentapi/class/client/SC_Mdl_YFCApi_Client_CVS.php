<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Base_Ex.php');
// }}}
/**
 * 決済モジュール 決済処理: コンビニ決済
 */
class SC_Mdl_YFCApi_Client_CVS extends SC_Mdl_YFCApi_Client_Base_Ex {

    /**
     * 決済実行を行う.
     * @param array $arrOrder
     * @param array $arrParam
     * @param array $arrPaymentInfo
     * @return bool
     */
    function doPaymentRequest($arrOrder, $arrParam, $arrPaymentInfo) {

        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        //支払方法から各種決済のfunction_divを取得
        $functionDiv = SC_Util_Mdl_YFCApi_Cache_Ex::getCode('cvs_function_div', $arrParam['cvs']);
        $server_url = $this->getApiUrl($functionDiv);

        $arrSendKey = array(
            'function_div',
            'trader_code',
            'device_div',
            'order_no',
            'goods_name',
            'settle_price',
            'buyer_name_kanji',
            'buyer_name_kana',
            'buyer_tel',
            'buyer_email');

        //機能区分
        $arrParam['function_div'] = $functionDiv;
        //決済ステータスを「決済手続き中」で記録
        $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_WAIT;

        return $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
    }
}
