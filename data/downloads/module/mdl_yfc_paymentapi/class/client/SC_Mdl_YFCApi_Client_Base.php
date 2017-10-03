<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
// }}}

/**
 * 決済モジュール 決済処理 基底クラス
 */
class SC_Mdl_YFCApi_Client_Base {
    var $arrErr = array();
    var $arrResults = null;

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
        $arrSendData = array();
        foreach ($arrSendKey as $key) {
            switch ($key) {
                case 'trader_code':
                    $arrSendData[$key] = $arrMdlSetting['shop_id'];
                    break;
                case 'device_div':
                    $arrSendData[$key] = SC_Util_Mdl_YFCApi_Ex::getDeviceCode();
                    break;
                case 'order_no':
                    $arrSendData[$key] = $arrOrder['order_id'];
                    break;
                case 'settle_price':
                case 'new_price':
                    $arrSendData[$key] = $arrOrder['payment_total'];
                    break;
                case 'buyer_name_kanji':
                    $arrSendData[$key] = SC_Util_Mdl_YFCApi_Ex::convertProhibitedChar($arrOrder['order_name01'] . '　'. $arrOrder['order_name02']);
                    break;
                case 'buyer_name_kana':
                    $arrSendData[$key] = SC_Util_Mdl_YFCApi_Ex::convertProhibitedChar($arrOrder['order_kana01'] . '　'. $arrOrder['order_kana02']);
                    break;
                case 'buyer_tel':
                    $arrSendData[$key] = $arrOrder['order_tel01'] . '-' . $arrOrder['order_tel02'] . '-' . $arrOrder['order_tel03'];
                    break;
                case 'buyer_email':
                    $arrSendData[$key] = $arrOrder['order_email'];
                    break;
                case 'goods_name':
                    $arrSendData[$key] = SC_Util_Mdl_YFCApi_Ex::getItemName($arrOrder['order_id']);
                    break;
                case 'card_code_api':
                    $arrSendData[$key] = SC_Util_Mdl_YFCApi_Ex::getCardCode($arrParam['card_no']);
                    break;
                case 'reserve_1':
                    $arrSendData[$key] = 'EC-CUBE' . MDL_YFCAPI_VERSION;
                    break;
                case 'last_credit_date':
                    $arrSendData[$key] = $arrParam['lastCreditDate'];
                    break;
                case 'card_exp':
                    $arrSendData[$key] = isset($arrParam['card_exp']) ? $arrParam['card_exp'] : $arrParam['card_exp_month'] . $arrParam['card_exp_year'];
                    break;
                //3Dセキュア用
                case 'comp_cd':
                    $arrSendData[$key] = $arrParam['COMP_CD'];
                    break;
                case 'item_price':
                    $arrSendData[$key] = $arrParam['ITEM_PRICE'];
                    break;
                case 'item_tax':
                    $arrSendData[$key] = $arrParam['ITEM_TAX'];
                    break;
                case 'cust_cd':
                    $arrSendData[$key] = $arrParam['CUST_CD'];
                    break;
                case 'shop_id':
                    $arrSendData[$key] = $arrParam['SHOP_ID'];
                    break;
                case 'term_cd':
                    $arrSendData[$key] = $arrParam['TERM_CD'];
                    break;
                case 'crd_res_cd':
                    $arrSendData[$key] = $arrParam['CRD_RES_CD'];
                    break;
                case 'res_ve':
                    $arrSendData[$key] = $arrParam['RES_VE'];
                    break;
                case 'res_pa':
                    $arrSendData[$key] = $arrParam['RES_PA'];
                    break;
                case 'res_code':
                    $arrSendData[$key] = $arrParam['RES_CODE'];
                    break;
                case 'three_d_inf':
                    $arrSendData[$key] = $arrParam['3D_INF'];
                    break;
                case 'three_d_tran_id':
                    $arrSendData[$key] = $arrParam['3D_TRAN_ID'];
                    break;
                case 'send_dt':
                    $arrSendData[$key] = $arrParam['SEND_DT'];
                    break;
                case 'hash_value':
                    $arrSendData[$key] = $arrParam['HASH_VALUE'];
                    break;
                case 'three_d_token':
                    $arrSendData[$key] = $arrParam['threeDToken'];
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
     * 注文情報リクエスト
     * @param string $url API URL
     * @param array $arrSendKey 送信キー
     * @param array $arrOrder 注文情報
     * @param array $arrParam その他パラメタ
     * @param array $arrPaymentInfo 支払方法設定
     * @param array $arrMdlSetting モジュール設定
     * @return bool
     */
    function sendOrderRequest($url, $arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting) {
        //コンビニ情報と取引状況を保持するためここでarrParamを保管
        $arrTempParam = $arrParam;
        //リクエスト用データ取得
        $arrSendData = $this->getSendData($arrSendKey, $arrOrder, $arrParam, $arrPaymentInfo, $arrMdlSetting);
        //リクエスト送信
        $ret = $this->sendRequest($url, $arrSendData);
        if ($ret) {
            $arrParam = (array)$this->getResults();
            unset($arrParam['threeDAuthHtml']);
        }else {
            $arrParam = array();
            $arrParam['error'] = $this->getError();
        }
        //決済情報設定
        $arrParam['function_div'] = $arrSendData['function_div'];
        $arrParam['order_no'] = $arrOrder['order_id'];

        if (!SC_Utils_Ex::isBlank($arrSendData['settle_price'])) {
            $arrParam['settle_price'] = $arrSendData['settle_price'];
        }
        if (!SC_Utils_Ex::isBlank($arrSendData['device_div'])) {
            $arrParam['device_div'] = $arrSendData['device_div'];
        }
        if (!SC_Utils_Ex::isBlank($arrSendData['option_service_div'])) {
            $arrParam['option_service_div'] = $arrSendData['option_service_div'];
        }
        if (!SC_Utils_Ex::isBlank($arrSendData['auth_div'])) {
            $arrParam['auth_div'] = $arrSendData['auth_div'];
        }
        if (!SC_Utils_Ex::isBlank($arrSendData['pay_way'])) {
            $arrParam['pay_way'] = $arrSendData['pay_way'];
        }
        if (!SC_Utils_Ex::isBlank($arrSendData['card_key'])) {
            $arrParam['card_key'] = $arrSendData['card_key'];
        }
        if (!SC_Utils_Ex::isBlank($arrSendData['card_code_api'])) {
            $arrParam['card_code_api'] = $arrSendData['card_code_api'];
        }
        if (!SC_Utils_Ex::isBlank($arrSendData['scheduled_shipping_date'])) {
            $arrParam['scheduled_shipping_date'] = $arrSendData['scheduled_shipping_date'];
        }
        if (!SC_Utils_Ex::isBlank($arrSendData['slip_no'])) {
            $arrParam['slip_no'] = $arrSendData['slip_no'];
        }
        if (!SC_Utils_Ex::isBlank($arrTempParam['action_status'])) {
            $arrParam['action_status'] = $arrTempParam['action_status'];
        }
        if (!SC_Utils_Ex::isBlank($arrTempParam['cvs'])) {
            $arrParam['cvs'] = $arrTempParam['cvs'];
            $arrOrder['cvs'] = $arrTempParam['cvs'];
        }
        if (!SC_Utils_Ex::isBlank($arrSendData['new_price'])) {
            $arrParam['new_price'] = $arrSendData['new_price'];
        }
        //決済ログの記録
        SC_Util_Mdl_YFCApi_Ex::setOrderPayData($arrOrder, $arrParam);
        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        // 成功時のみ表示用データの構築
        $this->setOrderPaymentViewData($arrOrder, $arrPaymentInfo);
        return true;
    }

    /**
     * ユーティリティリクエスト
     * @param string $url API URL
     * @param array $arrSendKey 送信キー
     * @param array $arrOrder 注文情報
     * @param array $arrParam その他パラメタ
     * @param array $arrMdlSetting モジュール設定
     * @return boolean $ret リクエスト結果
     */
    function sendUtilRequest($url, $arrSendKey, $arrOrder, $arrParam, $arrMdlSetting) {

        //リクエスト用データ取得
        $arrSendData = $this->getSendData($arrSendKey, $arrOrder, $arrParam, array(), $arrMdlSetting);
        //リクエスト送信
        return $this->sendRequest($url, $arrSendData);
    }
    /**
     * リクエスト
     * @param string $url API URL
     * @param array $arrSendData 送信データ
     * @return bool リクエスト結果
     */
    function sendRequest($url, $arrSendData) {
        //決済基本クラス取得
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        //送信パラメタのロギング
        $objMdl->printLog('SendRequest:' . $url);
        $objMdl->printLog($arrSendData);
        //通信実行
        $options = array('timeout' => MDL_YFCAPI_HTTP_TIMEOUT);
        $objReq = new HTTP_Request($url, $options);
        $objReq->setMethod('POST');
        foreach ($arrSendData as $key => $value) {
            $objReq->addPostData($key, mb_convert_encoding($value, 'UTF-8'));
        }
        $ret = $objReq->sendRequest();

        //PEARチェック
        if (PEAR::isError($ret)) {
            $msg = '通信エラー:' . $ret->getMessage();
            $this->setError($msg);
            return false;
        }

        //HTTPレスポンスチェック
        $code = $objReq->getResponseCode();
        $objMdl->printLog('Response:' . $code);
        switch ($code) {
            case 200:
                break;
            default:
                $msg = 'HTTPレスポンスエラー:CODE:' . $code;
                $this->setError($msg);
                return false;
                break;
        }

        //レスポンス内容チェック
        $response_body = $objReq->getResponseBody();
        if (SC_Utils_Ex::isBlank($response_body)) {
            $msg = 'レスポンスデータエラー: レスポンスがありません。';
            $this->setError($msg);
            return false;
        }

        //レスポンスボディをパース
        $arrRet = $this->parseResponse($response_body);
        $this->setResults($arrRet);

        //エラー確認
        if (!SC_Utils_Ex::isBlank($this->getError())) {
            return false;
        }
        return true;
    }

    /**
     * エラーメッセージ設定
     * @param string $msg
     * @return void
     */
    function setError($msg) {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objMdl->printLog($msg);
        $this->arrErr[] = $msg;
    }

    /**
     * エラーメッセージ取得
     * @return array
     */
    function getError() {
        return $this->arrErr;
    }

    /**
     * レスポンスを解析する
     *
     * @param string $string レスポンス
     * @return array 解析結果
     */
    function parseResponse($string) {
        $arrRet = array();
        $string = trim($string);
        $arrRet = SC_Util_Mdl_YFCApi_Ex::changeXml2Array($string);
        if (isset($arrRet['errorCode']) && !SC_Utils_Ex::isBlank($arrRet['errorCode'])) {
            $this->setError(SC_Util_Mdl_YFCApi_Cache_Ex::getCode('error', $arrRet['errorCode']));
        }
        return $arrRet;
    }

    /**
     * 通信結果を設定する
     *
     * @param array $arrResults レスポンス
     * @return void
     */
    function setResults($arrResults) {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $objMdl->printLog($arrResults);
        $this->arrResults = $arrResults;
    }

    /**
     * 通信結果を取得する
     *
     * @return array
     */
    function getResults() {
        return $this->arrResults;
    }

    /**
     * 受注データに決済情報をセット
     * @param $arrOrder
     * @param $arrPaymentInfo
     */
    function setOrderPaymentViewData($arrOrder, $arrPaymentInfo) {
        $arrData = array();
        $arrResult = $this->getResults();
        $arrOrder = array_merge($arrOrder, $arrResult);

        //送信日時(yyyyMMddHHmmss)
        if (!SC_Utils_Ex::isBlank($arrOrder['returnDate'])) {
            $arrData['returnDate']['name'] = '注文日時';
            $arrData['returnDate']['value'] = SC_Util_Mdl_YFCApi_Ex::getDateFromNumber('Y年m月d日 H時i分s秒', $arrOrder['returnDate']);
        }
        //ご注文番号
        if (!SC_Utils_Ex::isBlank($arrOrder['order_id'])) {
            $arrData['OrderId']['name'] = 'ご注文番号';
            $arrData['OrderId']['value'] = $arrOrder['order_id'];
        }
        //与信承認番号
        if (!SC_Utils_Ex::isBlank($arrOrder['crdCResCd'])) {
            $arrData['crdCResCd']['name'] = '与信承認番号';
            $arrData['crdCResCd']['value'] = $arrOrder['crdCResCd'];
        }

        /* セブン-イレブン決済 */
        //払込票番号
        if (!SC_Utils_Ex::isBlank($arrOrder['billingNo'])) {
            $arrData['billingNo']['name'] = '払込票番号';
            $arrData['billingNo']['value'] = $arrOrder['billingNo'];
        }
        //払込票URL
        if (!SC_Utils_Ex::isBlank($arrOrder['billingUrl'])) {
            $arrData['billingUrl']['name'] = '払込票URL';
            $arrData['billingUrl']['value'] = $arrOrder['billingUrl'];
        }
        /* ファミリーマート決済 */
        //企業コード
        if (!SC_Utils_Ex::isBlank($arrOrder['companyCode'])) {
            $arrData['companyCode']['name'] = '企業コード';
            $arrData['companyCode']['value'] = $arrOrder['companyCode'];
        }
        //注文番号(ファミリーマート)
        if (!SC_Utils_Ex::isBlank($arrOrder['orderNoF'])) {
            $arrData['orderNoF']['name'] = '注文番号(ファミリーマート)';
            $arrData['orderNoF']['value'] = $arrOrder['orderNoF'];
        }
        /* ローソン、サークルKサンクス、ミニストップ、セイコーマート決済 */
        //受付番号
        if (!SC_Utils_Ex::isBlank($arrOrder['econNo'])) {
            $arrData['econNo']['name'] = '受付番号';
            $arrData['econNo']['value'] = $arrOrder['econNo'];
        }
        /* コンビニ決済共通 */
        //支払期限日
        if (!SC_Utils_Ex::isBlank($arrOrder['expiredDate'])) {
            $arrData['expiredDate']['name'] = '支払期限日';
            $arrData['expiredDate']['value'] = SC_Util_Mdl_YFCApi_Ex::getDateFromNumber('Y年m月d日', $arrOrder['expiredDate']);
        }

        //決済完了案内タイトル（クレジット）
        if (!SC_Utils_Ex::isBlank($arrPaymentInfo['order_mail_title1']) && !SC_Utils_Ex::isBlank($arrPaymentInfo['order_mail_body1'])) {
            $arrData['order_mail_title1']['name'] = $arrPaymentInfo['order_mail_title1'];
            $arrData['order_mail_title1']['value'] = $arrPaymentInfo['order_mail_body1'];
        }

        //決済完了案内タイトル（コンビニ）
        if (!SC_Utils_Ex::isBlank($arrOrder['cvs'])) {
            $title_key = 'order_mail_title_' . $arrOrder['cvs'];
            $body_key = 'order_mail_body_' . $arrOrder['cvs'];
            if (!SC_Utils_Ex::isBlank($arrPaymentInfo[$title_key])
            && !SC_Utils_Ex::isBlank($arrPaymentInfo[$body_key])) {
                $arrData[$title_key]['name'] = $arrPaymentInfo[$title_key];
                $arrData[$title_key]['value'] = $arrPaymentInfo[$body_key];
            }
        }

        if (!SC_Utils_Ex::isBlank($arrData)) {
            $arrData['title']['value'] = '1';
            $arrData['title']['name'] = $arrPaymentInfo['payment_method'];
            $sqlval[MDL_YFCAPI_ORDER_COL_PAYVIEW] = serialize($arrData);
            $objPurchase = new SC_Helper_Purchase_Ex();
            $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], null, null, null, $sqlval);
        }
    }

    /**
     * API URLを取得
     * @param string $code APIコード
     * @return string API URL
     */
    function getApiUrl($code='') {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $api_info = ($objMdl->getUserSettings('exec_mode') == '1') ? 'api_url':'api_test_gateway';
        return SC_Util_Mdl_YFCApi_Cache_Ex::getCode($api_info, $code);
    }

    /**
     * 決済実行
     * 
     * オーバーライド先で以下の機能を実装
     * 
     * モジュール基本クラス取得
     * モジュール設定値取得
     * 注文情報から決済データ設定
     * API URL設定
     * 送信キー
     * 決済ステータス設定
     * 決済実行
     * 
     * @param array $arrOrder 注文情報
     * @param array $arrParam 送信パラメタ
     * @param array $arrPaymentInfo 支払方法設定
     * @return bool
     */
    function doPaymentRequest($arrOrder, $arrParam, $arrPaymentInfo) {
    }
}

