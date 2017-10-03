<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(MDL_YFCAPI_HELPEREX_PATH . "SC_Helper_Mdl_YFCApi_Plugin_Ex.php");
require_once(CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php");
// }}}

/**
 * 決済モジュール モジュール設定画面クラス
 */
class LC_Page_Admin_Mdl_YFCApi_Config extends LC_Page_Admin_Ex {

    // {{{ functions
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $this->tpl_mainpage = MDL_YFCAPI_TEMPLATE_PATH. 'admin/config.tpl';
        $this->tpl_subtitle = 'クロネコヤマト カード・後払い一体型決済モジュール〈埋込型〉';
        $this->arrPayments = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('pay_name');
        $this->arrExecMode = array(0 => "テスト環境",1 => "本番環境");
        $this->arrUseOption = array(0 => "契約済み", 1 => "未契約");
        $this->arrAdvanceSale = array(0 => "利用する", 1 => "利用しない");
        $this->arrUpdateFile = array(
            array(
                "src" => "recv.php",
                "dst" => USER_REALDIR . "mdl_yfc_paymentapi/s/recv.php",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/s/recv.php"
            ),
            array(
                "src" => "htaccess.php",
                "dst" => USER_REALDIR . "mdl_yfc_paymentapi/s/.htaccess",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/s/.htaccess"
            ),
            array(
                "src" => "deliv_complete_mail.tpl",
                "dst" => TEMPLATE_REALDIR . "mail_templates/deliv_complete_mail.tpl",
                "disp" => "data/Smarty/templates/" . TEMPLATE_NAME . "/mail_templates/deliv_complete_mail.tpl"
            ),
            array(
                "src" => "LC_Page_Admin_Order_Ex.php",
                "dst" => CLASS_EX_REALDIR . "page_extends/admin/order/LC_Page_Admin_Order_Ex.php",
                "disp" => "data/class_extends/page_extends/admin/order/LC_Page_Admin_Order_Ex.php",
            ),
            array(
                "src" => "function.ycf_is_deliv_disp.php",
                "dst" => DATA_REALDIR . "smarty_extends/function.ycf_is_deliv_disp.php",
                "disp" => "data/smarty_extends/function.ycf_is_deliv_disp.php",
            ),
            array(
                "src" => "media/loading.gif",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "loading.gif",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/loading.gif"
            ),
            array(
                "src" => "media/btn_regist.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "btn_regist.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/btn_regist.jpg"
            ),
            array(
                "src" => "media/btn_regist_on.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "btn_regist_on.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/btn_regist_on.jpg"
            ),
            array(
                "src" => "media/btn_regist_card.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "btn_regist_card.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/btn_regist_card.jpg"
            ),
            array(
                "src" => "media/btn_regist_card_on.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "btn_regist_card_on.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/btn_regist_card_on.jpg"
            ),
            array(
                "src" => "media/btn_carddelete.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "btn_carddelete.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/btn_carddelete.jpg"
            ),
            array(
                "src" => "media/btn_carddelete_on.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "btn_carddelete_on.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/btn_carddelete_on.jpg"
            ),
            array(
                "src" => "media/btn_carddelete_mini.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "btn_carddelete_mini.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/btn_carddelete_mini.jpg"
            ),
            array(
                "src" => "media/btn_carddelete_mini_on.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "btn_carddelete_mini_on.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/btn_carddelete_mini_on.jpg"
            ),
            array(
                "src" => "media/security_code_info.png",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "security_code_info.png",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . "/security_code_info.png"
            ),
            array(
                "src" => "media/cvs_logo_21.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "cvs_logo_21.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . '/cvs_logo_21.jpg'
            ),
            array(
                "src" => "media/cvs_logo_22.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "cvs_logo_22.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . '/cvs_logo_22.jpg'
            ),
            array(
                "src" => "media/cvs_logo_23.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "cvs_logo_23.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . '/cvs_logo_23.jpg'
            ),
            array(
                "src" => "media/cvs_logo_24.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "cvs_logo_24.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . '/cvs_logo_24.jpg'
            ),
            array(
                "src" => "media/cvs_logo_25.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "cvs_logo_25.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . '/cvs_logo_25.jpg'
            ),
            array(
                "src" => "media/cvs_logo_26.jpg",
                "dst" => MDL_YFCAPI_MEDIAFILE_PATH . "cvs_logo_26.jpg",
                "disp" => "html/" . USER_DIR . MDL_YFCAPI_CODE . '/cvs_logo_26.jpg'
            )
        );
        //クロネコ代金後払い
        $this->arrSendDiv = array(0 => "同梱しない", 1 => "同梱する");
        $this->arrDelivDisp = array(0 => "利用する", 1 => "利用しない");
    }

    /**
     * Page のプロセス.
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
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objMdl->install();

        switch($this->getMode()) {
        case 'register':
            list($this->arrForm, $this->arrErr, $this->tpl_onload) = $this->lfRegisterMode($_POST);
            if (SC_Utils_Ex::isBlank($this->arrErr)) {
                $this->lfRegistPage($this->arrForm['is_tpl_init']['value']);
                $plugin_id = $this->lfRegistPlugins();
                $this->lfRegistBloc($this->arrForm['enable_payment_type']['value'], $plugin_id, $this->arrForm['is_tpl_init']['value']);
            }
            break;
        default:
            list($this->arrForm, $this->tpl_onload) = $this->lfDefaultMode();
            break;
        }
        $this->setTemplate($this->tpl_mainpage);
    }

    /**
     * 初回表示処理
     *
     * @return array
     */
    function lfDefaultMode() {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $subData = $objMdl->getUserSettings();
        $objFormParam = $this->lfInitParam($subData);
        $tpl_onload = '';
        return array($objFormParam->getFormParamList(), $tpl_onload);
    }

    /**
     * 登録ボタン押下時の処理
     *
     * @param $arrParam
     * @return array
     */
    function lfRegisterMode(&$arrParam) {
        // 認証情報
        $objSess = new SC_Session_Ex();
        $tpl_onload ='';

        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objFormParam = $this->lfInitParam($arrParam);
        if ($arrErr = $this->lfCheckError($objFormParam)) {
            return array($objFormParam->getFormParamList(), $arrErr, $tpl_onload);
        }
        $arrForm = $objFormParam->getHashArray();
        if($arrParam['is_file_copy'] == '1') {
            $objMdl->updateFile($this->arrUpdateFile);
            $arrFailedFile = $objMdl->getFailedCopyFile();
            if (count($arrFailedFile) > 0) {
                $arrErr = array('is_file_copy' => '書き込み権限のないファイルが存在します。');
                foreach($arrFailedFile as $file) {
                    $alert = $file . 'に書込権限を与えてください。';
                    $tpl_onload .= 'alert("' . $alert . '");';
                }
                return array($objFormParam->getFormParamList(), $arrErr, $tpl_onload);
            }
        }
        $objMdl->registerUserSettings($arrForm);

        // del_flgを削除にしておく
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $arrUpdVal = array('del_flg' => 1);
        $where = 'module_code = ?';
        $arrWhereVal = array(MDL_YFCAPI_CODE);
        $objQuery->update('dtb_payment', $arrUpdVal, $where, $arrWhereVal);

        foreach ($arrForm['enable_payment_type'] as $payment_type_id) {
            $arrData = array();
            $arrData['payment_method'] = $this->arrPayments[ $payment_type_id ];
            $arrData[MDL_YFCAPI_PAYMENT_COL_PAYID] = $payment_type_id;
            $arrData['fix'] = 3;
            $arrData['creator_id'] = $objSess->member_id;
            // クレジットカード決済の場合は手数料を0円とし、手数料設定不可とする
            if($payment_type_id == MDL_YFCAPI_PAYID_CREDIT) {
                $arrData['charge']     = 0;
                $arrData['charge_flg'] = 2;
            }
            $arrData['update_date'] = 'CURRENT_TIMESTAMP';
            $arrData['module_path'] = MDL_YFCAPI_PATH . 'payment.php';
            $arrData['module_code'] = $objMdl->getCode(true);
            $arrData['del_flg'] = '0';

            $arrPayment = $this->lfGetPaymentDB($payment_type_id);

            // 更新データがあれば更新する。
            if (count($arrPayment) > 0){
                // データが存在していればUPDATE、無ければINSERT
                $where = "module_code = ? AND " . MDL_YFCAPI_PAYMENT_COL_PAYID . " = ?";
                $arrWhereVal = array($objMdl->getCode(true), (string)$payment_type_id);
                $arrDefault = $this->lfGetDefaultPaymentConfig($payment_type_id, $arrForm);
                if ($arrDefault['charge'] != '0') {
                    $arrData['charge'] = $arrDefault['charge'];
                }
                $arrData['rule_min']       = $arrDefault['rule_min'];
                $arrData['upper_rule_max'] = $arrDefault['upper_rule_max'];
                $objQuery->update('dtb_payment', $arrData, $where, $arrWhereVal);
            } else {
                // ランクの最大値を取得する
                $max_rank = $objQuery->max('rank', 'dtb_payment');
                $arrData["create_date"] = "CURRENT_TIMESTAMP";
                $arrData["rank"] = $max_rank + 1;
                $arrData['payment_id'] = $objQuery->nextVal('dtb_payment_payment_id');
                $arrData = array_merge($this->lfGetDefaultPaymentConfig($payment_type_id, $arrForm), $arrData);
                $objQuery->insert("dtb_payment", $arrData);
            }
        }

        $objQuery->commit();

        $tpl_onload .= 'alert("登録完了しました。\n");window.close();';
        return array($objFormParam->getFormParamList(), $arrErr, $tpl_onload);
    }

    /**
     * フォームパラメータ初期化
     *
     * @param array $arrData
     * @return SC_FormParam_Ex $objFormParam
     */
    function lfInitParam($arrData = array()) {
        $objFormParam = new SC_FormParam_Ex();

        $objFormParam->addParam('動作モード', 'exec_mode', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'),isset($arrData['exec_mode']) ? $arrData['exec_mode'] : "0");
        $objFormParam->addParam('加盟店コード（アクセスID）', 'shop_id', INT_LEN, 'a', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('決済方法', 'enable_payment_type', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK'), isset($arrData['enable_payment_type']) ? $arrData['enable_payment_type'] : "");
        $objFormParam->addParam('オプションサービス', 'use_option', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK'),isset($arrData['use_option']) ? $arrData['use_option'] : "1");
        $objFormParam->addParam('アクセスキー', 'access_key', 7, 'n', array('NUM_CHECK'), isset($arrData['access_key ']) ? $arrData['access_key '] : "");
        $objFormParam->addParam('テンプレート初期化', 'is_tpl_init', INT_LEN, 'n', array('NUM_CHECK'),isset($arrData['is_tpl_init ']) ? $arrData['is_tpl_init '] : "0");
        $objFormParam->addParam('ファイルのコピー', 'is_file_copy', INT_LEN, 'n', array('NUM_CHECK'),isset($arrData['is_file_copy ']) ? $arrData['is_file_copy '] : "0");
        $objFormParam->addParam('予約販売機能', 'advance_sale', INT_LEN, 'n', array('NUM_CHECK'),isset($arrData['advance_sale']) ? $arrData['advance_sale'] : "1");

        //クロネコ代金後払い
        $objFormParam->addParam('加盟店コード', 'ycf_str_code', 11, 'n', array('NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('パスワード', 'ycf_str_password', 8, 'a', array('ALNUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('請求書の同梱', 'ycf_send_div', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK'),'0');
        $objFormParam->addParam('出荷予定日', 'ycf_ship_ymd', 2, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('メールの追跡情報表示機能', 'ycf_deliv_disp', INT_LEN, 'n', array('EXIST_CHECK', 'NUM_CHECK', 'MAX_LENGTH_CHECK'), '0');
        $objFormParam->addParam('請求書再発行通知メール：受取メールアドレス', 'ycf_invoice_reissue_mail_address', MTEXT_LEN, 'a', array('SPTAB_CHECK', 'EMAIL_CHAR_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('請求書再発行通知メール：メールヘッダー', 'ycf_invoice_reissue_mail_header', LLTEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('請求書再発行通知メール：メールフッター', 'ycf_invoice_reissue_mail_footer', LLTEXT_LEN, 'KVa', array('MAX_LENGTH_CHECK'));

        $objFormParam->setParam($arrData);
        $objFormParam->convParam();
        return $objFormParam;
    }

    /**
     * 入力パラメータの検証
     *
     * @param SC_FormParam_Ex $objFormParam
     * @return array|null
     */
    function lfCheckError(&$objFormParam) {
        $arrErr = null;
        $arrErr = $objFormParam->checkError();
        if (extension_loaded('curl') === false) {
            $arrErr['err'] .= '※ curl拡張モジュールがロード出来ません。<br />PHPの動作環境がEC-CUBEのシステム環境要件を満たしているか確認して下さい。<br />';
        }
        if (extension_loaded('mbstring') === false) {
            $arrErr['err'] .= '※ mbstring拡張モジュールがロード出来ません。<br />PHPの動作環境がEC-CUBEのシステム環境要件を満たしているか確認して下さい。<br />';
        }
        if (extension_loaded('json') === false) {
            $arrErr['err'] .= '※ JSON拡張モジュールがロード出来ません。<br />PHPの動作環境がEC-CUBEのシステム環境要件を満たしているか確認して下さい。<br />';
        }

        //オプションサービスが契約済の場合、アクセスキーと予約販売機能は必須とする。
        $arrForm = $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrForm);
        if($arrForm['use_option'] == "0") {
            $objErr->doFunc(array('アクセスキー', 'access_key'), array('EXIST_CHECK'));
            $objErr->doFunc(array('予約販売機能', 'advance_sale'), array('EXIST_CHECK'));
        }

        //クロネコ代金後払い以外を選択時必須
        foreach($arrForm['enable_payment_type'] as $key => $pay_id){
            if ($pay_id != MDL_YFCAPI_PAYID_DEFERRED) {
                $objErr->doFunc(array('加盟店コード（アクセスID）', 'shop_id'), array('EXIST_CHECK'));
            }
        }

        //クロネコ代金後払いを選択時必須
        if (in_array(MDL_YFCAPI_PAYID_DEFERRED, $arrForm['enable_payment_type'])) {
            $objErr->doFunc(array('加盟店コード', 'ycf_str_code'), array('EXIST_CHECK'));
            $objErr->doFunc(array('パスワード', 'ycf_str_password'), array('EXIST_CHECK'));
            $objErr->doFunc(array('請求書再発行通知メール：受取メールアドレス', 'ycf_invoice_reissue_mail_address'), array('EXIST_CHECK'));
            $objErr->doFunc(array('請求書再発行通知メール：メールヘッダー', 'ycf_invoice_reissue_mail_header'), array('EXIST_CHECK'));
        }

        //出荷予定日は最大90日
        if (SC_Utils_Ex::isBlank($arrErr['ycf_ship_ymd']) && $arrForm['ycf_ship_ymd'] > 90) {
            $arrErr['ycf_ship_ymd'] = '※ 出荷予定日は90日以内で入力して下さい。';
        }

        if(!SC_Utils_Ex::isBlank($objErr->arrErr)) {
            $arrErr = array_merge($arrErr, $objErr->arrErr);
        }

        return $arrErr;
    }
    // DBからデータを取得する
    function lfGetPaymentDB($type){
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrVal = array($objMdl->getCode(true), (string)$type);
        $arrRet = $objQuery->select("module_id", "dtb_payment", "module_code = ? AND " . MDL_YFCAPI_PAYMENT_COL_PAYID . " = ?", $arrVal);
        return $arrRet;
    }

    function lfIsRegistPaymentModule() {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $module_code = $objMdl->getCode(true);
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        if($objQuery->count('dtb_payment', 'module_code = ?', array($module_code))) {
            return true;
        }else{
            return false;
        }
    }

    function lfRegistPlugins() {
        $plugin_code = 'YfcApiCore';
        $is_enable = true;
        $plugin_id = SC_Helper_Mdl_YFCApi_Plugin_Ex::installPluginFromPluginInfo($plugin_code, $is_enable, '0');

        $plugin_code = 'YfcApiUtils';
        $is_enable = true;
        $plugin_id = SC_Helper_Mdl_YFCApi_Plugin_Ex::installPluginFromPluginInfo($plugin_code, $is_enable, '1');

        SC_Utils_Ex::clearCompliedTemplate();
        return $plugin_id;
    }

    function lfRegistPage($is_force) {
        $arrPageId = array();
        // 決済画面をデザインテンプレートに足す
        $page_name = '商品購入/決済画面';
        $url = 'shopping/load_payment_module.php';
        $filename = 'shopping/load_payment_module';

        $tpl_data = file_get_contents(MDL_YFCAPI_TEMPLATE_PATH . 'default/load_payment_module.tpl');
        $device_type_id = DEVICE_TYPE_PC;
        $page_id = SC_Helper_Mdl_YFCApi_Plugin_Ex::setPageData($tpl_data, $page_name, $url, $filename, $device_type_id, $is_force);
        $arrPageId[ $filename ][ $device_type_id ] = $page_id;

        $tpl_data = file_get_contents(MDL_YFCAPI_TEMPLATE_PATH . 'sphone/load_payment_module.tpl');
        $device_type_id = DEVICE_TYPE_SMARTPHONE;
        $page_id = SC_Helper_Mdl_YFCApi_Plugin_Ex::setPageData($tpl_data, $page_name, $url, $filename, $device_type_id, $is_force);
        $arrPageId[ $filename ][ $device_type_id ] = $page_id;

        $tpl_data = file_get_contents(MDL_YFCAPI_TEMPLATE_PATH . 'mobile/load_payment_module.tpl');
        $device_type_id = DEVICE_TYPE_MOBILE;
        $page_id = SC_Helper_Mdl_YFCApi_Plugin_Ex::setPageData($tpl_data, $page_name, $url, $filename, $device_type_id, $is_force);
        $arrPageId[ $filename ][ $device_type_id ] = $page_id;

        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objMdl->registerSubData($arrPageId, 'page_setting');
    }

    function lfGetDefaultPaymentConfig($payment_type_id, $arrForm) {
        $arrData = array();
        $arrData['charge'] = '0';
        $arrData['rule_max'] = '1';

        switch ($payment_type_id) {
            case MDL_YFCAPI_PAYID_CREDIT:
                $arrData['rule_max']   = MDL_YFCAPI_CREDIT_RULE_MIN;
                $arrData['upper_rule'] = MDL_YFCAPI_CREDIT_RULE_MAX;
                break;
            case MDL_YFCAPI_PAYID_CVS:
                $arrData['rule_max']   = MDL_YFCAPI_CVS_RULE_MIN;
                $arrData['upper_rule'] = MDL_YFCAPI_CVS_RULE_MAX;
                break;
            case MDL_YFCAPI_PAYID_EDY:
                $arrData['rule_max']   = MDL_YFCAPI_EDY_RULE_MIN;
                $arrData['upper_rule'] = MDL_YFCAPI_EDY_RULE_MAX;
                break;
            case MDL_YFCAPI_PAYID_MOBILEEDY:
                $arrData['rule_max']   = MDL_YFCAPI_EDY_RULE_MIN;
                $arrData['upper_rule'] = MDL_YFCAPI_EDY_RULE_MAX;
                break;
            case MDL_YFCAPI_PAYID_SUICA:
                $arrData['rule_max']   = MDL_YFCAPI_SUICA_RULE_MIN;
                $arrData['upper_rule'] = MDL_YFCAPI_SUICA_RULE_MAX;
                break;
            case MDL_YFCAPI_PAYID_MOBILESUICA:
                $arrData['rule_max']   = MDL_YFCAPI_SUICA_RULE_MIN;
                $arrData['upper_rule'] = MDL_YFCAPI_SUICA_RULE_MAX;
                break;
            case MDL_YFCAPI_PAYID_WAON:
                $arrData['rule_max']   = MDL_YFCAPI_WAON_RULE_MIN;
                $arrData['upper_rule'] = MDL_YFCAPI_WAON_RULE_MAX;
                break;
            case MDL_YFCAPI_PAYID_MOBILEWAON:
                $arrData['rule_max']   = MDL_YFCAPI_WAON_RULE_MIN;
                $arrData['upper_rule'] = MDL_YFCAPI_WAON_RULE_MAX;
                break;
            case MDL_YFCAPI_PAYID_NETBANK:
                $arrData['rule_max']   = MDL_YFCAPI_NETBANK_RULE_MIN;
                $arrData['upper_rule'] = MDL_YFCAPI_NETBANK_RULE_MAX;
                break;
            case MDL_YFCAPI_PAYID_DEFERRED:
                $arrData['rule_max']   = MDL_YFCAPI_DEFERRED_RULE_MIN;
                $arrData['upper_rule'] = MDL_YFCAPI_DEFERRED_RULE_MAX;
                $arrData['charge'] = '190';
                if ($arrForm['ycf_send_div'] == '1') {
                    $arrData['charge'] = '100';
                }
                break;
            default:
                break;
        }
        $arrData['rule_min']       = $arrData['rule_max'];
        $arrData['upper_rule_max'] = $arrData['upper_rule'];
        return $arrData;
    }

    function lfRegistBloc($arrPaymentTypeId, $plugin_id, $is_force) {
        $arrBlocId = array();
        foreach ($arrPaymentTypeId as $payment_type_id) {
            $filename = "";
            switch ($payment_type_id) {
                case MDL_YFCAPI_PAYID_CREDIT:
                    $filename = "yfc_credit";
                    break;
                case MDL_YFCAPI_PAYID_CVS:
                    $filename = "yfc_cvs";
                    break;
                case MDL_YFCAPI_PAYID_EDY:
                    $filename = "yfc_edy";
                    break;
                case MDL_YFCAPI_PAYID_SUICA:
                    $filename = "yfc_suica";
                    break;
                case MDL_YFCAPI_PAYID_WAON:
                    $filename = "yfc_waon";
                    break;
                case MDL_YFCAPI_PAYID_NETBANK:
                    $filename = "yfc_netbank";
                    break;
                case MDL_YFCAPI_PAYID_DEFERRED:
                    $filename = "yfc_deferred";
                    break;
                case MDL_YFCAPI_PAYID_MOBILEEDY:
                case MDL_YFCAPI_PAYID_MOBILESUICA:
                case MDL_YFCAPI_PAYID_MOBILEWAON:
                default:
                    break;
            }
            if($filename != "") {
                $bloc_name = $this->arrPayments[$payment_type_id] . "入力フォーム";
                if (file_exists(MDL_YFCAPI_TEMPLATE_PATH . 'default/bloc/' . $filename . '.tpl')) {
                    $bloc_data = file_get_contents(MDL_YFCAPI_TEMPLATE_PATH . 'default/bloc/' . $filename . '.tpl');
                    $device_type_id = DEVICE_TYPE_PC;
                    $bloc_id = SC_Helper_Mdl_YFCApi_Plugin::setBlocData($plugin_id, $bloc_data, $device_type_id, $bloc_name, $filename, "", $is_force);
                    $arrBlocId[ $filename ][ $device_type_id ] = $bloc_id;
                }
                if (file_exists(MDL_YFCAPI_TEMPLATE_PATH . 'sphone/bloc/' . $filename . '.tpl')) {
                    $bloc_data = file_get_contents(MDL_YFCAPI_TEMPLATE_PATH . 'sphone/bloc/' . $filename . '.tpl');
                    $device_type_id = DEVICE_TYPE_SMARTPHONE;
                    $bloc_id = SC_Helper_Mdl_YFCApi_Plugin::setBlocData($plugin_id, $bloc_data, $device_type_id, $bloc_name, $filename, "", $is_force);
                    $arrBlocId[ $filename ][ $device_type_id ] = $bloc_id;
                }
                if (file_exists(MDL_YFCAPI_TEMPLATE_PATH . 'mobile/bloc/' . $filename . '.tpl')) {
                    $bloc_data = file_get_contents(MDL_YFCAPI_TEMPLATE_PATH . 'mobile/bloc/' . $filename . '.tpl');
                    $device_type_id = DEVICE_TYPE_MOBILE;
                    $bloc_id = SC_Helper_Mdl_YFCApi_Plugin::setBlocData($plugin_id, $bloc_data, $device_type_id, $bloc_name, $filename, "", $is_force);
                    $arrBlocId[ $filename ][ $device_type_id ] = $bloc_id;
                }
            }
        }
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objMdl->registerSubData($arrBlocId, 'bloc_setting');
    }

}
