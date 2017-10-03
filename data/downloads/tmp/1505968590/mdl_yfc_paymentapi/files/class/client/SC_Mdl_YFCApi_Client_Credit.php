<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Base_Ex.php');
// }}}
/**
 * 決済モジュール 決済処理: クレジットカード
 */
class SC_Mdl_YFCApi_Client_Credit extends SC_Mdl_YFCApi_Client_Base_Ex {

    /**
     * 3Dセキュア実行を行う.
     * @param $arrOrder
     * @param $arrParam
     * @param $arrPaymentInfo
     * @return bool
     */
    function do3dTran($arrOrder, $arrParam, $arrPaymentInfo) {

        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();

        //決済データ確認
        if (SC_Utils_Ex::isBlank($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA])) {
            $msg = '3Dセキュア認証遷移エラー:決済データが受注情報に見つかりませんでした.';
            $objMdl->printLog($msg);
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />3Dセキュア認証遷移エラー<br />この手続きは無効となりました。<br />決済データが受注情報に見つかりませんでした。");
            SC_Response_Ex::actionExit();
        } else {
            $arrPayData = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array(($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA]));
        }

        //取引ID確認
        if ($arrPayData['order_no'] != $arrOrder['order_id']) {
            $msg = '3Dセキュア認証遷移エラー:取引IDが一致しませんでした。(' .$arrPayData['order_no'] . ':' .  $arrOrder['order_id'] . ')';
            $objMdl->printLog($msg);
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />3Dセキュア認証遷移エラー<br />この手続きは無効となりました。<br />取引IDが一致しませんでした。");
            SC_Response_Ex::actionExit();
        }

        $arrMdlSetting = $objMdl->getUserSettings();

        $server_url = $this->getApiUrl('A02');

        $arrSendKey = array(
            'function_div',
            'trader_code',
            'order_no',
            'comp_cd',
            'card_no',
            'card_exp',
            'item_price',
            'item_tax',
            'cust_cd',
            'shop_id',
            'term_cd',
            'crd_res_cd',
            'res_ve',
            'res_pa',
            'res_code',
            'three_d_inf',
            'three_d_tran_id',
            'send_dt',
            'hash_value',
            'three_d_token');

        //決済ステータスを「3Dセキュア認証中」で記録
        $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_3D_WAIT;
        //機能区分
        $arrParam['function_div'] = 'A02';
        //カード番号
        $arrParam['card_no'] = $arrParam['CARD_NO'];
        //有効期限
        $arrParam['card_exp'] = $arrParam['CARD_EXP'];
        //3Dトークン
        $arrParam['threeDToken'] = $arrPayData['threeDToken'];

        return $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
    }

    /**
     * 決済実行を行う.
     * @param array $arrOrder
     * @param array $arrParam
     * @param array $arrPaymentInfo
     * @return bool
     */
    function doPaymentRequest($arrOrder, $arrParam, $arrPaymentInfo) {

        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //決済データ設定
        if (!SC_Utils_Ex::isBlank($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA])) {
            $arrPayData = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array(($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA]));
            $arrOrder = array_merge($arrOrder, $arrPayData);
        }
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
            'option_service_div');

        //機能区分
        $arrParam['function_div'] = 'A01';
        //決済ステータスを「決済手続き中」で記録
        $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_WAIT;
        //オプションサービス区分 00：通常受注 01：オプションサービス受注
        //(条件)
        //オプションサービス契約済み ：use_option==0(必須)
        //購入時カード預かり         ：register_card==1
        //預かりカードでの購入       ：use_registed_card==1
        //予約商品購入               ：tpl_is_reserve==true
        if($arrMdlSetting['use_option'] == '0' && ($arrParam['register_card'] == '1' || $arrParam['use_registed_card'] == '1' || $arrParam['tpl_is_reserve'] == true)) {
            $arrParam['option_service_div'] = '01';
        } else {
            $arrParam['option_service_div'] = '00';
        }
        //支払方法設定：本人認証サービス(3Dセキュア) 0:利用しない 1:利用する
        $arrParam['auth_div'] = ($arrPaymentInfo['TdFlag'] == '0') ? '2' : '1';
        //3Dセキュア未加入・迂回時(3Dセキュア未加入/デバイスPC[デバイスコード:2]以外の場合)
        if($arrParam['info_use_threeD'] == MDL_YFCAPI_3D_EXCLUDED
           || SC_Util_Mdl_YFCApi_Ex::getDeviceCode() != 2) {
            $arrParam['auth_div'] = '2';
        }

        //カード情報
        //オプションサービス区分が「00:通常受注」、または「01:オプションサービス受注」かつお預かりカード登録時
        if($arrParam['option_service_div'] == '00'
         || ($arrParam['option_service_div'] == '01' && $arrParam['register_card'] =='1')){
            $arrSendKey[] = 'card_code_api';                //カード会社コード(API用)
            $arrSendKey[] = 'card_no';                      //カード番号
            $arrSendKey[] = 'card_owner';                   //カード名義人
            $arrSendKey[] = 'card_exp';                     //カード有効期限
        }
        //セキュリティコード
        //認証区分が「2：３Ｄセキュアなし、セキュリティコード認証あり」
        if($arrParam['auth_div'] == '2') {
            $arrSendKey[] = 'security_code';                //セキュリティコード
        }
        //加盟店ECサイトURL
        //認証区分が「1：３Ｄセキュアあり、セキュリティコード認証なし」
        if($arrParam['auth_div'] == '1') {
            $arrSendKey[] = 'trader_ec_url';                //加盟点ECサイトURL
            $arrParam['trader_ec_url'] = SC_Utils_Ex::sfRmDupSlash(MDL_YFCAPI_TRADER_URL);
            //決済ステータスを「3Dセキュア認証中」で記録
            $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_3D_WAIT;
        }
        //オプションサービス区分が「01:オプションサービス受注」
        if($arrParam['option_service_div'] == '01') {
            $arrSendKey[] = 'member_id';                     //会員ＩＤ
            $arrSendKey[] = 'authentication_key';            //認証キー
            $arrSendKey[] = 'check_sum';                     //チェックサム
            $arrParam['member_id'] = SC_Util_Mdl_YFCApi_Ex::getMemberId($arrOrder['customer_id']);
            $arrParam['authentication_key'] = SC_Util_Mdl_YFCApi_Ex::getAuthenticationKey($arrOrder['customer_id']);
            $arrParam['check_sum'] = SC_Util_Mdl_YFCApi_Ex::getCheckSum($arrParam, $arrMdlSetting);
        }
        //オプションサービスで登録されているクレジットカード情報を利用
        if($arrParam['option_service_div'] == '01' 
        && $arrParam['use_registed_card'] =='1'){
            $arrSendKey[] = 'card_key';                      //カード識別キー
            $arrSendKey[] = 'last_credit_date';              //最終利用日時
            //最終利用日時をセット
            $arrParam['lastCreditDate'] = $this->lfGetLastCreditDate($arrOrder['customer_id'],$arrParam['card_key']);
        }
        //予約商品購入の場合
        if($arrParam['option_service_div'] == '01'
         && $arrParam['tpl_is_reserve'] == true){
            $arrSendKey[] = 'scheduled_shipping_date';       //出荷予定日
            //出荷予定日取得
            $scheduled_shipping_date = SC_Util_Mdl_YFCApi_Ex::getMaxScheduledShippingDate($arrOrder['order_id']);
            //パラメータ用に整形
            $arrParam['scheduled_shipping_date'] = SC_Util_Mdl_YFCApi_Ex::getFormatedDate($scheduled_shipping_date);
        }

        return $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
    }
    /**
     * 使用カードの最終利用日取得
     * @param integer $customer_id
     * @param integer $card_key
     * @return string $last_credit_date
     */
    function lfGetLastCreditDate($customer_id,$card_key){
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //お預かり情報照会
        $objClient = new SC_Mdl_YFCApi_Client_Util_Ex();
        $result = $objClient->doGetCard($customer_id);
        if(!$result) {
            $objMdl->printLog($objClient->getError());
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />お預かり照会エラー<br />この手続きは無効となりました。<br />");
            SC_Response_Ex::actionExit();
        }
        $arrResults = $this->lfGetArrCardInfo($objClient->getResults());
        return $arrResults['cardData'][($card_key - 1)]['lastCreditDate'];
    }

    /**
     * カード預かり情報取得（整理済）
     *
     * 預かり情報１件の場合と２件以上の場合で配列の構造を合わせる
     * @param array $arrCardInfos
     * @return array $arrResults
     */
    function lfGetArrCardInfo($arrCardInfos = array()) {
        if (isset($arrCardInfos['cardUnit']) && $arrCardInfos['cardUnit'] == '1') {
            $arrTmp = array();
            foreach ($arrCardInfos as $key => $val) {
                if($key == 'cardData') {
                    $arrTmp[$key][0] = $val;
                } else {
                    $arrTmp[$key] = $val;
                }
            }
            $arrResults = $arrTmp;
        } else {
            $arrResults = $arrCardInfos;
        }
        
        return $arrResults;
    }
}
