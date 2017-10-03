<?php
/*
*
* MultiReportPlugin
* Copyright(c) 2014 Cyber-Will Inc. All Rights Reserved.
*
* http://www.cyber-will.co.jp/
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

class MultiReportPlugin extends SC_Plugin_Base {

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
        // プラグインのロゴ画像をアップ
        if (file_exists(PLUGIN_UPLOAD_REALDIR ."MultiReportPlugin/logo.png")){
            if (copy(PLUGIN_UPLOAD_REALDIR . "MultiReportPlugin/logo.png", PLUGIN_HTML_REALDIR . "MultiReportPlugin/logo.png") === false);
        }
        //APIの配置
        copy(PLUGIN_UPLOAD_REALDIR . "MultiReportPlugin/templates/MultiReportApi.php", HTML_REALDIR . ADMIN_DIR . "MultiReportApi.php");

        MultiReportPlugin::setData('create');
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
        // ロゴ画像削除
        if (file_exists(PLUGIN_HTML_REALDIR ."MultiReportPlugin/logo.png")) {
            if (SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "MultiReportPlugin/logo.png") === false);
        }
        MultiReportPlugin::setData('delete');
        unlink(HTML_REALDIR . ADMIN_DIR . "MultiReportApi.php");
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
        // nop
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
     */
    function register(SC_Helper_Plugin $objHelperPlugin) {
        $objHelperPlugin->addAction('LC_Page_Admin_Order_Pdf_action_after', array($this, 'contents_set'));
        $objHelperPlugin->addAction('prefilterTransform', array(&$this, 'prefilterTransform'), 1);
    }

    // 初期インストール用SQLを発行
    function setData($type) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $filepath = PLUGIN_UPLOAD_REALDIR . "../tmp/plugin_install/" . DB_TYPE . "_"  . $type . ".sql";
        $arrErr = false;

        if ($type == 'create') {
            $filepath = PLUGIN_UPLOAD_REALDIR . "../tmp/plugin_install/" . DB_TYPE . "_"  . $type . ".sql";
        }else if ($type == 'delete') {
            $filepath = PLUGIN_UPLOAD_REALDIR . "MultiReportPlugin/" . DB_TYPE . "_"  . $type . ".sql";
        }

        if (!file_exists($filepath)) {
            echo $filepath. "<br>";
            echo 'SQLファイルがありません。一度プラグインを削除して下さい';
            exit;
        } else {
            if ($fp = fopen($filepath, 'r')) {
                $sql = fread($fp, filesize($filepath));
                fclose($fp);
            }
        }
        $objQuery = SC_Query_Ex::getSingletonInstance();
        
        $sql_split = split(';', $sql);
        foreach ($sql_split as $key => $val) {
            SC_Utils::sfFlush(true);
            if (trim($val) != '') {
                $ret = $objQuery->query($val);
                if (PEAR::isError($ret) && $disp_err) {
                    GC_Utils_Ex::gfPrintLog($ret->userinfo, PLUGIN_LOG_REALFILE);
                    break;
                } else {
                    GC_Utils_Ex::gfPrintLog('OK:' . $val, PLUGIN_LOG_REALFILE);
                }
            }
        }
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
        $objTransform = new SC_Helper_Transform($source);
        $template_dir = PLUGIN_UPLOAD_REALDIR . 'MultiReportPlugin/';
        // order/pdf_input.tpl時
        if (strpos($filename, 'order/pdf_input.tpl') !== false) {
           // DBから取得した値をselectとして格納する。
           $objTransform->select('html body form#form1')->insertBefore(file_get_contents($template_dir . 'templates/snip.admin_order_pdf_add.tpl'));
        }
        //トランスフォームされた値で書き換え
        $source = $objTransform->getHTML();
    }

    function contents_set($objPage) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = "name";
        $from = "plg_dtb_report";
        // 帳票種類を一括取得
        $objPage->arrType = $objQuery->getCol($col, $from);
    }

}
