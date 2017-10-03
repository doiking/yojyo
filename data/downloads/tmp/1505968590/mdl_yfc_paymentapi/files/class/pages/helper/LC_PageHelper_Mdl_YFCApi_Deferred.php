<?php
/**
 * Copyright(c)2015, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MDL_YFCAPI_PAGE_HELPEREX_PATH . 'LC_PageHelper_Mdl_YFCApi_Base_Ex.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Deferred_Ex.php');
// }}}
/**
 * 決済モジュール 決済画面ヘルパー：後払い決済
 */
class LC_PageHelper_Mdl_YFCApi_Deferred extends LC_PageHelper_Mdl_YFCApi_Base_Ex
{

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param array $arrPaymentInfo モジュール設定情報
     * @param array $arrOrder 受注情報
     * @return void
     */
    function initParam(&$objFormParam, &$arrPaymentInfo, &$arrOrder)
    {
        $objFormParam->addParam('加盟店コード', 'ycfStrCode', 11, 'n', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('受注番号', 'orderNo', INT_LEN, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('受注日', 'orderYmd', 8, 'n', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('出荷予定日', 'shipYmd', 8, 'n', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('氏名', 'name', 30, MDL_YFCAPI_CONV_OP_DOUBLE, array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('氏名カナ', 'nameKana', 80, 'k', array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('郵便番号', 'postCode', 7, 'n', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('住所1', 'address1', 25, MDL_YFCAPI_CONV_OP_DOUBLE, array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
        $objFormParam->addParam('住所2', 'address2', 25, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('電話番号', 'telNum', 11, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('メールアドレス', 'email', 64, 'a', array('MAX_LENGTH_CHECK', 'EMAIL_CHECK'));
        $objFormParam->addParam('決済金額総計', 'totalAmount', 6, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('送り先区分', 'sendDiv', 1, 'n', array('EXIST_CHECK', 'MAX_LENGTH_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('カート社識別コード', 'cartCode', 20, 'a', array('MAX_LENGTH_CHECK'));
        $detail_cnt = SC_Util_Mdl_YFCApi_Ex::getCountDetailDeferred($arrOrder);
        for($i = 1; $i <= $detail_cnt; $i++){
            $objFormParam->addParam('購入商品名称'.$i, 'itemName'.$i, 30, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
            $objFormParam->addParam('購入商品数量'.$i, 'itemCount'.$i, 4, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
            $objFormParam->addParam('購入商品単価'.$i, 'unitPrice'.$i);
            $objFormParam->addParam('購入商品小計'.$i, 'subTotal'.$i);
            // 購入者商品名称1は必須
            if ($i == 1) {
                $objFormParam->overwriteParam('itemName1', 'arrCheck', array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
            }
        }
        $shipping_cnt = SC_Util_Mdl_YFCApi_Ex::getCountShipping($arrOrder['order_id']);
        for($i = 1; $i <= $shipping_cnt; $i++){
            $seq = $i;
            if ($seq == 1) {
                // 1件目の項目に数字を振らない
                $seq = '';
            }
            $objFormParam->addParam('送り先名称'.$seq, 'sendName'.$seq, 30, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
            $objFormParam->addParam('送り先郵便番号'.$seq, 'sendPostCode'.$seq, 7, 'n', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'NUM_CHECK'));
            $objFormParam->addParam('送り先住所1'.$seq, 'sendAddress1'.$seq, 25, MDL_YFCAPI_CONV_OP_DOUBLE, array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
            $objFormParam->addParam('送り先住所2'.$seq, 'sendAddress2'.$seq, 25, MDL_YFCAPI_CONV_OP_DOUBLE, array('MAX_LENGTH_CHECK'));
            $objFormParam->addParam('送り先電話番号'.$seq, 'sendTelNum'.$seq, 11, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
        }
        $objFormParam->addParam('依頼日時', 'requestDate', 14, 'n', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'NUM_CHECK'));
        $objFormParam->addParam('パスワード', 'password', 8, 'a', array('EXIST_CHECK', 'NUM_COUNT_CHECK', 'ALNUM_CHECK'));

        // パラメタセット
        $this->lfSetParam($objFormParam, $arrOrder);
        $objFormParam->convParam();
        // 文字長調整処理
        $this->lfSubStr($objFormParam, $detail_cnt, $shipping_cnt);
    }

    /**
     * 入力内容のチェックを行なう.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param array $arrOrder 受注情報
     * @return array 入力チェック結果の配列
     */
    function checkError(&$objFormParam, $arrOrder)
    {
        $arrParam = $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrParam);
        $objErr->arrErr = $objFormParam->checkError();

        if (!SC_Utils_Ex::isBlank($objErr->arrErr['orderNo'])) {
            // 受注番号が不正だと以降の処理に影響が出るのでここでreturnする
            return $objErr->arrErr;
        }

        // 商品明細（商品のみ）が1件もなければエラー
        if ($this->lfGetCountOrderDetail($arrParam['orderNo']) < 1) {
            $objErr->arrErr['detailCount'] = '※ 購入商品の明細がありません。<br />';
        }

        // 配送先数が10より多ければエラー
        $shipping_cnt = SC_Util_Mdl_YFCApi_Ex::getCountShipping($arrParam['orderNo']);
        if ($shipping_cnt > MDL_YFCAPI_DEFERRED_DELIV_ADDR_MAX) {
            $objErr->arrErr['sendCount'] = '※ 送り先の上限数は'.MDL_YFCAPI_DEFERRED_DELIV_ADDR_MAX.'件です。<br />';
        }

        // 送り先区分が「1:自分以外」の場合必須
        if ($arrParam['sendDiv'] == '1') {
            for ($i = 1; $i <= $shipping_cnt; $i++) {
                $seq = $i;
                if ($seq == 1) {
                    // 1件目の項目に数字を振らない
                    $seq = '';
                }
                $objErr->doFunc(array('送り先名称'.$seq, 'sendName'.$seq), array('EXIST_CHECK'));
                $objErr->doFunc(array('送り先郵便番号'.$seq, 'sendPostCode'.$seq), array('EXIST_CHECK'));
                $objErr->doFunc(array('送り先住所1'.$seq, 'sendAddress1'.$seq), array('EXIST_CHECK'));
            }
        }

        // 決済金額総計が支払方法利用条件を満たしていなければエラー
        $arrPaymentInfo = SC_Util_Mdl_YFCApi_Ex::getPaymentTypeConfig($arrOrder['payment_id']);
        if (SC_Utils_Ex::isBlank($objErr->arrErr['totalAmount']) && !SC_Utils_Ex::isBlank($arrPaymentInfo['rule_max']) && $arrParam['totalAmount'] < $arrPaymentInfo['rule_max']) {
            $objErr->arrErr['totalAmount'] = '※ 決済金額総計が支払方法利用条件を満たしておりません。<br />';
        }
        if (SC_Utils_Ex::isBlank($objErr->arrErr['totalAmount']) && !SC_Utils_Ex::isBlank($arrPaymentInfo['upper_rule']) && $arrParam['totalAmount'] > $arrPaymentInfo['upper_rule']) {
            $objErr->arrErr['totalAmount'] = '※ 決済金額総計が支払方法利用条件を満たしておりません。<br />';
        }

        // 受注日の妥当性チェック
        if (SC_Utils_Ex::isBlank($objErr->arrErr['orderYmd']) && !$this->lfCheckDate($arrParam['orderYmd'])) {
            $objErr->arrErr['orderYmd'] = '※ 受注日が不正です。<br />';
        }

        // 出荷予定日の妥当性チェック
        if (SC_Utils_Ex::isBlank($objErr->arrErr['shipYmd']) && !$this->lfCheckDate($arrParam['shipYmd'])) {
            $objErr->arrErr['shipYmd'] = '※ 出荷予定日が不正です。<br />';
        }

        // 依頼日時の妥当性チェック
        if (SC_Utils_Ex::isBlank($objErr->arrErr['requestDate']) && (!$this->lfCheckDate($arrParam['requestDate']) || !$this->lfCheckTime($arrParam['requestDate']))) {
            $objErr->arrErr['requestDate'] = '※ 依頼日時が不正です。<br />';
        }

        $detail_cnt = SC_Util_Mdl_YFCApi_Ex::getCountDetailDeferred($arrOrder);
        for($i = 1; $i <= $detail_cnt; $i++) {
            // 購入商品数量最大値チェック
            if (SC_Utils_Ex::isBlank($objErr->arrErr['itemCount'.$i]) && !SC_Utils_Ex::isBlank($arrParam['itemCount'.$i]) && $arrParam['itemCount'.$i] > 9999) {
                $objErr->arrErr['itemCount'.$i] = '※ 商品数量は9999までです。<br />';
            }
            // 購入商品単価エラーチェック
            if (SC_Utils_Ex::isBlank($objErr->arrErr['unitPrice'.$i]) && !SC_Utils_Ex::isBlank($arrParam['unitPrice'.$i]) && !preg_match('/^[-0-9]+$/', $arrParam['unitPrice'.$i])) {
                $objErr->arrErr['unitPrice'.$i] = '※ 商品単価が不正です。<br />';
            }
            if (SC_Utils_Ex::isBlank($objErr->arrErr['unitPrice'.$i]) && !SC_Utils_Ex::isBlank($arrParam['unitPrice'.$i])) {
                $price_abs = abs($arrParam['unitPrice'.$i]);
                if ($price_abs > 999999) {
                    $objErr->arrErr['unitPrice'.$i] = '※ 商品単価が不正です。<br />';
                }
            }
            // 購入商品小計エラーチェック
            if (SC_Utils_Ex::isBlank($objErr->arrErr['subTotal'.$i]) && !SC_Utils_Ex::isBlank($arrParam['subTotal'.$i]) && !preg_match('/^[-0-9]+$/', $arrParam['subTotal'.$i])) {
                $objErr->arrErr['subTotal'.$i] = '※ 商品小計が不正です。<br />';
            }
            if (SC_Utils_Ex::isBlank($objErr->arrErr['subTotal'.$i]) && !SC_Utils_Ex::isBlank($arrParam['subTotal'.$i])) {
                $subtotal_abs = abs($arrParam['subTotal'.$i]);
                if ($subtotal_abs > 999999) {
                    $objErr->arrErr['subTotal'.$i] = '※ 商品小計が不正です。<br />';
                }
            }
        }

        // APIパスワードのエラー文言変更
        if (!SC_Utils_Ex::isBlank($objErr->arrErr['password'])) {
            $objErr->arrErr['password'] = 'パスワードが不正です。店舗までお問合わせ下さい。<br />';
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
    function modeAction($mode, &$objFormParam, &$arrOrder, &$objPage)
    {

        $objPurchase = new SC_Helper_Purchase_Ex();

        $objPage->auto_submit = false;

        switch ($mode) {
            case 'next':
                $objPage->arrErr = $this->checkError($objFormParam, $arrOrder);
                if (SC_Utils_Ex::isBlank($objPage->arrErr)) {
                    // 決済実行
                    $objClient = new SC_Mdl_YFCApi_Client_Deferred_Ex();
                    $result = $objClient->doPaymentRequest($arrOrder, $objFormParam->getHashArray(), $objPage->arrPaymentInfo);

                    if ($result) {
                        $order_status = ORDER_NEW;
                        $objQuery =& SC_Query_Ex::getSingletonInstance();
                        $objQuery->begin();
                        $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
                        $objQuery->commit();
                        $objPurchase->sendOrderMail($arrOrder['order_id']);
                        SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                        $objPage->actionExit();
                    } else {
                        $objPage->arrErr['payment'] = '決済処理でエラーが発生しました。';
                    }
                }
                break;
            case 'return':
                $objPurchase->rollbackOrder($arrOrder['order_id'], ORDER_CANCEL, true);
                SC_Response_Ex::sendRedirect(SHOPPING_PAYMENT_URLPATH);
                SC_Response_Ex::actionExit();
                break;
            default:
                $objPage->auto_submit = true;
                break;
        }
    }

    /**
     * 画面に設定するテンプレート名を返す
     *
     * @return string テンプレートファイル名
     */
    function getFormBloc()
    {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $arrBlocId = $objMdl->getSubData('bloc_setting');
        $device_type_id = SC_Display_Ex::detectDevice();
        $bloc_id = $arrBlocId['yfc_deferred'][$device_type_id];
        if ($bloc_id) {
            $objLayout = new SC_Helper_PageLayout_Ex();
            $arrBloc = $objLayout->getBlocs($device_type_id, 'bloc_id = ?', array($bloc_id), true);
            return $arrBloc[0]['tpl_path'];
        }
        return '';
    }

    /**
     * 購入商品明細数を取得する.
     *
     * @param integer $order_id 受注番号
     * @return integer 購入商品明細数
     */
    function lfGetCountOrderDetail($order_id)
    {
        // dtb_order_detailから購入商品明細数を取得する
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        return $objQuery->count('dtb_order_detail', 'order_id = ?', array($order_id));
    }

    /**
     * 日付の妥当性チェック.
     *
     * @param string $date Ymd形式の日付
     * @return bool
     */
    function lfCheckDate($date)
    {
        $year  = substr($date, 0, 4); // 年
        $month = substr($date, 4, 2); // 月
        $day   = substr($date, 6, 2); // 日

        return checkdate($month, $day, $year);
    }

    /**
     * 時間の妥当性チェック.
     *
     * @param string $date YmdHis形式の日時
     * @return bool
     */
    function lfCheckTime($date)
    {
        $hour  = substr($date, 8, 2);   // 時
        $minute = substr($date, 10, 2); // 分
        $second = substr($date, 12, 2); // 秒

        if ($hour >= 0 && $hour <= 23 &&
            $minute >= 0 && $minute <= 59 &&
            $second >= 0 && $second <= 59
        ) {
            return true;
        }

        return false;
    }

    /**
     * パラメータに値をセットする.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param array $arrOrder 受注情報
     * @return void
     */
    function lfSetParam(&$objFormParam, &$arrOrder)
    {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();
        $arrItem = SC_Util_Mdl_YFCApi_Ex::getOrderDetailDeferred($arrOrder);
        $arrShipping = SC_Util_Mdl_YFCApi_Ex::getOrderShipping($arrOrder['order_id']);

        $arrParam = $objFormParam->getHashArray();

        $arrParam['ycfStrCode'] = $arrMdlSetting['ycf_str_code'];
        $arrParam['orderNo'] = $arrOrder['order_id'];
        $arrParam['orderYmd'] = SC_Util_Mdl_YFCApi_Ex::getFormatedDate($arrOrder['create_date'], 'Ymd');
        $arrParam['shipYmd'] = date('Ymd', strtotime($arrOrder['create_date'] . ' +'.$arrMdlSetting['ycf_ship_ymd'] . 'day'));
        $arrParam['name'] = $arrOrder['order_name01'].'　'.$arrOrder['order_name02'];
        $arrParam['nameKana'] = $arrOrder['order_kana01'].' '.$arrOrder['order_kana02'];
        $arrParam['postCode'] = $arrOrder['order_zip01'].$arrOrder['order_zip02'];
        $arrParam['address1'] = SC_Util_Mdl_YFCApi_Ex::getPrefName($arrOrder['order_pref']).$arrOrder['order_addr01'].'　'.$arrOrder['order_addr02'];
        $arrParam['address2'] = '';
        $arrParam['telNum'] = $arrOrder['order_tel01'].$arrOrder['order_tel02'].$arrOrder['order_tel03'];
        $arrParam['email'] = $arrOrder['order_email'];
        $arrParam['totalAmount'] = $arrOrder['payment_total'];
        $arrParam['sendDiv'] = SC_Util_Mdl_YFCApi_Ex::getsendDiv($arrMdlSetting['ycf_send_div'], $arrOrder, $arrShipping);;
        $arrParam['cartCode'] = 'eccube213';
        foreach($arrItem as $key => $val){
            $seq = $key + 1;
            $arrParam['itemName'.$seq] = $val['itemName'];
            $arrParam['itemCount'.$seq] = $val['itemCount'];
            $arrParam['unitPrice'.$seq] = $val['unitPrice'];
            $arrParam['subTotal'.$seq] = $val['subTotal'];
        }
        foreach($arrShipping as $key => $val){
            $seq = $key + 1;
            if ($seq == 1) {
                // 1件目の項目に数字を振らない
                $seq = '';
            }
            $arrParam['sendName'.$seq] = $val['shipping_name01'].'　'.$val['shipping_name02'];
            $arrParam['sendPostCode'.$seq] = $val['shipping_zip01'].$val['shipping_zip02'];
            $arrParam['sendAddress1'.$seq] = SC_Util_Mdl_YFCApi_Ex::getPrefName($val['shipping_pref']).$val['shipping_addr01'].'　'.$val['shipping_addr02'];
            $arrParam['sendAddress2'.$seq] = '';
            $arrParam['sendTelNum'.$seq] = $val['shipping_tel01'].$val['shipping_tel02'].$val['shipping_tel03'];
        }
        $arrParam['requestDate'] = date('YmdHis');
        $arrParam['password'] = $arrMdlSetting['ycf_str_password'];

        $objFormParam->setParam($arrParam);
    }

    /**
     * 文字列を切り取る.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param integer $detail_cnt 注文明細数
     * @param integer $shipping_cnt 送り先数
     * @return void
     */
    function lfSubStr(&$objFormParam, $detail_cnt, $shipping_cnt)
    {
        $arrParam = $objFormParam->getHashArray();

        $arrParam['name'] = mb_substr($arrParam['name'], 0, 30);
        $arrParam['nameKana'] = mb_substr($arrParam['nameKana'], 0, 80);
        $address = $arrParam['address1'];
        $arrParam['address1'] = mb_substr($address, 0, 25);
        if (mb_substr($address, 25, 25) != '') {
            $arrParam['address2'] = mb_substr($address, 25, 25);
        }
        $arrParam['email'] = mb_substr($arrParam['email'], 0, 64);
        for($i = 1; $i <= $detail_cnt; $i++){
            $arrParam['itemName'.$i] = mb_substr($arrParam['itemName'.$i], 0, 30);
        }
        for($i = 1; $i <= $shipping_cnt; $i++){
            $seq = $i;
            if ($seq == 1) {
                // 1件目の項目に数字を振らない
                $seq = '';
            }
            $arrParam['sendName'.$seq] = mb_substr($arrParam['sendName'.$seq], 0, 30);
            $sendAddress = $arrParam['sendAddress1'.$seq];
            $arrParam['sendAddress1'.$seq] = mb_substr($sendAddress, 0, 25);
            if (mb_substr($sendAddress, 25, 25) != '') {
                $arrParam['sendAddress2'.$seq] = mb_substr($sendAddress, 25, 25);
            }
        }

        $objFormParam->setParam($arrParam);
    }

}
