<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */

// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Base_Ex.php');
// }}}

/**
 * 決済モジュール 決済処理: 各種取引処理
 */
class SC_Mdl_YFCApi_Client_Util extends SC_Mdl_YFCApi_Client_Base_Ex {

    /**
     * お預かり情報照会
     * @param integer $customer_id 顧客ID
     * @param array $arrParam パラメタ
     * @return bool
     */
    function doGetCard($customer_id, $arrParam=array()) {
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        
        //オプション未契約の場合はお預かり照会を行わない
        if($arrMdlSetting['use_option'] == '1') return false;
        //非会員の場合はお預かり照会を行わない
        if(SC_Utils_Ex::isBlank($customer_id) || $customer_id == '0') {
            return false;
        }
        //API設定
        $server_url = $this->getApiUrl('A03');
        //送信キー
        $arrSendKey = array(
            'function_div',
            'trader_code',
            'member_id',
            'authentication_key',
            'check_sum');

        //個別パラメタ
        $arrParam['function_div'] = 'A03';
        $arrParam['member_id'] = $customer_id;
        $arrParam['authentication_key'] = $customer_id;
        $arrParam['check_sum'] = SC_Util_Mdl_YFCApi_Ex::getCheckSum($arrParam, $arrMdlSetting);

        return $this->sendUtilRequest($server_url, $arrSendKey, array(), $arrParam, $arrMdlSetting);
    }

    /**
     * お預かり情報登録
     * @param integer $customer_id 顧客ID
     * @param array $arrParam パラメタ
     * @return bool
     */
    function doRegistCard($customer_id, $arrParam=array()) {
        //非会員の場合はお預かり情報登録を行わない
        if(SC_Utils_Ex::isBlank($customer_id) || $customer_id == '0') {
            return false;
        }
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //API設定
        $server_url = $this->getApiUrl('A01');
        //送信キー
        $arrSendKey = array(
            'function_div',
            'trader_code',
            'device_div',
            'order_no',
            'settle_price',
            'buyer_name_kanji',
            'buyer_tel',
            'buyer_email',
            'auth_div',
            'pay_way',
            'option_service_div',
            'card_code_api',
            'card_no',
            'card_owner',
            'card_exp',
            'security_code',
            'member_id',
            'authentication_key',
            'check_sum');

        //ダミー注文作成
        $arrOrder = $this->lfGetDummyOrder($customer_id);
        //個別パラメータ
        $arrParam['function_div']       = 'A01';
        $arrParam['auth_div']           = '2';      //3Dセキュア利用しない
        $arrParam['pay_way']            = '1';      //支払回数は強制的に「一括払い」
        $arrParam['option_service_div'] = '01';     //登録時のため 01:オプションサービス受注
        $arrParam['member_id']          = $customer_id;
        $arrParam['authentication_key'] = SC_Util_Mdl_YFCApi_Ex::getAuthenticationKey($customer_id);
        $arrParam['check_sum']          = SC_Util_Mdl_YFCApi_Ex::getCheckSum($arrParam, $arrMdlSetting);

        return $this->sendUtilRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrMdlSetting);
    }

    /**
     * お預かり情報削除
     * @param integer $customer_id 顧客ID
     * @param array $arrParam パラメタ
     * @return bool
     */
    function doDeleteCard($customer_id, $arrParam=array()) {
        //非会員の場合はお預かり情報削除を行わない
        if(SC_Utils_Ex::isBlank($customer_id) || $customer_id == '0') {
            return false;
        }
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //API設定
        $server_url = $this->getApiUrl('A05');
        //送信キー
        $arrSendKey = array(
            'function_div',
            'trader_code',
            'member_id',
            'authentication_key',
            'check_sum',
            'card_key',
            'last_credit_date');
        //個別パラメタ
        $arrParam['function_div'] = 'A05';
        $arrParam['member_id'] = $customer_id;
        $arrParam['authentication_key'] = $customer_id;
        $arrParam['check_sum'] = SC_Util_Mdl_YFCApi_Ex::getCheckSum($arrParam, $arrMdlSetting);
        $arrParam['card_key'] = $arrParam['cardKey'];   //預かり削除は事前に照会を行うため、照会レスポンスのキーを利用

        return $this->sendUtilRequest($server_url, $arrSendKey, array(), $arrParam, $arrMdlSetting);
    }

    /**
     * 出荷情報登録
     * 
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doShipmentEntry($arrOrder) {
        $ret = false;
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //決済設定情報取得
        $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($arrOrder['payment_id']);
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        //API設定
        $server_url = $this->getApiUrl('E01');
        //送信キー
        $arrSendKey = array(
            'function_div',
            'trader_code',
            'order_no',
            'slip_no');
        
        $arrParam = array();
        //個別パラメタ
        $arrParam['function_div'] = 'E01';
        
        //配送先情報取得
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrShipping = $objPurchase->getShippings($arrOrder['order_id']);
        //配送先ごとに出荷情報登録処理
        //処理成功分送り状番号保持用配列
        $arrSuccessSlip = array();
        foreach ($arrShipping as $shipping) {
            $arrParam['slip_no'] = $shipping['plg_yfcapi_deliv_slip'];
            $ret = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
            if($ret) {
                $arrSuccessSlip[] = $arrParam['slip_no'];
                //荷物問い合わせURL保持
                $this->lfRegistSlipUrl($shipping);
            } else {
                return array($ret, $arrSuccessSlip);
            }
        }
        
        //処理成功の場合、注文データの取引状況更新（精算確定待ちへ）
        if($ret) {
            SC_Util_Mdl_YFCApi_Ex::sfRegistOrderPayStatus($arrOrder['order_id'], MDL_YFCAPI_ACTION_STATUS_WAIT_SETTLEMENT);
        }
        return array($ret, $arrSuccessSlip);
    }

    /**
     * 出荷情報取消
     * 
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doShipmentCancel($arrOrder) {
        $ret = false;
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //クレジットカード決済以外は対象外
        if($arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] != MDL_YFCAPI_PAYID_CREDIT){
            $msg = '出荷情報取消に対応していない決済です。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return $ret;
        }
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //決済設定情報取得
        $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($arrOrder['payment_id']);
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        //API設定
        $server_url = $this->getApiUrl('E02');
        //送信キー
        $arrSendKey = array(
                'function_div',
                'trader_code',
                'order_no',
                'slip_no');
        
        $arrParam = array();
        //個別パラメタ
        $arrParam['function_div'] = 'E02';
        //配送先情報取得
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrShipping = $objPurchase->getShippings($arrOrder['order_id']);
        //配送先ごとに出荷情報取消処理
        foreach ($arrShipping as $shipping) {
            $arrParam['slip_no'] = $shipping['plg_yfcapi_deliv_slip'];
            $ret = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
            if(!$ret) return $ret;
        }
        
        //処理成功の場合、注文データの取引状況更新(与信完了へ)
        if($ret) {
            SC_Util_Mdl_YFCApi_Ex::sfRegistOrderPayStatus($arrOrder['order_id'], MDL_YFCAPI_ACTION_STATUS_COMP_AUTH);
        }
        return $ret;
    }

    /**
     * 出荷情報登録ロールバック
     * 
     * 出荷情報登録処理（複数配送）で失敗した際
     * それまでに成功した登録済送り状番号の取消処理をする
     * 
     * @param array $arrOrder 注文情報
     * @param array $arrSuccessSlip
     * @return void
     */
    function doRollbackCommit($arrOrder, $arrSuccessSlip) {
        //成功出荷情報が0件の場合は処理しない
        if(count($arrSuccessSlip) === 0) return;
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //決済設定情報取得
        $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($arrOrder['payment_id']);
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        //API設定
        $server_url = $this->getApiUrl('E02');
        //送信キー
        $arrSendKey = array(
                'function_div',
                'trader_code',
                'order_no',
                'slip_no');
        
        $arrParam = array();
        //個別パラメタ
        $arrParam['function_div'] = 'E02';
        //配送先ごとの処理
        foreach ($arrSuccessSlip as $slip) {
            $arrParam['slip_no'] = $slip;
            $ret = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        }
    }

    /**
     * 出荷予定日変更(予約商品購入のみ)
     * 
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doChangeDate($arrOrder) {
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //クレジットカード決済以外、予約商品未購入注文は対象外
        if($arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] != MDL_YFCAPI_PAYID_CREDIT && SC_Util_Mdl_YFCApi_Ex::isReservedOrder($arrOrder['$order_id'])){
            $msg = '出荷予定日変更に対応していない注文です。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //決済設定情報取得
        $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($arrOrder['payment_id']);
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        //API設定
        $server_url = $this->getApiUrl('E03');
        //送信キー
        $arrSendKey = array(
                'function_div',
                'trader_code',
                'order_no',
                'scheduled_shipping_date');
        
        $arrParam = array();
        //個別パラメタ
        $arrParam['function_div'] = 'E03';
        $arrParam['scheduled_shipping_date'] = SC_Util_Mdl_YFCApi_Ex::getFormatedDate($arrOrder['plg_yfcapi_scheduled_shipping_date']);
        
        return $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
    }

    /**
     * 決済取消(クレジット決済)
     * 
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doCancel($arrOrder) {
        $res = false;
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        
       if($arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] != MDL_YFCAPI_PAYID_CREDIT){
            $msg = '決済キャンセル・返品エラー：キャンセル・返品処理に対応していない決済です。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //決済設定情報取得
        $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($arrOrder['payment_id']);
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        //API設定
        $server_url = $this->getApiUrl('A06');
        //送信キー
        $arrSendKey = array(
                'function_div',
                'trader_code',
                'order_no');

        $arrParam = array();
        //機能区分
        $arrParam['function_div'] = 'A06';

        $res = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        
        //処理成功の場合、注文データの取引状況更新（取消へ）
        if($res) {
            SC_Util_Mdl_YFCApi_Ex::sfRegistOrderPayStatus($arrOrder['order_id'], MDL_YFCAPI_ACTION_STATUS_CANCEL);
        }
        return $res;
    }

    /**
     * 決済状況取得.
     * 
     * 対応状況の更新を実行
     * (1)レスポンスの処理結果が0件ではない
     * (2)レスポンスと注文番号が同じ
     * (3)レスポンス値と注文データの決済手段が同じ
     * 
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doGetOrderInfo($arrOrder) {
        $res = false;
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //決済設定情報取得
        $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($arrOrder['payment_id']);
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        $server_url = $this->getApiUrl('E04');
        //送信キー
        $arrSendKey = array(
                'function_div',
                'trader_code',
                'order_no');

        $arrParam = array();
        //機能区分
        $arrParam['function_div'] = 'E04';

        $res = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        
        //処理成功の場合、注文データの更新
        if($res) {
            //レスポンス値取得
            $arrResults = (array)$this->getResults();
            //注文データ更新処理（取引状況）
            $res = $this->lfUpdateGotOrderInfo($arrResults, $arrOrder);
        }
        return $res;
    }

    /**
     * 金額変更
     * 
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doChangePrice($arrOrder) {
        $ret = false;
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //クレジットカード決済以外は対象外
        if($arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] != MDL_YFCAPI_PAYID_CREDIT){
            $msg = '金額変更に対応していない決済です。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return $ret;
        }
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //オプションサービスを契約していない場合は対象外（0:契約済 1:未契約）
        if($arrMdlSetting['use_option'] != '0'){
            $msg = 'オプション契約が必要な機能になります。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return $ret;
        }
        //決済設定情報取得
        $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($arrOrder['payment_id']);
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        //API設定
        $server_url = $this->getApiUrl('A07');
        //送信キー
        $arrSendKey = array(
                'function_div',
                'trader_code',
                'order_no',
                'new_price');
        
        $arrParam = array();
        //機能区分
        $arrParam['function_div'] = 'A07';
        
        $res = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        
        //処理成功の場合、決済金額の更新をおこなう
        if($res){
            SC_Util_Mdl_YFCApi_Ex::sfUpdateOrderSettlePrice($arrOrder['order_id'], $arrOrder['payment_total']);
        }
        return $res;
    }

    /**
     * ダミー注文取得（カード情報登録用）.
     * 
     * @param integer $customer_id
     * @return array $arrOrder
     */
    function lfGetDummyOrder($customer_id) {
        $arrOrder = array();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerData($customer_id);

        //必須情報
        $arrOrder['order_id']      = $objPurchase->getNextOrderID();
        $arrOrder['payment_total'] = 1; //1円与信
        $arrOrder['order_name01']  = $arrCustomer['name01'];
        $arrOrder['order_name02']  = $arrCustomer['name02'];
        $arrOrder['order_tel01']   = $arrCustomer['tel01'];
        $arrOrder['order_tel02']   = $arrCustomer['tel02'];
        $arrOrder['order_tel03']   = $arrCustomer['tel03'];
        $arrOrder['order_email']   = $arrCustomer['email'];
        
        return $arrOrder;
    }

    /**
     * 注文データ更新（取引情報照会）
     * 
     * 取引情報照会で得られた情報を注文データへ更新する.
     * 条件は以下の通り
     * 
     * (1)照会レスポンス結果が0件ではない
     * (2)注文番号が同じ
     * (3)支払方法が同じ
     * 
     * 
     * 支払い方法は以下情報を利用
     * 
     * 【取引情報照会レスポンス】
     * 0:クレジットカード, 1:ネットコンビニ, 2:ネットバンク, 3:電子マネー
     * 
     * 【注文データ保持情報】
     * 10:クレジットカード決済
     * 30:コンビニ決済
     * 42～47：電子マネー決済
     * 52：ネットバンク決済
     * 
     * @param array $arrParam 取引情報照会レスポンス
     * @param array $arrOrder 注文情報
     * @return boolean
     */
    function lfUpdateGotOrderInfo($arrParam = array(), &$arrOrder) {
        $res = false;
        
        if(
            //処理結果が0件ではない
            $arrParam['resultCount'] > 0 &&
            //注文番号が同じ
            $arrParam['resultData']['orderNo'] == $arrOrder['order_id'] &&
            //支払方法が同じ
           (($arrParam['resultData']['settleMethodDiv'] == '0' && $arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] == MDL_YFCAPI_PAYID_CREDIT)  ||
            ($arrParam['resultData']['settleMethodDiv'] == '1' && $arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] == MDL_YFCAPI_PAYID_CVS)     ||
            ($arrParam['resultData']['settleMethodDiv'] == '2' && $arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] == MDL_YFCAPI_PAYID_NETBANK) ||
            ($arrParam['resultData']['settleMethodDiv'] == '3' && $arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] >= MDL_YFCAPI_PAYID_EDY && $arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] <= MDL_YFCAPI_PAYID_MOBILEWAON))
        ) {
            //取引状況更新
            $arrParam['action_status'] = $arrParam['resultData']['statusInfo'];
            SC_Util_Mdl_YFCApi_Ex::setOrderPayData($arrOrder, $arrParam);
            $res = true;
        }
        return $res;
    }

    /**
     * 荷物問い合わせURL更新
     * 
     * 出荷情報登録レスポンスで取得した荷物問い合わせURLをDBに保持
     * 
     * @param array $shipping
     * @return void
     */
    function lfRegistSlipUrl($shipping) {
        //荷物問い合わせURL取得
        $arrResults = $this->getResults();
        $slipurl = $arrResults['slipUrlPc'];
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        //荷物問い合わせURL更新
        $objQuery->begin();
        $updateval = array();
        $updateval['plg_yfcapi_deliv_slip_url'] = $slipurl;
        $objQuery->update('dtb_shipping', $updateval, 'shipping_id = ? AND order_id = ?', array($shipping['shipping_id'], $shipping['order_id']));
        $objQuery->commit();
    }
}
