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
require_once(MDL_YFCAPI_CLASSEX_PATH . 'plugin_extends/LC_YfcApiCore_Ex.php');

/**
 * プラグインのメインクラス
 *
 * @package YfcApiCore
 * @version $Id: $
 */
class YfcApiCore extends SC_Plugin_Base {

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
        if(is_file(PLUGIN_UPLOAD_REALDIR."YfcApiCore/logo.png")) {
            copy(PLUGIN_UPLOAD_REALDIR . "YfcApiCore/logo.png", PLUGIN_HTML_REALDIR . "YfcApiCore/logo.png");
        }
        
        $objDB = new SC_Helper_DB_Ex();
        
        //送り状番号カラム追加
        $objDB->sfColumnExists('dtb_shipping', 'plg_yfcapi_deliv_slip', 'TEXT', '', true);
        //荷物問い合わせURLカラム追加
        $objDB->sfColumnExists('dtb_shipping', 'plg_yfcapi_deliv_slip_url', 'TEXT', '', true);
        //出荷情報登録成功送り状番号カラム追加
        $objDB->sfColumnExists('dtb_shipping', 'plg_yfcapi_last_deliv_slip', 'TEXT', '', true);
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
        $obj = new LC_YfcApiCore_Ex();
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
        $obj = new LC_YfcApiCore();
        $obj->actionHook($class_name, $hook_point, $objPage, $this);
    }

    function addParam($class_name, $param) {
        if (strpos($class_name, 'LC_Page_Admin_Order_Edit') !== false) {
            // 送り状番号
            $param->addParam('送り状番号', 'plg_yfcapi_deliv_slip', 12, 'n', array('NUM_CHECK', 'NUM_COUNT_CHECK'));
            // 追跡URL
            $param->addParam('追跡URL', 'plg_yfcapi_deliv_slip_url', URL_LEN, 'a', array('MAX_LENGTH_CHECK'));
            // 登録成功送り状番号
            $param->addParam('登録成功送り状番号', 'plg_yfcapi_last_deliv_slip', 12, 'n', array('NUM_CHECK', 'NUM_COUNT_CHECK'));
        }
    }

}

