<?php
/*
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(MDL_YFCAPI_HELPEREX_PATH . "SC_Helper_Mdl_YFCApi_Plugin_Ex.php");
require_once(MDL_YFCAPI_CLASSEX_PATH . 'plugin_extends/LC_YfcApiUtils_Ex.php');

/**
 * 補助追加機能プラグイン
 *
 * @package YfcApiUtils
 * @version $Id: $
 */
class YfcApiUtils extends SC_Plugin_Base {

    /**
     * コンストラクタ
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    /**
     * インストール
     * installはプラグインのインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin plugin_infoを元にDBに登録されたプラグイン情報(dtb_plugin)
     * @return void
     */
    function install($arrPlugin) {
        // 必要なファイルをコピーします.
        if (is_file(PLUGIN_UPLOAD_REALDIR."YfcApiUtils/logo.png")) {
            copy(PLUGIN_UPLOAD_REALDIR."YfcApiUtils/logo.png", PLUGIN_HTML_REALDIR."YfcApiUtils/logo.png");
        }
        if (is_file(PLUGIN_UPLOAD_REALDIR."YfcApiUtils/ico_".MDL_YFCAPI_PRODUCT_TYPE_ID.".gif")) {
            copy(PLUGIN_UPLOAD_REALDIR."YfcApiUtils/ico_".MDL_YFCAPI_PRODUCT_TYPE_ID.".gif", USER_TEMPLATE_REALDIR . "default/img/icon/ico_".MDL_YFCAPI_PRODUCT_TYPE_ID.".gif");
            copy(PLUGIN_UPLOAD_REALDIR."YfcApiUtils/ico_".MDL_YFCAPI_PRODUCT_TYPE_ID.".gif", USER_TEMPLATE_REALDIR . "sphone/img/icon/ico_".MDL_YFCAPI_PRODUCT_TYPE_ID.".gif");
        }
        
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objDB = new SC_Helper_DB_Ex();
        
        //商品種別ID設定（予約商品）
        if(!SC_Helper_DB_Ex::sfDataExists('mtb_product_type', 'id = ?', array(MDL_YFCAPI_PRODUCT_TYPE_ID))) {
            $objQuery->insert("mtb_product_type", array('id'=>MDL_YFCAPI_PRODUCT_TYPE_ID, 'name'=>'予約商品', 'rank'=>MDL_YFCAPI_PRODUCT_TYPE_ID));
            $masterData = new SC_DB_MasterData_Ex();
            $masterData->createCache('mtb_product_type');
        }
        //商品ステータス設定（後払い不可）
        if(!SC_Helper_DB_Ex::sfDataExists('mtb_status', 'id = ?', array(MDL_YFCAPI_PRODUCT_STATUS_ID))) {
            $objQuery->insert("mtb_status", array('id'=>MDL_YFCAPI_PRODUCT_STATUS_ID, 'name'=>'後払い不可', 'rank'=>MDL_YFCAPI_PRODUCT_STATUS_ID));
            $masterData = new SC_DB_MasterData_Ex();
            $masterData->createCache('mtb_status');
        }
        //商品ステータス画像設定（後払い不可）
        if(!SC_Helper_DB_Ex::sfDataExists('mtb_status_image', 'id = ?', array(MDL_YFCAPI_PRODUCT_STATUS_ID))) {
            $objQuery->insert("mtb_status_image", array('id'=>MDL_YFCAPI_PRODUCT_STATUS_ID, 'name'=>'img/icon/ico_'.MDL_YFCAPI_PRODUCT_STATUS_ID.'.gif', 'rank'=>MDL_YFCAPI_PRODUCT_STATUS_ID));
            $masterData = new SC_DB_MasterData_Ex();
            $masterData->createCache('mtb_status_image');
        }
        //注文ステータス設定（クレジットカード出荷登録済み）
        if(!SC_Helper_DB_Ex::sfDataExists('mtb_order_status', 'id = ?', array(MDL_YFCAPI_ORDER_SHIPPING_REGISTERED))) {
            $objQuery->insert("mtb_order_status", array('id'=>MDL_YFCAPI_ORDER_SHIPPING_REGISTERED, 'name'=>'クレジットカード出荷登録済み', 'rank'=>MDL_YFCAPI_ORDER_SHIPPING_REGISTERED));
            $masterData = new SC_DB_MasterData_Ex();
            $masterData->createCache('mtb_order_status');
        }
        //注文ステータスカラー設定（クレジットカード出荷登録済み）
        if(!SC_Helper_DB_Ex::sfDataExists('mtb_order_status_color', 'id = ?', array(MDL_YFCAPI_ORDER_SHIPPING_REGISTERED))) {
            $objQuery->insert("mtb_order_status_color", array('id'=>MDL_YFCAPI_ORDER_SHIPPING_REGISTERED, 'name'=>'#CCFFCC', 'rank'=>MDL_YFCAPI_ORDER_SHIPPING_REGISTERED));
            $masterData = new SC_DB_MasterData_Ex();
            $masterData->createCache('mtb_order_status_color');
        }
        //受注データ出荷予定日カラム追加
        $objDB->sfColumnExists('dtb_order', 'plg_yfcapi_scheduled_shipping_date', 'DATE', '', true);
        //受注データdtb_csv対応
        if(!SC_Helper_DB_Ex::sfDataExists('dtb_csv', 'csv_id = ? AND col =?', array(3, 'plg_yfcapi_scheduled_shipping_date'))) {
            $arrData = array();
            $arrData['no']                     = $objQuery->nextVal('dtb_csv_no');
            $arrData['csv_id']                 = 3;
            $arrData['col']                    = 'plg_yfcapi_scheduled_shipping_date';
            $arrData['disp_name']              = '出荷予定日';
            $arrData['rw_flg']                 = 1;
            $arrData['status']                 = 2;
            $arrData['create_date']            = 'now()';
            $arrData['update_date']            = 'now()';
            $objQuery->insert("dtb_csv", $arrData);
        }
        //予約商品出荷予定日カラム追加
        $objDB->sfColumnExists('dtb_products', 'plg_yfcapi_reserve_date', 'DATE', '', true);
        //予約商品出荷予定日dtb_csv対応
        if(!SC_Helper_DB_Ex::sfDataExists('dtb_csv', 'csv_id = ? AND col =?', array(1, 'plg_yfcapi_reserve_date'))) {
            $arrData = array();
            $arrData['no']                     = $objQuery->nextVal('dtb_csv_no');
            $arrData['csv_id']                 = 1;
            $arrData['col']                    = 'plg_yfcapi_reserve_date';
            $arrData['disp_name']              = '予約商品出荷予定日';
            $arrData['rw_flg']                 = 1;
            $arrData['status']                 = 2;
            $arrData['create_date']            = 'now()';
            $arrData['update_date']            = 'now()';
            $objQuery->insert("dtb_csv", $arrData);
        }
        
        //配送業者追加（予約商品用）
        if(!SC_Helper_DB_Ex::sfDataExists('dtb_deliv', 'del_flg = 0 AND product_type_id = ?', array(MDL_YFCAPI_PRODUCT_TYPE_ID))) {
            $objDelivery = new SC_Helper_Delivery_Ex();
            $arrMaxDeliv = $objQuery->getRow("MAX(rank) AS max_rank", "dtb_deliv", "del_flg = 0");
            $maxRank = $arrMaxDeliv['max_rank'] + 1;
            $deliv_id = $objQuery->nextVal('dtb_deliv_deliv_id');
            $sqlval = array(
                'product_type_id' => MDL_YFCAPI_PRODUCT_TYPE_ID,
                'name'            => '予約商品配送業者',
                'service_name'    => '予約商品配送業者',
                'remark'          => null,
                'confirm_url'     => null,
                'rank'            => $maxRank,
                'status'          => '1',
                'del_flg'         => '0',
                'creator_id'      => $_SESSION['member_id'],
                'create_date'     => 'now()',
                'update_date'     => 'now()',
            );
            //支払方法紐づけ
            $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
            $arrPayment = $objQuery->getRow("payment_id", "dtb_payment", "module_code = ?", array($objMdl->getCode(true)));
            $sqlval['payment_ids'][] = $arrPayment['payment_id'];
            
            if(INPUT_DELIV_FEE) {
                $sqlval['deliv_fee'] = array();
                for ($cnt = 1; $cnt <= DELIVFEE_MAX; $cnt++) {
                    $keyname = "fee$cnt";
                    $fee = array();
                    $fee['fee_id'] = $cnt;
                    $fee['fee'] = 0;
                    $fee['pref'] = $cnt;
                    $sqlval['deliv_fee'][$cnt] = $fee;
                }
            }
            $deliv_id = $objDelivery->save($sqlval);
        }
        //B2用配達時間帯カラム追加
        $objDB->sfColumnExists('dtb_delivtime', 'plg_yfcapi_b2_time_id', 'INT DEFAULT 0', '', true);
        //出荷完了メール追加
        //mtb_mail_template
        if(!SC_Helper_DB_Ex::sfDataExists('mtb_mail_template', 'id = ?', array(MDL_YFCAPI_MAIL_COMMON_ID))) {
            $objQuery->insert("mtb_mail_template", array('id'=>MDL_YFCAPI_MAIL_COMMON_ID, 'name'=>'配送完了メール', 'rank'=>MDL_YFCAPI_MAIL_COMMON_ID));
            $masterData = new SC_DB_MasterData_Ex();
            $masterData->createCache('mtb_mail_template');
        }
        //mtb_mail_tpl_path
        if(!SC_Helper_DB_Ex::sfDataExists('mtb_mail_tpl_path', 'id = ?', array(MDL_YFCAPI_MAIL_COMMON_ID))) {
            $objQuery->insert("mtb_mail_tpl_path", array('id'=>MDL_YFCAPI_MAIL_COMMON_ID, 'name'=>'mail_templates/deliv_complete_mail.tpl', 'rank'=>MDL_YFCAPI_MAIL_COMMON_ID));
            $masterData = new SC_DB_MasterData_Ex();
            $masterData->createCache('mtb_mail_tpl_path');
        }
        //dtb_mailtemplate
        if(!SC_Helper_DB_Ex::sfDataExists('dtb_mailtemplate', 'template_id = ?', array(MDL_YFCAPI_MAIL_COMMON_ID))) {
            //店名取得
            $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
            $arrData = array();
            $arrData['template_id'] = MDL_YFCAPI_MAIL_COMMON_ID;
            $arrData['subject'] = '【'.$arrInfo['shop_name'].'】配送完了しました。';
            $arrData['header'] = '

この度はご注文いただき誠にありがとうございます。
下記ご注文の配送が完了しました。
';
            $arrData['footer'] = '


=========================================================================



このメッセージはお客様へのお知らせ専用ですので、
このメッセージへの返信としてご質問をお送りいただいても回答できません。
ご了承ください。

ご質問やご不明な点がございましたら、こちらからお願いいたします。
';
            $arrData['creator_id'] = $_SESSION['member_id'];
            $arrData['del_flg'] = 0;
            $arrData['create_date'] = 'now()';
            $arrData['update_date'] = 'now()';
            $objQuery->insert("dtb_mailtemplate", $arrData);
        }
    }

    /**
     * アンインストール
     * uninstallはアンインストール時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function uninstall($arrPlugin) {
        // nop
    }

    /**
     * 稼働
     * enableはプラグインを有効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function enable($arrPlugin) {
        copy(PLUGIN_UPLOAD_REALDIR . "YfcApiUtils/upload_csv_b2.php", HTML_REALDIR . ADMIN_DIR . "order/upload_csv_b2.php");
        copy(PLUGIN_UPLOAD_REALDIR . "YfcApiUtils/change_card.php", HTML_REALDIR . "mypage/change_card.php");
        
        $page_name = 'MYページ/カード編集';
        $url = 'mypage/change_card.php';
        $filename = 'mypage/change_card';
        
        $tpl_data = file_get_contents(MDL_YFCAPI_TEMPLATE_PATH . 'default/change_card.tpl');
        $device_type_id = DEVICE_TYPE_PC;
        $is_force = true;
        $page_id = SC_Helper_Mdl_YFCApi_Plugin_Ex::setPageData($tpl_data, $page_name, $url, $filename, $device_type_id, $is_force);
        
        $tpl_data = file_get_contents(MDL_YFCAPI_TEMPLATE_PATH . 'sphone/change_card.tpl');
        $device_type_id = DEVICE_TYPE_SMARTPHONE;
        $is_force = true;
        $page_id = SC_Helper_Mdl_YFCApi_Plugin_Ex::setPageData($tpl_data, $page_name, $url, $filename, $device_type_id, $is_force);
    }

    /**
     * 停止
     * disableはプラグインを無効にした際に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function disable($arrPlugin) {
        // nop
    }

    /**
     * 処理の介入箇所とコールバック関数を設定
     * registerはプラグインインスタンス生成時に実行されます
     *
     * @param SC_Helper_Plugin $objHelperPlugin
     * @param integer $priority
     */
    function register(SC_Helper_Plugin $objHelperPlugin, $priority) {
        return parent::register($objHelperPlugin, $priority);
    }

    // プラグイン独自の設定データを追加
    function insertFreeField() {
    }

    function insertBloc($arrPlugin) {
    }

    /**
     * プレフィルタコールバック関数
     *
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        $class_name = get_class($objPage);
        $obj = new LC_YfcApiUtils_Ex();
        $obj->actionPrefilterTransform($class_name, $source, $objPage, $filename, $this);
    }

    function hookActionBefore(LC_Page_Ex $objPage) {
        $this->callHookAction('before', $objPage);
    }

    function hookActionAfter(LC_Page_Ex $objPage) {
        $this->callHookAction('after', $objPage);
    }

    function hookActionMode(LC_Page_Ex $objPage) {
        $this->callHookAction('mode', $objPage);
    }

    function callHookAction($hook_point, &$objPage) {
        $class_name = get_class($objPage);
        $obj = new LC_YfcApiUtils_Ex();
        $obj->actionHook($class_name, $hook_point, $objPage, $this);
    }

}