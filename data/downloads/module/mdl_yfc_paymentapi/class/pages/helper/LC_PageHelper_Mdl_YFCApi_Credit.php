<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(MDL_YFCAPI_PAGE_HELPEREX_PATH . 'LC_PageHelper_Mdl_YFCApi_Base_Ex.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Credit_Ex.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Util_Ex.php');
// }}}
/**
 * 決済モジュール 決済画面ヘルパー：クレジット決済
 */
class LC_PageHelper_Mdl_YFCApi_Credit extends LC_PageHelper_Mdl_YFCApi_Base_Ex {

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param array $arrPaymentInfo モジュール設定情報
     * @param array $arrOrder 受注情報
     * @return void
     */
    function initParam(&$objFormParam, &$arrPaymentInfo, &$arrOrder) {
        $objFormParam->addParam("登録済お支払いカード", "card_key", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"), "");
        $objFormParam->addParam("カード番号", "card_no", MDL_YFCAPI_CREDIT_NO_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("カード有効期限年", "card_exp_year", 2, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("カード有効期限月", "card_exp_month", 2, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("カード名義人", "card_owner", MDL_YFCAPI_CARD_ORNER_LEN, 'a', array("MAX_LENGTH_CHECK", "SPTAB_CHECK"), "");
        $objFormParam->addParam("セキュリティコード", "security_code", MDL_YFCAPI_SECURITY_CODE_MAX_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"), "");
        $objFormParam->addParam("お支払い方法", "pay_way", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "GRAPH_CHECK"), "");
        $objFormParam->addParam("カード情報登録", "register_card", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"), "");
        $objFormParam->addParam("登録カード利用", "use_registed_card", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"), "");
    }

    /**
     * 入力内容のチェックを行なう.
     *
     * @param SC_FormParam_Ex  $objFormParam
     * @param array   $arrOrder 注文データ
     * @param string  $mode モード
     * @return array 入力チェック結果の配列
     */
    function checkError(&$objFormParam, $arrOrder, $mode = '') {
        $arrParam = $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrParam);
        $objErr->arrErr = $objFormParam->checkError();

        //必須チェックはユーザPOST、処理で分岐
        $objErr->arrErr = $this->lfExistCheckMode($objFormParam, $mode);

        //カード削除の場合は以下のチェック不要
        if($mode == 'deleteCard') return $objErr->arrErr;

        //登録済カード利用でない場合
        if (SC_Utils_Ex::isBlank($objErr->arrErr) && $arrParam['use_registed_card'] != '1') {
            //カード桁数チェック
            if (strlen($arrParam['card_no']) < 10 || strlen($arrParam['card_no']) > 16) {
                $objErr->arrErr['card_no'] = '※ カード番号の桁数が足りません。<br />';
            }
            //カード名義人チェック
            if(preg_match('/^[A-Z]+\s[A-Z]+$/', $arrParam['card_owner']) !== 1) {
                $objErr->arrErr['card_owner'] = '※ カード名義人は半角英字(大文字)で、姓名の間に半角スペースを入力してください。<br />';
            }
            //有効期限チェック
            if (strtotime('-1 month') > strtotime('20' . $arrParam['card_exp_year'] . '/' . $arrParam['card_exp_month'] . '/1')) {
                $objErr->arrErr['card_exp_year'] = '※ 有効期限が過ぎたカードは利用出来ません。<br />';
            }
        }

        //予約商品購入の場合
        if(SC_Utils_Ex::isBlank($objErr->arrErr) && SC_Util_Mdl_YFCApi_Ex::isReservedOrder($arrOrder['order_id'])) {
            //オプションサービス契約＋予約販売利用が必要
            $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
            $arrMdlSetting = $objMdl->getUserSettings();
            if($arrMdlSetting['use_option'] != '0' || $arrMdlSetting['advance_sale'] != '0') {
                $objErr->arrErr['payment'] = '※ 現在のご契約内容では予約商品販売は行えません。大変お手数をおかけいたしますが店舗運営者までお問い合わせくださいませ。<br />';
            }
            //登録済カード利用もしくはカード情報登録が必須(register_card = 1, use_registed_card = 1)
            if(SC_Utils_Ex::isBlank($objErr->arrErr) && $arrParam['register_card'] != '1' && $arrParam['use_registed_card'] != '1') {
                $objErr->arrErr['payment'] = '※ 予約商品購入はカード情報お預かり、もしくは登録済カード情報でのご購入が必要です。<br />';
            }
        }

        if(SC_Utils_Ex::isBlank($objErr->arrErr)) {
            //セキュリティコードチェック
            if (!SC_Utils_Ex::isBlank($arrParam['security_code']) &&
            (strlen($arrParam['security_code']) < MDL_YFCAPI_SECURITY_CODE_MIN_LEN
            || strlen($arrParam['security_code']) > MDL_YFCAPI_SECURITY_CODE_MAX_LEN)) {
                $objErr->arrErr['security_code'] = '※ セキュリティコードの桁数が足りません。<br />';
            }
        }

        return $objErr->arrErr;
    }

    /**
     * 画面モード毎のアクションを行う
     *
     * @param string $mode Mode値
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param array $arrOrder 受注情報
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @return void
     */
    function modeAction($mode, &$objFormParam, &$arrOrder, &$objPage) {
        //マスタ取得
        $objDate = new SC_Date_Ex(date('Y'), date('Y') + 15);
        $objPage->arrYear = $objDate->getZeroYear();
        $objPage->arrMonth = $objDate->getZeroMonth();

        //支払回数設定
        $arrPayMethod = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('pay_method');
        $this->lfSetPayMethod($objPage, $arrPayMethod);

        //注文ヘルパ取得
        $objPurchase = new SC_Helper_Purchase_Ex();

        //会員情報クラス取得
        $objCustomer = new SC_Customer_Ex();

        //予約商品存在確認
        $objPage->tpl_is_reserve = SC_Util_Mdl_YFCApi_Ex::isReservedOrder($arrOrder['order_id']);

        //modeでの分岐
        switch($mode) {
            case 'next':
                //入力チェック
                $objPage->arrErr = $this->checkError($objFormParam, $arrOrder, $mode);
                if (!SC_Utils_Ex::isBlank($objPage->arrErr)) break;
                //決済実行
                if($this->lfDoNext($arrOrder, $objFormParam->getHashArray(), $objPage, $objPurchase)){
                    //予約商品購入の場合は出荷予定日をセット
                    if($objPage->tpl_is_reserve) {
                        //出荷予定日を取得
                        $scheduled_shipping_date = SC_Util_Mdl_YFCApi_Ex::getMaxScheduledShippingDate($arrOrder['order_id']);
                        //出荷予定日を注文データに保持
                        SC_Util_Mdl_YFCApi_Ex::setOrderScheduledShippingDate($arrOrder['order_id'], $scheduled_shipping_date);
                    }
                    //完了ページへリダイレクト
                    SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                    $objPage->actionExit();
                }
                break;
            case '3dTran':
                //ACSからの戻りは加工せずに戻すこと
                //決済実行
                if($this->lfDo3dTran($arrOrder, $_REQUEST, $objPage, $objPurchase)){
                    //予約商品購入の場合は出荷予定日をセット
                    if($objPage->tpl_is_reserve) {
                        //出荷予定日を取得
                        $scheduled_shipping_date = SC_Util_Mdl_YFCApi_Ex::getMaxScheduledShippingDate($arrOrder['order_id']);
                        //出荷予定日を注文データに保持
                        SC_Util_Mdl_YFCApi_Ex::setOrderScheduledShippingDate($arrOrder['order_id'], $scheduled_shipping_date);
                    }
                    //完了ページへリダイレクト
                    SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                    $objPage->actionExit();
                }
                break;
            case 'return':
                //「決済処理中」を無効にする
                $objPurchase->rollbackOrder($arrOrder['order_id'], ORDER_CANCEL, true);
                //確認ページへリダイレクト
                SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
                SC_Response_Ex::actionExit();
                break;
            case 'deleteCard':
                //入力チェック
                $objPage->arrErr = $this->checkError($objFormParam, $arrOrder, $mode);
                if (!SC_Utils_Ex::isBlank($objPage->arrErr)) break;
                // ログインしている場合のみ
                if ($objCustomer->isLoginSuccess(true)) {
                    //預かりカードを削除する
                    $this->lfDoDeleteCard($arrOrder, $objFormParam->getHashArray(), $objPage);
                    return;
                }
                break;
            case 'getCard':
            default:
                break;
        }
        // ログインしているかつオプション契約あり(use_option=0)の場合
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();
        if ($objCustomer->isLoginSuccess(true) && $arrMdlSetting['use_option'] == '0') {
            $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerData($objCustomer->getValue('customer_id'));
            //お預かり情報を取得
            $this->lfDoGetCard($arrCustomer['customer_id'], $objPage);
            //モードが空で預かりを取得できた場合
            if($mode == '' && $objPage->tpl_is_luggage_card){
                $objFormParam->setParam(array('use_registed_card'=>'1'));
            }
        }
    }

    /**
     * 画面に設定するテンプレート名を返す
     *
     * @return string テンプレートファイル名
     */
    function getFormBloc() {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $arrBlocId = $objMdl->getSubData('bloc_setting');
        $device_type_id = SC_Display_Ex::detectDevice();
        $bloc_id =  $arrBlocId['yfc_credit'][ $device_type_id ];
        if ($bloc_id) {
            $objLayout = new SC_Helper_PageLayout_Ex();
            $arrBloc = $objLayout->getBlocs($device_type_id, 'bloc_id = ?', array($bloc_id), true);
            return $arrBloc[0]['tpl_path'];
        }
        return '';
    }

    /**
     * 決済実行.
     *
     * @param array $arrOrder 受注情報
     * @param array $arrParam パラメタ
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @param SC_Helper_Purchase_Ex $objPurchase 注文ヘルパ
     * @return bool リダイレクト
     */
    function lfDoNext($arrOrder, $arrParam, &$objPage, $objPurchase){

        //予約商品有無
        $arrParam['tpl_is_reserve'] = $objPage->tpl_is_reserve;
        // 決済実行
        $objClient = new SC_Mdl_YFCApi_Client_Credit_Ex();
        $result = $objClient->doPaymentRequest($arrOrder, $arrParam, $objPage->arrPaymentInfo);

        //決済結果取得
        $arrResults = $objClient->getResults();

        //3Dセキュア未加入迂回処理
        if ($arrResults['errorCode'] == MDL_YFCAPI_3D_EXCLUDED) {
            //3Dセキュア利用判定のため次の処理(request)にerrorCodeを渡す
            $arrParam['info_use_threeD'] = $arrResults['errorCode'];
            //決済実行(objClientを再度インスタンス化.エラーログ等を引き継がないため.)
            $objClient = new SC_Mdl_YFCApi_Client_Credit_Ex();
            $result = $objClient->doPaymentRequest($arrOrder, $arrParam, $objPage->arrPaymentInfo);
            //決済結果取得
            $arrResults = $objClient->getResults();
        }

        //リクエスト結果確認
        if (!$result) {
            $arrErr = $objClient->getError();
            $objPage->arrErr['payment'] = '※ 決済でエラーが発生しました。<br />' . implode('<br />', $arrErr);

            //決済ステータスを「決済中断」に変更する
            $sqlval[MDL_YFCAPI_ORDER_COL_PAYSTATUS] = MDL_YFCAPI_ACTION_STATUS_NG_TRANSACTION;
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], null, null, null, $sqlval);
            $objQuery->commit();
            return false;
        }

        //3Dセキュア無しの場合
        if($arrResults['threeDAuthHtml'] == '' && $arrResults['threeDToken'] == ''){
            //注文状況を「新規受付」へ
            $order_status = ORDER_NEW;
            $sqlval=array();
            $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
            //「予約販売」の場合「予約受付完了」に変更する
            if (SC_Util_Mdl_YFCApi_Ex::isReserve($objPage->tpl_is_reserve, $arrOrder['order_id'])) {
                $sqlval[MDL_YFCAPI_ORDER_COL_PAYSTATUS] = MDL_YFCAPI_ACTION_STATUS_COMP_RESERVE;
            } else {
                //「与信完了」に変更する
                $sqlval[MDL_YFCAPI_ORDER_COL_PAYSTATUS] = MDL_YFCAPI_ACTION_STATUS_COMP_AUTH;
            }
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
            $objQuery->commit();
            //「注文受付メール」送信
            $objPurchase->sendOrderMail($arrOrder['order_id']);
            return true;
        }

        //ACSリダイレクト設定
        //ACS-URL(正確にはHTMLデータ)のcharsetがShift_JISのため文字コード変換
        header('Content-Type: text/html; charset=Shift_JIS');
        echo mb_convert_encoding($arrResults['threeDAuthHtml'], "SJIS-win", "UTF-8");
        exit;
    }

    /**
     * 3Dセキュア与信実行.
     *
     * @param array $arrOrder 受注情報
     * @param array $arrParam
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @param SC_Helper_Purchase_Ex $objPurchase 注文ヘルパ
     * @return bool リダイレクト
     */
    function lfDo3dTran($arrOrder, $arrParam, &$objPage, $objPurchase){
        // 決済実行
        $objClient = new SC_Mdl_YFCApi_Client_Credit_Ex();
        //ACSからの戻りは加工せずに戻す
        $result = $objClient->do3dTran($arrOrder, $arrParam, $objPage->arrPaymentInfo);
        if(!$result){
            $arrErr = $objClient->getError();
            $objPage->arrErr['payment'] = '※ 決済でエラーが発生しました。<br />' . implode('<br />', $arrErr);

            //決済ステータスを「決済中断」に変更する
            $sqlval[MDL_YFCAPI_ORDER_COL_PAYSTATUS] = MDL_YFCAPI_ACTION_STATUS_NG_TRANSACTION;
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], null, null, null, $sqlval);
            $objQuery->commit();
            return false;
        }

        //注文状況を「新規受付」へ
        $order_status = ORDER_NEW;
        $sqlval=array();
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        if (SC_Util_Mdl_YFCApi_Ex::isReserve($objPage->tpl_is_reserve, $arrOrder['order_id'])) {
            //「予約販売」の場合「予約受付完了」に変更する
            $sqlval[MDL_YFCAPI_ORDER_COL_PAYSTATUS] = MDL_YFCAPI_ACTION_STATUS_COMP_RESERVE;
        } else {
            //「与信完了」に変更する
            $sqlval[MDL_YFCAPI_ORDER_COL_PAYSTATUS] = MDL_YFCAPI_ACTION_STATUS_COMP_AUTH;
        }
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
        $objQuery->commit();
        //「注文受付メール」送信
        $objPurchase->sendOrderMail($arrOrder['order_id']);
        return true;
    }

    /**
     * お預かり情報削除
     * @param array $arrOrder 受注情報
     * @param array $arrParam パラメタ
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @return bool リダイレクト
     */
    function lfDoDeleteCard($arrOrder, $arrParam, &$objPage){
        $objPage->tpl_is_luggage_card = false;
        $objPage->tpl_plg_target_seq = 0;
        $arrResults = array();

        //お預かり情報照会
        $objClient = new SC_Mdl_YFCApi_Client_Util_Ex();
        $result = $objClient->doGetCard($arrOrder['customer_id'], $arrParam);
        if(!$result) {
            $arrErr = $objClient->getError();
            $objPage->arrErr['payment'] = '※ お預かり照会でエラーが発生しました。<br />' . implode('<br />', $arrErr);
            return false;
        } else {
            $arrResults = $this->lfGetArrCardInfo($objClient->getResults());
        }

        //削除対象のカード情報セット
        foreach($arrResults['cardData'] as $cardData) {
            if($cardData['cardKey'] == $arrParam['card_key']) {
                $arrDeleteCardData = $cardData;
            }
        }

        //削除対象が予約販売利用有りの場合はエラーで返す
        if(isset($arrDeleteCardData['subscriptionFlg']) && $arrDeleteCardData['subscriptionFlg'] == '1') {
            $objPage->arrErr['payment'] = '※ 予約販売利用有りのカード情報は削除できません。';
            return false;
        }

        //お預かり情報削除
        $result = $objClient->doDeleteCard($arrOrder['customer_id'], $arrDeleteCardData);
        if(!$result) {
            $arrErr = $objClient->getError();
            $objPage->arrErr['payment'] = '※ お預かり情報削除でエラーが発生しました。<br />' . implode('<br />', $arrErr);
        } else {
            $arrResults = $this->lfGetArrCardInfo($objClient->getResults());
            //削除後にまだカード情報が存在する場合はテンプレート変数にセット
            if(isset($arrResults['cardUnit']) && $arrResults['cardUnit'] != '0') {
                $objPage->tpl_is_luggage_card = true;
                $objPage->arrResults = $arrResults;
                $objPage->tpl_payment_onload = 'fnDispTarget();';
                $this->lfSetLastCreditDate($objPage);
            }
        }
        //会員  ：登録パラメータを維持
        //非会員：なにもしない
        if($arrOrder['customer_id'] != '0') {
            $objPage->tpl_is_regist_card = true;
        }
        return true;
    }

    /**
     * お預かり照会実行.
     * @param integer $customer_id 顧客ID
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @return void
     */
    function lfDoGetCard($customer_id, &$objPage){
        $objPage->tpl_is_luggage_card = false;
        $objPage->tpl_plg_target_seq = 0;

        // お預かり照会実行
        $objClient = new SC_Mdl_YFCApi_Client_Util_Ex();
        $result = $objClient->doGetCard($customer_id, $_REQUEST);
        if(!$result){
            $arrErr = $objClient->getError();
            $objPage->arrErr['payment'] = '※ お預かり照会でエラーが発生しました。<br />' . implode('<br />', $arrErr);
        } else {
            $arrResults = $this->lfGetArrCardInfo($objClient->getResults());
            if(isset($arrResults['cardUnit']) && $arrResults['cardUnit'] != '0') {
                $objPage->tpl_is_luggage_card = true;
                $objPage->arrResults = $arrResults;
                $objPage->tpl_payment_onload = 'fnDispTarget();';
                $this->lfSetLastCreditDate($objPage);
            }
        }
        if($customer_id != '0') {
            $objPage->tpl_is_regist_card = true;
        }
    }

    /**
     * 入力内容のチェックの分岐を行う.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param string $mode モード
     * @return array 入力チェック結果の配列
     */
    function lfExistCheckMode(&$objFormParam,$mode){
        $arrErr = array();
        switch($mode){
            case 'next':
                $arrErr = $this->lfExistCheckNext($objFormParam);
                break;
            case 'deleteCard':
                $arrErr = $this->lfExistCheckDeleteCard($objFormParam);
                break;
            default:
                break;
        }
        return $arrErr;
    }

    /**
     * 与信の必須チェック.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @return array 入力チェック結果の配列
     */
    function lfExistCheckNext(&$objFormParam){
        //一時チェック済みパラメタ取得
        $arrParam = $objFormParam->getHashArray();
        //必須チェック用インスタンス生成
        $tempObjFormParam = new SC_FormParam_Ex();
        //パラメタ設定
        if(isset($arrParam['use_registed_card']) && $arrParam['use_registed_card'] == '1') {
            $tempObjFormParam->addParam("登録済お支払いカード", "card_key", INT_LEN, 'n', array("EXIST_CHECK"));
        }else{
            $tempObjFormParam->addParam("カード番号", "card_no", MDL_YFCAPI_CREDIT_NO_LEN, 'n', array("EXIST_CHECK"));
            $tempObjFormParam->addParam("カード有効期限年", "card_exp_year", 2, 'n', array("EXIST_CHECK"));
            $tempObjFormParam->addParam("カード有効期限月", "card_exp_month", 2, 'n', array("EXIST_CHECK"));
            $tempObjFormParam->addParam("カード名義人", "card_owner", 20, 'a', array("EXIST_CHECK"));
        }
        //共通項目をセット
        $tempObjFormParam->addParam("セキュリティコード", "security_code", MDL_YFCAPI_SECURITY_CODE_MAX_LEN, 'n', array("EXIST_CHECK"));
        $tempObjFormParam->addParam("お支払い方法", "pay_way", INT_LEN, 'n', array("EXIST_CHECK"));
        //チェック実行
        $tempObjFormParam->setParam($arrParam);
        $tempObjFormParam->convParam();
        return $tempObjFormParam->checkError();
    }

    /**
     * カード削除時の必須チェック.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @return array 入力チェック結果の配列
     */
    function lfExistCheckDeleteCard(&$objFormParam){
        //一時チェック済みパラメタ取得
        $arrParam = $objFormParam->getHashArray();
        //必須チェック用インスタンス生成
        $tempObjFormParam = new SC_FormParam_Ex();
        //パラメタ設定
        $tempObjFormParam->addParam("登録済お支払いカード", "card_key", INT_LEN, 'n', array("EXIST_CHECK"));
        //チェック実行
        $tempObjFormParam->setParam($arrParam);
        $tempObjFormParam->convParam();
        return $tempObjFormParam->checkError();
    }

    /**
     * 支払回数をセット.
     *
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @param array $arrPayMethod 支払回数
     * @return void
     */
    function lfSetPayMethod(&$objPage, $arrPayMethod) {
        foreach ($objPage->arrPaymentInfo['credit_pay_methods'] as $pay_method) {
            if(!SC_Utils_Ex::isBlank($arrPayMethod[$pay_method])) {
                $objPage->arrPayMethod[$pay_method] = $arrPayMethod[$pay_method];
            }
        }
    }

    /**
     * 最終利用日のカードをデフォルト値にセットする.
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @return void
     */
    function lfSetLastCreditDate(&$objPage){
        $tmp_last_credit_date = 0;
        foreach ($objPage->arrResults['cardData'] as $cardData) {
            if($tmp_last_credit_date <= $cardData['lastCreditDate']) {
                $objPage->tpl_plg_target_seq = $cardData['cardKey'];
                $tmp_last_credit_date = $cardData['lastCreditDate'];
            }
        }
    }

    /**
     * カード預かり情報取得（整理済）
     *
     * 預かり情報１件の場合と２件以上の場合で配列の構造を合わせる
     * @param array $arrCardInfos
     * @return array $arrResult
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
