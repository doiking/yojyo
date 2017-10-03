<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
// }}}
/**
 * 決済モジュール用 汎用関数クラス
 */
class SC_Util_Mdl_YFCApi{

    /**
     * 受注情報に決済情報をセット
     * @param array $arrOrder 受注情報
     * @param array $arrData 決済レスポンス array('key'=>'value')
     * @return void
     */
    public static function setOrderPayData($arrOrder, $arrData) {

        //決済情報チェック
        $arrData = (array)SC_Util_Mdl_YFCApi_Ex::checkEncode($arrData);

        //受注情報取得
        $objPurchase = new SC_Helper_Purchase_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        //トランザクション開始
        $objQuery->begin();

        $sqlval = array();
        $arrOrder = $objPurchase->getOrder($arrOrder['order_id']);
        //受注情報から決済ログ取得
        if (SC_Utils_Ex::isBlank($arrOrder[MDL_YFCAPI_ORDER_COL_PAYLOG])) {
            $arrLog = array();
        } else {
            $arrLog = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array($arrOrder[MDL_YFCAPI_ORDER_COL_PAYLOG]);
        }
        //決済ログを追加
        $arrLog[] = array( date('Y-m-d H:i:s') => $arrData );
        $sqlval[MDL_YFCAPI_ORDER_COL_PAYLOG] = SC_Utils_Ex::jsonEncode($arrLog);
        //受注情報から決済データを取得
        if (SC_Utils_Ex::isBlank($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA])) {
            $arrPayData = array();
        } else {
            $arrPayData = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA]);
        }

        //決済データのマージ
        foreach ($arrData as $key => $val) {
            if (SC_Utils_Ex::isBlank($val) && !SC_Utils_Ex::isBlank($arrPayData[$key])) {
                unset($arrData[$key]);
            }
        }
        $arrPayData = array_merge($arrPayData , $arrData);
        $sqlval[MDL_YFCAPI_ORDER_COL_PAYDATA] = SC_Utils_Ex::jsonEncode($arrPayData);

        //決済状況の記録
        if (!SC_Utils_Ex::isBlank($arrData['action_status'])) {
            $sqlval[MDL_YFCAPI_ORDER_COL_PAYSTATUS] = $arrData['action_status'];
        }
        //審査結果の記録
        if (!SC_Utils_Ex::isBlank($arrData['result_code'])) {
            $sqlval[MDL_YFCAPI_ORDER_COL_EXAMRESULT] = $arrData['result_code'];
        }

        //受注データ更新
        $newStatus = null;
        $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $newStatus, null, null, $sqlval);

        //トランザクション終了
        $objQuery->commit();
    }

    /**
     * 受注決済情報を取得
     * @param integer $order_id 注文番号
     * @return array 受注決済情報（受注＋決済）
     */
    public static function getOrderPayData($order_id) {

        //受注情報取得
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrOrder = $objPurchase->getOrder($order_id);
        if ($arrOrder['del_flg'] == '1') {
            $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
            $objMdl->printLog('getOrderPayData Error: deleted order. order_id = ' . $order_id);
            return false;
        }

        //受注情報から決済情報を取得
        if (SC_Utils_Ex::isBlank($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA])) {
            $arrPayData = array();
        } else {
            $arrPayData = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA]);
        }

        //受注情報から決済ログを取得
        if (SC_Utils_Ex::isBlank($arrOrder[MDL_YFCAPI_ORDER_COL_PAYLOG])) {
            $arrPayData['payment_log'] = array();
        } else {
            $arrPayData['payment_log'] = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array($arrOrder[MDL_YFCAPI_ORDER_COL_PAYLOG]);
        }

        //受注情報と決済情報をマージ
        $arrOrder = array_merge($arrOrder, (array)$arrPayData);
        return $arrOrder;
    }

    /**
     * 支払方法情報を取得する
     *
     * @param integer $payment_id 支払いID（EC-CUBEの支払方法ID)
     * @return array 支払方法情報。決済モジュール管理対象である場合、内部識別コードを同時に設定する
     */
    function getPaymentInfo($payment_id) {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $arrData = $objQuery->getRow('*', 'dtb_payment', 'payment_id = ? and module_code = ?', array($payment_id, $objMdl->getCode(true)));
        if (SC_Utils_Ex::isBlank($arrData)) {
            return false;
        }

        // 決済モジュールの対象決済であるかの判断と内部識別コードの設定を同時に行う。
        $arrPaymentCode = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('pay_code');
        $arrData[MDL_YFCAPI_CODE . '_payment_code'] = $arrPaymentCode[$arrData[MDL_YFCAPI_PAYMENT_COL_PAYID]];
        return $arrData;
    }

    /**
     * 支払方法設定値を取得する。
     *
     * @param integer $payment_id 支払いID（EC-CUBEの支払方法ID)
     * @return array 支払方法設定値
     */
    public static function getPaymentTypeConfig($payment_id) {
        $arrData = SC_Util_Mdl_YFCApi_Ex::getPaymentInfo($payment_id);
        //支払方法設定がない場合はそのまま戻す
        if (SC_Utils_Ex::isBlank($arrData[MDL_YFCAPI_PAYMENT_COL_CONFIG])) return $arrData;

        //設定値がある場合は、取得データにマージする
        $arrTemp = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array($arrData[MDL_YFCAPI_PAYMENT_COL_CONFIG]);
        if ($arrTemp !== false) {
            $arrData = array_merge($arrData, $arrTemp);
        } else {
            $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
            $objMdl->printLog('broken config dtb_payment.' . MDL_YFCAPI_PAYMENT_COL_CONFIG . ' payment_id = ' . $payment_id);
        }
        return $arrData;
    }

    /**
     * ヤマトフィナンシャル対応支払方法を取得する
     *
     * @return array 支払方法データ
     */
    public static function getYfcPayments() {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrVal = array($objMdl->getCode(true));
        $objQuery->setOrder('rank desc');
        return $objQuery->select('*', 'dtb_payment', 'module_code = ? AND del_flg = 0', $arrVal);
    }

    /**
     * 支払方法に設定値をセット
     *
     * @param integer $payment_id
     * @param array $arrData
     * @return array 支払方法データ
     */
    public static function setPaymentTypeConfig($payment_id, $arrData) {
        if (SC_Utils_Ex::isBlank($arrData)) {
            $arrData = array();
        }
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objMdl->printLog('set paymentTypeConfig payment_id:' . $payment_id);
        $objMdl->printLog($arrData);
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $arrVal = array(MDL_YFCAPI_PAYMENT_COL_CONFIG => SC_Utils_Ex::jsonEncode($arrData));
        $objQuery->update('dtb_payment', $arrVal, 'payment_id = ? AND module_code = ?', array($payment_id, $objMdl->getCode(true)));
    }

    /**
     * 禁止文字か判定を行う。
     *
     * @param string $value 判定対象
     * @return boolean 結果
     */
    public static function isProhibitedChar($value) {
        $check_char = mb_convert_encoding($value, "SJIS-win", "UTF-8");

        $arrProhibited = array('815C','8160','8161','817C','8191','8192','81CA');
        foreach ($arrProhibited as $prohibited) {
            if (hexdec($prohibited) == hexdec(bin2hex($check_char))) {
                return true;
            }
        }

        if (hexdec('8740') <= hexdec(bin2hex($check_char)) && hexdec('879E') >= hexdec(bin2hex($check_char))) {
            return true;
        }
        if ((hexdec('ED40') <= hexdec(bin2hex($check_char)) && hexdec('ED9E') >= hexdec(bin2hex($check_char)))
         || (hexdec('ED9F') <= hexdec(bin2hex($check_char)) && hexdec('EDFC') >= hexdec(bin2hex($check_char)))
         || (hexdec('EE40') <= hexdec(bin2hex($check_char)) && hexdec('EE9E') >= hexdec(bin2hex($check_char)))
         || (hexdec('FA40') <= hexdec(bin2hex($check_char)) && hexdec('FA9E') >= hexdec(bin2hex($check_char))) 
         || (hexdec('FA9F') <= hexdec(bin2hex($check_char)) && hexdec('FAFC') >= hexdec(bin2hex($check_char)))
         || (hexdec('FB40') <= hexdec(bin2hex($check_char)) && hexdec('FB9E') >= hexdec(bin2hex($check_char)))
         || (hexdec('FB9F') <= hexdec(bin2hex($check_char)) && hexdec('FBFC') >= hexdec(bin2hex($check_char)))
         || (hexdec('FC40') <= hexdec(bin2hex($check_char)) && hexdec('FC4B') >= hexdec(bin2hex($check_char)))){
            return true;
        }
        if ((hexdec('EE9F') <= hexdec(bin2hex($check_char)) && hexdec('EEFC') >= hexdec(bin2hex($check_char)))
         ||(hexdec('F040') <= hexdec(bin2hex($check_char)) && hexdec('F9FC') >= hexdec(bin2hex($check_char)))) {
            return true;
        }

        return false;
    }

    /**
     * 禁止文字を全角スペースに置換する。
     *
     * @param string $value 対象文字列
     * @return string 結果
     */
    public static function convertProhibitedChar($value) {
        $ret = $value;
        for ($i = 0; $i < mb_strlen($value); $i++) {
            $tmp = mb_substr($value, $i , 1);
            if (SC_Util_Mdl_YFCApi_Ex::isProhibitedChar($tmp)) {
                $ret = str_replace($tmp, "　", $ret);
            }
        }
        return $ret;
    }

    /**
     * 禁止半角記号を半角スペースに変換する。
     *
     * @param string $value
     * @return string 変換した値
     */
    public static function convertProhibitedKigo($value) {
        global $arrProhibitedKigo;
        foreach ($arrProhibitedKigo as $prohibitedKigo) {
            if(strstr($value, $prohibitedKigo)) {
                $value = str_replace($prohibitedKigo, " ", $value);
            }
        }
        return $value;
    }

    /**
     * 文字列から指定バイト数を切り出す。
     *
     * @param string $value
     * @param integer $len
     * @return string 結果
     */
    public static function subString($value, $len) {
        $ret = '';
        $value = mb_convert_encoding($value, "SJIS-win", "UTF-8");
        for ($i = 1; $i <= mb_strlen($value); $i++) {
            $tmp = mb_substr($value, 0 , $i);
            if (strlen($tmp) <= $len) {
                $ret = mb_convert_encoding($tmp, "UTF-8", "SJIS-win");
            } else {
                break;
            }
        }
        return $ret;
    }

    /**
     * 配列データからログに記録しないデータをマスクする
     *
     * @param array $arrData
     * @return array マスク後データ
     */
    public static function setMaskData($arrData) {
        foreach ($arrData as $key => &$val) {
            if (is_array($val) || is_object($val)) {
                $val = SC_Util_Mdl_YFCApi_Ex::setMaskData($val);
                continue;
            }
            switch($key) {
                case 'card_no':
                    $val = str_repeat('*', strlen($val) -4) . substr($val,-4);
                    break;
                case 'CARD_NO':
                    $val = str_repeat('*', strlen($val) -4) . substr($val,-4);
                    break;
                case 'security_code':
                    $val = str_repeat('*', strlen($val));
                    break;
                case 'card_exp':
                    $val = str_repeat('*', strlen($val));
                    break;
                case 'cardExp':
                    $val = str_repeat('*', strlen($val));
                    break;
                case 'authentication_key':
                    $val = str_repeat('*', strlen($val));
                    break;
                case 'check_sum':
                    //先頭8文字のみとする
                    $val = substr($val,0,8) .'...';
                    break;
                default:
                    break;
            }
        }
        return $arrData;
    }

    /**
     * エンコードチェック
     * CHAR_CODE > SJIS-win > CHAR_CODEで欠落のないことを確認する
     * @param array $arrData
     * @return array $arrData
     */
    public static function checkEncode($arrData=array()) {
        foreach ($arrData as $key => $val) {
            //未設定、配列、単語空白以外の場合はスキップ
            if (!$val || is_array($val) || preg_match('/^[\w\s]+$/i', $val)) {
                continue;
            }
            //CHAR_CODE > SJIS-WIN > CHAR_CODEで欠落のないことを確認
            $temp = mb_convert_encoding($val, 'SJIS-win', CHAR_CODE);
            $temp = mb_convert_encoding($temp, CHAR_CODE, 'SJIS-win');
            if ($val !== $temp) {
                $temp = mb_convert_encoding($val, CHAR_CODE, 'SJIS-win');
                $temp = mb_convert_encoding($temp, 'SJIS-win', CHAR_CODE);
                if ($val === $temp) {
                    $arrData[$key] = mb_convert_encoding($val, CHAR_CODE, 'SJIS-win');
                } else {
                    $arrData[$key] = 'unknown encoding strings';
                }
            }
        }
        return $arrData;
    }

    /**
     * 配列データのマージ
     * マージデータが空の場合、上書きしない
     * @param array $arrData マージデータ
     * @param array $tempData 元データ
     * @return array $tempData
     */
    public static function mergeArrayData($arrData , $tempData) {
        foreach ($arrData as $key => $val) {
            if (SC_Utils_Ex::isBlank($val) && !SC_Utils_Ex::isBlank($tempData[$key])) {
                unset($arrData[$key]);
            }
        }
        return array_merge($tempData, (array)$arrData);
    }

    /**
     * クラスメッセージの取得
     * MDL_YFCAPI_DEBUGが有効の場合、バックトレースする。
     * @return string $class_msg
     */
    public static function getClassMsg() {
        $class_msg = '';
        if (MDL_YFCAPI_DEBUG) {
            $arrBacktrace = debug_backtrace();
            if (is_object($arrBacktrace[0]['object'])) {
                $class_name = get_class($arrBacktrace[0]['object']);
                $parent_class_name = get_parent_class($arrBacktrace[0]['object']);
                $class_msg = $parent_class_name . ' -> ' . $class_name . ' -> ';
            }
        } else {
            $class_msg = basename($_SERVER["SCRIPT_NAME"]);
        }
        return $class_msg;
    }

    /**
     * XMLを配列化
     * @param string $xml
     * @return array $arrXml
     */
    public static function changeXml2Array($xml) {
        require_once('XML/Serializer.php');
        require_once('XML/Unserializer.php');
        // XMLを配列化
        $objXmlUnserializer = new XML_Unserializer();
        $objXmlUnserializer->setOption("parseAttributes", true);
        $objXmlUnserializer->unserialize($xml);
        $arrXml = $objXmlUnserializer->getUnserializedData();
        return $arrXml;
    }

    /**
     * 注文番号から商品名取得
     * @param integer $order_id 注文番号
     * @param int $len 文字長 MDL_YFCAPI_ITEM_NAME_LEN
     * @param array $arrDetail 注文明細行
     * @return string $ret 商品名
     */
    public static function getItemName($order_id, $len = MDL_YFCAPI_ITEM_NAME_LEN, $arrDetail = array()) {
        if (SC_Utils_Ex::isBlank($arrDetail)) {
            $arrOrderDetail = SC_Helper_Purchase_Ex::getOrderDetail($order_id, false);
        } else {
            $arrOrderDetail = $arrDetail;
        }
        $ret = $arrOrderDetail[0]['product_name'];
        $ret = mb_convert_encoding($ret, 'UTF-8');
        $ret = SC_Util_Mdl_YFCApi_Ex::convertProhibitedKigo($ret);
        $ret = SC_Util_Mdl_YFCApi_Ex::convertProhibitedChar($ret);
        return SC_Util_Mdl_YFCApi_Ex::subString($ret, $len);
    }

    /**
     * オブジェクトを配列に変換
     * @param stdClass $obj
     * @return array $tempArray
     */
    public static function obj2Arr($obj){
        if (!is_object($obj) && !is_array($obj)) return $obj;
        $arr = (array) $obj;
        $tempArray = array();
        //http://php.net/manual/ja/language.types.array.php
        //integer として妥当な形式の文字列は integer 型にキャストされる
        foreach ( $arr as $key => $value ){
            $tempArray[$key] = SC_Util_Mdl_YFCApi_Ex::obj2Arr($value);
        }
        return $tempArray;
    }

    /**
     * PAYIDが設定済みでYFC決済タイプ (EC-CUBE内の決済種別 識別ID)かを判別
     * @param integer $pay_id 決済タイプ識別ID 
     * @return bool
     */
    public static function isYfcPayId($pay_id) {
        $arrPayId = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('pay_code');
        return array_key_exists($pay_id, $arrPayId);
    }

    /**
     * 端末区分を取得する
     * 
     *  1:スマートフォン
     *  2:PC
     *  3:携帯電話
     * 99:管理画面
     * 
     * @return integer $retDevCode
     */
    public static function getDeviceCode(){
        switch (SC_Display_Ex::detectDevice()) {
            case DEVICE_TYPE_SMARTPHONE:
                $retDevCode = 1;
                break;
            case DEVICE_TYPE_PC:
                $retDevCode = 2;
                break;
            case DEVICE_TYPE_MOBILE:
                $retDevCode = 3;
                break;
            case DEVICE_TYPE_ADMIN:
                $retDevCode = DEVICE_TYPE_ADMIN;
                break;
            default:
                $retDevCode = 2;
                break;
        }
        return $retDevCode;
    }

    /**
     * 決済手段区分を取得する
     * 
     *  //  1  ＵＣ
     *  //  2  ダイナース
     *  //  3  ＪＣＢ
     *  //  4  ＤＣ
     *  //  5  三井住友クレジット
     *  //  6  ＵＦＪ
     *  //  7  クレディセゾン
     *  //  8  ＮＩＣＯＳ
     *  //  9  ＶＩＳＡ
     *  // 10  ＭＡＳＴＥＲ
     *  // 11  イオンクレジット
     *  // 12  アメックス
     *  // 13  ＴＯＰ＆カード
     *  
     * @param integer
     * @return integer
     */
    public static function getCardCode($card_no){
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $exec_mode = $objMdl->getUserSettings('exec_mode');
        //初期値
        $retCode = 99;
        //  2  ダイナース
        if(preg_match('/^30[0-5]\d+$|^3095\d+$|^36\d+$|^37\d+$|^3[8-9]\d+$/', $card_no)) {
            $retCode = 2;
        }
        //  3  ＪＣＢ
        if(preg_match('/^352[8-9]\d+$|^35[3-8]\d+$/',$card_no)) {
            $retCode = 3;
        }
        //  9  ＶＩＳＡ
        if(preg_match('/^4\d+$/', $card_no)) {
            $retCode = 9;
        }
        // 10  ＭＡＳＴＥＲ
        if(preg_match('/^5\d+$/', $card_no)) {
            $retCode = 10;
        }
        // 12  アメックス
        if(preg_match('/^34\d+$|^37\d+$/', $card_no)) {
            $retCode = 12;
        }
        // ダミーカードはVISAとして返す
        if($exec_mode == '0' && preg_match('/^0000\d+$/', $card_no)) {
            $retCode = 9;
        }
        return $retCode;
    }

    /**
     * 認証キー取得
     *
     * @param integer $customer_id 会員ID
     * @return string 認証キー
     */
    public static function getAuthenticationKey($customer_id){
        return ($customer_id != '0') ? $customer_id : GC_Utils_Ex::gfMakePassword(8);
    }

    /**
     * メンバーID取得
     * 
     * @param integer $customer_id
     * @return integer $customer_id
     */
    public static function getMemberId($customer_id){
        return ($customer_id != '0') ? $customer_id : date('YmdHis');
    }

    /**
     * チェックサム取得
     * 
     * @param array $arrParam パラメタ
     * @param array $arrMdlSetting モジュール設定
     * @return string
     */
    public static function getCheckSum($arrParam, $arrMdlSetting){
        $authKey = $arrParam['authentication_key'];
        $memberId =$arrParam['member_id'];
        $accessKey = $arrMdlSetting['access_key'];
        $checksum = hash('sha256', $memberId.$authKey.$accessKey);
        return $checksum;
    }

    /**
     * JSON 文字列をデコードする（Array型を必ず返す）
     * 
     * SC_Utils_Ex::jsonDecodeでオブジェクト型を返す場合があるため
     * 必ずArray型で返すようにする
     * 
     * @param string $json JSON 形式にエンコードされた文字列
     * @return array
     */
    public static function jsonDecode2Array($json){
        $arrRet = SC_Util_Mdl_YFCApi_Ex::obj2Arr(SC_Utils_Ex::jsonDecode($json));
        return $arrRet;
    }

    /**
     * 数字の日付から日付フォーマットデータを取得
     * 20140123 から日付フォーマット
     * @param string $format
     * @param integer $number
     * @return string 日付フォーマット
     */
    public static function getDateFromNumber($format='Ymd',$number){
        if(!SC_Utils_Ex::sfIsInt($number)) return date($format);
        $number = (string)$number;
        $shortFlag = (strlen($number) < 9) ? true : false;
        $year = substr($number,0,4);
        $month = substr($number,4,2);
        $day = substr($number,6,2);
        $hour = ($shortFlag) ? '0' : substr($number,8,2);
        $minute = ($shortFlag) ? '0' : substr($number,10,2);
        $second = ($shortFlag) ? '0' : substr($number,12,2);
        return date($format, mktime($hour, $minute, $second, $month, $day, $year));
    }

    /**
     * 日付(DATE型)を整形する
     *
     * 例)
     * DB値  ： 2014-02-11
     * 戻り値： 20140211
     *
     * @param string $date
     * @param string $format
     * @return string
     */
    public static function getFormatedDate($date, $format='Ymd') {
        if(SC_Utils_Ex::isBlank($date)) return $date;
        if(SC_Utils_Ex::isBlank($format)) return $date;
        return date($format, strtotime($date));
    }

    /**
     * 注文に予約商品が含まれているか判別
     * 
     * @param integer $order_id 注文番号
     * @return boolean
     */
    public static function isReservedOrder($order_id) {
        if(!SC_Utils_Ex::sfIsInt($order_id) && $order_id <= '0') return false;
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrOrderDetail = $objPurchase->getOrderDetail($order_id);
        
        foreach ($arrOrderDetail as $val) {
            if($val['product_type_id'] == MDL_YFCAPI_PRODUCT_TYPE_ID) {
                return true;
            }
        }
        return false;
    }

    /**
     * クレジット決済注文の判別
     *
     * @param array $arrOrderId 注文番号
     * @return boolean
     */
    public static function isCreditOrderFromId($arrOrderId=array()) {
        $orderCnt = count($arrOrderId);
        if(count($orderCnt) == 0) return false;
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $from   = 'dtb_order JOIN (SELECT payment_id, module_code FROM dtb_payment) p ON dtb_order.payment_id = p.payment_id';
        $where  = 'order_id IN (' . SC_Utils_Ex::repeatStrWithSeparator('?', $orderCnt) . ')';
        $where .= ' AND (' . MDL_YFCAPI_ORDER_COL_PAYID . ' IS NULL OR ' . MDL_YFCAPI_ORDER_COL_PAYID . ' <> ?';
        $where .= ' OR p.module_code <> ?)';
        $arrVal = $arrOrderId;
        $arrVal[] = MDL_YFCAPI_PAYID_CREDIT;
        $arrVal[] = $objMdl->getCode(true);
        $unmatchCount = $objQuery->count($from, $where, $arrVal);
        return ($unmatchCount > 0) ? false : true;
    }

    /**
     * EC-CUBEパラメタチェック配列からエラー配列取得
     * @param array $arrErr
     * @return array $tempArrErr
     */
    public static function sfGetParamArrMsg($arrErr=array()) {
        $tempArrErr = array();
        foreach ($arrErr as $value) {
            $tempArrErr[] = $value;
        }
        return $tempArrErr;
    }

    /**
     * 出荷予定日を注文情報に保持する（予約商品販売用）
     * 
     * @param integer $order_id
     * @param string  $scheduled_shipping_date
     * @return void
     */
    public static function setOrderScheduledShippingDate($order_id, $scheduled_shipping_date) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $sqlval['plg_yfcapi_scheduled_shipping_date'] = $scheduled_shipping_date;
        $objQuery->update('dtb_order', $sqlval, 'order_id = ?', array($order_id));
        $objQuery->commit();
    }

    /**
     * 購入データと商品テーブルから予約商品出荷予定日を取得する
     * 
     * １注文に対し複数の予約商品が存在する場合は
     * 出荷予定日が一番未来の日付を返す
     * 
     * @param integer $order_id
     * @return NULL|string $maxScheduledDate
     */
    public static function getMaxScheduledShippingDate($order_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        //購入商品明細情報取得
        //(1)商品種別ID
        //(2)予約商品出荷予定日
        $sql = 'SELECT pc.product_type_id, p.plg_yfcapi_reserve_date FROM dtb_order_detail AS od INNER JOIN dtb_products_class AS pc ON od.product_class_id = pc.product_class_id INNER JOIN dtb_products AS p ON pc.product_id = p.product_id WHERE order_id = ?';
        $arrOrderDetail = $objQuery->getAll($sql, array($order_id));
        $maxScheduledDate = null;
        foreach ($arrOrderDetail as $detail) {
            //予約商品かつ出荷予定日が一番未来の日付を更新する
            if($detail['product_type_id'] == MDL_YFCAPI_PRODUCT_TYPE_ID && !empty($detail['plg_yfcapi_reserve_date']) && $maxScheduledDate < $detail['plg_yfcapi_reserve_date']) {
                $maxScheduledDate = $detail['plg_yfcapi_reserve_date'];
            }
        }
        return $maxScheduledDate;
    }

    /**
     * 送り状番号登録済確認
     * 
     * 該当注文番号の配送情報に「送り状番号」が登録済であるかチェックする.
     * 
     * true :送り状番号が該当注文配送先すべてに登録されている
     * false:送り状番号が登録されていない注文が存在する
     * 
     * @param array $arrOrderId
     * @return boolean
     */
    public static function isSlippingOn($arrOrderId=array()) {
        if (count($arrOrderId) == 0) return false;
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $strOrderIdIN = 'order_id IN ('.SC_Utils_Ex::repeatStrWithSeparator('?', count($arrOrderId)).')';
        $cnt = $objQuery->count('dtb_shipping', $strOrderIdIN.' AND plg_yfcapi_deliv_slip IS NULL', $arrOrderId);
        return ($cnt > 0) ? false : true;
    }

    /**
     * 出荷予定日存在確認
     * 
     * 注文情報に出荷予定日が入力されているか確認
     * 
     * true : 該当注文で出荷予定日が設定済み
     * false: 該当注文で出荷予定日が設定されていなものあり
     * 
     * @param array $arrOrderId
     * @return boolean
     */
    public static function isExistScheduledShippingDate($arrOrderId=array()) {
        if (count($arrOrderId) == 0) return false;
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $strOrderIdIN = 'order_id IN ('.SC_Utils_Ex::repeatStrWithSeparator('?', count($arrOrderId)).')';
        $where = $strOrderIdIN . ' AND plg_yfcapi_scheduled_shipping_date IS NULL';
        $cnt = $objQuery->count('dtb_order', $where, $arrOrderId);
        return ($cnt > 0) ? false : true;
    }

    /**
     * 取引状況不一致判定
     * 
     * 対象注文が該当の取引状況のいずれかで【ない】か判定
     * dtb_order.memo04で判定するため決済未使用の場合は対象外とする.
     * 
     * true : 該当注文の中で取引状況のいずれでもない場合
     * false: 該当注文の中で取引状況のいずれかに合致した場合
     * 
     * @param array $arrOrderId
     * @param array $arrStatus
     * @return boolean
     */
    public static function isUnMatchPayStatus($arrOrderId=array(), $arrStatus=array()) {
        if (count($arrOrderId) == 0 || count($arrStatus) == 0) return false;
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = MDL_YFCAPI_ORDER_COL_PAYSTATUS;
        //IN句生成（order_id IN , memo04 NOT IN）
        $strOrderIdIN = 'order_id IN ('.SC_Utils_Ex::repeatStrWithSeparator('?', count($arrOrderId)).')';
        $strMemo04IN  = MDL_YFCAPI_ORDER_COL_PAYSTATUS.' NOT IN ('.SC_Utils_Ex::repeatStrWithSeparator('?', count($arrStatus)).')';
        $where = $strOrderIdIN . ' AND ' . $strMemo04IN;
        $arrWhereVal = array_merge($arrOrderId, $arrStatus);
        $cnt = $objQuery->count('dtb_order', $where, $arrWhereVal);
        return ($cnt > 0) ? true : false;
    }

    /**
     * 複数配送先上限判定
     * 
     * 該当注文のお届け先が上限（99件）に達しているか判定する.
     * 
     * true :該当注文の中のいずれかでお届け先が上限に達している場合
     * false:いずれの注文もお届け先上限に達していない場合
     * 
     * @param array $arrOrderId
     * @return boolean
     */
    public static function isUpperLimitedShippings($arrOrderId=array()) {
        if (count($arrOrderId) == 0) return false;
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $strOrderIdIN = 'order_id IN ('.SC_Utils_Ex::repeatStrWithSeparator('?', count($arrOrderId)).')';
        $arrOrderId[] = MDL_YFCAPI_DELIV_ADDR_MAX;
        $where = $strOrderIdIN.' GROUP BY order_id HAVING COUNT(*) > ?';
        $cnt = $objQuery->count('dtb_shipping', $where, $arrOrderId);
        return ($cnt > 0) ? true : false;
    }

    /**
     * 共通送り状番号での注文同梱上限チェック
     * 
     * 
     * true : 該当注文の中に注文同梱上限をオーバーしている注文が存在する
     * false: 注文同梱上限に達していない
     * 
     * @param array $arrOrderId
     * @return boolean
     */
    public static function isUpperLimitedShippedNum($arrOrderId=array()) {
        if (count($arrOrderId) == 0) return false;
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        //IN句生成（order_id IN , memo04 NOT IN）
        $strOrderIdIN = 'order_id IN ('.SC_Utils_Ex::repeatStrWithSeparator('?', count($arrOrderId)).')';
        $arrOrderId[] = MDL_YFCAPI_SHIPPED_MAX;
        $where = 'plg_yfcapi_deliv_slip IN (SELECT plg_yfcapi_deliv_slip FROM dtb_shipping WHERE '.$strOrderIdIN.') GROUP BY plg_yfcapi_deliv_slip HAVING COUNT(*) > ?';
        $cnt = $objQuery->count('dtb_shipping', $where, $arrOrderId);
        return ($cnt > 0) ? true : false;
    }

    /**
     * 共通送り状番号で注文同梱時の発送同一チェック
     * 
     * (1)対象注文番号の送り状番号取得（複数可）
     * (2)送り状番号を対象とし配送先情報を取得（各送り状番号の配送先情報を配列で保持）
     * (3)各送り状番号で配送先情報が異なるかチェック
     *    比較カラムは以下
     *    1.shipping_name01
     *    2.shipping_name02
     *    3.shipping_tel01
     *    4.shipping_tel02
     *    5.shipping_tel03
     *    6.shipping_addr01
     *    7.shipping_addr02
     * 
     * @param array $arrOrderId
     * @return boolean
     */
    public static function isExistUnequalShipping($arrOrderId=array()) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $strOrderIdIN = 'order_id IN ('.SC_Utils_Ex::repeatStrWithSeparator('?', count($arrOrderId)).')';
        //キー（比較カラム＋送り状番号）を元に集約した結果、２件以上存在する場合は同送り状番号で異なる配送先情報が存在する
        $sql = <<< __EOF__
        SELECT COUNT(*) FROM
        (
            SELECT plg_yfcapi_deliv_slip AS plg_slip FROM dtb_shipping
                WHERE plg_yfcapi_deliv_slip IN (SELECT plg_yfcapi_deliv_slip FROM dtb_shipping WHERE $strOrderIdIN )
            GROUP BY
                shipping_name01
              , shipping_name02
              , shipping_tel01
              , shipping_tel02
              , shipping_tel03
              , shipping_addr01
              , shipping_addr02
              , plg_yfcapi_deliv_slip
        ) AS o
        GROUP BY plg_slip
        HAVING COUNT(*) > 1
__EOF__;

        $arrRet = $objQuery->getAll($sql, $arrOrderId, MDB2_FETCHMODE_ASSOC);
        return (count($arrRet[0]) > 0) ? true : false;
    }

    /**
     * 文字列から改行コードを削除する
     * @param string $str
     * @return string $str
     */
    public static function deleteBr($str){
        $str = str_replace("\n\r", "", $str);
        $str = str_replace("\r", "", $str);
        $str = str_replace("\n", "", $str);
        return $str;
    }


    /**
     * 伝票番号 桁数チェック・セブンチェックを行う.
     *
     * @param integer $delivSlip
     * @return bool
     */
    public static function checkDelivSlip($delivSlip=0) {
        $arrStr = str_split ((string)$delivSlip);
        //桁数チェック
        if(count($arrStr) != 12) return false;
        //セブンチェック（先頭11桁÷7の余りが末尾1桁）
        $tempMod = 0;
        for($i=0; $i < 11; $i++) {
            $tempMod = $tempMod * 10 + (int)$arrStr[$i];
            $tempMod %= 7;
        }
        if($tempMod !== (int)$arrStr[11]) return false;
        return true;
    }

    /**
     * 決済状況更新
     *
     * 各種リクエスト時の成功可否によって決済状況を更新する際に利用する.
     * ログには記録しない
     *
     * @param integer $order_id
     * @param string $pay_status
     * @return void
     */
    public static function sfRegistOrderPayStatus($order_id, $pay_status = MDL_YFCAPI_ACTION_STATUS_SEND_REQUEST) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $sqlval = array();
        $sqlval[MDL_YFCAPI_ORDER_COL_PAYSTATUS] = $pay_status;
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->update('dtb_order', $sqlval, 'order_id = ?', array($order_id));
        $objQuery->commit();
    }
    /**
     * 決済金額更新
     *
     * クレジット金額変更時に保持している決済金額を更新する際に利用する.
     * ログには記録しない
     *
     * @param integer $order_id
     * @param integer $new_price
     * @return void
     */
    public static function sfUpdateOrderSettlePrice($order_id, $new_price) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        
        $sqlval = array();
        //受注情報取得
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrOrder = $objPurchase->getOrder($order_id);
        
        //受注情報から決済データを取得
        if (SC_Utils_Ex::isBlank($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA])) {
            $arrPayData = array();
        } else {
            $arrPayData = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA]);
        }
        
        //変更後の金額で決済金額を上書する
        $arrPayData['settle_price'] = $new_price;
        $sqlval[MDL_YFCAPI_ORDER_COL_PAYDATA] = SC_Utils_Ex::jsonEncode($arrPayData);
        
        //受注情報の決済データを更新する
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->begin();
        $objQuery->update('dtb_order', $sqlval, 'order_id = ?', array($order_id));
        $objQuery->commit();
    }

    /**
     * 後払い決済金額更新
     *
     * 後払い決済金額変更時に保持している決済金額を更新する際に利用する.
     * ログには記録しない
     *
     * @param integer $order_id 受注ID
     * @param integer $new_price 設定する決済金額
     */
    public static function sfUpdateOrderPrice($order_id, $new_price) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();

        $sqlval = array();
        //受注情報取得
        $objPurchase = new SC_Helper_Purchase_Ex();
        $arrOrder = $objPurchase->getOrder($order_id);

        //受注情報から決済データを取得
        if (SC_Utils_Ex::isBlank($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA])) {
            $arrPayData = array();
        } else {
            $arrPayData = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array($arrOrder[MDL_YFCAPI_ORDER_COL_PAYDATA]);
        }

        //変更後の金額で決済金額を上書する
        $arrPayData['totalAmount'] = $new_price;
        $sqlval[MDL_YFCAPI_ORDER_COL_PAYDATA] = SC_Utils_Ex::jsonEncode($arrPayData);

        //受注情報の決済データを更新する
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->begin();
        $objQuery->update('dtb_order', $sqlval, 'order_id = ?', array($order_id));
        $objQuery->commit();
    }
    
    /**
     * 予約商品再与信期限オーバーチェック
     * 
     * 予約商品の再与信日（出荷予定日含む10日前）を超えていることを確認
     * 
     * @param string $scheduled_shipping_date
     * @return boolean
     */
    public static function sfIsOverDeadLineReCredit($scheduled_shipping_date = '') {
        //出荷予定日の9日前
        //再与信は出荷予定日を含む10日前のため9日前として算出
        $reCreditDate = date('Ymd', strtotime($scheduled_shipping_date.' -'.MDL_YFCAPI_DEADLINE_RECREDIT.' day'));
        return (date('Ymd') >= $reCreditDate);
    }

    /**
     * 支払方法名取得
     * 
     * 決済手段(settle_method)から支払方法名を取得する.
     * コンビニの場合はコンビニの種類も後ろに結合して取得する.
     * 
     * @param  string $settle_method 決済手段
     * @return string $payment_name 支払方法名
     */
    public static function getPayNameFromSettleMethod($settle_method) {
        //支払方法(名前)セット
        if($settle_method >= MDL_YFCAPI_CREDIT_METHOD_UC && $settle_method <= MDL_YFCAPI_CREDIT_METHOD_TOP) {
            $payment_name = 'クレジットカード決済';
        } elseif ($settle_method >= MDL_YFCAPI_CVS_METHOD_SEVENELEVEN && $settle_method <= MDL_YFCAPI_CVS_METHOD_CIRCLEK) {
            $arrCvs = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('cvs');
            $payment_name = 'コンビニ決済 ';
            switch ($settle_method) {
                case MDL_YFCAPI_CVS_METHOD_SEVENELEVEN:
                    $payment_name .= $arrCvs[MDL_YFCAPI_CVS_METHOD_SEVENELEVEN];
                    break;
                case MDL_YFCAPI_CVS_METHOD_LAWSON:
                    $payment_name .= $arrCvs[MDL_YFCAPI_CVS_METHOD_LAWSON];
                    break;
                case MDL_YFCAPI_CVS_METHOD_FAMILYMART:
                    $payment_name .= $arrCvs[MDL_YFCAPI_CVS_METHOD_FAMILYMART];
                    break;
                case MDL_YFCAPI_CVS_METHOD_SEICOMART:
                    $payment_name .= $arrCvs[MDL_YFCAPI_CVS_METHOD_SEICOMART];
                    break;
                case MDL_YFCAPI_CVS_METHOD_MINISTOP:
                    $payment_name .= $arrCvs[MDL_YFCAPI_CVS_METHOD_MINISTOP];
                    break;
                case MDL_YFCAPI_CVS_METHOD_CIRCLEK:
                    $payment_name .= $arrCvs[MDL_YFCAPI_CVS_METHOD_CIRCLEK];
                    break;
                default:
                    $payment_name .= '不明なコンビニ';
                    break;
            }
        } elseif ($settle_method == MDL_YFCAPI_EMONEY_METHOD_RAKUTENEDY) {
            $payment_name = '楽天Edy決済';
        } elseif ($settle_method == MDL_YFCAPI_EMONEY_METHOD_M_RAKUTENEDY) {
            $payment_name = '楽天モバイルEdy決済';
        } elseif ($settle_method == MDL_YFCAPI_EMONEY_METHOD_SUICA) {
            $payment_name = 'Suica決済';
        } elseif ($settle_method == MDL_YFCAPI_EMONEY_METHOD_M_SUICA) {
            $payment_name = 'モバイルSuica決済';
        } elseif ($settle_method == MDL_YFCAPI_EMONEY_METHOD_WAON) {
            $payment_name = 'WAON決済';
        } elseif ($settle_method == MDL_YFCAPI_EMONEY_METHOD_M_WAON) {
            $payment_name = 'モバイルWAON決済';
        } elseif ($settle_method == MDL_YFCAPI_NETBANK_METHOD_RAKUTENBANK) {
            $payment_name = 'ネットバンク決済';
        } else {
            $payment_name = '不明な支払方法';
        }

        return $payment_name;
    }

    /**
     * 支払い方法チェック
     * 
     * 戻り値
     *  true  支払方法不整合あり
     *  false 支払方法不整合なし
     * 
     * POST値とpay_idとの支払方法整合性チェック
     * POST値では支払方法ではなく決済手段として送信されるため、以下のような間接的なチェックとする.
     * 
     * (1)クレジットカード決済
     *    POST    settle_method 1～13, 99
     *            pay_id        10
     * (2)コンビニ決済
     *    POST    settle_method 21～26
     *            pay_id        30
     * (3)電子マネー(楽天Edy)
     *    POST    settle_method 61
     *            pay_id        42
     * (4)電子マネー(楽天モバイルEdy)
     *    POST    settle_method 62
     *            pay_id        43
     * (5)電子マネー(Suica決済)
     *    POST    settle_method 63
     *            pay_id        44
     * (6)電子マネー(モバイルSuica決済)
     *    POST    settle_method 64
     *            pay_id        45
     * (7)電子マネー(WAON決済)
     *    POST    settle_method 65
     *            pay_id        46
     * (8)電子マネー(モバイルWAON決済)
     *    POST    settle_method 66
     *            pay_id        47
     * (9)ネットバンク決済
     *    POST    settle_method 41
     *            pay_id        52
     * 
     * @param  string $settle_method 決済手段(POST)
     * @param  string $pay_id        決済タイプ（識別ID)
     * @return bool
     */
    public static function isCheckPaymentMethod($settle_method, $pay_id) {
        $isError = false;
        $err_message = '';
        //支払方法チェック
        //(1)クレジットカード決済
        if(
           ($settle_method >= MDL_YFCAPI_CREDIT_METHOD_UC && $settle_method <= MDL_YFCAPI_CREDIT_METHOD_TOP) &&
           ($pay_id != MDL_YFCAPI_PAYID_CREDIT)
          ) {
            $isError = true;
        }
        //(2)コンビニ決済
        if(
           ($settle_method >= MDL_YFCAPI_CVS_METHOD_SEVENELEVEN && $settle_method <= MDL_YFCAPI_CVS_METHOD_CIRCLEK) &&
           ($pay_id != MDL_YFCAPI_PAYID_CVS)
          ) {
            $isError = true;
        }
        //(3)電子マネー(楽天Edy)
        if($settle_method == MDL_YFCAPI_EMONEY_METHOD_RAKUTENEDY && $pay_id != MDL_YFCAPI_PAYID_EDY) {
            $isError = true;
        }
        //(4)電子マネー(楽天モバイルEdy)
        if($settle_method == MDL_YFCAPI_EMONEY_METHOD_M_RAKUTENEDY && $pay_id != MDL_YFCAPI_PAYID_MOBILEEDY) {
            $isError = true;
        }
        //(5)電子マネー(Suica決済)
        if($settle_method == MDL_YFCAPI_EMONEY_METHOD_SUICA && $pay_id != MDL_YFCAPI_PAYID_SUICA) {
            $isError = true;
        }
        //(6)電子マネー(モバイルSuica決済)
        if($settle_method == MDL_YFCAPI_EMONEY_METHOD_M_SUICA && $pay_id != MDL_YFCAPI_PAYID_MOBILESUICA) {
            $isError = true;
        }
        //(7)電子マネー(WAON決済)
        if($settle_method == MDL_YFCAPI_EMONEY_METHOD_WAON && $pay_id != MDL_YFCAPI_PAYID_WAON) {
            $isError = true;
        }
        //(8)電子マネー(モバイルWAON決済)
        if($settle_method == MDL_YFCAPI_EMONEY_METHOD_M_WAON && $pay_id != MDL_YFCAPI_PAYID_MOBILEWAON) {
            $isError = true;
        }
        //(9)ネットバンク決済
        if($settle_method == MDL_YFCAPI_NETBANK_METHOD_RAKUTENBANK && $pay_id != MDL_YFCAPI_PAYID_NETBANK) {
            $isError = true;
        }
        return $isError;
    }

    /**
     * 注文の明細行数をカウントする.
     *
     * @param array $arrOrder
     * @return integer $ret
     */
    public static function getCountDetailDeferred($arrOrder)
    {
        $ret = 0;
        // 送料分を1カウント
        $ret++;
        // 手数料を1カウント
        $ret++;
        // ポイント値引きがあれば1カウント
        if($arrOrder['use_point'] != '0'){
            $ret++;
        }
        // dtb_order_detailから明細数を取得する
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $ret += $objQuery->count('dtb_order_detail', 'order_id = ?', array($arrOrder['order_id']));

        // カウントが10を超えていたら強制的に10を返す
        if($ret > 10){
            $ret = 10;
        }

        return $ret;
    }

    /**
     * 配送先数をカウントする.
     *
     * @param integer $order_id
     * @return integer $ret
     */
    public static function getCountShipping($order_id)
    {
        // dtb_shippingからお届け先数を取得する
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $ret = $objQuery->count('dtb_shipping', 'order_id = ?', array($order_id));

        return $ret;
    }

    /**
     * 都道府県名を取得する.
     *
     * @param integer $pref_id
     * @return string $ret
     */
    public static function getPrefName($pref_id)
    {
        $masterData = new SC_DB_MasterData_Ex();
        $arrPref = $masterData->getMasterData('mtb_pref');

        $ret = $arrPref[$pref_id];

        return $ret;
    }

    /**
     * 送り先区分を取得する.
     *
     * @param string $mdl_send_div モジュール設定値「請求書の同梱」の値
     * @param array $arrOrder 注文情報
     * @param array $arrShipping 送り先情報
     * @return integer 0:自分送り、1:自分以外、2:同梱
     */
    public static function getSendDiv($mdl_send_div, $arrOrder, $arrShipping) {
        // 送り先が複数であれば1を返す
        if (count($arrShipping) > 1) {
            return 1;
        }
        // 単一配送で購入者情報と送り先情報が異なる場合1を返す
        if ($arrOrder['order_name01'] != $arrShipping[0]['shipping_name01'] ||
            $arrOrder['order_name02'] != $arrShipping[0]['shipping_name02'] ||
            $arrOrder['order_kana01'] != $arrShipping[0]['shipping_kana01'] ||
            $arrOrder['order_kana02'] != $arrShipping[0]['shipping_kana02'] ||
            $arrOrder['order_tel01'] != $arrShipping[0]['shipping_tel01'] ||
            $arrOrder['order_tel02'] != $arrShipping[0]['shipping_tel02'] ||
            $arrOrder['order_tel03'] != $arrShipping[0]['shipping_tel03'] ||
            $arrOrder['order_zip01'] != $arrShipping[0]['shipping_zip01'] ||
            $arrOrder['order_zip02'] != $arrShipping[0]['shipping_zip02'] ||
            $arrOrder['order_pref'] != $arrShipping[0]['shipping_pref'] ||
            $arrOrder['order_addr01'] != $arrShipping[0]['shipping_addr01'] ||
            $arrOrder['order_addr02'] != $arrShipping[0]['shipping_addr02']
        ) {
            return 1;
        }

        // 単一配送でモジュール設定値「請求書の同梱」が1:同梱するになっている場合2を返す
        if ($mdl_send_div == '1') {
            return 2;
        }

        return 0;
    }

    /**
     * 注文明細情報を取得する.
     *
     * @param $arrOrder
     * @return array
     */
    public static function getOrderDetailDeferred($arrOrder) {
        // 注文商品明細
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = 'product_name, price, quantity, tax_rate, tax_rule';
        $from = 'dtb_order_detail';
        $where = 'order_id = ?';

        $arrOrderDetail = $objQuery->select($col, $from, $where, array($arrOrder['order_id']));

        $arrRet = array();
        foreach($arrOrderDetail as $key => $val){
            $arrRet[$key]['itemName'] = $val['product_name'];
            $arrRet[$key]['itemCount'] = $val['quantity'];
            $arrRet[$key]['unitPrice'] = SC_Helper_DB_Ex::sfCalcIncTax($val['price'], $val['tax_rate'], $val['tax_rule']);
            $arrRet[$key]['subTotal'] = $arrRet[$key]['unitPrice'] * $val['quantity'];
        }

        // 送料、手数料、ポイント値引き
        $arrOrderKey = array(
            'deliv_fee' => '送料',
            'charge' => '手数料'
        );

        $arrOrderData = array();
        foreach($arrOrderKey as $key => $name){
            $arrOrderData[] = array(
                'itemName' => $name,
                'itemCount' => '',
                'unitPrice' => '',
                'subTotal' => $arrOrder[$key]
            );
        }

        if($arrOrder['use_point'] != '0'){
            $arrOrderData[] = array(
                'itemName' => '使用ポイント',
                'itemCount' => '',
                'unitPrice' => '',
                'subTotal' => '-'.$arrOrder['use_point']
            );
        }
        
        if($arrOrder['discount'] != '0'){
            $arrOrderData[] = array(
                'itemName' => '値引き',
                'itemCount' => '',
                'unitPrice' => '',
                'subTotal' => '-'.$arrOrder['discount']
            );
        }

        // 明細が10行より多くなる場合、商品明細を丸める
        $detail_cnt = count($arrOrderDetail);
        $order_cnt = count($arrOrderData);
        $round_cnt = ($detail_cnt + $order_cnt) - 10;

        if($round_cnt > 0){
            $round_subtotal = 0;
            for($i = $detail_cnt - $round_cnt - 1; $i < $detail_cnt; $i++){
                $round_subtotal = $round_subtotal + $arrRet[$i]['subTotal'];
                unset($arrRet[$i]);
            }
            $arrRet[] = array(
                'itemName' =>  'その他商品',
                'itemCount' => '',
                'unitPrice' => '',
                'subTotal' => $round_subtotal
            );
        }

        // 送料、手数料、ポイント値引きの明細
        foreach($arrOrderData as $key => $val){
            $arrRet[] = array(
                'itemName' => $val['itemName'],
                'itemCount' => '',
                'unitPrice' => '',
                'subTotal' => $val['subTotal'],
            );
        }

        // キーを0から振り直す
        return array_merge($arrRet);
    }

    /**
     * 送り先情報を取得する.
     *
     * @param integer $order_id
     * @return array
     */
    public static function getOrderShipping($order_id) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = <<< __EOS__
            shipping_name01,
            shipping_name02,
            shipping_kana01,
            shipping_kana02,
            shipping_tel01,
            shipping_tel02,
            shipping_tel03,
            shipping_pref,
            shipping_zip01,
            shipping_zip02,
            shipping_zipcode,
            shipping_addr01,
            shipping_addr02
__EOS__;
        $from = 'dtb_shipping';
        $where = 'order_id = ? AND del_flg = 0';
        $objQuery->setOrder('shipping_id');

        return $objQuery->select($col, $from, $where, array($order_id));
    }

    /**
     * クロネコ代金後払い注文の判別.
     *
     * @param array $arrOrderId 注文番号
     * @return bool
     */
    public static function isDeferredOrderFromId($arrOrderId=array())
    {
        $order_cnt = count($arrOrderId);
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $table = 'dtb_order t1 INNER JOIN dtb_payment t2 ON t1.payment_id = t2.payment_id';
        $where = 't1.'.MDL_YFCAPI_ORDER_COL_PAYID.' = ? AND t2.module_code = ? AND order_id IN (' . SC_Utils_Ex::repeatStrWithSeparator('?', $order_cnt) . ')';
        $arrVal = array(MDL_YFCAPI_PAYID_DEFERRED, MDL_YFCAPI_CODE);
        $arrVal = array_merge($arrVal, $arrOrderId);

        $deferred_cnt = $objQuery->count($table, $where, $arrVal);

        if ($order_cnt != $deferred_cnt) {
            return false;
        }

        return true;
    }

    /**
     * 与信審査結果登録
     *
     * @param integer $order_id
     * @param string $result_code
     * @return void
     */
    public static function sfRegistOrderExamResult($order_id, $result_code) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $sqlval = array();
        $sqlval[MDL_YFCAPI_ORDER_COL_EXAMRESULT] = $result_code;
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->update('dtb_order', $sqlval, 'order_id = ?', array($order_id));
        $objQuery->commit();
    }

    /**
     * 全配送先の送信に成功した送り状番号が保持されているかを確認.
     *
     * true :送信に成功した送り状番号が該当注文配送先すべてに登録されている
     * false:送信に成功した送り状番号が登録されていない注文が存在する
     *
     * @param array $arrOrderId
     * @return boolean
     */
    public static function isAllExistLastDelivSlip($arrOrderId=array())
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $where = 'order_id IN ('.SC_Utils_Ex::repeatStrWithSeparator('?', count($arrOrderId)).') AND del_flg = 0 AND plg_yfcapi_last_deliv_slip IS NULL';
        $cnt = $objQuery->count('dtb_shipping', $where, $arrOrderId);
        return ($cnt > 0) ? false : true;
    }

    /**
     * 予約販売可否判別
     * 　予約商品購入
     * 　モジュール設定値　オプションあり
     * 　再与信日期限を超えていない場合
     * @param bool $reserveFlg
     * @param integer $order_id
     * @return bool
     */
    public static function isReserve($reserveFlg, $order_id)
    {
        if(!$reserveFlg){
            return false;
        }

        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $arrMdlSetting = $objMdl->getUserSettings();
        if($arrMdlSetting['use_option'] != '0'){
            return false;
        }

        //出荷予定日取得
        $scheduled_shipping_date = SC_Util_Mdl_YFCApi_Ex::getFormatedDate(
            SC_Util_Mdl_YFCApi_Ex::getMaxScheduledShippingDate($order_id)
        );
        return !SC_Util_Mdl_YFCApi_Ex::sfIsOverDeadLineReCredit($scheduled_shipping_date);
    }
}
