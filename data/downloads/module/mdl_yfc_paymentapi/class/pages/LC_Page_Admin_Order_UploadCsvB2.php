<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(CLASS_EX_REALDIR . "page_extends/admin/LC_Page_Admin_Ex.php");
// }}}

/**
 * B2CSV登録のページクラス.
 *
 * @package Page
 */
class LC_Page_Admin_Order_UploadCsvB2 extends LC_Page_Admin_Ex
{

    /** 登録フォームカラム情報 **/
    public $arrFormKeyList;

    public $arrRowErr;

    public $arrRowResult;

    public $arrRowShipmentEntryReport;

    public $arrCreditOrderId;

    public $arrDeferredOrderId;

    public $arrSetting;

    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init(){
        parent::init();
        $this->tpl_mainpage = MDL_YFCAPI_TEMPLATE_PATH. 'admin/order/upload_csv_b2.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'upload_csv_b2';
        $this->tpl_maintitle = '受注管理';
        $this->tpl_subtitle = '送り状番号登録';
        $this->arrCreditOrderId = array();
        $this->arrDeferredOrderId = array();
        $this->arrSetting = $this->lfLoadData();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process(){
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        $this->objDb = new SC_Helper_DB_Ex();

        // CSVファイルアップロード情報の初期化
        $objUpFile = new SC_UploadFile_Ex(CSV_TEMP_REALDIR, CSV_TEMP_REALDIR);
        $this->lfInitFile($objUpFile);

        // パラメーター情報の初期化
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);

        $this->max_upload_csv_size = SC_Utils_Ex::getUnitDataSize(CSV_SIZE);

        $objFormParam->setHtmlDispNameArray();
        $this->arrTitle = $objFormParam->getHtmlDispNameArray();

        switch ($this->getMode()) {
            case 'csv_upload':
                $this->doUploadCsv($objFormParam, $objUpFile);
                break;
            default:
                break;
        }

    }

    /**
     * クレジット決済の注文IDを追加する
     *
     * @param  integer $order_id
     * @return void
     */
    public function addCreditOrderId($order_id){
        //保持していない注文IDのみ
        if(!in_array($order_id, $this->arrCreditOrderId)){
            //クレジット決済のみ登録
            if(SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array($order_id))){
                $this->arrCreditOrderId[] = $order_id;
            }
        }
    }

    /**
     * クロネコ代金後払い決済の注文IDを追加する
     *
     * @param  integer $order_id
     * @return void
     */
    public function addDeferredOrderId($order_id){
        //保持していない注文IDのみ
        if(!in_array($order_id, $this->arrDeferredOrderId)){
            //クロネコ代金後払い決済のみ登録
            if(SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId(array($order_id))){
                $this->arrDeferredOrderId[] = $order_id;
            }
        }
    }

    /**
     * 出荷情報登録結果のメッセージをプロパティへ追加する
     *
     * @param  integer $order_id
     * @param  string  $message
     * @param  bool  $errFlag
     * @return void
     */
    public function addRowShipmentEntryReport($order_id, $message, $errFlag = false){
        if($errFlag) $message = '<span class="attention">' .$message .'</span>';
        $this->arrRowShipmentEntryReport[] = '注文番号：'. $order_id . ' 処理結果：' . $message;
    }

    /**
     * 登録/編集結果のメッセージをプロパティへ追加する
     *
     * @param  integer $line_count 行数
     * @param  string  $message    メッセージ
     * @return void
     */
    public function addRowResult($line_count, $message){
        $this->arrRowResult[] = $line_count . '行目：' . $message;
    }

    /**
     * 登録/編集結果のエラーメッセージをプロパティへ追加する
     *
     * @param  integer $line_count 行数
     * @param  string  $message    メッセージ
     * @return void
     */
    public function addRowErr($line_count, $message){
        $this->arrRowErr[] = ($line_count != 0) ? $line_count . '行目：' . $message : $message;
    }

    /**
     * CSVアップロードを実行します.
     *
     * @param SC_FormParam_Ex $objFormParam
     * @param SC_UploadFile_Ex $objUpFile
     * @return void
     */
    public function doUploadCsv(&$objFormParam, &$objUpFile){
        // ファイルアップロードのチェック
        $objUpFile->makeTempFile('csv_file');
        $arrErr = $objUpFile->checkExists();
        if (count($arrErr) > 0) {
            $this->arrErr = $arrErr;

            return;
        }
        // 一時ファイル名の取得
        $filepath = $objUpFile->getTempFilePath('csv_file');
        // CSVファイルの文字コード変換
        $enc_filepath = SC_Utils_Ex::sfEncodeFile($filepath, CHAR_CODE, CSV_TEMP_REALDIR);
        // CSVファイルのオープン
        $fp = fopen($enc_filepath, 'r');
        // 失敗した場合はエラー表示
        if (!$fp) {
            SC_Utils_Ex::sfDispError('');
        }

        // 登録フォーム カラム情報
        $this->arrFormKeyList = $objFormParam->getKeyList();

        // 登録対象の列数
        $col_max_count = $this->arrSetting['use_b2_format'] == '1' ? MDL_YFCAPI_B2CSV_BASE_COLUMN_NUMBER : $objFormParam->getCount();

        // 行数
        $line_count = 0;

        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        $errFlag = false;

        while (!feof($fp)) {
            $arrCSV = fgetcsv($fp, CSV_LINE_MAX);
            // 行カウント
            $line_count++;
            // ヘッダ行はスキップ
            if ($line_count == 1 && $this->arrSetting['header_output']) {
                continue;
            }
            // 空行はスキップ
            if (empty($arrCSV)) {
                continue;
            }
            // 取り込みフォーマットが ｢2項目｣ の場合、2項目の B2CSV のみアップロード可能
            // 取り込みフォーマットが ｢95項目｣ の場合、95項目以上の B2CSV がアップロード可能
            $col_count = count($arrCSV);
            if (($this->arrSetting['use_b2_format'] == '1' && $col_max_count > $col_count) 
                || ($this->arrSetting['use_b2_format'] != '1' && $col_max_count != $col_count)
            ) {
                $this->addRowErr($line_count, '※ 項目数が' . $col_count . '個検出されました。項目数は' . $col_max_count . '個になります。');
                $errFlag = true;
                break;
            }
            // シーケンス配列を格納する。
            $objFormParam->setParam($arrCSV, true);
            // 入力値の変換
            $objFormParam->convParam();
            // <br>なしでエラー取得する。
            $arrCSVErr = $this->lfCheckError($objFormParam);

            // 入力エラーチェック
            if (count($arrCSVErr) > 0) {
                foreach ($arrCSVErr as $err) {
                    $this->addRowErr($line_count, $err);
                }
                $errFlag = true;
                break;
            }

            $order_id = $this->lfRegistDelivSlip($objQuery, $line_count, $objFormParam);
            $this->addCreditOrderId($order_id);
            $this->addDeferredOrderId($order_id);
            $this->addRowResult($line_count, '注文番号_発送情報ID：'.$objFormParam->getValue('order_shipping_id'). ' / 送り状番号：' . $objFormParam->getValue('plg_yfcapi_deliv_slip'));
        }

        //出荷情報登録
        if($this->arrSetting['use_b2_shipping_entry'] == '1' && !$errFlag){
            //出荷情報登録前確認処理
            $arrErrShipment = $this->lfCheckErrorShipmentEntry();
            if(count($arrErrShipment)>0){
                $this->addRowErr(0, implode('<br />', $arrErrShipment));
                $errFlag = true;
            }
            //出荷情報登録処理
            if(!$errFlag){
                $this->lfDoShipmentEntry();
            }
        }

        // 実行結果画面を表示
        $this->tpl_mainpage = MDL_YFCAPI_TEMPLATE_PATH. 'admin/order/upload_csv_b2_complete.tpl';

        fclose($fp);

        if ($errFlag) {
            $objQuery->rollback();

            return;
        }

        $objQuery->commit();

    }

    /**
     * ファイル情報の初期化を行う.
     *
     * @param SC_UploadFile_Ex $objUpFile
     * @return void
     */
    public function lfInitFile(&$objUpFile){
        $objUpFile->addFile('CSVファイル', 'csv_file', array('csv'), CSV_SIZE, true, 0, 0, false);
    }

    /**
     * 入力情報の初期化を行う.
     *
     * @param SC_FormParam_Ex $objFormParam
     * @return void
     */
    public function lfInitParam(&$objFormParam){
        $objFormParam->addParam("注文番号_発送情報ID", "order_shipping_id", 20, 'a', array('EXIST_CHECK', 'GRAPH_CHECK', 'MAX_LENGTH_CHECK'));
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("送り状種別", "plg_yfcapi_deliv_slip_type");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("クール区分", "plg_yfcapi_cool_kb");
        $objFormParam->addParam("送り状番号", "plg_yfcapi_deliv_slip", 12, 'n', array('EXIST_CHECK','NUM_CHECK', 'MAX_LENGTH_CHECK'));
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("出荷予定日", "deliv_date");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け予定", "shipping_date");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("配達時間帯", "plg_yfcapi_deliv_time_code");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け先コード", "plg_yfcapi_deliv_code");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け先電話番号", "shipping_tel");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け先電話番号枝番", "shipping_tel_no");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け先郵便番号", "shipping_zip");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け先住所", "shipping_addr01");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け先住所（アパートマンション名）", "shipping_addr02");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け先会社・部門名１", "shipping_company_name");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け先会社・部門名２", "shipping_company_name02");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け先名", "shipping_name");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け先名称略カナ", "shipping_kana");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("敬称", "shipping_title");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("ご依頼主コード", "order_code");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("ご依頼主電話番号", "order_tel");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("ご依頼主電話番号枝番", "order_tel_no");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("ご依頼主郵便番号", "order_zip");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("ご依頼主住所", "order_addr01");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("ご依頼主住所（ｱﾊﾟｰﾄﾏﾝｼｮﾝ名）", "order_addr02");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("ご依頼主名", "order_name");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("ご依頼主略称カナ", "order_kana");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("品名コード１", "shipping_product_code01");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("品名１", "shipping_product_name01");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("品名コード２", "shipping_product_code02");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("品名２", "shipping_product_code02");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("荷扱い１", "shipping_handling01");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("荷扱い２", "shipping_handling02");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("記事", "shipping_memo");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("コレクト代金引換額（税込）", "shipping_collect_inctax");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("コレクト内消費税額等", "shipping_collect_tax");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("営業所止置き", "shipping_hold_reassign");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("営業所コード", "shipping_hold_office_code");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("発行枚数", "publish_count");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("個数口枠の印字", "publish_koguchi");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("ご請求先顧客コード", "plg_yfcapi_claim_customer_code");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("ご請求先分類コード", "plg_yfcapi_claim_type_code");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("運賃管理番号", "plg_yfcapi_transportation_no");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("注文時カード払いデータ登録", "service_card_register");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("注文時カード払い加盟店番号", "service_card_shop_no");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("注文時カード払い申し込み受付番号１", "service_card_order_no01");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("注文時カード払い申し込み受付番号２", "service_card_order_no02");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("注文時カード払い申し込み受付番号３", "service_card_order_no03");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け予定eメール利用区分", "service_deliv_mail_enable");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け予定eメールe-mailアドレス", "service_deliv_mail_address");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("入力機種", "service_deliv_device_id");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け予定eメールメッセージ", "service_deliv_mail_message");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け完了eメール利用区分", "service_complete_mail_enable");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け完了eメールe-mailアドレス", "service_complete_mail_address");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("お届け完了eメールメッセージ", "service_complete_mail_message");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("クロネコ収納代行利用区分", "service_receiving_agent_enable");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備", "service_receiving_agent_yobi");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行請求金額（税込）", "service_receiving_agent_claim_payment_total");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行内消費税額等", "service_receiving_agent_claim_tax");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行請求先郵便番号", "service_receiving_agent_zip");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行請求先住所", "service_receiving_agent_addr01");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行請求先住所（ｱﾊﾟｰﾄﾏﾝｼｮﾝ名)", "service_receiving_agent_addr02");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行請求先会社･部門名１", "service_receiving_agent_claim_campany01");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行請求先会社･部門名２", "service_receiving_agent_claim_campany02");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行請求先名（漢字）", "service_receiving_agent_claim_name");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行請求先名（カナ）", "service_receiving_agent_claim_kana");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行問合せ先名（カナ）", "service_receiving_agent_info_kana");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行問合せ先郵便番号", "service_receiving_agent_info_zip");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行問合せ先住所", "service_receiving_agent_info_addr01");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行問合せ先住所（ｱﾊﾟｰﾄﾏﾝｼｮﾝ名）", "service_receiving_agent_info_addr02");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行問合せ先電話番号", "service_receiving_agent_info_tel");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行管理番号", "service_receiving_agent_no");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行品名", "service_receiving_agent_product_name");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("収納代行備考", "service_receiving_agent_memo");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備０１", "reserve1");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備０２", "reserve2");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備０３", "reserve3");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備０４", "reserve4");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備０５", "reserve5");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備０６", "reserve6");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備０７", "reserve7");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備０８", "reserve8");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備０９", "reserve9");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備１０", "reserve10");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備１１", "reserve11");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備１２", "reserve12");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("予備１３", "reserve13");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("投函予定メール利用区分", "posting_plan_mail_enable");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("投函予定メールe-mailアドレス", "posting_plan_mail_address");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("投函予定メールメッセージ", "posting_plan_mail_message");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("投函完了メール(受人宛て)利用区分", "posting_complete_deliv_mail_enable");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("投函完了メール(受人宛て)e-mailアドレス", "posting_complete_deliv_mail_address");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("投函完了メール(受人宛て)メッセージ", "posting_complete_deliv_mail_message");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("投函完了メール(出人宛て)利用区分", "posting_complete_order_mail_enable");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("投函完了メール(出人宛て)e-mailアドレス", "posting_complete_order_mail_address");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam("投函完了メール(出人宛て)メッセージ", "posting_complete_order_mail_message");
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam('連携管理番号', 'plg_yfcapi_control_no');
        if($this->arrSetting['use_b2_format'] == '1') $objFormParam->addParam('通知メールアドレス', 'notification_mail_address');
    }

    /**
     * 入力チェックを行う.
     *
     * @param SC_FormParam_Ex $objFormParam
     * @return array
     */
    public function lfCheckError(&$objFormParam){
        // 入力データを渡す。
        $arrRet =  $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrRet);
        $objErr->arrErr = $objFormParam->checkError(false);
        //配送番号を分解
        if(SC_Utils_Ex::isBlank($objErr->arrErr['order_shipping_id'])){
            list($order_id, $shipping_id) = explode('_', $arrRet['order_shipping_id']);
            if(SC_Utils_Ex::isBlank($order_id) || SC_Utils_Ex::isBlank($shipping_id)){
                $objErr->arrErr['order_shipping_id'] = '※ 配送情報が不正です。';
            }
            if(!SC_Utils_Ex::sfIsInt($order_id) || !SC_Utils_Ex::sfIsInt($shipping_id)){
                $objErr->arrErr['order_shipping_id'] = '※ 配送情報が不正です。';
            }
        }
        //配送情報存在チェック（配送番号が問題ない場合のみ）
        if(SC_Utils_Ex::isBlank($objErr->arrErr['order_shipping_id'])){
            if(!$this->objDb->sfIsRecord('dtb_shipping', 'order_id , shipping_id', array($order_id, $shipping_id))){
                $objErr->arrErr['order_shipping_id'] = '※ 配送情報(' . $arrRet['order_shipping_id'] . ')が存在しません。';
            }
        }
        //送り状番号の有効性チェック
        if(SC_Utils_Ex::isBlank($objErr->arrErr['plg_yfcapi_deliv_slip'])){
            if(!SC_Util_Mdl_YFCApi_Ex::checkDelivSlip($arrRet['plg_yfcapi_deliv_slip'])){
                $objErr->arrErr['plg_yfcapi_deliv_slip'] = '※ 送り状番号が不正です。';
            }
        }
        return $objErr->arrErr;
    }

    /**
     * 指定された行番号をmicrotimeに付与してDB保存用の時間を生成する。
     * トランザクション内のCURRENT_TIMESTAMPは全てcommit()時の時間に統一されてしまう為。
     *
     * @param  string $line_no 行番号
     * @return string $time DB保存用の時間文字列
     */
    public function lfGetDbFormatTimeWithLine($line_no = '')
    {
        $time = date('Y-m-d H:i:s');
        // 秒以下を生成
        if ($line_no != '') {
            $microtime = sprintf('%06d', $line_no);
            $time .= ".$microtime";
        }

        return $time;
    }

    /**
     * プラグイン設定値を取得
     *
     * @param void
     * @return array $arrRet 設定値
     */
    function lfLoadData() {
        $arrRet = array();
        $arrData = SC_Plugin_Util_Ex::getPluginByPluginCode("YfcApiUtils");
        if (!SC_Utils_Ex::isBlank($arrData['free_field1'])) {
            $arrRet = unserialize($arrData['free_field1']);
        }
        return $arrRet;
    }

    /**
     * 伝票番号登録を行う.
     *
     * @param SC_Query $objQuery SC_Queryインスタンス
     * @param string|integer $line 処理中の行数
     * @param SC_FormParam_Ex $objFormParam
     * @return integer $order_id
     */
    function lfRegistDelivSlip($objQuery, $line = '', &$objFormParam) {
        // 登録データ対象取得
        $arrRet = $objFormParam->getHashArray();
        list($order_id, $shipping_id) = explode('_', $arrRet['order_shipping_id']);
        $sqlval = array();
        $sqlval['plg_yfcapi_deliv_slip'] = $arrRet['plg_yfcapi_deliv_slip'];
        $sqlval['update_date'] = $this->lfGetDbFormatTimeWithLine($line);
        // UPDATEの実行
        $objQuery->update('dtb_shipping', $sqlval, ' shipping_id = ? AND order_id = ? ', array($shipping_id, $order_id));
        return $order_id;
    }

    /**
     * 決済情報登録前入力チェックを行う.
     *
     * @return array
     */
    public function lfCheckErrorShipmentEntry(){
        //クレジット決済及びクロネコ代金後払い決済が1件もない場合は空の配列を返す
        if(count($this->arrCreditOrderId) == 0 && count($this->arrDeferredOrderId) == 0) return array();

        $arrErr = array();
        //取引状況チェック（与信完了）
        foreach ($this->arrCreditOrderId as $order_id) {
            //対応支払方法チェック
            if(!SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array($order_id))){
                $arrErr[] = "注文番号:{$order_id} 「出荷情報登録」に対応していない決済です。";
                //取引状況チェック(与信完了)
            } elseif(SC_Util_Mdl_YFCApi_Ex::isUnMatchPayStatus(array($order_id), array(MDL_YFCAPI_ACTION_STATUS_COMP_AUTH))) {
                $arrErr[] = "注文番号:{$order_id} 「出荷情報登録」に対応していない取引状況です。";
                //送り状番号必須チェック
            } elseif(!SC_Util_Mdl_YFCApi_Ex::isSlippingOn(array($order_id))) {
                $arrErr[] = "注文番号:{$order_id} 送り状番号が登録されていない配送先が存在します。";
                //複数配送送り先上限チェック(99件まで）
            } elseif(SC_Util_Mdl_YFCApi_Ex::isUpperLimitedShippings(array($order_id))) {
                $arrErr[] = "注文番号:{$order_id} 1つの注文に対する出荷情報の上限（".MDL_YFCAPI_DELIV_ADDR_MAX."件）を超えています。";
                //共通送り状番号での注文同梱上限(3件まで)チェック
            } elseif(SC_Util_Mdl_YFCApi_Ex::isUpperLimitedShippings(array($order_id))) {
                $arrErr[] = "注文番号:{$order_id} 同一の送り状番号で同梱可能な注文数（".MDL_YFCAPI_SHIPPED_MAX."件）を超えています。";
                //共通送り状番号で注文同梱時の発送先同一チェック
            } elseif(SC_Util_Mdl_YFCApi_Ex::isExistUnequalShipping(array($order_id))) {
                $arrErr[] = "注文番号:{$order_id} 同一の送り状番号で配送先が異なるものが存在しています。";
            }
        }

        $objPurchase = new SC_Helper_Purchase_Ex();
        foreach ($this->arrDeferredOrderId as $order_id) {
            $arrOrder = $objPurchase->getOrder($order_id);
            if(!SC_Util_Mdl_YFCApi_Ex::isDeferredOrderFromId(array($order_id))){
                //対応支払方法チェック
                $arrErr[] = "注文番号:{$order_id} 「出荷情報登録」に対応していない決済です。";
            } elseif(!SC_Util_Mdl_YFCApi_Ex::isSlippingOn(array($order_id))) {
                //送り状番号必須チェック
                $arrErr[] = "注文番号:{$order_id} 送り状番号が登録されていない配送先が存在します。";
            } elseif($arrOrder[MDL_YFCAPI_ORDER_COL_EXAMRESULT] != MDL_YFCAPI_DEFERRED_AVAILABLE) {
                //審査結果チェック(ご利用可)
                $arrErr[] = "注文番号:{$order_id} 「出荷情報登録」に対応していない審査結果です。";
            } elseif(SC_Util_Mdl_YFCApi_Ex::getCountShipping($order_id) > MDL_YFCAPI_DEFERRED_DELIV_ADDR_MAX) {
                //配送先数チェック
                $arrErr[] = "注文番号:{$order_id} 1つの注文に対するお届け先の上限（".MDL_YFCAPI_DEFERRED_DELIV_ADDR_MAX."件）を超えております。";
            }
        }

        return $arrErr;
    }

    /**
     * 決済情報登録を行う.
     *
     * @return array
     */
    public function lfDoShipmentEntry(){
        //クレジット決済及びクロネコ代金後払い決済が1件もない場合は空の配列を返す
        if(count($this->arrCreditOrderId) == 0 && count($this->arrDeferredOrderId) == 0) return array();
        //決済情報登録
        foreach ($this->arrCreditOrderId as $order_id) {
            //決済クライアント生成
            $objClient = new SC_Mdl_YFCApi_Client_Util_Ex();
            $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($order_id);
            list($ret, $arrSuccessSlip) = $objClient->doShipmentEntry($arrOrder);
            if($ret){
                $this->addRowShipmentEntryReport($order_id, '出荷情報登録成功しました。');
            } else {
                $arrErr = $objClient->getError();
                $this->addRowShipmentEntryReport($order_id, implode(' / ', $arrErr),true);
                //複数配送時出荷情報登録ロールバック
                $objClient->doRollbackCommit($arrOrder, $arrSuccessSlip);
            }
        }

        foreach ($this->arrDeferredOrderId as $order_id) {
            //決済クライアント生成
            $objClient = new SC_Mdl_YFCApi_Client_Deferred_Util_Ex();
            $arrOrder = SC_Util_Mdl_YFCApi_Ex::getOrderPayData($order_id);
            list($ret, $success_cnt, $failure_cnt) = $objClient->doShipmentEntry($arrOrder);
            $mess = '出荷情報登録 成功'.$success_cnt.'件 失敗'.$failure_cnt.'件';
            if($ret){
                $this->addRowShipmentEntryReport($order_id, '出荷情報登録成功しました。 / '.$mess);
            } else {
                $arrErr = $objClient->getError();
                $this->addRowShipmentEntryReport($order_id, implode(' / ', $arrErr).' / '.$mess , true);
            }
        }
    }
}
