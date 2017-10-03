<?php
/**
 * Copyright(c)2015, Yamato Financial Co.,Ltd. All rights reserved.
 */

// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Base_Ex.php');
// }}}

/**
 * 決済モジュール 決済処理: 各種取引処理
 */
class SC_Mdl_YFCApi_Client_Deferred_Util extends SC_Mdl_YFCApi_Client_Base_Ex {


    /**
     * 出荷情報登録
     *
     * @param array $arrOrder 注文情報
     * @return array (bool リクエスト結果, int 登録成功数, int 登録失敗数)
     */
    function doShipmentEntry($arrOrder) {
        $res = false;
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        //API設定
        $server_url = $this->getApiUrl('KAASL0010APIAction');
        //送信キー
        $arrSendKey = array(
            'ycfStrCode',
            'orderNo',
            'paymentNo',
            'processDiv',
            'requestDate',
            'password'
        );

        $arrParam = array();

        //配送先情報取得
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrShipping = $objPurchase->getShippings($arrOrder['order_id'], false);
        //登録処理成功カウント
        $success_cnt = 0;
        //配送先ごとに出荷情報登録処理
        foreach ($arrShipping as $shipping) {
            if (!SC_Utils_Ex::isBlank($shipping['plg_yfcapi_last_deliv_slip']) &&
                $shipping['plg_yfcapi_deliv_slip'] == $shipping['plg_yfcapi_last_deliv_slip']
            ) {
                //送信成功しているなら再送信しない
                continue;
            }

            //処理区分が0:新規登録
            $arrParam['processDiv'] = 0;
            $arrParam['paymentNo'] = $shipping['plg_yfcapi_deliv_slip'];
            if (!SC_Utils_Ex::isBlank($shipping['plg_yfcapi_last_deliv_slip'])) {
                $arrSendKey[] = 'shipYmd';
                $arrSendKey[] = 'beforePaymentNo';
                //処理区分が1:変更登録
                $arrParam['processDiv'] = 1;
                $arrParam['shipYmd'] = date('Ymd');
                $arrParam['beforePaymentNo'] = $shipping['plg_yfcapi_last_deliv_slip'];
            }

            $res = $this->sendUtilRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrMdlSetting);

            if ($res) {
                //レスポンス値取得
                $arrParam = (array)$this->getResults();
                //受注情報に決済情報をセット
                SC_Util_Mdl_YFCApi_Ex::setOrderPayData($arrOrder, $arrParam);
                //送信に成功した送り状番号を登録
                $this->lfRegistLastDelivSlip($shipping);
                //登録処理成功カウント
                $success_cnt++;
            } else {
                return array($res, $success_cnt, (count($arrShipping) - $success_cnt));
            }
        }

        //注文データの取引状況更新（送り状番号登録済）
        if($res) {
            SC_Util_Mdl_YFCApi_Ex::sfRegistOrderPayStatus($arrOrder['order_id'], MDL_YFCAPI_DEFERRED_REGIST_DELIV_SLIP);
        }

        return array($res, $success_cnt, (count($arrShipping) - $success_cnt));
    }

    /**
     * 出荷情報取消
     *
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doShipmentCancel($arrOrder) {
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        //API設定
        $server_url = $this->getApiUrl('KAASL0010APIAction');
        //送信キー
        $arrSendKey = array(
            'ycfStrCode',
            'orderNo',
            'processDiv',
            'requestDate',
            'password'
        );

        $arrParam = array();

        //処理区分を9:取消
        $arrParam['processDiv'] = 9;
        $res = $this->sendUtilRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrMdlSetting);

        if ($res) {
            //レスポンス値取得
            $arrParam = (array)$this->getResults();
            $arrParam['action_status'] = MDL_YFCAPI_DEFERRED_AUTH_OK;
            //受注情報に決済情報をセット
            SC_Util_Mdl_YFCApi_Ex::setOrderPayData($arrOrder, $arrParam);
            //送信に成功した送り状番号を削除
            $this->lfDeleteLastDelivSlip($arrOrder['order_id']);
        }

        return $res;
    }

    /**
     * 決済取消(クロネコ代金後払い)
     *
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doCancel($arrOrder) {
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();

        if($arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] != MDL_YFCAPI_PAYID_DEFERRED){
            $msg = '与信取消エラー：与信取消に対応していない決済です。';
            $objMdl->printLog($msg);
            $this->setError($msg);
            return false;
        }
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        //API設定
        $server_url = $this->getApiUrl('KAACL0010APIAction');
        //送信キー
        $arrSendKey = array(
            'ycfStrCode',
            'orderNo',
            'requestDate',
            'password'
        );

        $arrParam = array();

        $res = $this->sendUtilRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrMdlSetting);

        if($res) {
            //レスポンス値取得
            $arrParam = (array)$this->getResults();
            $arrParam['action_status'] = MDL_YFCAPI_DEFERRED_AUTH_CANCEL;
            $arrParam['requestDate'] = $arrParam['returnDate'];
            //受注情報に決済情報をセット
            SC_Util_Mdl_YFCApi_Ex::setOrderPayData($arrOrder, $arrParam);
        }
        return $res;
    }

    /**
     * 与信結果取得.
     *
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doGetAuthResult($arrOrder) {
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        $server_url = $this->getApiUrl('KAARS0010APIAction');
        //送信キー
        $arrSendKey = array(
            'ycfStrCode',
            'orderNo',
            'requestDate',
            'password'
        );

        $arrParam = array();
        $res = $this->sendUtilRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrMdlSetting);

        if($res) {
            //レスポンス値取得
            $arrParam = (array)$this->getResults();
            if(!SC_Utils_Ex::isBlank($arrParam['result'])){
                $arrParam['result_code'] = $arrParam['result'];
            }
            //受注情報に決済情報をセット
            SC_Util_Mdl_YFCApi_Ex::setOrderPayData($arrOrder, $arrParam);
        }

        return $res;
    }

    /**
     * 取引状況取得.
     *
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doGetOrderInfo($arrOrder)
    {
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //モジュール設定値取得
        $arrMdlSetting = $objMdl->getUserSettings();
        //受注決済情報を取得
        $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($arrOrder['order_id']);
        $server_url = $this->getApiUrl('KAAST0010APIAction');
        //送信キー
        $arrSendKey = array(
            'ycfStrCode',
            'orderNo',
            'requestDate',
            'password'
        );

        $arrParam = array();
        $res = $this->sendUtilRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrMdlSetting);

        if($res){
            //レスポンス値取得
            $arrParam = (array)$this->getResults();
            if(!SC_Utils_Ex::isBlank($arrParam['result'])) {
                $arrParam['action_status'] = $arrParam['result'];
            }
            //受注情報に決済情報をセット
            SC_Util_Mdl_YFCApi_Ex::setOrderPayData($arrOrder, $arrParam);
        }

        return $res;
    }

    /**
     * 送信に成功した送り状番号を登録
     *
     * @param array $shipping
     * @return void
     */
    function lfRegistLastDelivSlip($shipping) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $objQuery->begin();
        $sqlval = array();
        $sqlval['plg_yfcapi_last_deliv_slip'] = $shipping['plg_yfcapi_deliv_slip'];
        $objQuery->update('dtb_shipping', $sqlval, 'shipping_id = ? AND order_id = ?', array($shipping['shipping_id'], $shipping['order_id']));
        $objQuery->commit();
    }

    /**
     * 送信に成功した送り状番号を削除
     *
     * @param string $order_id
     * @return void
     */
    function lfDeleteLastDelivSlip($order_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $objQuery->begin();
        $sqlval = array();
        $sqlval['plg_yfcapi_last_deliv_slip'] = NULL;
        $objQuery->update('dtb_shipping', $sqlval, 'order_id = ?', array($order_id));
        $objQuery->commit();
    }

    /**
     * 送信データを取得
     * @param array $arrSendKey 送信キー
     * @param array $arrOrder 注文情報
     * @param array $arrParam その他パラメタ
     * @param array $arrPaymentInfo 支払方法設定
     * @param array $arrMdlSetting モジュール設定
     * @return array $arrSendData 送信データ
     */
    function getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting) {

        $arrShipping = SC_Util_Mdl_YFCApi_Ex::getOrderShipping($arrOrder['order_id']);
        
        $address = mb_convert_kana(SC_Util_Mdl_YFCApi_Ex::getPrefName($arrOrder['order_pref']).$arrOrder['order_addr01'].'　'.$arrOrder['order_addr02'],'KVA');
        
        $arrSendData = array();
        foreach ($arrSendKey as $key) {
            switch ($key) {
                case 'ycfStrCode':
                    $arrSendData[$key] = $arrMdlSetting['ycf_str_code'];
                    break;
                case 'orderNo':
                    $arrSendData[$key] = $arrOrder['order_id'];
                    break;
                case 'shipYmd':
                    $arrSendData[$key] = date('Ymd', strtotime($arrOrder['create_date'] . ' +'.$arrMdlSetting['ycf_ship_ymd'] . 'day'));
                    break;
                case 'postCode':
                    $arrSendData[$key] = $arrOrder['order_zip01'].$arrOrder['order_zip02'];
                    break;
                case 'address1':
                    $arrSendData[$key] = mb_substr($address, 0, 25);
                    break;
                case 'address2':
                    $arrSendData[$key] = '';
                    if (mb_substr($address, 25, 25) != '') {
                        $arrSendData[$key] = mb_substr($address, 25, 25);
                    }
                    break;
                case 'totalAmount':
                    $arrSendData[$key] = $arrOrder['payment_total'];
                    break;
                case 'sendDiv':
                    if (isset($arrParam[$key])) {
                        $arrSendData[$key] = $arrParam[$key];
                    } else {
                        $arrSendData[$key] = SC_Util_Mdl_YFCApi_Ex::getsendDiv($arrMdlSetting['ycf_send_div'], $arrOrder, $arrShipping);
                    }
                    break;
                case 'billPostCode':
                    $arrSendData[$key] = '';
                    break;
                case 'requestDate':
                    $arrSendData[$key] = date('YmdHis');
                    break;
                case 'password':
                    $arrSendData[$key] = $arrMdlSetting['ycf_str_password'];
                    break;
                default:
                    //優先順位
                    //$arrParam > $arrOrder > $arrPaymentInfo > $arrMdlSetting
                    if (isset($arrParam[$key])) {
                        $arrSendData[$key] = $arrParam[$key];
                    } elseif (isset($arrOrder[$key])) {
                        $arrSendData[$key] = $arrOrder[$key];
                    } elseif (isset($arrPaymentInfo[$key])) {
                        $arrSendData[$key] = $arrPaymentInfo[$key];
                    } elseif (isset($arrMdlSetting[$key])) {
                        $arrSendData[$key] = $arrMdlSetting[$key];
                    }
                    break;
            }
        }
        return $arrSendData;
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
        //後払い決済以外は対象外
        if ($arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] != MDL_YFCAPI_PAYID_DEFERRED) {
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
        $server_url = $this->getApiUrl('KAAKK0010APIAction');
        //送信キー
        $arrSendKey = array(
            'ycfStrCode',
            'orderNo',
            'shipYmd',
            'postCode',
            'address1',
            'address2');

        $arrItem = SC_Util_Mdl_YFCApi_Ex::getOrderDetailDeferred($arrOrder);
        
        //受注詳細ごとに商品情報取得処理
        foreach ($arrItem as $key=>$val) {
            $seq = $key + 1;
            $arrSendKey[] = 'itemName'.$seq;
            $arrSendKey[] = 'itemCount'.$seq;
            $arrSendKey[] = 'unitPrice'.$seq;
            $arrSendKey[] = 'subTotal'.$seq;
            $arrParam['itemName'.$seq] = mb_substr(mb_convert_kana($val['itemName'], 'KVA'), 0, 30);
            $arrParam['itemCount'.$seq] = $val['itemCount'];
            $arrParam['unitPrice'.$seq] = $val['unitPrice'];
            $arrParam['subTotal'.$seq] = $val['subTotal'];
        }
        $arrSendKey[] = 'totalAmount';
        $arrSendKey[] = 'sendDiv';
        $arrSendKey[] = 'billPostCode';
        $arrSendKey[] = 'password';
        $arrSendKey[] = 'requestDate';
        
        // 送り先区分：sendDivはブランクで送信
        $arrParam['sendDiv'] = '';
        
        $res = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);

        //処理成功の場合、決済金額の更新をおこなう
        if($res){
            SC_Util_Mdl_YFCApi_Ex::sfUpdateOrderPrice($arrOrder['order_id'], $arrOrder['payment_total']);
        }
        return $res;
    }

    /**
     * 請求書再発行.
     *
     * @param array $arrOrder 注文情報
     * @return bool
     */
    function doInvoiceReissue($arrOrder, $request_type)
    {
        $ret = false;
        //モジュール基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //後払い決済以外は対象外
        if ($arrOrder[MDL_YFCAPI_ORDER_COL_PAYID] != MDL_YFCAPI_PAYID_DEFERRED) {
            $msg = '請求書再発行に対応していない決済です。';
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
        $server_url = $this->getApiUrl('KAARR0010APIAction');
        //送信キー
        $arrSendKey = array(
            'ycfStrCode',
            'orderNo',
            'password',
            'requestContents',
            'reasonReissue',
            'reasonReissueEtc',
            'shipYmd',
            'sendDiv',
            'postCode',
            'address1',
            'address2');

        // 送り先区分：sendDivはブランクで送信
        $arrParam['sendDiv'] = '';
        
        $arrParam['requestContents'] = $request_type;
        $arrParam['reasonReissue'] = '';
        $arrParam['reasonReissueEtc'] = '';
        if ($request_type == 1) {
            $arrParam['reasonReissue'] = 6;
            $arrParam['reasonReissueEtc'] = '不明';
        }
        
        $arrItem = SC_Util_Mdl_YFCApi_Ex::getOrderDetailDeferred($arrOrder);

        //受注詳細ごとに商品情報取得処理
        foreach ($arrItem as $key=>$val) {
            $seq = $key + 1;
            $arrSendKey[] = 'itemName'.$seq;
            $arrSendKey[] = 'itemCount'.$seq;
            $arrSendKey[] = 'unitPrice'.$seq;
            $arrSendKey[] = 'subTotal'.$seq;
            $arrParam['itemName'.$seq] = mb_convert_kana(mb_substr($val['itemName'], 0, 30), 'KVA');
            $arrParam['itemCount'.$seq] = $val['itemCount'];
            $arrParam['unitPrice'.$seq] = $val['unitPrice'];
            $arrParam['subTotal'.$seq] = $val['subTotal'];
        }
        $arrSendKey[] = 'billPostCode';

        $res = $this->sendOrderRequest($server_url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        
        //処理成功の場合、かつ請求書再発行の場合、請求書再発行通知メール送信
        if($res && $request_type == 1){
            // 請求書再発行通知メール(送信あり)
            $template_id = $arrOrder['device_type_id'] == DEVICE_TYPE_MOBILE ? 2 : 1;
            $this->lfSendOrderMail(
                $arrOrder['order_id'],
                $template_id,
                '請求書再発行のお知らせ',
                $arrMdlSetting['ycf_invoice_reissue_mail_header'],
                $arrMdlSetting['ycf_invoice_reissue_mail_footer'],
                $arrMdlSetting['ycf_invoice_reissue_mail_address']);
        }
        return $res;
    }

    /**
     * 請求書再発行通知メール送信処理
     * @param $order_id
     * @param $template_id
     * @param $subject
     * @param $header
     * @param $footer
     * @param $send_address
     * @return void
     */
    public function lfSendOrderMail($order_id, $template_id, $subject, $header, $footer, $send_address)
    {
        $objMail = new SC_Helper_Mail_Ex();
        
        $arrTplVar = new stdClass();
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $arrTplVar->arrInfo = $arrInfo;

        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrTplVar->tpl_header = $header;
        $arrTplVar->tpl_footer = $footer;
        $tmp_subject = $subject;

        // 受注情報の取得
        $where = 'order_id = ? AND del_flg = 0';
        $arrOrder = $objQuery->getRow('*', 'dtb_order', $where, array($order_id));

        if (empty($arrOrder)) {
            trigger_error("該当する受注が存在しない。(注文番号: $order_id)", E_USER_ERROR);
        }

        $where = 'order_id = ?';
        $objQuery->setOrder('order_detail_id');
        $arrTplVar->arrOrderDetail = $objQuery->select('*', 'dtb_order_detail', $where, array($order_id));

        // 配送情報の取得
        $arrTplVar->arrShipping = $objMail->sfGetShippingData($order_id);

        $arrTplVar->Message_tmp = $arrOrder['message'];

        // 会員情報の取得
        $customer_id = $arrOrder['customer_id'];
        $objQuery->setOrder('customer_id');
        $arrRet = $objQuery->select('point', 'dtb_customer', 'customer_id = ?', array($customer_id));
        $arrCustomer = isset($arrRet[0]) ? $arrRet[0] : '';

        $arrTplVar->arrCustomer = $arrCustomer;
        $arrTplVar->arrOrder = $arrOrder;

        //その他決済情報
        if ($arrOrder['memo02'] != '') {
            $arrOther = unserialize($arrOrder['memo02']);

            foreach ($arrOther as $other_key => $other_val) {
                if (SC_Utils_Ex::sfTrim($other_val['value']) == '') {
                    $arrOther[$other_key]['value'] = '';
                }
            }

            $arrTplVar->arrOther = $arrOther;
        }

        // 都道府県変換
        $arrTplVar->arrPref = $objMail->arrPref;
        // 国変換
        $arrTplVar->arrCountry = $objMail->arrCountry;

        $objCustomer = new SC_Customer_Ex();
        $arrTplVar->tpl_user_point = $objCustomer->getValue('point');

        $objMailView = null;
        if (SC_Display_Ex::detectDevice() == DEVICE_TYPE_MOBILE) {
            $objMailView = new SC_MobileView_Ex();
        } else {
            $objMailView = new SC_SiteView_Ex();
        }
        // メール本文の取得
        $objMailView->setPage($objMail->getPage());
        $objMailView->assignobj($arrTplVar);
        $body = $objMailView->fetch($objMail->arrMAILTPLPATH[$template_id]);

        // メール送信処理
        $objSendMail = new SC_SendMail_Ex();
        $bcc = $send_address;
        $from = $arrInfo['email03'];
        $error = $arrInfo['email04'];
        $tosubject = $objMail->sfMakeSubject($tmp_subject, $objMailView);

        $objSendMail->setItem('', $tosubject, $body, $from, $arrInfo['shop_name'], $from, $error, $error, $bcc);
        $objSendMail->setTo($arrOrder['order_email'], $arrOrder['order_name01'] . ' '. $arrOrder['order_name02'] .' 様');

        if ($objSendMail->sendMail()) {
            $objMail->sfSaveMailHistory($order_id, $template_id, $tosubject, $body);
        }

        return $objSendMail;
    }

}
