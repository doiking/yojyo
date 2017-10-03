<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once CLASS_EX_REALDIR . 'page_extends/LC_Page_Ex.php';
// }}}
/**
 * 決済モジュール 結果受信クラス
 *
 */
class LC_Page_Mdl_YFCApi_Recv extends LC_Page_Ex {

    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        $this->skip_load_page_layout = true;
        parent::init();
        $this->httpCacheControl('nocache');
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        //POST値ログ出力
        $this->lfSetPostLog($_POST);
        //POST値変換・チェック
        $objFormParam = new SC_FormParam();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        $arrErr = $this->lfCheckError($objFormParam);

        //注文情報取得
        $arrOrder = array();
        if (SC_Utils_Ex::isBlank($arrErr['order_no'])) {
            $order_id = $objFormParam->getValue('order_no');
            $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($order_id);
        }

        if (SC_Utils_Ex::isBlank($arrErr)) {
            //レシーブ処理
            $res = $this->lfDoReceive($objFormParam->getHashArray(), $arrOrder);
            $this->lfSendResponse($res);
            SC_Response_Ex::actionExit();
        } else {
            //ログ出力
            $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
            $objMdl->printLog('param_error_all:' . print_r($arrErr,true));
            //エラーメール送信（受注データ不在）
            if(!SC_Utils_Ex::isBlank($arrErr['order_id'])) {
                $this->lfDoNoOrder($objFormParam->getHashArray());
            }
            //エラーメール送信（決済未使用）
            if(!SC_Utils_Ex::isBlank($arrErr['function_div'])) {
                $this->lfDoNoFunctionDiv($objFormParam->getHashArray());
            }
            //エラーメール送信（支払方法不一致）
            if(!SC_Utils_Ex::isBlank($arrErr['pay_method'])) {
                $this->lfDoUnMatchPayMethod($objFormParam->getHashArray(), $arrOrder);
            }
            //エラーメール送信（決済金額不一致）
            if(!SC_Utils_Ex::isBlank($arrErr['settle_price'])) {
                $this->lfDoUnMatchSettlePrice($objFormParam->getHashArray(), $arrOrder);
            }
            $this->lfSendResponse(false);
            SC_Response_Ex::actionExit();
        }
    }

    /**
     * レシーブ処理
     * 
     * @param array    $arrParam POST値
     * @param array    $arrOrder
     * @return boolean $res
     */
    function lfDoReceive(&$arrParam, &$arrOrder) {
        $pay_id = $arrOrder[MDL_YFCAPI_ORDER_COL_PAYID];
        switch ($pay_id) {
            //コンビニ決済
            case MDL_YFCAPI_PAYID_CVS:
                $res = $this->lfDoRecvCvs($arrParam, $arrOrder['order_id']);
                break;
            //クレジットカード決済
            case MDL_YFCAPI_PAYID_CREDIT:
                $res = $this->lfDoRecvCredit($arrParam, $arrOrder);
                break;
            default:
                $res = false;
                break;
        }
        // 受注データ更新（取引状況）
        if($res) {
            SC_Util_Mdl_YFCApi_Ex::setOrderPayData($arrOrder, $arrParam);
        }
        
        return $res;
    }

    /**
     * レシーブ処理（コンビニ決済）
     * 
     * 正常・異常ともに想定内のPOST値であればtrueを返す.
     * 想定しないPOST値の場合はfalseを返す.
     * 
     * @param array   $arrParam POST値
     * @param integer $order_id
     * @return boolean
     */
    function lfDoRecvCvs(&$arrParam, $order_id) {
        
        $order_status = null;
        $sqlval = array();
        
        switch ($arrParam['settle_detail']) {
            //入金完了（速報）
            case MDL_YFCAPI_ACTION_STATUS_PROMPT_REPORT:
                $order_status = ORDER_PRE_END;
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_PROMPT_REPORT;
                break;
            //入金完了（確報）
            case MDL_YFCAPI_ACTION_STATUS_DIFINIT_REPORT:
                $order_status = ORDER_PRE_END;
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_DIFINIT_REPORT;
                break;
            //購入者都合エラー（支払期限切れ、コンビニエンスストアから入金取消の通知が発生した場合等）
            case MDL_YFCAPI_ACTION_STATUS_NG_CUSTOMER:
                $order_status = ORDER_CANCEL;
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_NG_CUSTOMER;
                break;
            //決済機関都合エラー（コンビニエンスストアより応答がない場合、異常の応答を受けた場合等）
            case MDL_YFCAPI_ACTION_STATUS_NG_PAYMENT:
                //ステータスは更新しない
                //$order_status = ORDER_CANCEL;
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_NG_PAYMENT;
                break;
            //その他のシステムエラー
            case MDL_YFCAPI_ACTION_STATUS_NG_SYSTEM:
                //ステータスは更新しない
                //$order_status = ORDER_CANCEL;
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_NG_SYSTEM;
                break;
            default:
                return false;
        }
        //対応状況更新
        if(!SC_Utils_Ex::isBlank($order_status)) {
            $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
            $objMdl->printLog('update order status:' .  $order_status);
            
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objQuery->begin();
            $objPurchase->sfUpdateOrderStatus($order_id, $order_status, null, null, $sqlval);
            $objQuery->commit();
        }
        return true;
    }

    /**
     * レシーブ処理（予約販売用：クレジットカード決済）
     * 
     * 正常・異常ともに想定内のPOST値であればtrueを返す.
     * 想定しないPOST値の場合、または対象でない取引状況の場合はfalseを返す.
     * 
     * @param array $arrParam POST値
     * @param array $arrOrder
     * @return boolean
     */
    function lfDoRecvCredit(&$arrParam, &$arrOrder) {
        //取引状況「予約受付完了」の場合のみ処理する.
        if($arrOrder[MDL_YFCAPI_ORDER_COL_PAYSTATUS] != MDL_YFCAPI_ACTION_STATUS_COMP_RESERVE) return false;
        
        switch ($arrParam['settle_detail']) {
            //与信完了
            case MDL_YFCAPI_ACTION_STATUS_COMP_AUTH:
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_COMP_AUTH;
                break;
            //購入者都合エラー（カード情報に誤りがある場合等）
            case MDL_YFCAPI_ACTION_STATUS_NG_CUSTOMER:
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_NG_CUSTOMER;
                break;
            //加盟店都合エラー（決済取消等）
            case MDL_YFCAPI_ACTION_STATUS_NG_SHOP:
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_NG_SHOP;
                break;
            //決済機関都合エラー（決済機関から応答が無い場合、異常の応答を受けた場合等）
            case MDL_YFCAPI_ACTION_STATUS_NG_PAYMENT:
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_NG_PAYMENT;
                break;
            //その他システムエラー
            case MDL_YFCAPI_ACTION_STATUS_NG_SYSTEM:
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_NG_SYSTEM;
                break;
            //予約販売与信エラー
            case MDL_YFCAPI_ACTION_STATUS_NG_RESERVE:
                $arrParam['action_status'] = MDL_YFCAPI_ACTION_STATUS_NG_RESERVE;
                break;
            default:
                return false;
        }
        return true;
    }

    /**
     * POST ログは全て残す.
     *
     * @param array $arrPost
     * @return void
     */
    function lfSetPostLog($arrPost) {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objMdl->printLog('******* receiver data start *******');
        $objMdl->printLog(print_r($arrPost,true));
        $objMdl->printLog('******* receiver data end *******');
    }

    /**
     * レスポンスを返す。
     *
     * @param boolean $result
     * @return void
     */
    function lfSendResponse($result) {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objMdl->printLog('response:' . $result ? 'true' : 'false');
        if($result) {
            echo '0';
        } else {
            echo '1';
        }
    }

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam("trader_code",   "trader_code",   20, 'a', array("MAX_LENGTH_CHECK", "GRAPH_CHECK", "EXIST_CHECK"));
        $objFormParam->addParam("order_no",      "order_no", INT_LEN, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK", "EXIST_CHECK"));
        $objFormParam->addParam("settle_price",  "settle_price",   7, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("settle_date",   "settle_date",   14, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("settle_result", "settle_result",  1, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("settle_detail", "settle_detail",  2, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
        $objFormParam->addParam("settle_method", "settle_method",  2, 'n', array("MAX_LENGTH_CHECK", "NUM_CHECK"));
    }

    /**
     * 入力内容のチェックを行なう.
     *
     * @param object $objFormParam   SC_FormParam インスタンス
     * @return array $objErr->arrErr 入力チェック結果の配列
     */
    function lfCheckError(&$objFormParam) {
        $objErr = new SC_CheckError_Ex($objFormParam->getHashArray());
        $objErr->arrErr = $objFormParam->checkError();

        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();

        //ショップIDチェック
        if ($objFormParam->getValue('trader_code') != $arrMdlSetting['shop_id']) {
            $objErr->arrErr['shop_id'] = '※shop_idが一致しません。';
        }

        if (SC_Utils_Ex::isBlank($objErr->arrErr)) {
            //決済データ
            $arrPaymentInfo = array();
            //注文データ取得
            $order_id = $objFormParam->getValue('order_no');
            $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($order_id);
            //該当注文存在チェック
            if(!SC_Utils_Ex::isBlank($arrOrder)) {
                //決済データ取得
                $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA]);
            } else {
                $objErr->arrErr['order_id'] = '※order_no '.$order_id.' が存在しません。';
            }

            if(SC_Utils_Ex::isBlank($objErr->arrErr['order_id'])) {
                //決済手段(POST)
                $settle_method = $objFormParam->getValue('settle_method');
                //チェック処理
                if(!isset($arrPaymentInfo['function_div'])) {
                    $objErr->arrErr['function_div'] = '※ECサイトの注文は決済をご利用になっておりません。';
                } else {
                    //支払方法チェック
                    if(SC_Util_Mdl_YFCApi_Ex::isCheckPaymentMethod($settle_method, $arrOrder[MDL_YFCAPI_ORDER_COL_PAYID])) {
                        $objErr->arrErr['pay_method'] = '※支払方法が一致していません。';
                    }
                    //コンビニ種類チェック
                    if(SC_Utils_Ex::isBlank($objErr->arrErr['pay_method']) &&
                       isset($arrPaymentInfo['cvs']) && $arrPaymentInfo['cvs'] != $settle_method) {
                        $objErr->arrErr['pay_method'] = '※コンビニエンスストアの種類が異なります。';
                    }
                    //決済金額チェック
                    if(SC_Utils_Ex::isBlank($objErr->arrErr['pay_method']) && $objFormParam->getValue('settle_price') != $arrOrder['payment_total']) {
                        $objErr->arrErr['settle_price'] = '※決済金額がECサイトのお支払い合計金額と異なります。';
                    }
                }
            }
        }

        return $objErr->arrErr;
    }

    /**
     * メールを送信する.
     *
     * @param string $tplpath
     * @param string $subject
     * @param array  $arrParam
     * @param array  $arrOrder
     * @return void
     */
    function lfSendMail($tplpath, $subject, $arrParam, $arrOrder = array()) {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objMdl->printLog('param_error:' . $subject . ' Param:' . print_r($arrParam,true));

        //支払方法(名前)セット
        $arrParam['settle_method'] = SC_Util_Mdl_YFCApi_Ex::getPayNameFromSettleMethod($arrParam['settle_method']);

        $objPage = new LC_Page_Ex();
        $objPage->arrParam = $arrParam;
        $objPage->arrOrder = $arrOrder;
        $objMailView = new SC_SiteView_Ex();
        $objMailView->assignobj($objPage);
        $body = $objMailView->fetch($tplpath);
        //店舗情報
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        //メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $to    = $arrInfo['email02'];
        $from  = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $objSendMail->setItem($to, $subject, $body, $from, $arrInfo['shop_name'], $from, $error, $error);
        $objSendMail->sendMail();
    }

    /**
     * エラーメール送信（受注データ不在）
     * 
     * 受注データが存在しない
     *
     * @param array  $arrParam
     * @return void
     */
    function lfDoNoOrder(&$arrParam) {
        $tplpath = MDL_YFCAPI_TEMPLATE_PATH . 'mail_templates/recv_no_order.tpl';
        $subject = MDL_YFCAPI_MODULE_NAME . ' 不一致データ検出';
        $this->lfSendMail($tplpath, $subject, $arrParam);
    }

    /**
     * エラーメール送信（決済未使用）
     * 
     * 受注データが存在するが決済を利用していない
     *
     * @param array  $arrParam
     * @return void
     */
    function lfDoNoFunctionDiv(&$arrParam) {
        $tplpath = MDL_YFCAPI_TEMPLATE_PATH . 'mail_templates/recv_no_function_div.tpl';
        $subject = MDL_YFCAPI_MODULE_NAME . ' 決済未使用データ検出';
        $this->lfSendMail($tplpath, $subject, $arrParam);
    }

    /**
     * エラーメール送信（支払方法不一致）
     *
     * 受注データとECサイトの受注の不一致
     * 決済の種類が異なる.コンビニの種類も含める.
     *
     * @param array $arrParam
     * @param array $arrOrder
     * @return void
     */
    function lfDoUnMatchPayMethod(&$arrParam, $arrOrder = array()) {
        $tplpath = MDL_YFCAPI_TEMPLATE_PATH . 'mail_templates/recv_unmatch_pay_method.tpl';
        $subject = MDL_YFCAPI_MODULE_NAME . ' 支払い方法不一致データ検出';
        $this->lfSendMail($tplpath, $subject, $arrParam, $arrOrder);
    }

    /**
     * エラーメール送信（決済金額不一致）
     *
     * 受注データとECサイトの受注の不一致
     * お支払い合計と決済金額が異なる.
     *
     * @param array $arrParam
     * @param array $arrOrder
     * @return void
     */
    function lfDoUnMatchSettlePrice(&$arrParam, $arrOrder = array()) {
        $tplpath = MDL_YFCAPI_TEMPLATE_PATH . 'mail_templates/recv_unmatch_settle_price.tpl';
        $subject = MDL_YFCAPI_MODULE_NAME . ' 決済金額不一致データ検出';
        $this->lfSendMail($tplpath, $subject, $arrParam, $arrOrder);
    }

    /**
     * POST アクセスの妥当性を検証する.
     *
     * 生成されたトランザクショントークンの妥当性を検証し,
     * 不正な場合はエラー画面へ遷移する.
     *
     * この関数は, 基本的に init() 関数で呼び出され, POST アクセスの場合は自動的に
     * トランザクショントークンを検証する.
     * ページによって検証タイミングなどを制御する必要がある場合は, この関数を
     * オーバーライドし, 個別に設定を行うこと.
     *
     * @access protected
     * @param boolean $is_admin 管理画面でエラー表示をする場合 true
     * @return void
     */
    function doValidToken($is_admin = false) {
        //nop
    }

}
