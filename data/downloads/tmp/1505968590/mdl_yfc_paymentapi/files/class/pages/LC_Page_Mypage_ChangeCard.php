<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/mypage/LC_Page_AbstractMypage_Ex.php';
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Util_Ex.php');
// }}}
/**
 * カード登録内容変更 のページクラス.
 *
 * @package YfcApiUtils
 * @author Yamato Financial Co.,Ltd.
 */
class LC_Page_Mypage_ChangeCard extends LC_Page_AbstractMypage_Ex {

    // {{{ functions

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_subtitle = 'カード情報登録内容変更';
        $this->tpl_mypageno = 'change_card';

        $this->httpCacheControl('nocache');
        $objDate = new SC_Date_Ex(date('Y'), date('Y') + 15);
        $this->arrYear = $objDate->getZeroYear();
        $this->arrMonth = $objDate->getZeroMonth();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        parent::process();
    }

    /**
     * Page のプロセス
     * @return void
     */
    function action() {

        $objCustomer = new SC_Customer_Ex();
        $customer_id = $objCustomer->getValue('customer_id');

        // パラメーター管理クラス,パラメーター情報の初期化
        $objFormParam = new SC_FormParam_Ex();

        $objClient = new SC_Mdl_YFCApi_Client_Util_Ex();
        $arrCustomer = SC_Helper_Customer_Ex::sfGetCustomerData($customer_id);

        $mode = $this->getMode();
        switch ($mode) {
            case 'deleteCard':
                $this->lfInitDeleteParam($objFormParam);
                $objFormParam->setParam($_POST);
                //入力チェック
                $this->arrErr = $this->checkError($objFormParam, $mode);
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    //お預かり情報削除
                    if($this->lfDoDeleteCard($customer_id, $objFormParam->getHashArray(), $this)) {
                        $this->tpl_is_success = true;
                    }
                }

                $objFormParam = new SC_FormParam_Ex();
                $this->lfInitRegistParam($objFormParam);
                $this->arrForm = $objFormParam->getFormParamList();

                break;
            case 'registCard':
                $this->lfInitRegistParam($objFormParam);
                $objFormParam->setParam($_POST);
                //入力チェック
                $this->arrErr = $this->checkError($objFormParam, $mode);
                if (SC_Utils_Ex::isBlank($this->arrErr)) {
                    //カード情報登録
                    if($this->lfDoRegistCard($customer_id, $objFormParam->getHashArray(), $this)){
                        $this->tpl_is_success = true;
                    }
                } else {
                    $this->arrForm = $objFormParam->getFormParamList();
                }
                break;
            default:
                $this->lfInitRegistParam($objFormParam);
                $this->arrForm = $objFormParam->getFormParamList();
                break;
        }

        //オプション契約チェック（未契約の場合エラーページ表示）
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();
        if($arrMdlSetting['use_option'] == '1') {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, '', true, '現在のご契約内容ではマイページカード編集ページはご利用になれません。<br />お手数をおかけいたしますが、店舗運営者へお問い合わせください。');
            SC_Response_Ex::actionExit();
        }

        //お預かり情報照会
        $ret = $objClient->doGetCard($arrCustomer['customer_id'], array());
        if (!$ret) {
            $this->arrErr['error'] = '※ お預かり照会でエラーが発生しました。<br />' . implode('<br />', $this->arrErr);
        } else {
            $this->arrData = $this->lfGetArrCardInfo($objClient->getResults());
        }
    }

    function lfInitRegistParam(&$objFormParam) {
        $objFormParam->addParam("カード番号", "card_no", MDL_YFCAPI_CREDIT_NO_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("カード有効期限年", "card_exp_year", 2, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("カード有効期限月", "card_exp_month", 2, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("カード名義人", "card_owner", 20, 'a', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "SPTAB_CHECK"), "");
        $objFormParam->addParam("セキュリティコード", "security_code", MDL_YFCAPI_SECURITY_CODE_MAX_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"), "");
    }

    function lfInitDeleteParam(&$objFormParam) {
        $objFormParam->addParam("カード識別キー", "card_key", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK", "EXIST_CHECK"));
    }

    /**
     * 入力内容のチェックを行なう.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param string $mode モード
     * @return array 入力チェック結果の配列
     */
    function checkError(&$objFormParam, $mode = '') {
        $arrParam = $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrParam);
        $objErr->arrErr = $objFormParam->checkError();
        
        //カード削除の場合は以下チェック不要
        if($mode == 'deleteCard') return $objErr->arrErr;
        
        if (SC_Utils_Ex::isBlank($objErr->arrErr)) {
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
            //セキュリティコードチェック
            if (!SC_Utils_Ex::isBlank($arrParam['security_code']) &&
              ( strlen($arrParam['security_code']) < MDL_YFCAPI_SECURITY_CODE_MIN_LEN
             || strlen($arrParam['security_code']) > MDL_YFCAPI_SECURITY_CODE_MAX_LEN) ) {
                $objErr->arrErr['security_code'] = '※ セキュリティコードの桁数が足りません。<br />';
            }
        }
        
        return $objErr->arrErr;
    }

    /**
     * お預かり情報削除
     * @param integer $customer_id 顧客ID
     * @param array $arrParam パラメタ
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @return bool
     */
    function lfDoDeleteCard($customer_id, $arrParam, $objPage) {

        //お預かり情報照会
        $objClient = new SC_Mdl_YFCApi_Client_Util_Ex();
        $result = $objClient->doGetCard($customer_id, $arrParam);
        if(!$result) {
            $arrErr = $objClient->getError();
            $objPage->arrErr['error'] = '※ お預かり照会でエラーが発生しました。<br />' . implode('<br />', $arrErr);
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
            $objPage->arrErr['error'] = '※ 予約販売利用有りのカード情報は削除できません。';
            return false;
        }

        //お預かり情報削除
        $result = $objClient->doDeleteCard($customer_id, $arrDeleteCardData);

        if(!$result) {
            $arrErr = $objClient->getError();
            $objPage->arrErr['error'] = '※ お預かり情報削除でエラーが発生しました。<br />' . implode('<br />', $arrErr);
        }

        return true;
    }

    /**
     * お預かり情報登録
     * @param integer $customer_id 顧客ID
     * @param array $arrParam パラメタ
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @return bool
     */
    function lfDoRegistCard($customer_id, $arrParam, $objPage) {

        //お預かり情報照会
        $objClient = new SC_Mdl_YFCApi_Client_Util_Ex();
        $result = $objClient->doGetCard($customer_id, $arrParam);
        if(!$result) {
            $arrErr = $objClient->getError();
            $objPage->arrErr['error2'] = '※ お預かり照会でエラーが発生しました。<br />' . implode('<br />', $arrErr);
            return false;
        } else {
            $arrResults = $this->lfGetArrCardInfo($objClient->getResults());
        }

        //登録数上限チェック
        if($arrResults['cardUnit'] >= MDL_YFCAPI_CREDIT_SAVE_LIMIT) {
            $arrErr = $objClient->getError();
            $objPage->arrErr['error2'] = '※ カードお預かりは'.MDL_YFCAPI_CREDIT_SAVE_LIMIT.'件までとなっております。<br />' . implode('<br />', $arrErr);
            return false;
        }

        //カード情報登録
        $result = $objClient->doRegistCard($customer_id, $arrParam);
        if (!$result) {
            $arrErr = $objClient->getError();
            $objPage->arrErr['error2'] = '※ カード情報登録でエラーが発生しました。<br />' . implode('<br />', $arrErr);
        }

        return true;
    }

    /**
     * カード預かり情報取得（整理済）
     *
     * 預かり情報１件の場合と２件以上の場合で配列の構造を合わせる
     * @param array $arrCardInfos
     * @return array
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