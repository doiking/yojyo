<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
// }}}
/**
 * 決済モジュール 決済画面クラス
 */
class LC_Page_Mdl_YFCApi_Helper extends LC_Page_Ex {
    var $type;
    var $objMdl;
    var $arrSetting;

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        //共通ページクラス初期化
        parent::init();
        //決済モジュール基本クラス取得
        $this->objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //決済モジュールセッティング取得
        $this->arrSetting = $this->objMdl->getUserSettings();
        //HTTPキャッシュ無効
        $this->httpCacheControl('nocache');
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
        //セッション設定
        $objSiteSess = new SC_SiteSession_Ex();
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objCartSess = new SC_CartSession_Ex();

        //注文番号取得
        if (!SC_Utils_Ex::isBlank($_SESSION['order_id'])) {
            $order_id = $_SESSION['order_id'];
        } else {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />注文情報の取得が出来ませんでした。<br />この手続きは無効となりました。");
        }

        //モバイル判定（モバイルは未対応）
        if(SC_MobileUserAgent::isMobile()) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "お選びになったお支払方法は携帯電話(フューチャーフォン)ではご利用になれません。<br><br>
                 大変お手数をおかけいたしますが、別のお支払い方法をお選びいただくか<br>
                 PC,またはスマートフォンでご利用くださいませ。<br>");
        }

        //受注情報取得
        $arrOrder = $objPurchase->getOrder($order_id);
        //ページタイトル設定
        $this->tpl_title = $arrOrder['payment_method'];

        // 受注情報が決済処理中となっているか確認
        if ($arrOrder['status'] != ORDER_PENDING) {
            switch ($arrOrder['status']) {
                case ORDER_NEW:
                case ORDER_PRE_END:
                    SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                    SC_Response_Ex::actionExit();
                    break;
                case ORDER_PAY_WAIT:
                    // リンク型遷移での戻りは各ヘルパーに処理させる場合があるため、リダイレクトしない。
                    if ($this->getMode() != 'yfc_return') {
                        SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                        SC_Response_Ex::actionExit();
                    }
                    break;
                default:
                    if ($this->getMode() != 'yfc_return' && !SC_Utils_Ex::isBlank($arrOrder['status'])) {
                        SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                        "例外エラー<br />注文情報が無効です。<br />この手続きは無効となりました。");
                        SC_Response_Ex::actionExit();
                    }
                    break;
            }
        }

        //支払方法情報取得
        $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($arrOrder['payment_id']);
        // 決済手段毎のページヘルパークラスを読み込み
        if (SC_Utils_Ex::isBlank($arrPaymentInfo[MDL_YFCAPI_CODE . '_payment_code'])) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />注文情報の決済方法と決済モジュールの設定が一致していません。<br />この手続きは無効となりました。<br />管理者に連絡をして下さい。");
            SC_Response_Ex::actionExit();
        }

        $helper_name = 'LC_PageHelper_Mdl_YFCApi_' . $arrPaymentInfo[MDL_YFCAPI_CODE . '_payment_code'] . '_Ex';

        if (!file_exists(MDL_YFCAPI_PAGE_HELPEREX_PATH . $helper_name . '.php')) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, "", true,
                "例外エラー<br />決済モジュールのページヘルパーが読み込めません。<br />この手続きは無効となりました。<br />管理者に連絡をして下さい。");
            SC_Response_Ex::actionExit();
        }

        $this->lfSetToken($arrOrder, $arrPaymentInfo[MDL_YFCAPI_PAYMENT_COL_PAYID]);

        require_once(MDL_YFCAPI_PAGE_HELPEREX_PATH . $helper_name . '.php');

        $objPageHelper = new $helper_name;
        $objFormParam = new SC_FormParam_Ex();
        $objPageHelper->initParam($objFormParam, $arrPaymentInfo, $arrOrder);

        $objFormParam->setParam($arrPaymentInfo);
        $objFormParam->setParam($_REQUEST);
        $objFormParam->convParam();

        $this->tpl_url = SHOPPING_MODULE_URLPATH;

        $this->arrPaymentInfo = $arrPaymentInfo;

        $objPageHelper->modeAction($this->getMode(), $objFormParam, $arrOrder, $this);

        $this->tpl_form_bloc_path = $objPageHelper->getFormBloc();

        $this->arrForm = $objFormParam->getFormParamList();
    }

    /**
     * トークンチェックしない.
     */
    function doValidToken() {
        // nothing.
    }

    function lfSetToken(&$arrOrder, $pay_id) {
        $objPurchase = new SC_Helper_Purchase_Ex();
        $sqlval[MDL_YFCAPI_ORDER_COL_TRANSID] = SC_Helper_Session_Ex::getToken();
        $sqlval[MDL_YFCAPI_ORDER_COL_PAYID] = $pay_id;
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], null, null, null, $sqlval);
        $objQuery->commit();
    }

    /**
     * 外部ページからの遷移の際に受注情報内のTRANSACTION IDとのCSRFチェックを行う。
     *
     * @param integer $order_id 受注ID
     * @param string $transactionid TRANSACTION ID
     * @return bool
     */
    function lfIsValidToken($order_id, $transactionid) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        if($objQuery->get(MDL_YFCAPI_ORDER_COL_TRANSID, 'dtb_order', 'order_id = ?', array($order_id)) == $transactionid) {
            return true;
        }
        return false;
    }

}
