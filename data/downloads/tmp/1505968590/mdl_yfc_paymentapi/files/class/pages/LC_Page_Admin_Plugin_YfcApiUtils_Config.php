<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';
// }}}
/**
 * プラグイン設定のクラス
 *
 * @package YfcApiUtils
 * @version $Id: $
 */
class LC_Page_Admin_Plugin_YfcApiUtils_Config extends LC_Page_Admin_Ex {

    var $arrForm = array();

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = MDL_YFCAPI_TEMPLATE_PATH . 'admin/config_yfcapi_utils.tpl';
        $this->tpl_subtitle = "YfcApiUtil 設定";
        //送り状種別マスタ
        $this->arrB2DelivSlipType = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('b2_deliv_slip_type');
        //クール区分マスタ
        $this->arrB2CoolKb = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('b2_cool_kb');
        //一行目タイトル行
        $this->arrB2HeaderOutput = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('b2_header_output');
        //ハイフン有り無し
        $this->arrB2Hyphenation = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('b2_hyphenation');
        //利用有り無し
        $this->arrB2Enable = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('b2_enable');
        //取込フォーマット
        $this->arrB2UseFormat = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('b2_use_format');
        //ご依頼主出力タイプ
        $this->arrB2OutputOrderType = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('b2_output_order_type');
        //配送サービスコード
        $this->arrDeliveryServiceCode = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('wc_delivery_service_code');
    }

    /**
     * プロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        //環境情報のチェック
        $this->tpl_msg = $this->lfEnvCheckError();

        //かならずPOST値のチェックを行う
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();

        $arrForm = array();
        switch ($this->getMode()) {
            case 'register':
                $arrForm = $objFormParam->getHashArray();
                $this->arrErr = $this->lfCheckError($objFormParam);
                // エラーなしの場合にはデータを送信
                if (count($this->arrErr) == 0) {
                    $this->registData($arrForm);
                    SC_Utils_Ex::clearCompliedTemplate();
                    $this->tpl_onload = "alert('設定が完了しました。');window.close();";
                    // DB登録後の値再表示のため
                    $arrForm = $this->loadData();
                }
                break;
            default:
                //デフォルトはDBから取得する
                $arrForm = $this->loadData();
                $this->tpl_is_init = true;
                break;
        }

        //POSTを使用せずにDBもしくはチェック後のハッシュで再設定する。
        $objFormParam->setParam($arrForm);
        $this->arrTitle = $this->lfSetHtmlDispNameArray($objFormParam);
        $this->arrForm = $objFormParam->getFormParamList();
        $this->setTemplate($this->tpl_mainpage);

        // 支払い方法の取得
        $this->arrPayment = SC_Helper_Payment_Ex::getIDValueList();
        // 配送業者の取得
        $this->arrDeliv = SC_Helper_Delivery_Ex::getIDValueList();
    }

    /**
     * パラメーター情報の初期化
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        //■基本設定(必須)
        $objFormParam->addParam('ご請求先顧客コード', 'claim_customer_code', 12, 'n', array('EXIST_CHECK', 'SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ご請求先分類コード', 'claim_type_code', 3, 'n', array('SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('運賃管理番号', 'transportation_no', 2, 'n', array('EXIST_CHECK', 'SPTAB_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '01');
        //■動作設定
        $objFormParam->addParam('一行目タイトル行', 'header_output', 1, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '1');
        $objFormParam->addParam('送り状種別', 'deliv_slip_type', 1, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('クール便区分', 'cool_kb', 1, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('電話番号', 'tel_hyphenation', 1, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'),'1');
        $objFormParam->addParam('郵便番号', 'zip_hyphenation', 1, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'),'1');
        $objFormParam->addParam('お届け予定eメール', 'service_deliv_mail_enable', 1, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'),'0');
        $objFormParam->addParam('お届け完了eメール', 'service_complete_mail_enable', 1, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'),'0');
        $objFormParam->addParam('お届け予定eメールメッセージ', 'service_deliv_mail_message', 74, 'KVSA', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('お届け完了eメールメッセージ', 'service_complete_mail_message', 159, 'KVSA', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('ご依頼主出力', 'output_order_type', 1, 'n', array('NUM_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('投函予定メール', 'posting_plan_mail_enable', 1, 'n', array('NUM_CHECK','MAX_LENGTH_CHECK'),'0');
        $objFormParam->addParam('投函予定メールメッセージ', 'posting_plan_mail_message', 74, 'KVSA', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('投函完了メール(注文者宛)', 'posting_complete_deliv_mail_enable', 1, 'n', array('NUM_CHECK','MAX_LENGTH_CHECK'),'0');
        $objFormParam->addParam('投函完了メール(注文者宛)メッセージ', 'posting_complete_deliv_mail_message', 159, 'KVSA', array('MAX_LENGTH_CHECK'));
        //■取込設定
        $objFormParam->addParam('取込フォーマット', 'use_b2_format', 1, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '0');
        $objFormParam->addParam('取込時出荷情報登録', 'use_b2_shipping_entry', 1, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'), '0');
        //■Webコレクト動作設定
        $objFormParam->addParam('配送サービスコード', 'delivery_service_code', 2, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
    }
    /**
     * プラグイン設定値をDBから取得.
     *
     * @return array $arrForm
     */
    function loadData() {
        $arrRet = array();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = "plugin_code = 'YfcApiUtils'";
        $arrData = $objQuery->getRow('*', 'dtb_plugin', $where);
        if (!SC_Utils_Ex::isBlank($arrData['free_field1'])) {
            $arrRet = unserialize($arrData['free_field1']);
        }

        $objFormParam = new SC_FormParam_Ex();
        //プラグインの設定画面用のlfInitParam
        $this->lfInitParam($objFormParam);
        //DBに登録された値を登録します
        $objFormParam->setParam($arrRet);
        //addParamされた情報でコンバートします。
        $objFormParam->convParam();
        // 入力項目のタイトル部分を取得（EXIST_CHECKが設定されている場合、'※必須'までタイトルを作る）
        $objFormParam->setHtmlDispNameArray();
        //変換された値をフォームの設定値で取得
        $arrForm = $objFormParam->getHashArray();
        return $arrForm;

    }

    // 画面表示用タイトル生成
    function lfSetHtmlDispNameArray(&$objFormParam) {
        $arrTitle = array();

        foreach ($objFormParam->keyname as $index => $key) {
            $find = false;
            foreach ($objFormParam->arrCheck[$index] as $val) {
                if ($val == 'EXIST_CHECK') {
                    $find = true;
                }
            }
            if ($find) {
                $arrTitle[$key] = $objFormParam->disp_name[$index] . '<span class="attention">*</span>';
            } else {
                $arrTitle[$key] = $objFormParam->disp_name[$index];
            }
        }
        return $arrTitle;
    }

    /**
     * プラグイン設定値をDBに書き込み.
     *
     * @param array $arrData
     * @return void
     */
    function registData($arrData) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // UPDATEする値を作成する。
        $sqlval = array();
        $sqlval['free_field1'] = serialize($arrData);
        $sqlval['free_field2'] = '';
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_code = 'YfcApiUtils'";
        // UPDATEの実行
        $objQuery->update('dtb_plugin', $sqlval, $where);
        $objQuery->commit();
    }

    /**
     * 環境情報のチェック
     *
     * @return array $tpl_msg
     */
    function lfEnvCheckError(){

        $tpl_msg = array();
        // check if php 5.3+. 
        if ( version_compare( PHP_VERSION, '5.3', '<=' ) ){
            $tpl_msg[] ="requires PHP 5.3 or higher";
        }
        return $tpl_msg;
    }

    /**
     * 入力エラーチェック
     *
     * @param  SC_FormParam_Ex $objFormParam
     * @return array
     */
    public function lfCheckError(&$objFormParam)
    {
        $arrErr = $objFormParam->checkError();
        $arrForm = $objFormParam->getHashArray();

        if ($arrForm['service_deliv_mail_enable'] == '1' && SC_Utils_Ex::isBlank($arrErr['service_deliv_mail_message'])) {
            if(SC_Utils_Ex::isBlank($arrForm['service_deliv_mail_message'])){
                $arrErr['service_deliv_mail_message'] = '※ 「お届け予定eメール」を利用する場合は必須です。<br />';
            }
        }
    
        if ($arrForm['service_complete_mail_enable'] == '1' && SC_Utils_Ex::isBlank($arrErr['service_complete_mail_message'])) {
            if(SC_Utils_Ex::isBlank($arrForm['service_complete_mail_message'])){
                $arrErr['service_complete_mail_message'] = '※ 「お届け完了eメール」を利用する場合は必須です。<br />';
            }
        }

        if ($arrForm['posting_plan_mail_enable'] == '1' && SC_Utils_Ex::isBlank($arrErr['posting_plan_mail_message'])) {
            if(SC_Utils_Ex::isBlank($arrForm['posting_plan_mail_message'])) {
                $arrErr['posting_plan_mail_message'] = '※ 「投函予定メール」を利用する場合は必須です。<br />';
            }
        }

        if ($arrForm['posting_complete_deliv_mail_enable'] == '1' && SC_Utils_Ex::isBlank($arrErr['posting_complete_deliv_mail_message'])) {
            if(SC_Utils_Ex::isBlank($arrForm['posting_complete_deliv_mail_message'])) {
                $arrErr['posting_complete_deliv_mail_message'] = '※ 「投函完了メール(注文者宛)」を利用する場合は必須です。<br />';
            }
        }

        return $arrErr;
    }
}
