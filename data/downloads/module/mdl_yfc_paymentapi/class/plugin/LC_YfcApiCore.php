<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
/**
 * プラグイン 決済コアの処理クラス
 */
class LC_YfcApiCore {

    /**
     * コンストラクタ
     */
    function __construct() {
    }

    /**
     * テンプレート処理
     * @param string $class_name
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @param SC_Plugin_Base $objPlugin
     * @return void
     */
    function actionPrefilterTransform($class_name, &$source, &$objPage, $filename, $objPlugin) {
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:
            case DEVICE_TYPE_SMARTPHONE:
            case DEVICE_TYPE_PC:
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                $template_dir = MDL_YFCAPI_TEMPLATE_PATH . 'admin/';
                if (strpos($filename, 'mail_templates') !== FALSE) {
                    return;
                }
                switch($filename){
                    case 'order/index.tpl':
                        $objTransform = new SC_Helper_Transform($source);
                        // 受注一覧画面
                        $template_path = $template_dir.'order/order_index_list_btn_add.tpl';
                        $objTransform->select('#form1 > div.btn')->appendChild(file_get_contents($template_path));
                        
                        $template_path = $template_dir.'order/order_index_list_header_add.tpl';
                        $objTransform->select('table.list > tr > th:last')->appendChild(file_get_contents($template_path));
                        
                        $template_path = $template_dir.'order/order_index_list_body_add.tpl';
                        $objTransform->select('table.list > tr', 1)->appendChild(file_get_contents($template_path));
                        
                        $template_path = $template_dir.'order/order_index_list_header_col_replace.tpl';
                        $objTransform->select('table.list > col', 1)->removeElement();
                        $objTransform->select('table.list')->appendFirst(file_get_contents($template_path));
                        
                        $source = $objTransform->getHTML();
                        break;
                    case 'order/edit.tpl':
                        $objTransform = new SC_Helper_Transform($source);
                        // 受注登録・編集画面
                        $template_path = $template_dir.'order/order_edit_status_add.tpl';
                        $objTransform->select('div#order')->appendFirst(file_get_contents($template_path));
                        
                        $template_path = $template_dir.'order/order_edit_payment_form_add.tpl';
                        $objTransform->select('div#order')->appendChild(file_get_contents($template_path));

                        $template_path = $template_dir.'order/order_edit_button_alert.tpl';
                        $objTransform->select('div#order')->appendChild(file_get_contents($template_path));
                        
                        $template_path = $template_dir.'order/order_edit_add_deliv_slip.tpl';
                        if (ECCUBE_VERSION == '2.13.0' || ECCUBE_VERSION == '2.13.1') {
                            $objTransform->select('div#order.contents-main table.form tr', 28)->appendChild(file_get_contents($template_path));
                        } else {
                            $objTransform->select('div#order.contents-main table.form tr', 31)->appendChild(file_get_contents($template_path));
                        }

                        $template_path = $template_dir.'order/order_edit_buyer_csv_form.tpl';
                        $objTransform->select('form#form1')->insertAfter(file_get_contents($template_path));

                        $source = $objTransform->getHTML();
                        break;
                    case 'basis/payment_input.tpl':
                        $objTransform = new SC_Helper_Transform($source);
                        // 支払方法登録画面
                        $template_path = $template_dir.'basis_payment_input_add.tpl';
                        $objTransform->select('table.form')->appendChild(file_get_contents($template_path));
                        // 支払方法登録画面（ロゴ画像文言追加用）
                        $template_path = $template_dir.'basis/basis_payment_input_add_logo_comment.tpl';
                        $objTransform->select('div.contents-main table tr', 3)->find('td', 0)->appendChild(file_get_contents($template_path));
                        $source = $objTransform->getHTML();
                        break;
                    default:
                        break;
                }
                break;
        }
    }

    /**
     * フックポイント分岐
     * @param string $class_name
     * @param string $hook_point after,before,mode
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param SC_Plugin_Base $objPlugin
     * @return void
     */
    function actionHook($class_name, $hook_point, &$objPage, $objPlugin) {
        switch ($class_name) {
            //受注管理＞受注一覧
            case 'LC_Page_Admin_Order_Ex':
                if ($hook_point == 'after') {
                    $this->lfDoAdminOrderAfter($objPage);
                } else if($hook_point == 'before') {
                    $this->lfDoAdminOrderBefore($objPage);
                }else{
                    $this->lfDoAdminOrderMode($objPage);
                }
                break;
            //受注管理＞受注登録
            case 'LC_Page_Admin_Order_Edit_Ex':
                if ($hook_point == 'after') {
                    $this->lfDoAdminOrderEditAfter($objPage);
                } else if($hook_point == 'before') {
                    $this->lfDoAdminOrderEditBefore($objPage);
                }else{
                    $this->lfDoAdminOrderEditMode($objPage);
                }
                break;
            //基本情報管理＞支払方法設定
            case 'LC_Page_Admin_Basis_PaymentInput_Ex':
                if ($hook_point == 'after') {
                    $this->lfDoAdminBasisPaymentInputAfter($objPage);
                } else if($hook_point == 'before') {
                    $this->lfDoAdminBasisPaymentInputBefore($objPage);
                }else{
                    $this->lfDoAdminBasisPaymentInputMode($objPage);
                }
                break;
            //購入完了
            case 'LC_Page_Shopping_Complete_Ex':
                if ($hook_point == 'after') {
                    $this->lfDoShoppingCompleteAfter($objPage);
                } else if($hook_point == 'before') {
                    $this->lfDoShoppingCompleteBefore($objPage);
                }else{
                    $this->lfDoShoppingCompleteMode($objPage);
                }
                break;
            default:
                break;
        }
    }

    /**
     * 受注管理＞受注一覧(after)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminOrderAfter(&$objPage) {
        //nop
    }

    /**
     * 受注管理＞受注一覧(before)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminOrderBefore(&$objPage) {
        //決済基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //オプションサービス契約情報（0:契約済 1:未契約）
        $objPage->plg_use_option = $arrMdlSetting['use_option'];
        //予約販売利用情報（0:利用する 1:利用しない）
        $objPage->plg_advance_sale = $arrMdlSetting['advance_sale'];
        
        $mode = $objPage->getMode();
        switch ($mode) {
            case 'plg_yfcapi_shipment_entry':
            case 'plg_yfcapi_shipment_entry_all':
            case 'plg_yfcapi_credit_cancel':
            case 'plg_yfcapi_credit_cancel_all':
                $objFormParam = new SC_FormParam_Ex();
                $this->lfInitParamOrder($objFormParam, $mode);
                $objFormParam->setParam($_REQUEST);
                $objFormParam->convParam();
                $arrErr = $this->lfCheckErrorOrderBefore($objFormParam, $mode);
                if(SC_Utils_Ex::isBlank($arrErr)){
                    $arrOrderId = $this->lfGetOrderId($objFormParam, $mode);
                    $this->lfDoModeActionOrderBefore($objPage, $mode, $arrOrderId);
                } else {
                    //API通信前エラー
                    $objPage->plg_yfcapi_error = implode('<br />', $arrErr);
                }
                $_POST['mode'] = 'search';
                $_REQUEST['mode'] = 'search';
                break;
            default:
                break;
        }
        if(!SC_Utils_Ex::isBlank($objPage->plg_yfcapi_error)) {
            $objPage->tpl_onload .= "window.alert('決済処理でエラーが生じました。エラー内容を確認してください。');";
        }
    }

    /**
     * 受注管理＞受注一覧(mode)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminOrderMode(&$objPage) {
        switch ($objPage->getMode()) {
            default:
                break;
        }
    }

    /**
     * 受注管理＞受注登録(after)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminOrderEditAfter(&$objPage) {

        // データ読み込み
        if (!SC_Utils_Ex::isBlank($objPage->arrForm['order_id']['value'])) {
        // 既存受注の場合
            $order_id = $objPage->arrForm['order_id']['value'];
            $objPage->arrPaymentData = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($order_id);
            $objPage->arrCVS = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('cvs');
            // 決済使用の注文
            if ($objPage->arrPaymentData[MDL_YFCAPI_ORDER_COL_PAYID]) {
                //支払方法ID
                $objPage->plg_yfcapi_payid = $objPage->arrPaymentData[MDL_YFCAPI_ORDER_COL_PAYID];
                //決済データ取得
                $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array($objPage->arrPaymentData[MDL_YFCAPI_ORDER_COL_PAYDATA]);
                //取引状況
                if($objPage->plg_yfcapi_payid == MDL_YFCAPI_PAYID_DEFERRED){
                    $arrPayStatus = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('deferred_status');
                }else{
                    $arrPayStatus = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('status');
                }
                $objPage->plg_yfcapi_pay_status = $arrPayStatus[$objPage->arrPaymentData[MDL_YFCAPI_ORDER_COL_PAYSTATUS]];
                //支払種別
                $arrPayName = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('pay_name');
                $objPage->plg_yfcapi_pay_name = $arrPayName[$objPage->arrPaymentData[MDL_YFCAPI_ORDER_COL_PAYID]];
                //決済状況ID
                $objPage->plg_yfcapi_pay_statusid = $objPage->arrPaymentData[MDL_YFCAPI_ORDER_COL_PAYSTATUS];
                //クレジットカード決済の場合
                if($objPage->plg_yfcapi_payid == MDL_YFCAPI_PAYID_CREDIT){
                    //支払方法（○回払い）
                    $arrPayMethod = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('pay_method');
                    $objPage->plg_yfcapi_pay_method = $arrPayMethod[$arrPaymentInfo['pay_way']];
                    //送り状番号登録状態
                    $objPage->plg_yfcapi_slip_on = SC_Util_Mdl_YFCApi_Ex::isSlippingOn(array($objPage->arrForm['order_id']['value']));
                }
                //コンビニ決済の場合
                if($objPage->plg_yfcapi_payid == MDL_YFCAPI_PAYID_CVS){
                    //コンビニ種類取得
                    $objPage->plg_yfcapi_cvs_method = $arrPaymentInfo['cvs'];
                }
                //クロネコ代金後払い
                if($objPage->plg_yfcapi_payid == MDL_YFCAPI_PAYID_DEFERRED){
                    //審査結果
                    $arrDeferredResult = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('deferred_result');
                    $objPage->plg_yfcapi_deferred_result_code = $objPage->arrPaymentData[MDL_YFCAPI_ORDER_COL_EXAMRESULT];
                    $objPage->plg_yfcapi_deferred_result = $arrDeferredResult[$objPage->plg_yfcapi_deferred_result_code];
                    //送り状番号登録状態
                    $objPage->plg_yfcapi_slip_on = SC_Util_Mdl_YFCApi_Ex::isSlippingOn(array($objPage->arrForm['order_id']['value']));
                    //全配送先の送信成功した送り状番号が保持されているかを確認
                    $objPage->plg_yfcapi_exist_last_deliv = SC_Util_Mdl_YFCApi_Ex::isAllExistLastDelivSlip(array($objPage->arrForm['order_id']['value']));
                }
            } else {
            // 決済未使用の注文
                $this->lfLimitPayments($objPage);
            }
        } else {
        // 新規受注の場合
            $this->lfLimitPayments($objPage);
        }
        
        //出荷予定日更新処理
        $mode = $objPage->getMode();
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParamOrderEdit($objFormParam, $mode);
        $order_id = $objPage->arrForm['order_id']['value'];
        $objFormParam->setParam($_REQUEST);
        $objFormParam->convParam();
        
        switch($mode) {
            case "edit":
                $arrErr = $this->lfCheckErrorOrderEditAfter($objFormParam, $mode);
                if(SC_Utils_Ex::isBlank($objPage->arrErr)) {
                    $objQuery =& SC_Query_Ex::getSingletonInstance();
                    $objQuery->begin();
                    $sqlval['plg_yfcapi_scheduled_shipping_date'] = $objFormParam->getValue('plg_yfcapi_scheduled_shipping_date');
                    $sqlval['update_date'] = "CURRENT_TIMESTAMP";
                    $objQuery->update('dtb_order', $sqlval, 'order_id = ?', array($order_id));
                    $objQuery->commit();
                } else {
                    $objPage->plg_yfcapi_scheduled_shipping_date_error_msg = implode('<br />', $arrErr);
                    $objPage->tpl_onload = '';
                }
                break;
            case "recalculate":
            case "payment":
            case "deliv":
            case "delete_product":
            case "select_product_detail":
            case "multiple":
            case "multiple_set_to":
            case "append_shipping":
            case 'plg_yfcapi_shipment_entry':
            case 'plg_yfcapi_shipment_cancel':
            case 'plg_yfcapi_get_info':
            case 'plg_yfcapi_change_date':
            case 'plg_yfcapi_credit_cancel':
            case 'plg_yfcapi_change_price':
            case 'deferred_cancel_auth':
            case 'deferred_get_auth':
            case 'deferred_get_info':
            case 'deferred_shipment_entry':
            case 'deferred_shipment_cancel':
            case 'deferred_change_price':
            case 'deferred_invoice_reissue':
            case 'deferred_invoice_reissue_withdrawn':
                break;
            case 'pre_edit':
            default:
                //DBから受注情報読み込む
                if(!SC_Utils_Ex::isBlank($order_id)) {
                    $objPurchase = new SC_Helper_Purchase_Ex();
                    $arrOrder = $objPurchase->getOrder($order_id);
                    $objFormParam->setParam($arrOrder);
                    //表示用のためDB値を整形
                    $temp_schedulde_shipping_date = $objFormParam->getValue('plg_yfcapi_scheduled_shipping_date');
                    $objFormParam->setValue('plg_yfcapi_scheduled_shipping_date', SC_Util_Mdl_YFCApi_Ex::getFormatedDate($temp_schedulde_shipping_date));
                }
                break;
        }
        $objPage->arrForm = array_merge($objPage->arrForm, (array)$objFormParam->getFormParamList());
    }

    /**
     * 受注管理＞受注登録(before)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminOrderEditBefore(&$objPage) {
        //決済基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //オプションサービス契約情報（0:契約済 1:未契約）
        $objPage->plg_use_option = $arrMdlSetting['use_option'];
        //予約販売利用情報（0:利用する 1:利用しない）
        $objPage->plg_advance_sale = $arrMdlSetting['advance_sale'];
        //キー追加（送り状番号）
        $objPage->arrShippingKeys[] = 'plg_yfcapi_deliv_slip';
        //キー追加（追跡URL）
        $objPage->arrShippingKeys[] = 'plg_yfcapi_deliv_slip_url';
        //キー追加（登録成功送り状番号）
        $objPage->arrShippingKeys[] = 'plg_yfcapi_last_deliv_slip';

        $objPage->plg_yfcapi_scheduled_shipping_date_error_msg = '';

        if ($_REQUEST['mode'] == 'edit' && !$this->lfCanEdit($objPage, $arrMdlSetting)) {
            $objPage->tpl_onload .= "window.alert('注文情報やお届け先情報を変更することはできません。');";
            $_REQUEST['mode'] = 'pre_edit';
        }

        $mode = $objPage->getMode();
        switch($mode) {
            case 'plg_yfcapi_shipment_entry':
            case 'plg_yfcapi_shipment_cancel':
            case 'plg_yfcapi_get_info':
            case 'plg_yfcapi_change_date':
            case 'plg_yfcapi_credit_cancel':
            case 'plg_yfcapi_change_price':
            case 'deferred_cancel_auth':
            case 'deferred_get_auth':
            case 'deferred_get_info':
            case 'deferred_shipment_entry':
            case 'deferred_shipment_cancel':
            case 'deferred_change_price':
            case 'deferred_invoice_reissue':
            case 'deferred_invoice_reissue_withdrawn':

                $objFormParam = new SC_FormParam_Ex();
                $this->lfInitParamOrderEdit($objFormParam, $mode);
                $objFormParam->setParam($_REQUEST);
                $objFormParam->convParam();
                $arrErr = $this->lfCheckErrorOrderEditBefore($objFormParam, $mode);

                if(!SC_Utils_Ex::isBlank($arrErr)){
                    $objPage->plg_yfcapi_error = implode('<br />', $arrErr);
                    return ;
                }

                $order_id = $objFormParam->getValue('order_id');
                if (SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId(array($order_id))) {
                    $objClient = new SC_Mdl_YFCApi_Client_Deferred_Util_Ex();
                } else {
                    $objClient = new SC_Mdl_YFCApi_Client_Util_Ex();
                }
                $ret = $this->lfDoModeActionOrderEditBefore($objClient, $mode, $order_id, $objPage);

                if(!$ret) {
                    $arrErr = $objClient->getError();
                    $objPage->plg_yfcapi_error = implode('<br />', $arrErr);
                }
                //ページ内入力項目を維持するため
                $_GET['mode'] = 'recalculate';
                break;
            case 'edit':
            case 'add':
                $objFormParam = new SC_FormParam_Ex();
                $this->lfInitParamOrderEdit($objFormParam, $mode);
                $objFormParam->setParam($_REQUEST);
                $objFormParam->convParam();
                $arrErr = $this->lfCheckErrorOrderEditBefore($objFormParam, $mode);
                if(!SC_Utils_Ex::isBlank($arrErr)) {
                    $objPage->plg_yfcapi_scheduled_shipping_date_error_msg = implode('<br />', $arrErr);
                }
                if(!SC_Utils_Ex::isBlank($objPage->plg_yfcapi_scheduled_shipping_date_error_msg)) {
                    //ページ内入力項目を維持するため
                    $_REQUEST['mode'] = 'recalculate';
                }
                break;
            case 'delete_product':
            case 'select_product_detail':
                $objFormParam = new SC_FormParam_Ex();
                $this->lfInitParamOrderEdit($objFormParam, $mode);
                $objFormParam->setParam($_REQUEST);
                $objFormParam->convParam();
                break;
            case 'multiple':
            case 'append_shipping':
                $objFormParam = new SC_FormParam_Ex();
                $this->lfInitParamOrderEdit($objFormParam, $mode);
                $objFormParam->setParam($_REQUEST);
                $objFormParam->convParam();
                if (SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId(array($objFormParam->getValue('order_id')))) {
                    $objPage->tpl_onload .= "window.alert('注文情報やお届け先情報を変更することはできません。');";
                    $_REQUEST['mode'] = 'pre_edit';
                }
                break;
            case 'deferred_buyer_csv':
                $objFormParam = new SC_FormParam_Ex();
                $this->lfInitParamOrderEdit($objFormParam, $mode);
                $objFormParam->setParam($_REQUEST);
                $objFormParam->convParam();
                $arrErr = $this->lfCheckErrorOrderEditBefore($objFormParam, $mode);

                if(!SC_Utils_Ex::isBlank($arrErr)){
                    $objPage->plg_yfcapi_error = implode('<br />', $arrErr);
                    return ;
                }

                $arrCsvData = $this->lfGetBuyerCsvData($objFormParam->getValue('order_id'));
                $arrBuyerCsvData = $this->lfConvertBuyerCsvData($arrCsvData);
                //CSV出力
                $objCSV = new SC_Helper_CSV_Ex();
                $fp = fopen('php://output', 'w');
                $header = $objCSV->sfArrayToCSV(SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('buyer_csv_header'));
                $header = mb_convert_encoding($header, 'SJIS-Win');
                $header .= "\r\n";
                fwrite($fp, $header);
                $objCSV->lfDownloadCsv(array($arrBuyerCsvData), 'deferred_buyer_');
                exit;
                break;
            default:
                break;
        }
    }

    /**
     * 受注管理＞受注登録(mode)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminOrderEditMode(&$objPage) {
        switch ($objPage->getMode()) {
            default:
                break;
        }
    }

    /**
     * 基本情報管理＞支払方法設定(after)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminBasisPaymentInputAfter(&$objPage) {
        //支払方法個別設定保持
        $objPage->arrCVS = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('cvs');
        $objDate = new SC_Date_Ex();
        $objPage->arrHour = $objDate->getHour();
        $objPage->arrMinutes = $objDate->getMinutes();
        $objPage->arrEnableFlags = array('1' => '利用する', '0' => '利用しない');
        $objPage->arrPayMethod = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('pay_method');

        $arrPayConfig = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($objPage->tpl_payment_id);

        $objPage->plg_yfcapi_payid = $arrPayConfig[MDL_YFCAPI_PAYMENT_COL_PAYID];
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParamPaymentInput($objFormParam, $objPage->plg_yfcapi_payid, $arrPayConfig, $objPage->getMode());
        $objFormParam->setParam($_REQUEST);
        $objFormParam->convParam();
        switch ($objPage->getMode()) {
            case 'edit':
                $arrErr = $this->lfCheckErrorPaymentInput($objFormParam, $objPage->plg_yfcapi_payid);
                $objPage->arrErr = array_merge($objPage->arrErr, (array)$arrErr);
                if (SC_Utils_Ex::isBlank($objPage->arrErr)) {
                    SC_Util_Mdl_YFCApi_Ex::setPaymentTypeConfig($objPage->tpl_payment_id, $objFormParam->getHashArray());
                } else {
                    $objPage->tpl_onload = '';
                }
                break;
            case 'pre_edit':
                $objFormParam->setParam($arrPayConfig);
                $objFormParam->convParam();
                break;
            case 'upload_image':
            case 'delete_image':
                break;
            default:
                if ($objPage->plg_yfcapi_payid == MDL_YFCAPI_PAYID_DEFERRED) {
                    if (!SC_Utils_Ex::isBlank($objPage->arrErr)) {
                        $objPayment = new SC_Helper_Payment_Ex();
                        $arrData = $objPayment->get($objFormParam->getValue('payment_id'));
                        $objFormParam->setParam($arrData);
                        $objPage->charge_flg = $arrData['charge_flg'];
                        $objPage->objUpFile->setDBFileList($arrData);
                    }
                }
                break;
        }
        $objPage->arrForm = array_merge($objPage->arrForm, (array)$objFormParam->getFormParamList());
    }

    /**
     * 基本情報管理＞支払方法設定(before)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminBasisPaymentInputBefore(&$objPage) {
        switch ($objPage->getMode()) {
            case 'edit':
                $objFormParam = new SC_FormParam_Ex();
                $objPage->lfInitParam($objPage->getMode(), $objFormParam);
                $objFormParam->setParam($_REQUEST);
                $objFormParam->convParam();
                $objPage->arrErr = $this->lfCheckErrorPaymentInputBefore($objFormParam);
                if (!SC_Utils_Ex::isBlank($objPage->arrErr['payment_id'])) {
                    return;
                }
                $arrPayConfig = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($objFormParam->getValue('payment_id'));
                if ($arrPayConfig[MDL_YFCAPI_PAYMENT_COL_PAYID] != MDL_YFCAPI_PAYID_DEFERRED) {
                    return;
                }
                if (!SC_Utils_Ex::isBlank($objPage->arrErr)) {
                    $_REQUEST['mode'] = 'default';
                    $objPage->tpl_payment_id = $objFormParam->getValue('payment_id');
                }
                break;
            default:
                break;
        }
    }

    /**
     * 基本情報管理＞支払方法設定(mode)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminBasisPaymentInputMode(&$objPage) {
        switch ($objPage->getMode()) {
            default:
                break;
        }
    }

    /**
     * 購入完了(after)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoShoppingCompleteAfter(&$objPage) {
        //nop
    }

    /**
     * 購入完了(before)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoShoppingCompleteBefore(&$objPage) {
        //決済情報取得
        if (!SC_Utils_Ex::isBlank($_SESSION['order_id'])) {
            $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($_SESSION['order_id']);
            if (!SC_Utils_Ex::isBlank($arrOrder[MDL_YFCAPI_ORDER_COL_PAYVIEW])) {
                $objPage->arrOther = unserialize($arrOrder[MDL_YFCAPI_ORDER_COL_PAYVIEW]);
            }
        }
    }

    /**
     * 購入完了(mode)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoShoppingCompleteMode(&$objPage) {
        switch ($objPage->getMode()) {
            default:
                break;
        }
    }

    /**
     * 受注マスタ フック時にパラメーター初期化.
     *
     * @param SC_FormParam_Ex $objFormParam
     * @param string $mode
     * @return void
     */
    function lfInitParamOrder(&$objFormParam, $mode) {
        switch($mode){
            case 'plg_yfcapi_shipment_entry':
            case 'plg_yfcapi_credit_cancel':
                $objFormParam->addParam('注文番号', 'order_id', INT_LEN, 'n',array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
                break;
            case 'plg_yfcapi_shipment_entry_all':
                $objFormParam->addParam('注文番号', 'plg_yfcapi_shipment_entry_order_id', INT_LEN, 'n',array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
                break;
            case 'plg_yfcapi_credit_cancel_all':
                $objFormParam->addParam('注文番号', 'plg_yfcapi_credit_cancel_order_id', INT_LEN, 'n',array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
                break;
            default:
                break;
        }
    }

    /**
     * 受注編集 フック時にパラメーター初期化.
     *
     * @param SC_FormParam_Ex $objFormParam
     * @param string $mode
     * @return void
     */
    function lfInitParamOrderEdit(&$objFormParam, $mode) {
        switch($mode){
            case 'deferred_buyer_csv':
                $objFormParam->addParam('受注日', 'order_ymd', 8, 'n', array('NUM_COUNT_CHECK', 'NUM_CHECK'));
                $objFormParam->addParam('出荷予定日', 'ship_ymd', 8, 'n', array('NUM_COUNT_CHECK', 'NUM_CHECK'));
                $objFormParam->addParam('受注番号', 'order_id', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
                $objFormParam->addParam('氏名', 'name', 30, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
                $objFormParam->addParam('氏名カナ', 'name_kana', 80, 'k', array('MAX_LENGTH_CHECK'));
                $objFormParam->addParam('郵便番号', 'postcode', 7, 'n', array('NUM_COUNT_CHECK', 'NUM_CHECK'));
                $objFormParam->addParam('住所1', 'address1', 25, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
                $objFormParam->addParam('住所2', 'address2', 25, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
                $objFormParam->addParam('電話番号', 'tel_num', 11, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
                for ($i = 1; $i <= 4; $i++) {
                    $objFormParam->addParam('予備'.$i, 'etc'.$i);
                }
                $objFormParam->addParam('メールアドレス', 'email', 64, 'a', array('MAX_LENGTH_CHECK', 'EMAIL_CHECK'));
                $objFormParam->addParam('決済金額総計', 'total_amount', 6, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
                for ($i = 1; $i <= 10; $i++) {
                    $objFormParam->addParam('明細'.$i.'商品名', 'item_name'.$i, 30, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
                    $objFormParam->addParam('明細'.$i.'数量', 'item_count'.$i, 4, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
                    $objFormParam->addParam('明細'.$i.'単価', 'unit_price'.$i, 7, 'a');
                    $objFormParam->addParam('明細'.$i.'小計', 'subtotal'.$i, 7, 'a');
                }
                $objFormParam->addParam('送付先選択', 'send_div', 1, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
                for ($i = 1; $i <= 10; $i++) {
                    $objFormParam->addParam('送付先'.$i.'郵便番号', 'send_postcode'.$i, 7, 'n', array('NUM_COUNT_CHECK', 'NUM_CHECK'));
                    $objFormParam->addParam('送付先'.$i.'住所①', 'send_address1'.$i, 25, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
                    $objFormParam->addParam('送付先'.$i.'住所②', 'send_address2'.$i, 25, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
                    $objFormParam->addParam('送付先'.$i.'名称', 'send_name'.$i, 30, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
                    $objFormParam->addParam('送付先'.$i.'電話番号', 'send_tel_num'.$i, 11, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
                }
                break;
            case 'plg_yfcapi_shipment_entry':
            case 'plg_yfcapi_shipment_cancel':
            case 'plg_yfcapi_get_info':
            case 'plg_yfcapi_change_date':
            case 'plg_yfcapi_credit_cancel':
            case 'plg_yfcapi_change_price':
            case 'deferred_cancel_auth':
            case 'deferred_get_auth':
            case 'deferred_get_info':
            case 'deferred_shipment_entry':
            case 'deferred_shipment_cancel':
            case 'deferred_change_price':
            case 'deferred_invoice_reissue':
            case 'deferred_invoice_reissue_withdrawn':
            case 'delete_product':
            case 'select_product_detail':
            case 'multiple':
            case 'append_shipping':
                $objFormParam->addParam('注文番号', 'order_id', INT_LEN, 'n',array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
                //break;
            default:
                $objFormParam->addParam('出荷予定日', 'plg_yfcapi_scheduled_shipping_date', 8, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'NUM_COUNT_CHECK'));
                break;
        }
    }

    /**
     * 支払方法設定 パラメーター初期化.
     * @param SC_FormParam_Ex $objFormParam
     * @param integer $pay_id
     * @param array $arrPayConfig
     * @param string $mode
     * @return void
     */
    function lfInitParamPaymentInput(&$objFormParam, $pay_id, &$arrPayConfig, $mode) {

        if ($pay_id == MDL_YFCAPI_PAYID_CREDIT) {
            $key = 'credit_pay_methods';
            $objFormParam->addParam('支払種別', $key, INT_LEN, 'a',
                    array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK')
            );
            $key = 'TdFlag';
            $objFormParam->addParam('本人認証サービス', $key , INT_LEN, 'n',
                    array('MAX_LENGTH_CHECK', 'NUM_CHECK'),
                    SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? '0' : $arrPayConfig[$key]
            );
        }

        if ($pay_id == MDL_YFCAPI_PAYID_CVS) {
            $key = 'conveni';
            $objFormParam->addParam('コンビニ選択', $key , INT_LEN, 'n',
                    array('MAX_LENGTH_CHECK', 'EXIST_CHECK', 'NUM_CHECK')
            );
        }

        $key = 'order_mail_title1';
        if (!isset($arrPayConfig[$key])) {
            $def_title = 'お支払いについて';
        } else {
            $def_title = '';
        }
        $objFormParam->addParam('決済完了案内タイトル', $key, STEXT_LEN, '',
                array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? $def_title : $arrPayConfig[$key]
        );

        $key = 'order_mail_body1';
        $objFormParam->addParam('決済完了案内本文', $key, MLTEXT_LEN, '',
                array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                SC_Utils_Ex::isBlank($arrPayConfig[$key]) ? $this->lfGetMailDefBody($pay_id) : $arrPayConfig[$key]
        );

        if ($pay_id == MDL_YFCAPI_PAYID_CVS) {
            $arrCVS = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('cvs');
            foreach ($arrCVS as $key => $name) {
                $ckey = 'order_mail_title_' . $key;
                if (!isset($arrPayConfig[$key])) {
                    $def_title = $name . 'でのお支払い';
                }
                $objFormParam->addParam($name . '決済完了案内タイトル', $ckey, STEXT_LEN, '',
                        array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                        SC_Utils_Ex::isBlank($arrPayConfig[$ckey]) ? $def_title : $arrPayConfig[$ckey]
                );
                $ckey = 'order_mail_body_' . $key;
                $objFormParam->addParam($name . '決済完了案内本文', $ckey, MLTEXT_LEN, '',
                        array('MAX_LENGTH_CHECK', 'SPTAB_CHECK'),
                        SC_Utils_Ex::isBlank($arrPayConfig[$ckey]) ? $this->lfGetMailDefBody($pay_id, $key) : $arrPayConfig[$ckey]
                );
            }
        }

        switch ($mode) {
            case 'edit':
            case 'upload_image':
            case 'delete_image':
            case 'pre_edit':
                break;
            default:
                if ($pay_id == MDL_YFCAPI_PAYID_DEFERRED) {
                    $objFormParam->addParam('支払いID', 'payment_id', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
                    $objFormParam->addParam('課金フラグ', 'charge_flg', INT_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
                    $objFormParam->addParam('支払方法', 'payment_method', STEXT_LEN, 'KVa', array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
                    $objFormParam->addParam('手数料', 'charge', PRICE_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
                    $objFormParam->addParam('利用条件(～円以上)', 'rule_max', PRICE_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
                    $objFormParam->addParam('利用条件(～円以下)', 'upper_rule', PRICE_LEN, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
                    $objFormParam->addParam('固定', 'fix');
                }
                break;
        }
    }

    /**
     * 受注編集after時のエラーチェック
     * 
     * @param SC_FormParam_Ex $objFormParam
     * @param string $mode
     * @return array $arrErr
     */
    function lfCheckErrorOrderEditAfter(&$objFormParam, $mode) {
        //パラメタチェック
        $arrErr = $objFormParam->checkError();
        if (!SC_Utils_Ex::isBlank($arrErr)) {
            return SC_Util_Mdl_YFCApi_Ex::sfGetParamArrMsg($arrErr);
        }
        
        switch ($mode) {
            case 'edit':
            case 'add':
                //日付チェック（出荷予定日）
                $tempDate = $objFormParam->getValue('plg_yfcapi_scheduled_shipping_date');
                if(!empty($tempDate)) {
                    $year  = substr($tempDate, 0, 4);
                    $month = substr($tempDate, 4, 2);
                    $day   = substr($tempDate, 6, 2);
                    if(!checkdate($month, $day, $year)) {
                        $arrErr[] = '出荷予定日が正しくありません<br />'; //通常のエラー表示と合わせるため<br />を追加
                    }
                }
                break;
        }
        return $arrErr;
    }

    /**
     * 受注編集before時のエラーチェック
     * @param SC_FormParam_Ex $objFormParam
     * @param string $mode
     * @return array $arrErr
     */
    function lfCheckErrorOrderEditBefore(&$objFormParam, $mode) {
        //パラメタチェック
        $arrErr = $objFormParam->checkError();
        if (!SC_Utils_Ex::isBlank($arrErr)) {
            return SC_Util_Mdl_YFCApi_Ex::sfGetParamArrMsg($arrErr);
        }

        //パラメタチェック済み注文番号を取得
        $arrOrderId = $this->lfGetOrderId($objFormParam, $mode);

        switch ($mode) {
            case 'plg_yfcapi_shipment_entry':
                //対応支払方法チェック
                if(!SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId($arrOrderId)){
                    $arrErr[] = '操作に対応していない決済です。';
                //取引状況チェック(与信完了)
                } elseif(SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus($arrOrderId, array(MDL_YFCAPI_ACTION_STATUS_COMP_AUTH))) {
                    $arrErr[] = '操作に対応していない取引状況です。';
                //送り状番号必須チェック
                } elseif(!SC_Util_Mdl_YFCApi_Ex::isSlippingOn($arrOrderId)) {
                    $arrErr[] = '送り状番号が登録されていない配送先が存在します。';
                //複数配送送り先上限チェック(99件まで）
                } elseif(SC_Util_Mdl_YFCApi_Ex::isUpperLimitedShippings($arrOrderId)) {
                    $arrErr[] = '1つの注文に対する出荷情報の上限（'.MDL_YFCAPI_DELIV_ADDR_MAX.'件）を超えています。';
                //共通送り状番号での注文同梱上限(3件まで)チェック
                } elseif(SC_Util_Mdl_YFCApi_Ex::isUpperLimitedShippedNum($arrOrderId)) {
                    $arrErr[] = '同一の送り状番号で同梱可能な注文数（'.MDL_YFCAPI_SHIPPED_MAX.'件）を超えています。';
                //共通送り状番号で注文同梱時の発送先同一チェック
                } elseif(SC_Util_Mdl_YFCApi_Ex::isExistUnequalShipping($arrOrderId)) {
                    $arrErr[] = '同一の送り状番号で配送先が異なるものが存在しています。';
                }
                break;
            case 'plg_yfcapi_shipment_cancel':
                //対応支払方法チェック
                if(!SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId($arrOrderId)){
                    $arrErr[] = '操作に対応していない決済です。';
                //取引状況チェック（精算確定以外でOK)
                } elseif(!SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus($arrOrderId, array(MDL_YFCAPI_ACTION_STATUS_COMP_REQUEST))) {
                    $arrErr[] = '操作に対応していない取引状況です。';
                //送り状番号必須チェック
                } elseif(!SC_Util_Mdl_YFCApi_Ex::isSlippingOn($arrOrderId)) {
                    $arrErr[] = '送り状番号が登録されていない配送先が存在します。';
                }
                break;
            case 'plg_yfcapi_get_info':
                break;
            case 'plg_yfcapi_change_date':
                //対応支払方法チェック
                if(!SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId($arrOrderId)){
                    $arrErr[] = '操作に対応していない決済です。';
                //取引状況チェック(予約受付完了)
                } elseif(SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus($arrOrderId, array(MDL_YFCAPI_ACTION_STATUS_COMP_RESERVE))) {
                    $arrErr[] = '操作に対応していない取引状況です。';
                //出荷予定日必須チェック
                } elseif(!SC_Util_Mdl_YFCApi_Ex::isExistScheduledShippingDate($arrOrderId)) {
                    $arrErr[] = '出荷予定日が設定されていません。';
                }
                break;
            case 'plg_yfcapi_credit_cancel':
                //対応支払方法チェック
                if(!SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId($arrOrderId)){
                    $arrErr[] = '操作に対応していない決済です。';
                //取引状況チェック(精算確定 以外ならばOK)
                } elseif(!SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus($arrOrderId, array(MDL_YFCAPI_ACTION_STATUS_COMMIT_SETTLEMENT))) {
                    $arrErr[] = '操作に対応していない取引状況です。';
                }
                break;
            case 'plg_yfcapi_change_price':
                //決済基本クラス取得
                $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
                //モジュール設定値取得
                $arrMdlSetting = $objMdl->getUserSettings();
                //オプション契約チェック（0:契約済 1:未契約）
                if($arrMdlSetting['use_option'] != '0'){
                    $arrErr[] = 'オプション契約が必要な機能になります。';
                //対応支払方法チェック
                }elseif(!SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId($arrOrderId)){
                    $arrErr[] = '操作に対応していない決済です。';
                //取引状況チェック(取消 以外ならばOK)
                } elseif(!SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus($arrOrderId, array(MDL_YFCAPI_ACTION_STATUS_CANCEL))) {
                    $arrErr[] = '操作に対応していない取引状況です。';
                }
                break;
            case 'deferred_cancel_auth':
                if(!SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId($arrOrderId)){
                    //対応支払方法チェック
                    $arrErr[] = '操作に対応していない決済です。';
                } elseif (!SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus($arrOrderId, array(MDL_YFCAPI_DEFERRED_AUTH_CANCEL))) {
                    //取引状況チェック(取消済 だったらエラー)
                    $arrErr[] = '操作に対応していない取引状況です。';
                }
                break;
            case 'deferred_get_auth':
                //対応支払方法チェック
                if(!SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId($arrOrderId)){
                    $arrErr[] = '操作に対応していない決済です。';
                }
                break;
            case 'deferred_get_info':
                //対応支払方法チェック
                if(!SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId($arrOrderId)){
                    $arrErr[] = '操作に対応していない決済です。';
                }
                break;
            case 'deferred_shipment_entry':
                $objPurchase = new SC_Helper_Purchase_Ex();
                $arrOrder = $objPurchase->getOrder($arrOrderId[0]);
                if(!SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId($arrOrderId)){
                    //対応支払方法チェック
                    $arrErr[] = '操作に対応していない決済です。';
                } elseif (!SC_Util_Mdl_YFCApi_Ex::isSlippingOn($arrOrderId)) {
                    //送り状番号必須チェック
                    $arrErr[] = '送り状番号が登録されていない配送先が存在します。';
                } elseif ($arrOrder[MDL_YFCAPI_ORDER_COL_EXAMRESULT] != MDL_YFCAPI_DEFERRED_AVAILABLE) {
                    //審査結果チェック(ご利用可)
                    $arrErr[] = '操作に対応していない審査結果です。';
                } elseif (SC_Util_Mdl_YFCApi_Ex::getCountShipping($arrOrderId[0]) > MDL_YFCAPI_DEFERRED_DELIV_ADDR_MAX) {
                    $arrErr[] = '1つの注文に対するお届け先の上限（'.MDL_YFCAPI_DEFERRED_DELIV_ADDR_MAX.'件）を超えております。';
                }
                break;
            case 'deferred_shipment_cancel':
                if (!SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId($arrOrderId)) {
                    //対応支払方法チェック
                    $arrErr[] = '操作に対応していない決済です。';
                } elseif (SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus($arrOrderId, array(MDL_YFCAPI_DEFERRED_REGIST_DELIV_SLIP))) {
                    //取引状況チェック(送り状番号登録済)
                    $arrErr[] = '操作に対応していない取引状況です。';
                } elseif (!SC_Util_Mdl_YFCApi_Ex::isAllExistLastDelivSlip($arrOrderId)) {
                    //全配送先の送信に成功した送り状番号が保持されているかを確認
                    $arrErr[] = '出荷情報登録されていない配送先が存在します。';
                }
                break;
            case 'deferred_buyer_csv':
                //対応支払方法チェック
                if(!SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId($arrOrderId)){
                    $arrErr[] = '操作に対応していない決済です。';
                }
                break;
            case 'deferred_change_price':
                //決済基本クラス取得
                $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
                //モジュール設定値取得
                $arrMdlSetting = $objMdl->getUserSettings();
                //オプション契約チェック（0:契約済 1:未契約）
                if($arrMdlSetting['use_option'] != '0'){
                    $arrErr[] = 'オプション契約が必要な機能になります。';
                    //対応支払方法チェック
                }elseif(!SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId($arrOrderId)){
                    $arrErr[] = '操作に対応していない決済です。';
                    //取引状況チェック(取消 以外ならばOK)
                } elseif(!SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus($arrOrderId, array(MDL_YFCAPI_DEFERRED_AUTH_CANCEL))) {
                    $arrErr[] = '操作に対応していない取引状況です。';
                }
                break;
            case 'deferred_invoice_reissue':
            case 'deferred_invoice_reissue_withdrawn':
                //対応支払方法チェック
                if(!SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId($arrOrderId)){
                    $arrErr[] = '操作に対応していない決済です。';
                }
                break;
            case 'edit':
            case 'add':
                //日付チェック（出荷予定日）
                $tempDate = $objFormParam->getValue('plg_yfcapi_scheduled_shipping_date');
                if(!empty($tempDate)) {
                    $year  = substr($tempDate, 0, 4);
                    $month = substr($tempDate, 4, 2);
                    $day   = substr($tempDate, 6, 2);
                    if(!checkdate($month, $day, $year)) {
                        $arrErr[] = '出荷予定日が正しくありません<br />'; //通常のエラー表示と合わせるため<br />を追加
                    }
                }
                break;
            default:
                break;
        }
        return $arrErr;
    }

    /**
     * 受注マスタbefore時のエラーチェック
     * @param SC_FormParam_Ex $objFormParam
     * @param string $mode
     * @return array $arrErr
     */
    function lfCheckErrorOrderBefore(&$objFormParam, $mode) {
        //パラメタチェック
        $arrErr = $objFormParam->checkError();
        if (!SC_Utils_Ex::isBlank($arrErr)) {
            return SC_Util_Mdl_YFCApi_Ex::sfGetParamArrMsg($arrErr);
        }

        //パラメタチェック済み注文番号を取得
        $arrOrderId = $this->lfGetOrderId($objFormParam, $mode);

        $objPurchase = new SC_Helper_Purchase_Ex();

        foreach($arrOrderId as $key => $order_id){
            $arrOrderIdVal = array($order_id);
            //対応支払方法チェック
            if (!SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId($arrOrderIdVal) && !SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId($arrOrderIdVal)) {
                $arrErr[] = '操作に対応していない決済です。';
            }

            //クレジットカード出荷情報登録処理
            if (SC_Utils_Ex::isBlank($arrErr) &&
                SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId($arrOrderIdVal) &&
                ($mode == 'plg_yfcapi_shipment_entry' || $mode == 'plg_yfcapi_shipment_entry_all')
            ) {
                //取引状況チェック（与信完了）
                if (SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus($arrOrderIdVal, array(MDL_YFCAPI_ACTION_STATUS_COMP_AUTH))) {
                    $arrErr[] = '操作に対応していない取引状況です。';
                    //送り状番号必須チェック
                } elseif (!SC_Util_Mdl_YFCApi_Ex::isSlippingOn($arrOrderIdVal)) {
                    $arrErr[] = '送り状番号が登録されていない配送先が存在します。';
                    //複数配送送り先上限チェック（99件まで）
                } elseif (SC_Util_Mdl_YFCApi_Ex::isUpperLimitedShippings($arrOrderIdVal)) {
                    $arrErr[] = '1つの注文に対する出荷情報の上限（'.MDL_YFCAPI_DELIV_ADDR_MAX.'件）を超えております。';
                    //共通送り状番号での注文同梱上限チェック
                } elseif (SC_Util_Mdl_YFCApi_Ex::isUpperLimitedShippedNum($arrOrderIdVal)) {
                    $arrErr[] = '同一の送り状番号で同梱可能な注文数（'.MDL_YFCAPI_SHIPPED_MAX.'件）を超えております。';
                    //共通送り状番号で注文同梱時の発送先同一チェック
                } elseif (SC_Util_Mdl_YFCApi_Ex::isExistUnequalShipping($arrOrderIdVal)) {
                    $arrErr[] = '同一の送り状番号で配送先が異なるものが存在しています。';
                }
            }

            //後払い出荷情報登録処理
            if (SC_Utils_Ex::isBlank($arrErr) &&
                SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId($arrOrderIdVal) &&
                ($mode == 'plg_yfcapi_shipment_entry' || $mode == 'plg_yfcapi_shipment_entry_all')
            ) {
                $arrOrder = $objPurchase->getOrder($order_id);
                if (!SC_Util_Mdl_YFCApi_Ex::isSlippingOn($arrOrderIdVal)) {
                    //送り状番号必須チェック
                    $arrErr[] = '送り状番号が登録されていない配送先が存在します。';
                } elseif ($arrOrder[MDL_YFCAPI_ORDER_COL_EXAMRESULT] != MDL_YFCAPI_DEFERRED_AVAILABLE) {
                    //審査結果チェック(ご利用可)
                    $arrErr[] = '操作に対応していない審査結果です。';
                } elseif (SC_Util_Mdl_YFCApi_Ex::getCountShipping($order_id) > MDL_YFCAPI_DEFERRED_DELIV_ADDR_MAX) {
                    $arrErr[] = '1つの注文に対するお届け先の上限（'.MDL_YFCAPI_DEFERRED_DELIV_ADDR_MAX.'件）を超えております。';
                }
            }
        }

        return $arrErr;
    }

    /**
     * 基本情報管理＞支払方法設定 エラーチェック
     * 
     * @param SC_FormParam_Ex $objFormParam
     * @return array
     */
    function lfCheckErrorPaymentInput(&$objFormParam) {
        $arrRet =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrRet);
        $objErr->arrErr = $objFormParam->checkError();

        return $objErr->arrErr;
    }

    /**
     * 基本情報管理＞支払方法設定(before) エラーチェック
     *
     * @param SC_FormParam_Ex $objFormParam
     * @return array
     */
    function lfCheckErrorPaymentInputBefore(&$objFormParam) {
        $arrData =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrData);
        $objErr->arrErr = $objFormParam->checkError();

        if (!SC_Utils_Ex::isBlank($objErr->arrErr['payment_id'])) {
            return $objErr->arrErr;
        }

        $arrPayConfig = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($arrData['payment_id']);

        // 利用条件(下限)チェック
        if (!SC_Utils_Ex::isBlank($arrPayConfig['rule_min'])) {
            $objErr->doFunc(array('利用条件(下限)', 'rule_max'), array('EXIST_CHECK'));
        }
        if (SC_Utils_Ex::isBlank($objErr->arrErr['rule_max']) && $arrData['rule_max'] < $arrPayConfig['rule_min']) {
            $objErr->arrErr['rule_max'] = '利用条件(下限)は' . $arrPayConfig['rule_min'] .'円以上にしてください。<br />';
        }
        // 利用条件(上限)チェック
        if (!SC_Utils_Ex::isBlank($arrPayConfig['upper_rule_max'])) {
            $objErr->doFunc(array('利用条件(上限)', 'upper_rule'), array('EXIST_CHECK'));
        }
        if (SC_Utils_Ex::isBlank($objErr->arrErr['upper_rule']) && $arrData['upper_rule'] > $arrPayConfig['upper_rule_max']) {
            $objErr->arrErr['upper_rule'] = '利用条件(上限)は' . $arrPayConfig['upper_rule_max'] .'円以下にしてください。<br />';
        }

        return $objErr->arrErr;
    }

    /**
     * 基本情報管理＞支払方法設定
     * 決済案内タイトル・本文初期値設定
     * 
     * @param string $pay_id
     * @param string $cvs_id
     * @return string
     */
    function lfGetMailDefBody($pay_id, $cvs_id = '') {
        $arrPaymentCode = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('pay_code');
        if (SC_Utils_Ex::isBlank($cvs_id)) {
            $filename = strtolower($arrPaymentCode[$pay_id]) . '.tpl';
        }else{
            $filename = strtolower($arrPaymentCode[$pay_id]) . '_' . $cvs_id . '.tpl';
        }
        $template_dir = MDL_YFCAPI_TEMPLATE_PATH . 'admin/mail/';
        if (is_file($template_dir . $filename)) {
            return file_get_contents($template_dir . $filename);
        }
        return '';
    }

    /**
     * お支払方法制限
     * 
     * お支払方法から決済の支払方法を削除する
     * 
     * @param LC_Page_Ex $objPage
     * @return boolean
     */
    function lfLimitPayments(&$objPage) {
        // 決済用支払方法取得
        $arrYfcApiPayments = SC_Util_Mdl_YFCApi_Ex::getYfcPayments();
        // 支払方法取得（調整前）
        $arrPayment = $objPage->arrPayment;
        foreach ($arrPayment as $key => $payment) {
            foreach ($arrYfcApiPayments as $yfc_payment) {
                if ($yfc_payment['payment_id'] == $key) {
                    unset($objPage->arrPayment[$key]);
                    break;
                }
            }
        }
        return true;
    }

    /**
     * 受注編集beforeフック時のモード処理
     * @param SC_Mdl_YFCApi_Client_Util_Ex $objClient ページオブジェクト
     * @param string $mode
     * @param integer $order_id
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return bool $ret
     */
    function lfDoModeActionOrderEditBefore(&$objClient, $mode, $order_id, &$objPage){
        $ret = true;
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($order_id);
        switch ($mode) {
            case 'plg_yfcapi_shipment_entry':
                list($ret, $arrSuccessSlip) = $objClient->doShipmentEntry($arrOrder);
                //複数配送時出荷情報登録ロールバック
                if(!$ret) {
                    $objClient->doRollbackCommit($arrOrder, $arrSuccessSlip);
                }
                break;
            case 'plg_yfcapi_shipment_cancel':
            case 'deferred_shipment_cancel':
                $ret = $objClient->doShipmentCancel($arrOrder);
                break;
            case 'plg_yfcapi_get_info':
                $ret = $objClient->doGetOrderInfo($arrOrder);
                break;
            case 'plg_yfcapi_change_date':
                $ret = $objClient->doChangeDate($arrOrder);
                break;
            case 'plg_yfcapi_credit_cancel':
                $ret = $objClient->doCancel($arrOrder);
                if($ret) {
                    //対応状況をキャンセルにする
                    $objPurchase = new SC_Helper_Purchase_Ex();
                    $objPurchase->cancelOrder($arrOrder['order_id'], ORDER_CANCEL);
                }
                break;
            case 'plg_yfcapi_change_price':
            case 'deferred_change_price':
                $ret = $objClient->doChangePrice($arrOrder);
                break;
            case 'deferred_cancel_auth':
                $ret = $objClient->doCancel($arrOrder);
                break;
            case 'deferred_get_auth':
                $ret = $objClient->doGetAuthResult($arrOrder);
                break;
            case 'deferred_get_info':
                $ret = $objClient->doGetOrderInfo($arrOrder);
                break;
            case 'deferred_shipment_entry':
                list($ret, $success_cnt, $failure_cnt) = $objClient->doShipmentEntry($arrOrder);
                $objPage->tpl_onload .= "window.alert('登録成功：" . $success_cnt . "件\\n登録失敗：" . $failure_cnt . "件');";
                break;
            case 'deferred_invoice_reissue':
                // 1：請求内容変更・請求書再発行
                $ret = $objClient->doInvoiceReissue($arrOrder, 1);
                break;
            case 'deferred_invoice_reissue_withdrawn':
                // 2：請求書再発行取下げ
                $ret = $objClient->doInvoiceReissue($arrOrder, 3);
                break;
            default:
                break;
        }
        return $ret;
    }

    /**
     * 受注一覧beforeフック時のモード処理
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $mode
     * @param array $arrOrderId
     * @return void
     */
    function lfDoModeActionOrderBefore(&$objPage, $mode, $arrOrderId) {
        $objPage->plg_yfcapi_error = '';
        foreach ($arrOrderId as $order_id) {
            $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($order_id);
            if ($arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] == MDL_YFCAPI_PAYID_DEFERRED) {
                $objClient = new SC_Mdl_YFCApi_Client_Deferred_Util_Ex();
            } else {
                $objClient = new SC_Mdl_YFCApi_Client_Util_Ex();
            }

            switch ($mode) {
                case 'plg_yfcapi_shipment_entry':
                case 'plg_yfcapi_shipment_entry_all':
                    list($ret) = $objClient->doShipmentEntry($arrOrder);
                    break;
                case 'plg_yfcapi_credit_cancel':
                case 'plg_yfcapi_credit_cancel_all':
                    $ret = $objClient->doCancel($arrOrder);
                    if($ret && $arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] != MDL_YFCAPI_PAYID_DEFERRED) {
                        //対応状況をキャンセルにする
                        $objPurchase = new SC_Helper_Purchase_Ex();
                        $objPurchase->cancelOrder($arrOrder['order_id'], ORDER_CANCEL);
                    }
                    break;
                default:
                    break;
            }

            if(!$ret) {
                $arrErr = $objClient->getError();
                if(!SC_Utils_Ex::isBlank($objPage->plg_yfcapi_error)) {
                    $objPage->plg_yfcapi_error .= '<br />';
                }
                $objPage->plg_yfcapi_error .= '注文番号：'.$order_id.'の決済で下記が発生しました。<br />';
                $objPage->plg_yfcapi_error .= implode('<br />', $arrErr);
            }
        }
    }

    /**
     * 注文番号を配列で取得
     * @param array SC_FormParam_Ex $objFormParam
     * @param string $mode
     * @return array
     */
    function lfGetOrderId(&$objFormParam, $mode) {
        switch ($mode) {
            case 'plg_yfcapi_shipment_entry':
            case 'plg_yfcapi_shipment_cancel':
            case 'plg_yfcapi_get_info':
            case 'plg_yfcapi_change_date':
            case 'plg_yfcapi_credit_cancel':
            case 'plg_yfcapi_change_price':
            case 'deferred_cancel_auth':
            case 'deferred_get_auth':
            case 'deferred_get_info':
            case 'deferred_shipment_entry':
            case 'deferred_shipment_cancel':
            case 'deferred_buyer_csv':
            case 'deferred_change_price':
            case 'deferred_invoice_reissue':
            case 'deferred_invoice_reissue_withdrawn':
            case 'edit':
            case 'add':
                $arrRet = array($objFormParam->getValue('order_id'));
                break;
            case 'plg_yfcapi_shipment_entry_all':
                $arrRet = $objFormParam->getValue('plg_yfcapi_shipment_entry_order_id');
                break;
            case 'plg_yfcapi_credit_cancel_all':
                $arrRet = $objFormParam->getValue('plg_yfcapi_credit_cancel_order_id');
                break;
            default:
                $arrRet = array();
                break;
        }
        return $arrRet;
    }

    /**
     * 受注の編集が可能ならtrue、不可能ならfalseを返す.
     *
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param array $arrMdlSetting モジュール設定値
     * @return bool
     */
    function lfCanEdit(&$objPage, $arrMdlSetting)
    {
        if (SC_Utils_Ex::isBlank($_REQUEST['order_id'])) {
            return false;
        }

        if (!SC_Utils_Ex::sfIsInt($_REQUEST['order_id'])) {
            return false;
        }

        if (!SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId(array($_REQUEST['order_id']))) {
            //クロネコ代金後払い決済以外なら無条件で編集可能
            return true;
        }

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objFormParam = new SC_FormParam_Ex();
        $objPage->lfInitParam($objFormParam);
        $objFormParam->setParam($_REQUEST);
        $objFormParam->convParam();
        $arrForm = $objFormParam->getHashArray();

        // 受注編集のうち、各種項目の変更が可能かどうか
        $price_change_flg = true;

        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrOrder = $objPurchase->getOrder($_REQUEST['order_id']);
        
        // 金額変更可能時（取消済み、入金済み、オプション契約：未）は、一部の変更チェックのみを実行
        if (!SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus(array($_REQUEST['order_id']), array(MDL_YFCAPI_DEFERRED_AUTH_CANCEL))
            || !SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus(array($_REQUEST['order_id']), array(MDL_YFCAPI_DEFERRED_PAID))
            || $arrOrder[MDL_YFCAPI_ORDER_COL_EXAMRESULT] != MDL_YFCAPI_DEFERRED_AVAILABLE
            || $arrMdlSetting['use_option'] != '0'
        ) {
            $price_change_flg = false;
        }
        
        //注文情報の変更確認
        $arrOrder = $this->lfGetChangeCheckOrderData($arrForm['order_id'], $objQuery);
        foreach ($arrOrder as $key => $val) {
            if($price_change_flg && ($key == 'discount' || $key == 'deliv_fee' || $key == 'charge' || $key == 'use_point')) continue;
            if ($arrForm[$key] != $val) {
                //変更があればfalse
                return false;
            }
        }

        if ($price_change_flg) {
            return true;
        }
        
        //注文商品情報の変更確認
        $arrOrderDetail = SC_Utils_Ex::sfSwapArray($this->lfGetChangeCheckOrderDetailData($arrForm['order_id'], $objQuery));
        foreach ($arrOrderDetail as $key => $val) {
            if ($arrForm[$key] != $val) {
                //変更があればfalse
                return false;
            }
        }
        //お届け先情報の変更確認
        $arrShippingTemp = SC_Utils_Ex::sfSwapArray($this->lfGetChangeCheckShippingData($arrForm['order_id'], $objQuery));
        $arrShippingId = $arrShippingTemp['shipping_id'];
        $arrShipping = array();
        //$arrFormとの比較のため加工する
        foreach ($arrShippingTemp as $key => $shipping) {
            foreach ($shipping as $key2 => $value) {
                $arrShipping[$key][$arrShippingId[$key2]] = $value;
            }
        }
        foreach ($arrShipping as $key => $val) {
            if ($arrForm[$key] != $val) {
                //変更があればfalse
                return false;
            }
        }
        //お届け先商品情報の変更確認
        $arrShipmentItem = array();
        foreach ($arrShippingId as $shipping_id) {
            $arrShipmentItemTemp = SC_Utils_Ex::sfSwapArray($this->lfGetChangeCheckShipmentItemData($arrForm['order_id'], $shipping_id, $objQuery));
            $arrShipmentItem['shipment_product_class_id'][$shipping_id] = $arrShipmentItemTemp['shipment_product_class_id'];
            $arrShipmentItem['shipment_product_name'][$shipping_id] = $arrShipmentItemTemp['shipment_product_name'];
            $arrShipmentItem['shipment_price'][$shipping_id] = $arrShipmentItemTemp['shipment_price'];
            $arrShipmentItem['shipment_quantity'][$shipping_id] = $arrShipmentItemTemp['shipment_quantity'];
        }
        foreach ($arrShipmentItem as $key => $val) {
            if ($arrForm[$key] != $val) {
                //変更があればfalse
                return false;
            }
        }
        return true;
    }

    /**
     * 買手情報一括登録CSV用のデータを取得する.
     *
     * @param string $order_id
     * @return array
     */
    function lfGetBuyerCsvData($order_id)
    {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        $arrCsvData = array();

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        // 受注情報
        $col = '*';
        $table = 'dtb_order';
        $where = 'order_id = ?';
        $arrWhereVal = array($order_id);
        $arrOrder = $objQuery->getRow($col, $table, $where, $arrWhereVal);
        // 受注日
        $arrCsvData['order_ymd'] = SC_Util_Mdl_YFCApi_Ex::getFormatedDate($arrOrder['create_date'], 'Ymd');
        // 出荷予定日
        $arrCsvData['ship_ymd'] = date('Ymd', strtotime('+' . $arrMdlSetting['ycf_ship_ymd'] . ' day'));
        // 受注番号
        $arrCsvData['order_id'] = $arrOrder['order_id'];
        // 氏名
        $arrCsvData['name'] = $arrOrder['order_name01'].'　'.$arrOrder['order_name02'];
        // 氏名（カナ）
        $arrCsvData['name_kana'] = $arrOrder['order_kana01'].' '.$arrOrder['order_kana02'];
        // 郵便番号
        $arrCsvData['postcode'] = $arrOrder['order_zip01'].$arrOrder['order_zip02'];
        // 住所①
        $arrCsvData['address1'] = SC_Util_Mdl_YFCApi_Ex::getPrefName($arrOrder['order_pref']).$arrOrder['order_addr01'].'　'.$arrOrder['order_addr02'];
        // 住所②
        $arrCsvData['address2'] = '';
        // 電話番号
        $arrCsvData['tel_num'] = $arrOrder['order_tel01'].$arrOrder['order_tel02'].$arrOrder['order_tel03'];
        // 予備１
        $arrCsvData['etc1'] = '';
        // 予備２
        $arrCsvData['etc2'] = '';
        // 予備３
        $arrCsvData['etc3'] = '';
        // 予備４
        $arrCsvData['etc4'] = '';
        // メールアドレス
        $arrCsvData['email'] = $arrOrder['order_email'];
        // 請求金額合計
        $arrCsvData['total_amount'] = $arrOrder['payment_total'];

        // 受注明細情報
        $arrOrderDetail = SC_Util_Mdl_YFCApi_Ex::getOrderDetailDeferred($arrOrder);
        for ($i = 0;$i < 10; $i++) {
            // 明細商品名
            $arrCsvData['item_name'.($i+1)] = $arrOrderDetail[$i]['itemName'];
            // 明細数量
            $arrCsvData['item_count'.($i+1)] = $arrOrderDetail[$i]['itemCount'];
            // 明細単価
            $arrCsvData['unit_price'.($i+1)] = $arrOrderDetail[$i]['unitPrice'];
            // 明細小計
            $arrCsvData['subtotal'.($i+1)] = $arrOrderDetail[$i]['subTotal'];
        }
        // 送付先情報
        $arrShipping = SC_Util_Mdl_YFCApi_Ex::getOrderShipping($order_id);
        // 送付先選択
        $arrCsvData['send_div'] = SC_Util_Mdl_YFCApi_Ex::getsendDiv($arrMdlSetting['ycf_send_div'], $arrOrder, $arrShipping);
        for ($i = 0; $i < 10; $i++) {
            // 送付先郵便番号
            $arrCsvData['send_postcode'.($i+1)] = $arrShipping[$i]['shipping_zip01'].$arrShipping[$i]['shipping_zip02'];
            // 送付先住所①
            $arrCsvData['send_address1'.($i+1)] = SC_Util_Mdl_YFCApi_Ex::getPrefName($arrShipping[$i]['shipping_pref']).$arrShipping[$i]['shipping_addr01'].'　'.$arrShipping[$i]['shipping_addr02'];
            // 送付先住所②
            $arrCsvData['send_address2'.($i+1)] = '';
            // 送付先名称
            $arrCsvData['send_name'.($i+1)] = $arrShipping[$i]['shipping_name01'].'　'.$arrShipping[$i]['shipping_name02'];
            // 送付先電話番号
            $arrCsvData['send_tel_num'.($i+1)] = $arrShipping[$i]['shipping_tel01'].$arrShipping[$i]['shipping_tel02'].$arrShipping[$i]['shipping_tel03'];
        }

        return $arrCsvData;
    }

    /**
     * 買手情報一括登録CSV用のデータを加工する.
     *
     * @param array $arrCsvData
     * @return array
     */
    function lfConvertBuyerCsvData($arrCsvData)
    {
        $objFormParam = new SC_FormParam();
        $this->lfInitParamOrderEdit($objFormParam, 'deferred_buyer_csv');
        $objFormParam->setParam($arrCsvData);
        $objFormParam->convParam($arrCsvData);
        $arrRet = $objFormParam->getHashArray();

        $arrRet['name'] = mb_substr($arrRet['name'], 0, 30);
        $arrRet['name_kana'] = mb_substr($arrRet['name_kana'], 0, 80);
        $address = $arrRet['address1'];
        $arrRet['address1'] = mb_substr($address, 0, 25);
        if (mb_substr($address, 25, 25) != '') {
            $arrRet['address2'] = mb_substr($address, 25, 25);
        }
        $arrRet['email'] = mb_substr($arrRet['email'], 0, 64);
        for($i = 1; $i <= 10; $i++){
            $arrRet['item_name'.$i] = mb_substr($arrRet['item_name'.$i], 0, 30);
        }
        for($i = 1; $i <= 10; $i++){
            $arrRet['send_name'.$i] = mb_substr($arrRet['send_name'.$i], 0, 30);
            $send_address = $arrRet['send_address1'.$i];
            $arrRet['send_address1'.$i] = mb_substr($send_address, 0, 25);
            if (mb_substr($send_address, 25, 25) != '') {
                $arrRet['send_address2'.$i] = mb_substr($send_address, 25, 25);
            }
        }

        return $arrRet;
    }

    /**
     * 注文情報の変更確認データを取得する.
     * @param $order_id
     * @param $objQuery
     * @return mixed
     */
    function lfGetChangeCheckOrderData($order_id, &$objQuery)
    {
        $col = <<< __EOS__
             order_id
            ,order_name01
            ,order_name02
            ,order_kana01
            ,order_kana02
            ,order_company_name
            ,order_email
            ,order_zip01
            ,order_zip02
            ,order_pref
            ,order_addr01
            ,order_addr02
            ,order_tel01
            ,order_tel02
            ,order_tel03
            ,order_fax01
            ,order_fax02
            ,order_fax03
            ,discount
            ,deliv_fee
            ,charge
__EOS__;

        if (USE_POINT === true) {
            $col .= ',use_point';
        }

        return $objQuery->getRow($col, 'dtb_order', 'order_id = ?', array($order_id));
    }

    /**
     * 注文商品情報の変更確認データを取得する.
     * @param $order_id
     * @param $objQuery
     * @return mixed
     */
    function lfGetChangeCheckOrderDetailData($order_id, &$objQuery)
    {
        $col = <<< __EOS__
             product_id
            ,product_class_id
            ,product_name
            ,price
            ,tax_rate
__EOS__;

        $objQuery->setOrder('order_detail_id');
        $arrRet = $objQuery->select($col, 'dtb_order_detail', 'order_id = ?', array($order_id));
        $objQuery->setOrder('');

        return $arrRet;
    }

    /**
     * お届け先情報の変更確認データを取得する.
     * @param $order_id
     * @param $objQuery
     * @return mixed
     */
    function lfGetChangeCheckShippingData($order_id, &$objQuery)
    {
        $col = <<< __EOS__
             shipping_id
            ,shipping_name01
            ,shipping_name02
            ,shipping_kana01
            ,shipping_kana02
            ,shipping_company_name
            ,shipping_tel01
            ,shipping_tel02
            ,shipping_tel03
            ,shipping_fax01
            ,shipping_fax02
            ,shipping_fax03
            ,shipping_pref
            ,shipping_zip01
            ,shipping_zip02
            ,shipping_addr01
            ,shipping_addr02
__EOS__;

        $objQuery->setOrder('shipping_id');
        $arrRet = $objQuery->select($col, 'dtb_shipping', 'order_id = ?', array($order_id));
        $objQuery->setOrder('');

        return $arrRet;
    }

    /**
     * お届け先商品情報の変更確認データを取得する.
     * @param $order_id
     * @param $shipping_id
     * @param $objQuery
     * @return mixed
     */
    function lfGetChangeCheckShipmentItemData($order_id, $shipping_id, &$objQuery)
    {
        $col = <<< __EOS__
             t1.product_class_id AS shipment_product_class_id
            ,t1.product_name AS shipment_product_name
            ,t1.price AS shipment_price
            ,t1.quantity AS shipment_quantity
__EOS__;
        $from = 'dtb_shipment_item t1 JOIN dtb_order_detail t2 USING (product_class_id, order_id)';
        $where = 'order_id = ? AND shipping_id = ?';

        $objQuery->setOrder('t2.order_detail_id');
        $arrRet = $objQuery->select($col, $from, $where, array($order_id, $shipping_id));
        $objQuery->setOrder('');

        return $arrRet;
    }
}
