<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
/**
 * プラグイン 決済補助の処理クラス
 * 
 */

require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Util_Ex.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Deferred_Util_Ex.php');

/**
 * プラグインの処理クラス
 */
class LC_YfcApiUtils {

    /** admin_common_utils_add.tpl適用対象外クラス名配列 */
    private $arrExcludeClassName = array(
            'LC_Page_Mdl_Yfc_Config_Ex',
            'LC_Page_Admin_Mdl_YFCApi_Config_Ex',
            'LC_Page_Admin_Plugin_YfcApiUtils_Config_Ex',
            'LC_Page_Admin_Contents_CsvSql_Ex',
            'LC_Page_Admin_Contents_RecommendSearch_Ex',
            'LC_Page_Admin_Customer_SearchCustomer_Ex',
            'LC_Page_Admin_Order_Disp_Ex',
            'LC_Page_Admin_Order_MailView_Ex',
            'LC_Page_Admin_Order_Multiple_Ex',
            'LC_Page_Admin_Order_Pdf_Ex',
            'LC_Page_Admin_Order_ProductSelect_Ex',
            'LC_Page_Admin_Products_ProductSelect_Ex',
            'LC_Page_Admin_System_Input_Ex'
        );

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
                if (preg_match('/^LC_Page_Mypage.*_Ex$/', $class_name) && strpos($filename, 'navi.tpl') === FALSE) {
                    //決済基本クラス取得
                    $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
                    //モジュール設定値取得
                    $arrMdlSetting = $objMdl->getUserSettings();
                    //オプションサービス契約が必須（0:契約済 1:未契約）
                    if($arrMdlSetting['use_option'] == '1') break;
                    $objTransform = new SC_Helper_Transform($source);
                    $template_file = 'mypage_navi_add.tpl';
                    switch($objPage->arrPageLayout['device_type_id']){
                        case DEVICE_TYPE_MOBILE:
                            break;
                        case DEVICE_TYPE_SMARTPHONE:
                            $template_dir = MDL_YFCAPI_TEMPLATE_PATH . 'sphone/';
                            $objTransform->select('h2.title',NULL, false)->insertAfter(file_get_contents($template_dir . $template_file));
                            break;
                        case DEVICE_TYPE_PC:
                        default:
                            $template_dir = MDL_YFCAPI_TEMPLATE_PATH . 'default/';
                            $objTransform->select('h2',NULL, false)->appendChild(file_get_contents($template_dir . $template_file));
                            break;
                    }
                    $source = $objTransform->getHTML();
                }
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                $template_dir = MDL_YFCAPI_TEMPLATE_PATH.'admin/';
                // admin_popupを使用したヘッダに関してはappendChildを行うとDOMが崩れるため対象外とする
                // LC_Page_Admin_Mail_Exに関しては$mode='query'の場合のみadmin_popupとなるため、特別対応
                if (preg_match('/^LC_Page_Admin.*_Ex$/', $class_name) &&
                        !in_array($class_name, $this->arrExcludeClassName) &&
                        !($class_name == 'LC_Page_Admin_Mail_Ex' && $objPage->getMode() == 'query')) {
                    $objTransform = new SC_Helper_Transform($source);
                    $template_file = 'admin_common_utils_add.tpl';
                    $objTransform->select('head')->appendChild(file_get_contents($template_dir . $template_file));
                    $source = $objTransform->getHTML();
                }
                switch ($filename) {
                    case 'products/product.tpl':
                        $objTransform = new SC_Helper_Transform($source);
                        // 商品管理・商品登録画面
                        $template_path = $template_dir.'products/products_product_add_error_msg.tpl';
                        $objTransform->select('div.contents-main > h2')->insertAfter(file_get_contents($template_path));
                        
                        $template_path = $template_dir.'products/products_product_add.tpl';
                        $objTransform->select('div#products > table > tr', 13)->appendChild(file_get_contents($template_path));
                        $source = $objTransform->getHTML();
                        break;
                    case 'products/confirm.tpl':
                        $objTransform = new SC_Helper_Transform($source);
                        // 商品管理・商品登録確認画面
                        $template_path = $template_dir.'products/products_product_confirm_add.tpl';
                        $objTransform->select('div#products > table > tr', 13)->appendChild(file_get_contents($template_path));
                        $source = $objTransform->getHTML();
                        break;
                    case 'order/index.tpl':
                        $objTransform = new SC_Helper_Transform($source);
                        // 受注管理・受注管理画面
                        $template_path = $template_dir.'order/order_index_add_products_type_search.tpl';
                        $objTransform->select('div.contents-main table tr', 8)->insertAfter(file_get_contents($template_path));
                        $source = $objTransform->getHTML();
                        break;
                    case 'basis/delivery_input.tpl':
                        $objTransform = new SC_Helper_Transform($source);
                        // 基本情報管理・配送方法設定
                        $template_path = $template_dir.'basis/delivery_input_add_delivtime_code.tpl';
                        $objTransform->select('div.contents-main table tr', 5)->find('td',0)->appendChild(file_get_contents($template_path));
                        $objTransform->select('div.contents-main table tr', 5)->find('td',1)->appendChild(file_get_contents($template_path));

                        $template_path = $template_dir.'basis/delivery_input_add_delivtime_code_warning.tpl';
                        $objTransform->select('div.contents-main > table')->appendChild(file_get_contents($template_path));
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
            //商品管理＞商品登録
            case 'LC_Page_Admin_Products_Product_Ex':
                if ($hook_point == 'after') {
                    $this->lfDoAdminProductsProductAfter($objPage);
                } else if($hook_point == 'before') {
                    $this->lfDoAdminProductsProductBefore($objPage);
                }else{
                    $this->lfDoAdminProductsProductMode($objPage);
                }
                break;
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
            //基本情報管理＞配送方法設定
            case 'LC_Page_Admin_Basis_DeliveryInput_Ex':
                if ($hook_point == 'after') {
                    $this->lfDoAdminBasisDeliveryInputAfter($objPage);
                } else if($hook_point == 'before') {
                    $this->lfDoAdminBasisDeliveryInputBefore($objPage);
                }else{
                    $this->lfDoAdminBasisDeliveryInputMode($objPage);
                }
                break;
            //支払方法選択
            case 'LC_Page_Shopping_Payment_Ex':
                if ($hook_point == 'after') {
                    $this->lfDoShoppingPaymentAfter($objPage);
                } else if($hook_point == 'before') {
                    $this->lfDoShoppingPaymentBefore($objPage);
                }else{
                    $this->lfDoShoppingPaymentMode($objPage);
                }
                break;
            default:
                break;
        }
    }

    /**
     * プラグイン設定値を取得
     *
     * @param void
     * @return array $arrRet 設定値
     */
    function loadData() {
        $arrRet = array();
        $arrData = SC_Plugin_Util_Ex::getPluginByPluginCode("YfcApiUtils");
        if (!SC_Utils_Ex::isBlank($arrData['free_field1'])) {
            $arrRet = unserialize($arrData['free_field1']);
        }
        return $arrRet;
    }

    /**
     * 商品管理＞商品登録(after)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminProductsProductAfter(&$objPage) {
        $mode = $objPage->getMode();
        $product_id = $objPage->arrForm['product_id'];
        
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParamProductsProduct($objFormParam, $mode);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        switch ($mode) {
            case 'complete':
                $objQuery =& SC_Query_Ex::getSingletonInstance();
                $objQuery->begin();
                $sqlval['plg_yfcapi_reserve_date'] = $objFormParam->getValue('plg_yfcapi_reserve_date');
                $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
                $objQuery->update('dtb_products', $sqlval, 'product_id = ?', array($product_id));
                $objQuery->commit();
                break;
            case 'edit':
            case 'upload_image':
            case "delete_image":
            case "upload_down":
            case "delete_down":
            case "recommend_select":
            case 'confirm_return':
                //出荷予定日入力値(POST値)を維持するためbreakが必要
                break;
            default:
                //出荷予定日（DB値）は整形が必要なため、ここで整形処理する.
                if(!empty($product_id)) {
                    //商品データ取得
                    $arrProductData = $objPage->lfGetProductData_FromDB($product_id);
                    //表示用のため整形した出荷予定日をセット
                    $formatedDate = SC_Util_Mdl_YFCApi_Ex::getFormatedDate($arrProductData['plg_yfcapi_reserve_date']);
                    if(!empty($formatedDate)) {
                        $objFormParam->setValue('plg_yfcapi_reserve_date', $formatedDate);
                    }
                }
                break;
        }
        $objPage->arrForm = array_merge($objPage->arrForm, $objFormParam->getHashArray());
    }


    /**
     * 商品管理＞商品登録(before)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminProductsProductBefore(&$objPage) {
        //決済基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //オプションサービス契約情報（0:契約済 1:未契約）
        $objPage->plg_use_option = $arrMdlSetting['use_option'];
        //予約販売利用情報（0:利用する 1:利用しない）
        $objPage->plg_advance_sale = $arrMdlSetting['advance_sale'];
        //エラーメッセージテンプレート変数(出荷予定日)
        $objPage->plg_yfcapi_reserve_date_error_msg = '';
        
        $mode = $objPage->getMode();
        switch($mode) {
            case 'edit':
                $objFormParam = new SC_FormParam_Ex();
                $this->lfInitParamProductsProduct($objFormParam, $mode);
                $objFormParam->setParam($_REQUEST);
                $objFormParam->convParam();
                $arrErr = $this->lfCheckErrorProductsProduct($objFormParam, $mode);
                if(!SC_Utils_Ex::isBlank($arrErr)){
                    $objPage->plg_yfcapi_reserve_date_error_msg = implode('<br />', $arrErr);
                }
                //ページ内入力項目を維持するためmodeをconfirm_returnにする
                if(!SC_Utils_Ex::isBlank($objPage->plg_yfcapi_reserve_date_error_msg)) {
                    $_REQUEST['mode'] = 'confirm_return';
                }
                break;
            default:
                break;
        }
    }

    /**
     * 商品管理＞商品登録(mode)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminProductsProductMode(&$objPage) {
        //nop
    }

    function lfInitParamProductsProduct(&$objFormParam, $mode) {
        switch ($mode) {
            case 'edit':
                if(!$_POST['has_product_class']) {
                    $objFormParam->addParam('商品種別', 'product_type_id', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
                }
            default:
                //予約商品出荷予定日
                $objFormParam->addParam('予約商品出荷予定日', 'plg_yfcapi_reserve_date', 8, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'NUM_COUNT_CHECK'));
                break;
        }
    }

    function lfCheckErrorProductsProduct(&$objFormParam, $mode) {
        //パラメタチェック
        $arrErr = $objFormParam->checkError();
        if(!SC_Utils_Ex::isBlank($arrErr)){
            return SC_Util_Mdl_YFCApi_Ex::sfGetParamArrMsg($arrErr);
        }
        
        switch ($mode) {
            case 'pre_edit':
            case 'copy':
                break;
            case 'edit':
                $tempDate = $objFormParam->getValue('plg_yfcapi_reserve_date');
                //新規登録，規格なし商品の編集の場合，以下の条件はNG
                if(!$_POST['has_product_class']) {
                    //【契約】
                    //(1)オプションサービス契約なし
                    //(2)予約販売利用なし
                    $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
                    $arrMdlSetting = $objMdl->getUserSettings();
                    if($objFormParam->getValue('product_type_id') == MDL_YFCAPI_PRODUCT_TYPE_ID && $arrMdlSetting['advance_sale'] == '1') {
                        $arrErr[] = '※ 予約商品は登録できません。商品種別を別の設定にするか、モジュール設定を見直してください。<br />';
                    }
                    //【出荷予定日】
                    //(1)予約商品
                    //(2)予約商品出荷予定日未設定
                    if(SC_Utils_Ex::isBlank($arrErr) && $objFormParam->getValue('product_type_id') == MDL_YFCAPI_PRODUCT_TYPE_ID && empty($tempDate)) {
                        $arrErr[] = '※ 予約商品の場合は必ず設定してください。<br />';
                    }
                }
                //日付チェック（出荷予定日）
                if(SC_Utils_Ex::isBlank($arrErr) && !empty($tempDate)) {
                    $year  = substr($tempDate, 0, 4);
                    $month = substr($tempDate, 4, 2);
                    $day   = substr($tempDate, 6, 2);
                    if(!checkdate($month, $day, $year)) {
                        $arrErr[] = '※ 出荷予定日が正しくありません<br />';
                    }
                }
                break;
            case 'complete':
            case 'upload_image':
            case 'delete_image':
            case 'upload_down':
            case 'delete_down':
            case 'recommend_select':
            case 'confirm_return':
            default:
                break;
        }
        return $arrErr;
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
        if($objPage->getMode() == 'plg_yfcapi_b2_csv'){
            $objFormParam = new SC_FormParam_Ex();
            $objPage->lfInitParam($objFormParam);
            $objFormParam->setParam($_POST);
            $objFormParam->convParam();
            $objFormParam->trimParam();
            $arrErr = $objPage->lfCheckError($objFormParam);
            $arrParam = $objFormParam->getHashArray();
            if (count($arrErr) == 0) {
                $where = 'dtb_order.del_flg = 0';
                $arrWhereVal = array();
                foreach ($arrParam as $key => $val) {
                    if ($val == '') {
                        continue;
                    }
                    $objPage->buildQuery($key, $where, $arrWhereVal, $objFormParam);
                    $where = preg_replace("/ create_date /", " dtb_order.create_date ", $where);
                    $where = preg_replace("/ update_date /", " dtb_order.update_date ", $where);
                }
                $order = 'dtb_order.update_date DESC, dtb_order.order_id DESC, dtb_shipping.shipping_id';
                $this->lfDoOutputB2Csv($where, $arrWhereVal, $order);
            }
            exit;
        } else if ($objPage->getMode() == 'plg_yfcapi_web_collect_csv') {
            $objFormParam = new SC_FormParam_Ex();
            $objPage->lfInitParam($objFormParam);
            $objFormParam->setParam($_POST);
            $objFormParam->convParam();
            $objFormParam->trimParam();
            $arrErr = $objPage->lfCheckError($objFormParam);
            $arrParam = $objFormParam->getHashArray();
            if (count($arrErr) == 0) {
                $where = 'dtb_order.del_flg = 0';
                $arrWhereVal = array();
                foreach ($arrParam as $key => $val) {
                    if ($val == '') {
                        continue;
                    }
                    $objPage->buildQuery($key, $where, $arrWhereVal, $objFormParam);
                    $where = preg_replace("/ create_date /", " dtb_order.create_date ", $where);
                    $where = preg_replace("/ update_date /", " dtb_order.update_date ", $where);
                }
                $order = 'dtb_order.update_date DESC, dtb_order.order_id DESC';
                $this->lfDoOutputWebCollectCSV($where, $arrWhereVal, $order);
            }
            exit;
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
     * 基本情報管理＞配送方法設定(after)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminBasisDeliveryInputAfter(&$objPage) {
        //配送時間帯コード取得
        $objPage->arrB2DelivTimeCode = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('b2_delivtime_code');
        //配送時間帯コード取得(afterの時点では$_POST['deliv_id']チェック済み)
        $arrDelivTimeConfig = $this->lfGetDelivTimeConfig($_POST['deliv_id']);
        $objFormParam = new SC_FormParam_Ex();
        //DB値を取得して設定
        $this->lfInitParamDeliveryInput($objFormParam, $arrDelivTimeConfig);
        $objFormParam->setParam($_REQUEST);
        $objFormParam->convParam();
        switch ($objPage->getMode()) {
            case 'edit':
                $arrErr = $objFormParam->checkError();
                if(SC_Utils_Ex::isBlank($arrErr)){
                    $this->lfRegistDataDelivtime($objFormParam->getHashArray(), $_POST['deliv_id']);
                }else{
                    trigger_error('', E_USER_ERROR);
                }
                break;
            default:
                break;
        }
        $objPage->arrForm = array_merge($objPage->arrForm, (array)$objFormParam->getFormParamList());
    }

    /**
     * 基本情報管理＞配送方法設定(before)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminBasisDeliveryInputBefore(&$objPage) {
        //nop
    }

    /**
     * 基本情報管理＞配送方法設定(mode)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoAdminBasisDeliveryInputMode(&$objPage) {
        switch ($objPage->getMode()) {
            default:
                break;
        }
    }

    /**
     * 配送方法設定 パラメーター初期化.
     * @param SC_FormParam_Ex $objFormParam
     * @param array $arrDelivTimeConfig
     * @return void
     */
    function lfInitParamDeliveryInput(&$objFormParam, $arrDelivTimeConfig) {
        for ($cnt = 1; $cnt <= DELIVTIME_MAX; $cnt++) {
            $objFormParam->addParam("お届け時間{$cnt}", "deliv_time$cnt", STEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'), $arrDelivTimeConfig[$cnt]["deliv_time"]);
            $objFormParam->addParam("B2配送時間帯{$cnt}","plg_yfcapi_b2_time_id{$cnt}", 4, 'n', array('NUM_CHECK','MAX_LENGTH_CHECK'), $arrDelivTimeConfig[$cnt]["plg_yfcapi_b2_time_id"]);
        }
    }

    /**
     * 配送業者IDからお届け時間の配列を取得する.
     *
     * @param  integer $deliv_id 配送業者ID
     * @return array   お届け時間の配列
     */
    function lfGetDelivTimeConfig($deliv_id){
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->setOrder('time_id');
        $results = $objQuery->select('time_id, deliv_time, plg_yfcapi_b2_time_id', 'dtb_delivtime', 'deliv_id = ?', array($deliv_id));
        $arrDelivTimeConfig = array();
        foreach ($results as $val) {
            $arrDelivTimeConfig[$val['time_id']]['deliv_time'] = $val['deliv_time'];
            $arrDelivTimeConfig[$val['time_id']]['plg_yfcapi_b2_time_id'] = $val['plg_yfcapi_b2_time_id'];
        }
        return $arrDelivTimeConfig;
    }

    /**
     * 配送業者IDからお届け時間の配列を取得する.
     *
     * @param $arrRet
     * @param  integer $deliv_id 配送業者ID
     * @return array   お届け時間の配列
     */
    function lfRegistDataDelivtime($arrRet,$deliv_id){
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // お届け時間
        $table = 'dtb_delivtime';
        $where = 'deliv_id = ? AND time_id = ?';
        for ($cnt = 1; $cnt <= DELIVTIME_MAX; $cnt++) {
            $sqlval = array();
            $arrWhereVal = array($deliv_id, $cnt);
            $keyname = "plg_yfcapi_b2_time_id$cnt";
            if ($arrRet["deliv_time{$cnt}"] != '') {
                $sqlval['plg_yfcapi_b2_time_id'] = $arrRet[$keyname];
                // 既存データの有無を確認
                $curData = $objQuery->select('*', $table, $where, $arrWhereVal);
                // 入力が空ではなく、DBに情報があれば更新
                if (count($curData)) {
                    $objQuery->update($table, $sqlval, $where, $arrWhereVal);
                }
            }
        }
        $objQuery->commit();
    }

    /**
     * B2CSV データを構築して出力する.
     *
     * @param string $where 検索条件の WHERE 句
     * @param array $arrVal 検索条件のパラメーター
     * @param string $order 検索結果の並び順
     * @return void
     */
    function lfDoOutputB2CSV($where, $arrVal, $order) {
        //実行時間を制限しない
        @set_time_limit(0);
        //プラグイン設定値を取得
        $arrSetting = $this->loadData();

        //データ取得
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $this->buildB2Cols($arrB2Cols,$arrSetting);
        $cols = SC_Utils_Ex::sfGetCommaList($arrB2Cols, true);
        $from = 'dtb_order';
        $from .= ' JOIN dtb_shipping USING(order_id)';
        $from .= ' LEFT JOIN dtb_delivtime ON dtb_order.deliv_id = dtb_delivtime.deliv_id AND dtb_shipping.time_id = dtb_delivtime.time_id';
        $objQuery->setOrder($order);
        $arrRet = $objQuery->select($cols,$from,$where,$arrVal);

        //出力データの加工
        $data = $this->convertB2Data($arrRet, $arrSetting);

        //CSV出力
        $objCSV = new SC_Helper_CSV_Ex();
        $fp = fopen('php://output', 'w');
        // ヘッダ構築
        if ($arrSetting['header_output'] == '1') {
            $header = $objCSV->sfArrayToCSV(SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('b2_header'));
            $header = mb_convert_encoding($header, 'SJIS-Win');
            $header .= "\r\n";
            fwrite($fp, $header);
        }
        $objCSV->lfDownloadCsv($data, 'yamato_b2_');
    }

    /**
     * Webコレクト取り込み用CSV データを構築して出力する.
     *
     * @param string $where 検索条件の WHERE 句
     * @param array $arrVal 検索条件のパラメーター
     * @param string $order 検索結果の並び順
     * @return void
     */
    function lfDoOutputWebCollectCSV($where, $arrVal, $order) {
        //実行時間を制限しない
        @set_time_limit(0);
        //プラグイン設定値を取得
        $arrSetting = $this->loadData();

        //データ取得
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $this->buildWebCollectCols($arrWebCollectCols,$arrSetting);
        $cols = SC_Utils_Ex::sfGetCommaList($arrWebCollectCols, true);
        $from = 'dtb_order';
        $from .= ' JOIN (SELECT payment_id, module_code FROM dtb_payment) dtb_payment USING (payment_id)';

        // 条件追加（送り状番号が登録されている）
        $where .= ' AND EXISTS (SELECT 1'
                . '               FROM dtb_shipping'
                . '              WHERE order_id = dtb_order.order_id'
                . '                AND dtb_shipping.plg_yfcapi_deliv_slip IS NOT NULL)'
                ;

        // 条件追加（支払方法がWEBコレのクレジット決済）
        $where .= ' AND module_code = ?';
        $arrVal[] = MDL_YFCAPI_CODE;
        $where .= ' AND ' . MDL_YFCAPI_ORDER_COL_PAYID . ' = ?';
        $arrVal[] = MDL_YFCAPI_PAYID_CREDIT;

        // 条件追加（注文ステータスが発送済み・クレジットカード出荷登録済み以外）
        $where .= ' AND status NOT IN (?,?)';
        $arrVal[] = ORDER_DELIV;
        $arrVal[] = MDL_YFCAPI_ORDER_SHIPPING_REGISTERED;


        $objQuery->setOrder($order);
        $arrRet = $objQuery->select($cols,$from,$where,$arrVal);

        //出力データの加工
        $data = $this->convertWebCollectData($arrRet, $arrSetting);

        //CSV出力
        $objCSV = new SC_Helper_CSV_Ex();
        $fp = fopen('php://output', 'w');
        // ヘッダ構築
        // 出力しない...
        //$header = $objCSV->sfArrayToCSV(SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('wc_header'));
        //$header = mb_convert_encoding($header, 'SJIS-Win');
        //$header .= "\r\n";
        //fwrite($fp, $header);

        $objCSV->lfDownloadCsv($data, 'web_collect_');

        //注文ステータス更新
        $objQuery->begin();
        foreach($arrRet as $arrRow){
            $arrUpdateVal = array();
            $arrUpdateVal['status'] = MDL_YFCAPI_ORDER_SHIPPING_REGISTERED;
            $arrUpdateVal['update_date'] = date('Y-m-d H:i:s');
            $objQuery->update('dtb_order', $arrUpdateVal, 'order_id = ?', array($arrRow['order_no']));
        }
        $objQuery->commit();
    }

    /**
     * B2用の端末IDを取得
     *  1:PC
     *  2:携帯(SPも携帯に含める)
     *  
     * @param integer $device_id
     * @return integer $retDevCode
     */
    function getDeviceCodeB2($device_id){
        switch ($device_id) {
            case DEVICE_TYPE_ADMIN:
            case DEVICE_TYPE_PC:
                $retDevCode = 1;
                break;
            default:
                $retDevCode = 2;
                break;
        }
        return $retDevCode;
    }

    /**
     * 文字列の調整(convert/truncate/改行コード/禁止文字)
     * @param string $str
     * @param string $option
     * @param integer $length
     * @param bool $is_truncate
     * @return string $str
     */
    function gen($str='', $option='KVa', $length = 0, $is_truncate = false){
        if(SC_Utils_Ex::isBlank($str)) return '';
        //エンコード
        $str =  mb_convert_kana($str,$option);
        //文字列中の改行コード削除
        $str = SC_Util_Mdl_YFCApi_Ex::deleteBr($str);
        //切り出し
        if($is_truncate) $str = mb_strimwidth($str, 0, $length);
        return $str;
    }

    /**
     * B2用CSVのコンバート.
     * @param array $arrData
     * @param array $arrSetting
     * @return array $tempData
     */
    function convertB2Data($arrData, $arrSetting){
        if(count($arrData) == 0) return array();
        $masterData = new SC_DB_MasterData_Ex();
        $arrPref = $masterData->getMasterData('mtb_pref');
        $arrSetting['baseinfo'] = SC_Helper_DB_Ex::sfGetBasisData();

        //決済基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();

        $tempData = array();
        foreach($arrData as $value){
            $tempData[] = $this->convertB2Line($value,$arrSetting,$arrPref,$arrMdlSetting);
        }
        return $tempData;
    }

    /**
     * B2用CSVのコンバート(行単位).
     * @param array $arrData
     * @param array $arrSetting
     * @param array $arrPref
     * @param array $arrMdlSetting
     * @return array $arrData
     */
    function convertB2Line($arrData,$arrSetting, $arrPref,$arrMdlSetting){
        if(count($arrData) == 0) return array();
        //配送時間
        $arrB2DelivTimeCode = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('b2_delivtime_code');
        //文字列のカット
        $truncate = ($arrSetting['is_truncate']=='1')? true : false;
        //依頼主の変更
        $arrData = $this->convertB2OrderCol($arrData, $arrSetting, $arrPref);

        //1. お客様管理番号
        $arrData['order_shipping_id'] = $this->gen($arrData['order_shipping_id'],'a',20,$truncate);
        //2. 送り状種別
        $arrData['plg_yfcapi_deliv_slip_type'] = $this->gen($arrSetting['deliv_slip_type'][$arrData['payment_id']],'n',1,$truncate);
        //3. クール区分
        $arrData['plg_yfcapi_cool_kb'] = $this->gen($arrSetting['cool_kb'][$arrData['deliv_id']],'n',1,$truncate);
        //4. 伝票番号
        $arrData['plg_yfcapi_deliv_slip'] = $this->gen($arrData['plg_yfcapi_deliv_slip'],'n',12,$truncate);
        //5. 出荷予定日(ダウンロードした日)
        $arrData['deliv_date'] = $this->gen($arrData['deliv_date'],'a',10,$truncate);
        //6. お届け予定（指定）日
        $arrData['shipping_date'] = $this->gen($arrData['shipping_date'],'a',10,$truncate);
        //7. 配達時間帯
        $arrData['plg_yfcapi_deliv_time_code'] = $this->gen($arrB2DelivTimeCode[$arrData['plg_yfcapi_b2_time_id']],'n',4,$truncate);
        //8.お届け先コード
        $arrData['plg_yfcapi_deliv_code'] = $this->gen($arrData['plg_yfcapi_deliv_code'],'n',20,$truncate);
        //9. お届け先電話番号
        $arrData['shipping_tel'] = $this->gen($arrData['shipping_tel'],'a',15,$truncate);
        //10. お届け先電話番号枝番
        $arrData['shipping_tel_no'] = $this->gen($arrData['shipping_tel_no'],'n',2,$truncate);
        //11. お届け先郵便番号
        $arrData['shipping_zip'] = $this->gen($arrData['shipping_zip'],'a',8,$truncate);
        //12. お届け先住所
        $arrData['shipping_addr01'] = $this->gen($arrData['shipping_addr01'],'KVa',64,$truncate);
        //13. お届け先住所（アパートマンション名）
        $arrData['shipping_addr02'] = $this->gen($arrData['shipping_addr02'],'KVa',32,$truncate);
        //14. お届け先会社・部門名１
        $arrData['shipping_company_name'] = $this->gen($arrData['shipping_company_name'],'KVa',50,$truncate);
        //15. お届け先会社・部門名２
        $arrData['shipping_company_name02'] = $this->gen($arrData['shipping_company_name02'],'KVa',50,$truncate);
        //16. お届け先名
        $arrData['shipping_name'] = $this->gen($arrData['shipping_name'],'KVa',32,$truncate);
        //17. お届け先名称略カナ
        $arrData['shipping_kana'] = $this->gen($arrData['shipping_kana'],'ks',50,$truncate);
        //18. 敬称 (空で出力)
        $arrData['shipping_title'] = $this->gen($arrData['shipping_title'],'KVa',4,$truncate);
        //19. ご依頼主コード
        $arrData['order_code'] = $this->gen($arrData['order_code'],'a',20,$truncate);
        //20. ご依頼主電話番号
        $arrData['order_tel'] = $this->gen($arrData['order_tel'],'a',15,$truncate);
        //21. ご依頼主電話番号枝番 (空で出力)
        $arrData['order_tel_no'] = $this->gen($arrData['order_tel_no'],'n',2,$truncate);
        //22. ご依頼主郵便番号
        $arrData['order_zip'] = $this->gen($arrData['order_zip'],'n',8,$truncate);
        //23. ご依頼主住所
        $arrData['order_addr01'] = $this->gen($arrData['order_addr01'],'KVa',64,$truncate);
        //24. ご依頼主住所（ｱﾊﾟｰﾄﾏﾝｼｮﾝ名）
        $arrData['order_addr02'] = $this->gen($arrData['order_addr02'],'KVa',32,$truncate);
        //25. ご依頼主名
        $arrData['order_name'] = $this->gen($arrData['order_name'],'KVa',32,$truncate);
        //26. ご依頼主略称カナ
        $arrData['order_kana'] = $this->gen($arrData['order_kana'],'ks',50,$truncate);
        //27. 品名コード１
        $arrData['shipping_product_code01'] = $this->gen($arrData['shipping_product_code01'],'a',30,$truncate);
        //28. 品名１
        $arrData['shipping_product_name01'] = $this->gen($arrData['shipping_product_name01'],'KVa',50,$truncate);
        //29. 品名コード２
        $arrData['shipping_product_code02'] = $this->gen($arrData['shipping_product_code02'],'a',30,$truncate);
        //30. 品名２
        $arrData['shipping_product_name02'] = $this->gen($arrData['shipping_product_name02'],'KVa',50,$truncate);
        //31. 荷扱い１
        $arrData['shipping_handling01'] = $this->gen($arrData['shipping_handling01'],'KVa',20,$truncate);
        //32. 荷扱い２
        $arrData['shipping_handling02'] = $this->gen($arrData['shipping_handling02'],'KVa',20,$truncate);
        //33. 記事
        $arrData['shipping_memo'] = $this->gen($arrData['shipping_memo'],'KVa',32,$truncate);
        //34. コレクト代金引換額（税込）
        //35. コレクト内消費税額等
        if($arrSetting['deliv_slip_type'][$arrData['payment_id']] == '2'){
            $arrData['shipping_collect_inctax'] = $this->gen($arrData['payment_total'],'n',7);
            $arrData['shipping_collect_tax'] = $this->gen($arrData['tax'],'n',7);
        }else{
            $arrData['shipping_collect_inctax'] = '';
            $arrData['shipping_collect_tax'] = '';
        }
        //36. 営業所止置き(0 : 利用しない 1 : 利用する)
        $arrData['shipping_hold_reassign'] = $this->gen('0','n',1,$truncate);
        //37. 営業所コード
        $arrData['shipping_hold_office_code'] = $this->gen('','n',6,$truncate);
        //38. 発行枚数
        $arrData['publish_count'] = $this->gen($arrData['publish_count'],'n',2,$truncate);
        //39. 個数口枠の印字(1 : 印字する 2 : 印字しない)
        $arrData['publish_koguchi'] = $this->gen($arrData['publish_koguchi'],'n',1,$truncate);
        //40. ご請求先顧客コード
        $arrData['plg_yfcapi_claim_customer_code'] = $this->gen($arrSetting['claim_customer_code'],'n',12,$truncate);
        //41. ご請求先分類コード
        $arrData['plg_yfcapi_claim_type_code'] = $this->gen($arrSetting['claim_type_code'],'n',3,$truncate);
        //42. 運賃管理番号
        $arrData['plg_yfcapi_transportation_no'] = $this->gen($arrSetting['transportation_no'],'n',2,$truncate);
        //43. 注文時カード払いデータ登録(0 : 無し 1 : 有り)
        $arrData['service_card_register'] = '';
        //44. 注文時カード払い加盟店番号
        $arrData['service_card_shop_no'] = '';
        //45. 注文時カード払い申し込み受付番号１
        $service_card_order_no01 = $arrData['service_card_order_no01'];
        $arrData['service_card_order_no01'] = '';
        if ($arrData['module_code'] == MDL_YFCAPI_CODE
            && ($arrData[MDL_YFCAPI_ORDER_COL_PAYID] == MDL_YFCAPI_PAYID_CREDIT)
        ) {
            // カード決済のみ
            $arrData['service_card_register'] = $this->gen('1','n',1,$truncate);
            $arrData['service_card_shop_no'] = $this->gen($arrMdlSetting['shop_id'], 'n', 9, $truncate);
            $arrData['service_card_order_no01'] = $this->gen($service_card_order_no01, 'a', 23);
        }
        //46. 注文時カード払い申し込み受付番号２ (空で出力)
        $arrData['service_card_order_no02'] = $this->gen('','a',23);
        //47. 注文時カード払い申し込み受付番号３ (空で出力)
        $arrData['service_card_order_no03'] = $this->gen('','a',23);
        //48. お届け予定eメール利用区分(0 : 利用しない 1 : 利用する)
        $arrData['service_deliv_mail_enable'] = $this->gen($arrSetting['service_deliv_mail_enable'],'n',1,$truncate);
        //49. お届け予定eメールe-mailアドレス
        $arrData['service_deliv_mail_address'] = ($arrData['service_deliv_mail_enable'] == '1') ? $this->gen($arrData['service_deliv_mail_address'],'a',60,$truncate) : '';
        //50. 入力機種
        $arrData['service_deliv_device_id'] = $this->gen($this->getDeviceCodeB2($arrData['service_deliv_device_id']),'n',1,$truncate);
        //51. お届け予定eメールメッセージ
        $arrData['service_deliv_mail_message'] = ($arrData['service_deliv_mail_enable'] == '1') ? $this->gen($arrSetting['service_deliv_mail_message'],'KVSA',148,$truncate) : '';
        //52. お届け完了eメール利用区分(0 : 利用しない 1 : 利用する)
        $arrData['service_complete_mail_enable'] = $this->gen($arrSetting['service_complete_mail_enable'],'n',1,$truncate);
        //53. お届け完了eメールe-mailアドレス
        $arrData['service_complete_mail_address'] = ($arrData['service_complete_mail_enable'] == '1') ? $this->gen($arrData['service_complete_mail_address'],'a',60,$truncate) : '';
        //54. お届け完了eメールメッセージ
        $arrData['service_complete_mail_message'] = ($arrData['service_complete_mail_enable'] == '1') ? $this->gen($arrSetting['service_complete_mail_message'],'KVSA',318,$truncate) : '';
        //55. クロネコ収納代行利用区分(0 : 無し 1 : 有り)
        $arrData['service_receiving_agent_enable'] = $this->gen('0','n',1,$truncate);
        //56. 予備
        $arrData['service_receiving_agent_yobi'] = $this->gen('','n',1,$truncate);
        //57. 収納代行請求金額（税込）
        $arrData['service_receiving_agent_claim_payment_total'] = $this->gen('','n',7,$truncate);
        //58. 収納代行内消費税額等
        $arrData['service_receiving_agent_claim_tax'] = $this->gen('','n',7,$truncate);
        //59. 収納代行請求先郵便番号
        $arrData['service_receiving_agent_zip'] = $this->gen('','a',8,$truncate);
        //60. 収納代行請求先住所
        $arrData['service_receiving_agent_addr01'] = $this->gen('','KVa',64,$truncate);
        //61. 収納代行請求先住所（ｱﾊﾟｰﾄﾏﾝｼｮﾝ名)
        $arrData['service_receiving_agent_addr02'] = $this->gen('','KVa',32,$truncate);
        //62. 収納代行請求先会社･部門名１
        $arrData['service_receiving_agent_claim_campany01'] = $this->gen('','KVa',50,$truncate);
        //63. 収納代行請求先会社･部門名２
        $arrData['service_receiving_agent_claim_campany02'] = $this->gen('','KVa',50,$truncate);
        //64. 収納代行請求先名（漢字）
        $arrData['service_receiving_agent_claim_name'] = $this->gen('','KVa',32,$truncate);
        //65. 収納代行請求先名（カナ）
        $arrData['service_receiving_agent_claim_kana'] = $this->gen('','ks',50,$truncate);
        //66. 収納代行問合せ先名（カナ）
        $arrData['service_receiving_agent_info_kana'] = $this->gen('','KVa',32,$truncate);
        //67. 収納代行問合せ先郵便番号
        $arrData['service_receiving_agent_info_zip'] = $this->gen('','a',8,$truncate);
        //68. 収納代行問合せ先住所
        $arrData['service_receiving_agent_info_addr01'] = $this->gen('','KVa',64,$truncate);
        //69. 収納代行問合せ先住所（ｱﾊﾟｰﾄﾏﾝｼｮﾝ名）
        $arrData['service_receiving_agent_info_addr02'] = $this->gen('','KVa',32,$truncate);
        //70. 収納代行問合せ先電話番号
        $arrData['service_receiving_agent_info_tel'] = $this->gen('','a',15,$truncate);
        //71. 収納代行管理番号
        $arrData['service_receiving_agent_no'] = $this->gen('','a',20,$truncate);
        //72. 収納代行品名
        $arrData['service_receiving_agent_product_name'] = $this->gen('','KVa',50,$truncate);
        //73. 収納代行備考
        $arrData['service_receiving_agent_memo'] = $this->gen('','KVa',28,$truncate);
        //74. 予備０１ (空で出力)
        $arrData['reserve1'] = $this->gen('','a',10,$truncate);
        //75. 予備０２ (空で出力)
        $arrData['reserve2'] = $this->gen('','a',10,$truncate);
        //76. 予備０３ (空で出力)
        $arrData['reserve3'] = $this->gen('','a',10,$truncate);
        //77. 予備０４ (空で出力)
        $arrData['reserve4'] = $this->gen('','a',10,$truncate);
        //78. 予備０５ (空で出力)
        $arrData['reserve5'] = $this->gen('','a',10,$truncate);
        //79. 予備０６ (空で出力)
        $arrData['reserve6'] = $this->gen('','a',10,$truncate);
        //80. 予備０７ (空で出力)
        $arrData['reserve7'] = $this->gen('','a',10,$truncate);
        //81. 予備０８ (空で出力)
        $arrData['reserve8'] = $this->gen('','a',10,$truncate);
        //82. 予備０９ (空で出力)
        $arrData['reserve9'] = $this->gen('','a',10,$truncate);
        //83. 予備１０ (空で出力)
        $arrData['reserve10'] = $this->gen('','a',10,$truncate);
        //84. 予備１１ (空で出力)
        $arrData['reserve11'] = $this->gen('','a',10,$truncate);
        //85. 予備１２ (空で出力)
        $arrData['reserve12'] = $this->gen('','a',10,$truncate);
        //86. 予備１３ (空で出力)
        $arrData['reserve13'] = $this->gen('','a',10,$truncate);
        //87. 投函予定メール利用区分(0 : 利用しない 1 : 利用する PC宛て 2 : 利用する モバイル宛て)
        $arrData['posting_plan_mail_enable'] = ($arrData['plg_yfcapi_deliv_slip_type'] == MDL_YFCAPI_DELIV_SLIP_TYPE_NEKOPOS && $arrSetting['posting_plan_mail_enable'] == '1') ? $this->gen($this->getDeviceCodeB2($arrData['device_type_id']),'n',1,$truncate) : '0';
        //88. 投函予定メールe-mailアドレス
        $arrData['posting_plan_mail_address'] = ($arrData['posting_plan_mail_enable'] != '0') ? $this->gen($arrData['posting_plan_mail_address'],'a',60,$truncate) : '';
        //89. 投函予定メールメッセージ
        $arrData['posting_plan_mail_message'] = ($arrData['posting_plan_mail_enable'] != '0') ? $this->gen($arrSetting['posting_plan_mail_message'],'KVSA',148,$truncate) : '';
        //90. 投函完了メール(受人宛て)利用区分(0 : 利用しない 1 : 利用する PC宛て 2 : 利用する モバイル宛て)
        $arrData['posting_complete_deliv_mail_enable'] = $this->gen('0','n',1,$truncate);
        //91. 投函完了メール(受人宛て)e-mailアドレス
        $arrData['posting_complete_deliv_mail_address'] = $this->gen('','a',60,$truncate);
        //92. 投函完了メール(受人宛て)メッセージ
        $arrData['posting_complete_deliv_mail_message'] = $this->gen('','KVSA',318,$truncate);
        //93. 投函完了メール(出人宛て)利用区分(0 : 利用しない 1 : 利用する PC宛て 2 : 利用する モバイル宛て)
        $arrData['posting_complete_order_mail_enable'] = ($arrData['plg_yfcapi_deliv_slip_type'] == MDL_YFCAPI_DELIV_SLIP_TYPE_NEKOPOS && $arrSetting['posting_complete_deliv_mail_enable'] == '1') ? $this->gen($this->getDeviceCodeB2($arrData['device_type_id']),'n',1,$truncate) : '0';
        //94. 投函完了メール(出人宛て)e-mailアドレス
        $arrData['posting_complete_order_mail_address'] = ($arrData['posting_complete_order_mail_enable'] != '0') ? $this->gen($arrData['posting_complete_order_mail_address'],'a',60,$truncate) : '';
        //95. 投函完了メール(出人宛て)メッセージ
        $arrData['posting_complete_order_mail_message'] = ($arrData['posting_complete_order_mail_enable'] != '0') ? $this->gen($arrSetting['posting_complete_deliv_mail_message'],'KVSA',318,$truncate) : '';
        //96. 連携管理番号
        $arrData['plg_yfcapi_control_no'] = $this->gen($arrData['plg_yfcapi_control_no'],'a',50,$truncate);
        //97. 通知メールアドレス
        $arrData['notification_mail_address'] = $this->gen($arrData['notification_mail_address'],'a',60,$truncate);

        //不要項目
        unset($arrData['deliv_id']);
        unset($arrData['payment_id']);
        unset($arrData['payment_total']);
        unset($arrData['tax']);
        unset($arrData['device_type_id']);
        unset($arrData[MDL_YFCAPI_ORDER_COL_PAYID]);
        unset($arrData['time_id']);
        unset($arrData['plg_yfcapi_b2_time_id']);
        unset($arrData['module_code']);

        return $arrData;
    }

    /**
     * Webコレクト取込用CSVのコンバート.
     * @param array $arrData
     * @param array $arrSetting
     * @return array $tempData
     */
    function convertWebCollectData($arrData, $arrSetting){
        if(count($arrData) == 0) return array();
        $arrSetting['baseinfo'] = SC_Helper_DB_Ex::sfGetBasisData();

        //決済基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();

        $tempData = array();
        foreach($arrData as $value){
            $tempData[] = $this->convertWebCollectLine($value,$arrSetting,$arrMdlSetting);
        }
        return $tempData;
    }

    /**
     * Webコレクト取込用CSVのコンバート(行単位).
     * @param array $arrData
     * @param array $arrSetting
     * @param array $arrMdlSetting
     * @return array $arrData
     */
    function convertWebCollectLine($arrData,$arrSetting,$arrMdlSetting){
        if(count($arrData) == 0) return array();

        //文字列のカット
        $truncate = ($arrSetting['is_truncate']=='1')? true : false;

        $tempData = array();

        //1. 受付番号
        $tempData['order_no'] = $this->gen($arrData['order_no'],'n',23,$truncate);
        //2. 送り状番号
        $tempData['slip_no'] = $this->gen($arrData['slip_no'],'a',24,$truncate);
        //3. 配送サービスコード
        $tempData['delivery_service_code '] = $this->gen($arrSetting['delivery_service_code'][$arrData['deliv_id']],'n',2,$truncate);

        return $tempData;
    }

    /**
     * B2用CSV「ご依頼主」のコンバート.
     * @param array $arrData
     * @param array $arrSetting
     * @param array $arrPref
     * @return array $arrData
     */
    function convertB2OrderCol($arrData, $arrSetting, $arrPref){
        if(count($arrData) == 0) return array();
        $tel_hyphenation = ($arrSetting['tel_hyphenation'] == '1') ? '-' : '';
        $zip_hyphenation = ($arrSetting['zip_hyphenation'] == '1') ? '-' : '';
        $baseinfo = $arrSetting['baseinfo'];
        switch($arrSetting['output_order_type']){
            case '1':
                //SHOPマスター＞基本情報
                $arrData['order_code']   = '';
                $arrData['order_tel']    = $baseinfo['tel01'] . $tel_hyphenation .$baseinfo['tel02']. $tel_hyphenation .$baseinfo['tel03'];
                $arrData['order_tel_no'] = '';
                $arrData['order_zip']    = $baseinfo['zip01'] . $zip_hyphenation .$baseinfo['zip02'];
                $arrData['order_addr01'] = $arrPref[$baseinfo['pref']].$baseinfo['addr01'];
                $arrData['order_addr02'] = $baseinfo['addr02'];
                $arrData['order_name']   = $baseinfo['company_name'];
                $arrData['order_kana']   = $baseinfo['company_kana'];
                break;
            case '2':
                //特定商取引法
                $arrData['order_code']   = '';
                $arrData['order_tel']    = $baseinfo['law_tel01'] . $tel_hyphenation .$baseinfo['law_tel02']. $tel_hyphenation .$baseinfo['law_tel03'];
                $arrData['order_tel_no'] = '';
                $arrData['order_zip']    = $baseinfo['law_zip01'] . $zip_hyphenation .$baseinfo['law_zip02'];
                $arrData['order_addr01'] = $arrPref[$baseinfo['law_pref']].$baseinfo['law_addr01'];
                $arrData['order_addr02'] = $baseinfo['law_addr02'];
                $arrData['order_name']   = $baseinfo['law_company'];
                $arrData['order_kana']   = '';
                break;
            default:
                break;
        }
        return $arrData;
    }

    /**
     * B2用SQLのカラム構築.
     * @param array $arrB2Cols
     * @param array $arrSetting
     */
    function buildB2Cols(&$arrB2Cols, $arrSetting){
        /* CSV取得項目 */
        $arrB2Cols = array();
        //1. お客様管理番号
        $arrB2Cols[] = "(dtb_order.order_id || '_' || dtb_shipping.shipping_id) AS order_shipping_id";
        //2. 送り状種別(空で取得)
        $arrB2Cols[] = "'' AS plg_yfcapi_deliv_slip_type";
        //3. クール区分(空で取得)
        $arrB2Cols[] = "'' AS plg_yfcapi_cool_kb";
        //4. 伝票番号
        $arrB2Cols[] = "plg_yfcapi_deliv_slip";
        //5. 出荷予定日(ダウンロードした日)
        if(DB_TYPE == 'pgsql') {
            $arrB2Cols[] = "TO_CHAR( NOW(), 'YYYY/MM/DD') AS deliv_date";
        }else{
            $arrB2Cols[] = "DATE_FORMAT( NOW(), '%Y/%m/%d') AS deliv_date";
        }
        //6. お届け予定（指定）日
        if(DB_TYPE == 'pgsql') {
            $arrB2Cols[] = "TO_CHAR( shipping_date, 'YYYY/MM/DD') AS shipping_date";
        }else{
            $arrB2Cols[] = "DATE_FORMAT( shipping_date, '%Y/%m/%d') AS shipping_date";
        }
        //7. 配達時間帯 (空で取得)
        $arrB2Cols[] = "'' AS plg_yfcapi_deliv_time_code";

        /* お届け先 */
        //8.お届け先コード (空で出力)
        $arrB2Cols[] = "'' AS plg_yfcapi_deliv_code";
        //9. お届け先電話番号
        if($arrSetting['tel_hyphenation'] == '1'){
            $arrB2Cols[] = "(COALESCE(shipping_tel01,'') || '-' || COALESCE(shipping_tel02,'') || '-' || COALESCE(shipping_tel03,'')) AS shipping_tel";
        }else{
            $arrB2Cols[] = "(COALESCE(shipping_tel01,'') || COALESCE(shipping_tel02,'') || COALESCE(shipping_tel03,'')) AS shipping_tel";
        }
        //10. お届け先電話番号枝番 (空で出力)
        $arrB2Cols[] = "'' AS shipping_tel_no";
        //11. お届け先郵便番号
        if($arrSetting['zip_hyphenation'] == '1'){
            $arrB2Cols[] = "(COALESCE(shipping_zip01,'') || '-' || COALESCE(shipping_zip02,'')) AS shipping_zip";
        } else {
            $arrB2Cols[] = "(COALESCE(shipping_zip01,'') || COALESCE(shipping_zip02,'')) AS shipping_zip";
        }
        //12. お届け先住所
        $arrB2Cols[] = "((SELECT name FROM mtb_pref WHERE mtb_pref.id = shipping_pref) || COALESCE(shipping_addr01 , '')) AS shipping_addr01";
        //13. お届け先住所（アパートマンション名）
        $arrB2Cols[] = "shipping_addr02";
        //14. お届け先会社・部門名１
        $arrB2Cols[] = "shipping_company_name";
        //15. お届け先会社・部門名２ (空で出力)
        $arrB2Cols[] = "'' AS shipping_company_name02";
        //16. お届け先名
        $arrB2Cols[] = "(COALESCE(shipping_name01,'') || '　' || COALESCE(shipping_name02,'')) AS shipping_name";
        //17. お届け先名称略カナ
        $arrB2Cols[] = "(COALESCE(shipping_kana01,'') || '　' || COALESCE(shipping_kana02,'')) AS shipping_kana";
        //18. 敬称 (空で出力)
        $arrB2Cols[] = "'' AS shipping_title";

        /* ご依頼主 */
        //19. ご依頼主コード
        $arrB2Cols[] = "'' AS order_code";
        //20. ご依頼主電話番号
        if($arrSetting['tel_hyphenation'] == '1'){
            $arrB2Cols[] = "(COALESCE(order_tel01,'') || '-' || COALESCE(order_tel02,'') || '-' || COALESCE(order_tel03,'')) AS order_tel";
        }else{
            $arrB2Cols[] = "(COALESCE(order_tel01,'') || COALESCE(order_tel02,'') || COALESCE(order_tel03,'')) AS order_tel";
        }
        //21. ご依頼主電話番号枝番 (空で出力)
        $arrB2Cols[] = "'' AS order_tel_no";
        //22. ご依頼主郵便番号
        if($arrSetting['zip_hyphenation'] == '1'){
            $arrB2Cols[] = "(COALESCE(order_zip01,'') || '-' || COALESCE(order_zip02,'')) AS order_zip";
        }else{
            $arrB2Cols[] = "(COALESCE(order_zip01,'') || COALESCE(order_zip02,'')) AS order_zip";
        }
        //23. ご依頼主住所
        $arrB2Cols[] = "((SELECT name FROM mtb_pref WHERE mtb_pref.id = order_pref) || COALESCE(order_addr01 , '')) AS order_addr01";
        //24. ご依頼主住所（ｱﾊﾟｰﾄﾏﾝｼｮﾝ名）
        $arrB2Cols[] = "order_addr02";
        //25. ご依頼主名
        $arrB2Cols[] = "(COALESCE(order_name01,'') || '　' || COALESCE(order_name02,'')) AS order_name";
        //26. ご依頼主略称カナ
        $arrB2Cols[] = "(COALESCE(order_kana01,'') || '　' || COALESCE(order_kana02,'')) AS order_kana";

        //27. 品名コード１
        //28. 品名１
        if(DB_TYPE == 'pgsql'){
            $arrB2Cols[] = "(SELECT product_code FROM dtb_shipment_item WHERE dtb_shipment_item.order_id=dtb_shipping.order_id AND dtb_shipment_item.shipping_id=dtb_shipping.shipping_id LIMIT 1 OFFSET 0) AS shipping_product_code01";
            $arrB2Cols[] = "(SELECT product_name FROM dtb_shipment_item WHERE dtb_shipment_item.order_id=dtb_shipping.order_id AND dtb_shipment_item.shipping_id=dtb_shipping.shipping_id LIMIT 1 OFFSET 0) AS shipping_product_name01";
        }else{
            $arrB2Cols[] = "(SELECT product_code FROM dtb_shipment_item WHERE dtb_shipment_item.order_id=dtb_shipping.order_id AND dtb_shipment_item.shipping_id=dtb_shipping.shipping_id LIMIT 0,1) AS shipping_product_code01";
            $arrB2Cols[] = "(SELECT product_name FROM dtb_shipment_item WHERE dtb_shipment_item.order_id=dtb_shipping.order_id AND dtb_shipment_item.shipping_id=dtb_shipping.shipping_id LIMIT 0,1) AS shipping_product_name01";
        }

        //29. 品名コード２
        //30. 品名２
        if(DB_TYPE == 'pgsql'){
            $arrB2Cols[] = "(SELECT product_code FROM dtb_shipment_item WHERE dtb_shipment_item.order_id=dtb_shipping.order_id AND dtb_shipment_item.shipping_id=dtb_shipping.shipping_id LIMIT 1 OFFSET 1) AS shipping_product_code02";
            $arrB2Cols[] = "(SELECT product_name FROM dtb_shipment_item WHERE dtb_shipment_item.order_id=dtb_shipping.order_id AND dtb_shipment_item.shipping_id=dtb_shipping.shipping_id LIMIT 1 OFFSET 1) AS shipping_product_name02";
        }else{
            $arrB2Cols[] = "(SELECT product_code FROM dtb_shipment_item WHERE dtb_shipment_item.order_id=dtb_shipping.order_id AND dtb_shipment_item.shipping_id=dtb_shipping.shipping_id LIMIT 1,1) AS shipping_product_code02";
            $arrB2Cols[] = "(SELECT product_name FROM dtb_shipment_item WHERE dtb_shipment_item.order_id=dtb_shipping.order_id AND dtb_shipment_item.shipping_id=dtb_shipping.shipping_id LIMIT 1,1) AS shipping_product_name02";
        }

        //31. 荷扱い１ (空で出力)
        $arrB2Cols[] = "'' AS shipping_handling01";
        //32. 荷扱い２ (空で出力)
        $arrB2Cols[] = "'' AS shipping_handling02";
        //33. 記事
        $arrB2Cols[] = "'' AS shipping_memo";

        //34. コレクト代金引換額（税込）(空で取得)
        $arrB2Cols[] = "'' AS shipping_collect_inctax";
        //35. コレクト内消費税額等(空で取得)
        $arrB2Cols[] = "'' AS shipping_collect_tax";

        //36. 営業所止置き(0 : 利用しない 1 : 利用する)(空で出力)
        $arrB2Cols[] = "'' AS shipping_hold_reassign";
        //37. 営業所コード(空で出力)
        $arrB2Cols[] = "'' AS shipping_hold_office_code";

        //38. 発行枚数(空で出力)
        $arrB2Cols[] = "'' AS publish_count";
        //39. 個数口枠の印字(1 : 印字する 2 : 印字しない)
        $arrB2Cols[] = "'' AS publish_koguchi";

        //40. ご請求先顧客コード
        $arrB2Cols[] = "'' AS plg_yfcapi_claim_customer_code";
        //41. ご請求先分類コード
        $arrB2Cols[] = "'' AS plg_yfcapi_claim_type_code";
        //42. 運賃管理番号
        $arrB2Cols[] = "'' AS plg_yfcapi_transportation_no";

        //43. 注文時カード払いデータ登録(0 : 無し 1 : 有り)
        $arrB2Cols[] = "'' AS service_card_register";
        //44. 注文時カード払い加盟店番号 (空で出力)
        $arrB2Cols[] = "'' AS service_card_shop_no";
        //45. 注文時カード払い申し込み受付番号１
        $arrB2Cols[] = "dtb_order.order_id AS service_card_order_no01";
        //46. 注文時カード払い申し込み受付番号２ (空で出力)
        $arrB2Cols[] = "'' AS service_card_order_no02";
        //47. 注文時カード払い申し込み受付番号３ (空で出力)
        $arrB2Cols[] = "'' AS service_card_order_no03";

        /* お届け予定eメール */
        //48. お届け予定eメール利用区分(0 : 利用しない 1 : 利用する)
        $arrB2Cols[] = "'' AS service_deliv_mail_enable";
        //49. お届け予定eメールe-mailアドレス
        //50. 入力機種
        if($arrSetting['service_deliv_mail_enable'] == '1'){
            $arrB2Cols[] = "order_email AS service_deliv_mail_address";
            $arrB2Cols[] = "device_type_id  AS service_deliv_device_id";
        }else{
            $arrB2Cols[] = "'' AS service_deliv_mail_address";
            $arrB2Cols[] = "''  AS service_deliv_device_id";
        }
        //51. お届け予定eメールメッセージ
        $arrB2Cols[] = "'' AS service_deliv_mail_message";

        /* お届け完了eメール */
        //52. お届け完了eメール利用区分(0 : 利用しない 1 : 利用する)
        $arrB2Cols[] = "'' AS service_complete_mail_enable";
        //53. お届け完了eメールe-mailアドレス
        if($arrSetting['service_complete_mail_enable'] == '1'){
            $arrB2Cols[] = "order_email AS service_complete_mail_address";
        }else{
            $arrB2Cols[] = "'' AS service_complete_mail_address";
        }
        //54. お届け完了eメールメッセージ
        $arrB2Cols[] = "'' AS service_complete_mail_message";

        /* 収納代行サービス（空を出力）*/
        //55. クロネコ収納代行利用区分(0 : 無し 1 : 有り)
        $arrB2Cols[] = "'' AS service_receiving_agent_enable";
        //56. 予備
        $arrB2Cols[] = "'' AS service_receiving_agent_yobi";
        //57. 収納代行請求金額（税込）
        $arrB2Cols[] = "'' AS service_receiving_agent_claim_payment_total";
        //58. 収納代行内消費税額等
        $arrB2Cols[] = "'' AS service_receiving_agent_claim_tax";
        //59. 収納代行請求先郵便番号
        $arrB2Cols[] = "'' AS service_receiving_agent_zip";
        //60. 収納代行請求先住所
        $arrB2Cols[] = "'' AS service_receiving_agent_addr01";
        //61. 収納代行請求先住所（ｱﾊﾟｰﾄﾏﾝｼｮﾝ名)
        $arrB2Cols[] = "'' AS service_receiving_agent_addr02";
        //62. 収納代行請求先会社･部門名１
        $arrB2Cols[] = "'' AS service_receiving_agent_claim_campany01";
        //63. 収納代行請求先会社･部門名２
        $arrB2Cols[] = "'' AS service_receiving_agent_claim_campany02";
        //64. 収納代行請求先名（漢字）
        $arrB2Cols[] = "'' AS service_receiving_agent_claim_name";
        //65. 収納代行請求先名（カナ）
        $arrB2Cols[] = "'' AS service_receiving_agent_claim_kana";
        //66. 収納代行問合せ先名（カナ）
        $arrB2Cols[] = "'' AS service_receiving_agent_info_kana";
        //67. 収納代行問合せ先郵便番号
        $arrB2Cols[] = "'' AS service_receiving_agent_info_zip";
        //68. 収納代行問合せ先住所
        $arrB2Cols[] = "'' AS service_receiving_agent_info_addr01";
        //69. 収納代行問合せ先住所（ｱﾊﾟｰﾄﾏﾝｼｮﾝ名）
        $arrB2Cols[] = "'' AS service_receiving_agent_info_addr02";
        //70. 収納代行問合せ先電話番号
        $arrB2Cols[] = "'' AS service_receiving_agent_info_tel";
        //71. 収納代行管理番号
        $arrB2Cols[] = "'' AS service_receiving_agent_no";
        //72. 収納代行品名
        $arrB2Cols[] = "'' AS service_receiving_agent_product_name";
        //73. 収納代行備考
        $arrB2Cols[] = "'' AS service_receiving_agent_memo";
        //74. 予備０１
        $arrB2Cols[] = "'' AS reserve1";
        //75. 予備０２
        $arrB2Cols[] = "'' AS reserve2";
        //76. 予備０３
        $arrB2Cols[] = "'' AS reserve3";
        //77. 予備０４
        $arrB2Cols[] = "'' AS reserve4";
        //78. 予備０５
        $arrB2Cols[] = "'' AS reserve5";
        //79. 予備０６
        $arrB2Cols[] = "'' AS reserve6";
        //80. 予備０７
        $arrB2Cols[] = "'' AS reserve7";
        //81. 予備０８
        $arrB2Cols[] = "'' AS reserve8";
        //82. 予備０９
        $arrB2Cols[] = "'' AS reserve9";
        //83. 予備１０
        $arrB2Cols[] = "'' AS reserve10";
        //84. 予備１１
        $arrB2Cols[] = "'' AS reserve11";
        //85. 予備１２
        $arrB2Cols[] = "'' AS reserve12";
        //86. 予備１３
        $arrB2Cols[] = "'' AS reserve13";
        
        /* 投函予定メール */
        //87. 投函予定メール利用区分
        $arrB2Cols[] = "'' AS posting_plan_mail_enable";
        //88. 投函予定メールe-mailアドレス
        $arrB2Cols[] = "order_email AS posting_plan_mail_address";
        //89. 投函予定メールメッセージ
        $arrB2Cols[] = "'' AS posting_plan_mail_message";

        /* 投函完了メール(受人宛て) */
        //90. 投函完了メール(受人宛て)利用区分
        $arrB2Cols[] = "'' AS posting_complete_deliv_mail_enable";
        //91. 投函完了メール(受人宛て)e-mailアドレス
        $arrB2Cols[] = "'' AS posting_complete_deliv_mail_address";
        //92. 投函完了メール(受人宛て)メッセージ
        $arrB2Cols[] = "'' AS posting_complete_deliv_mail_message";
        
        /* 投函完了メール(出人宛て) */
        //93. 投函完了メール(出人宛て)利用区分
        $arrB2Cols[] = "'' AS posting_complete_order_mail_enable";
        //94. 投函完了メール(出人宛て)e-mailアドレス
        $arrB2Cols[] = "order_email AS posting_complete_order_mail_address";
        //95. 投函完了メール(出人宛て)メッセージ
        $arrB2Cols[] = "'' AS posting_complete_order_mail_message";

        //96. 連携管理番号
        $arrB2Cols[] = "'' AS plg_yfcapi_control_no";
        //97. 通知メールアドレス
        $arrB2Cols[] = "'' AS notification_mail_address";

        //XX.加工用
        $arrB2Cols[] = "dtb_order.deliv_id";
        $arrB2Cols[] = "dtb_order.payment_id";
        $arrB2Cols[] = "dtb_order.payment_total";
        $arrB2Cols[] = "dtb_order.tax";
        $arrB2Cols[] = "dtb_order.device_type_id";
        $arrB2Cols[] = "dtb_order." . MDL_YFCAPI_ORDER_COL_PAYID;
        $arrB2Cols[] = "dtb_shipping.time_id";
        $arrB2Cols[] = "plg_yfcapi_b2_time_id";
        $arrB2Cols[] = "(SELECT module_code FROM dtb_payment WHERE dtb_order.payment_id = dtb_payment.payment_id) AS module_code";
    }

    /**
     * Webコレクト取込用SQLのカラム構築.
     * @param array $arrWebCollectCols
     * @param array $arrSetting
     */
    function buildWebCollectCols(&$arrWebCollectCols, $arrSetting){
        /* CSV取得項目 */
        $arrWebCollectCols = array();
        //1. 受付番号
        $arrWebCollectCols[] = "dtb_order.order_id AS order_no";
        //2. 送り状番号
        $arrWebCollectCols[] = "(SELECT plg_yfcapi_deliv_slip FROM dtb_shipping WHERE order_id = dtb_order.order_id AND plg_yfcapi_deliv_slip IS NOT NULL ORDER BY shipping_id LIMIT 1) AS slip_no";
        //3. 配送サービスコード

        //XX.加工用
        $arrWebCollectCols[] = "dtb_order.deliv_id";
    }

    /**
     * 支払方法選択(after)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoShoppingPaymentAfter(&$objPage) {
        if (!SC_Utils_Ex::isBlank($objPage->arrPayment)) {
            $this->lfCheckPayment($objPage->arrPayment);
        }
    }

    /**
     * 支払方法選択(before)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoShoppingPaymentBefore(&$objPage) {
        if ($objPage->getMode() == 'select_deliv' && SC_Display_Ex::detectDevice() != DEVICE_TYPE_MOBILE) {
            $objFormParam = new SC_FormParam_Ex();
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objSiteSess = new SC_SiteSession_Ex();
            $objCartSess = new SC_CartSession_Ex();

            $objPage->is_multiple = $objPurchase->isMultiple();
            // カートの情報を取得
            $objPage->arrShipping = $objPurchase->getShippingTemp($objPage->is_multiple);
            $objPage->tpl_uniqid = $objSiteSess->getUniqId();
            $arrOrderTemp = $objPurchase->getOrderTemp($objPage->tpl_uniqid);

            $objPage->lfInitParam($objFormParam, true, $objPage->arrShipping);
            $objFormParam->setParam($arrOrderTemp);
            $objFormParam->convParam();
            $objFormParam->setParam($_POST);

            $this->arrErr = $objFormParam->checkError();
            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                $deliv_id = $objFormParam->getValue('deliv_id');
                $arrSelectedDeliv = $objPage->getSelectedDeliv($objCartSess, $deliv_id);
                $arrSelectedDeliv['error'] = false;

                $this->lfCheckPayment($arrSelectedDeliv['arrPayment']);

                echo SC_Utils_Ex::jsonEncode($arrSelectedDeliv);
                exit;
            }
        }
    }

    /**
     * 支払方法選択(mode)
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function lfDoShoppingPaymentMode(&$objPage) {
        switch ($objPage->getMode()) {
            default:
                break;
        }
    }

    /**
     * 選択可能な支払方法を確認する.
     *
     * @param array $arrPayment 支払方法
     * @return void
     */
    function lfCheckPayment(&$arrPayment) {
        foreach ($arrPayment as $key => $data) {
            $arrPayConfig = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($data['payment_id']);
            $pay_id = $arrPayConfig[MDL_YFCAPI_PAYMENT_COL_PAYID];
            if ($pay_id == MDL_YFCAPI_PAYID_DEFERRED) {
                $in_cart = $this->checkProductStatusInCart(MDL_YFCAPI_PRODUCT_STATUS_ID);
                if ($in_cart) {
                    unset($arrPayment[$key]);
                }
            }
        }
        $arrTemp = $arrPayment;
        $arrPayment = array();
        foreach($arrTemp as $data) {
            $arrPayment[] = $data;
        }
    }

    /**
     * 指定した商品ステータスの商品がカートに含まれているか確認する.
     *
     * @param int $product_status_id 商品ステータスID
     * @return bool
     */
    function checkProductStatusInCart($product_status_id) {
        $objProduct = new SC_Product_Ex();
        $objCartSess = new SC_CartSession_Ex();
        $arrCartList = $objCartSess->getCartList($objCartSess->getKey());
        foreach ($arrCartList as $key => $val) {
            $arrProductStatus = $objProduct->getProductStatus(array($val['productsClass']['product_id']));
            if (is_array($arrProductStatus[$val['productsClass']['product_id']]) &&
                in_array($product_status_id, $arrProductStatus[$val['productsClass']['product_id']])
            ) {
                return true;
            }
        }

        return false;
    }
}