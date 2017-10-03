<?php
/*
 * CustomTradelaw
 * Copyright (C) 2012 SUNATMARK CO.,LTD. All Rights Reserved.
 * http://www.sunatmark.co.jp/
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


class CustomTradelaw extends SC_Plugin_Base {

    protected $template_dir;

    /**
     * コンストラクタ
     *
     * @param array $arrPlugin 自身のプラグイン情報
     * @return void
     */
    public function __construct(array $arrPlugin) {
        parent::__construct($arrPlugin);
        $this->template_dir     = PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/templates/';
    }

    /**
     * インストール
     * (プラグインのインストール時に実行)
     *
     * @param array $arrPlugin プラグイン情報
     * @return void
     */
    function install($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // メディアファイルコピー
		$cp_result = SC_Utils_Ex::sfCopyDir(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/front/', PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . '/');
        if ($cp_result === false) {
            $cp_fail = true;
        }
		else {
            if (preg_match('/失敗/us', $cp_result)) $cp_fail = true;
        }
        if (isset($cp_fail)) {
            $objQuery->delete('dtb_plugin', 'plugin_code = ?', array(get_class()));
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, '', false, PLUGIN_HTML_REALDIR.' に書き込めません。パーミッションをご確認ください。');
        }

        // テーブル
        // デフォルト項目管理テーブル
        $table_org_sql = <<< __EOS__
CREATE TABLE plg_customtradelaw_org (
    id integer NOT NULL,
    name text NOT NULL,
    disp boolean NOT NULL,
    PRIMARY KEY (id)
);
__EOS__;

        // 追加項目管理テーブル
        $table_add_sql = <<< __EOS__
CREATE TABLE plg_customtradelaw_add (
    id integer NOT NULL,
    item_no integer NOT NULL,
    disp boolean NOT NULL DEFAULT true,
    del_flag boolean NOT NULL DEFAULT false,
    create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    update_date timestamp NOT NULL,
    PRIMARY KEY (id)
);
__EOS__;

        // 項目並び順管理テーブル
        $table_order_sql = <<< __EOS__
CREATE TABLE plg_customtradelaw_order (
    id integer NOT NULL,
    item_order text NOT NULL,
    PRIMARY KEY (id)
);
__EOS__;

        // plg_customtradelaw_org用初期レコード
        $arrInsertOrg = array(
            array('id' => 1, 'name' => 'o1', 'disp' => true),
            array('id' => 2, 'name' => 'o2', 'disp' => true),
            array('id' => 3, 'name' => 'o3', 'disp' => true),
            array('id' => 4, 'name' => 'o4', 'disp' => true),
            array('id' => 5, 'name' => 'o5', 'disp' => true),
            array('id' => 6, 'name' => 'o6', 'disp' => true),
            array('id' => 7, 'name' => 'o7', 'disp' => true),
            array('id' => 8, 'name' => 'o8', 'disp' => true),
            array('id' => 9, 'name' => 'o9', 'disp' => true),
            array('id' => 10, 'name' => 'o10', 'disp' => true),
            array('id' => 11, 'name' => 'o11', 'disp' => true),
            array('id' => 12, 'name' => 'o12', 'disp' => true),
            array('id' => 13, 'name' => 'o13', 'disp' => true),
            array('id' => 14, 'name' => 'o14', 'disp' => true)
        );
        // plg_customtradelaw_order用初期レコード
        $arrInsertOrder = array(
            array('id' => 1, 'item_order' => 'o1,o2,o3,o4,o5,o6,o7,o8,o9,o10,o11,o12,o13,o14')
        );

        $objQuery = SC_Query_Ex::getSingletonInstance();

        // 現在DBに存在するテーブル一覧を取得
        $arrTableList = $objQuery->listTables();

        $objQuery->begin();

        if (!in_array('plg_customtradelaw_org', $arrTableList)) {
            // テーブル作成
            $objQuery->exec($table_org_sql);
            // レコード登録
            foreach($arrInsertOrg as $insert) {
                $objQuery->insert('plg_customtradelaw_org', $insert);
            }
        }

        if (!in_array('plg_customtradelaw_add', $arrTableList)) {
            // テーブル作成
            $objQuery->exec($table_add_sql);
            // シーケンス作成
            if (DB_TYPE == 'pgsql') {
                $objQuery->exec('CREATE SEQUENCE plg_customtradelaw_add_id_seq;');
            } else if (DB_TYPE == 'mysql') {
                $objQuery->exec('CREATE TABLE plg_customtradelaw_add_id_seq(sequence INT NOT NULL AUTO_INCREMENT, PRIMARY KEY (sequence));');
            }
        }

        if (!in_array('plg_customtradelaw_order', $arrTableList)) {
            // テーブル作成
            $objQuery->exec($table_order_sql);
            // レコード登録
            foreach($arrInsertOrder as $insert) {
                $objQuery->insert('plg_customtradelaw_order', $insert);
            }
        }

        $objQuery->commit();
    }

    /**
     * アンインストール
     * (アンインストール時に実行)
     *
     * @param array $arrPlugin プラグイン情報
     * @return void
     */
    function uninstall($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // 現在DBに存在するテーブル一覧を取得
        $arrTableList = $objQuery->listTables();
        $arrColumnList = $objQuery->listTableFields('dtb_baseinfo');

        $objQuery->begin();

        if (in_array('plg_customtradelaw_order', $arrTableList)) {
            // テーブル削除
            $objQuery->exec('DROP TABLE plg_customtradelaw_order;');
        }

        if (in_array('plg_customtradelaw_add', $arrTableList)) {
            // テーブル削除
            $objQuery->exec('DROP TABLE plg_customtradelaw_add;');
            // シーケンス削除
            if (DB_TYPE == 'pgsql') {
                $objQuery->exec('DROP SEQUENCE plg_customtradelaw_add_id_seq;');
            } else if (DB_TYPE == 'mysql') {
                $objQuery->exec('DROP TABLE plg_customtradelaw_add_id_seq;');
            }
        }

        if (in_array('plg_customtradelaw_org', $arrTableList)) {
            // テーブル削除
            $objQuery->exec('DROP TABLE plg_customtradelaw_org;');
        }

        foreach ($arrColumnList as $column) {
            if (strpos($column, 'plg_customtradelaw_') !== false) {
                $objQuery->exec('ALTER TABLE dtb_baseinfo DROP COLUMN ' . $column . ';');
            }
        }

        $objQuery->commit();
    }

    /**
     * 稼働
     * (プラグインを有効にした際に実行)
     *
     * @param array $arrPlugin プラグイン情報
     * @return void
     */
    function enable($arrPlugin) {
        // nop
    }

    /**
     * 停止
     * (プラグインを無効にした際に実行)
     *
     * @param array $arrPlugin プラグイン情報
     * @return void
     */
    function disable($arrPlugin) {
        // nop
    }

    /**
     * 処理の介入箇所とコールバック関数を設定
     * (プラグインインスタンス生成時に実行)
     *
     * @param SC_Helper_Plugin $objHelperPlugin プラグインヘルパーオブジェクト
     * @param  integer $priority 優先度
     * @return void
     */
    function register(SC_Helper_Plugin $objHelperPlugin, $priority) {
        parent::register($objHelperPlugin, $priority);
        // ヘッダへの追加
        $this->template_dir = PLUGIN_UPLOAD_REALDIR . 'CustomTradelaw/templates/';
        $objHelperPlugin->setHeadNavi($this->template_dir . 'plg_customtradelaw_header.tpl');
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
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_PC:
                if (strpos($filename, 'order/index.tpl') !== false) {
                    $order = array(0,1,2,4,5,6,7,8,9,10,11,12,13);
                    $objTransform->select('#undercolumn_order table')->appendChild(file_get_contents($this->template_dir . 'plg_customtradelaw_order_add_pc.tpl'));
                    $objTransform->find('tr',0)->insertBefore('<!--{foreach item=order from=$arrOrderItem}-->')->end();
                    for ($idx = 0; $idx < 13; $idx++) {
                        $objTransform->find('tr', $idx)->insertBefore('<!--{if $order == \'o' . ($order[$idx]+1) . '\'}-->')->insertAfter('<!--{/if}-->')->end();
                    }
                }
                break;
            case DEVICE_TYPE_MOBILE:
                if (strpos($filename, 'order/index.tpl') !== false) {
                    // テンプレート全体を置き換えるため、一旦divで包む
                    $objTransform = new SC_Helper_Transform('<div>' . $source . '</div>');
                    // div全体を置き換える
                    $objTransform->select('div')->replaceElement(file_get_contents($this->template_dir . 'plg_customtradelaw_order_add_mp.tpl'));
                }
                break;
            case DEVICE_TYPE_SMARTPHONE:
                if (strpos($filename, 'order/index.tpl') !== false) {
                    $order = array(0,1,2,4,5,6,7,8,9,10,11,12,13);
                    $objTransform->select('#undercolumn dl.form_info')->appendFirst('<!--{foreach item=order from=$arrOrderItem}-->')->appendChild(file_get_contents($this->template_dir . 'plg_customtradelaw_order_add_sp.tpl'));
                    for ($idx = 0; $idx < 13; $idx++) {
                        $objTransform->find('dt', $idx)->insertBefore('<!--{if $order == \'o' . ($order[$idx]+1) . '\'}-->')->end()->find('dd',$idx)->insertAfter('<!--{/if}-->')->end();
                    }
                }
                break;
            case DEVICE_TYPE_ADMIN:
            default:
                if (strpos($filename, 'basis/tradelaw.tpl') !== false) {
                    $objTransform->select('form#form1')->insertBefore(file_get_contents($this->template_dir . 'plg_customtradelaw_admin_tradelaw_message.tpl'))->appendFirst('<input type="hidden" name="plg_customtradelaw_item_no" id="plg_customtradelaw_item_no" value="<!--{$item_no}-->" />');
                    $objTransform->find('table.form')->appendFirst('<!--{foreach item=order from=$arrOrder}-->')->appendChild(file_get_contents($this->template_dir . 'plg_customtradelaw_admin_tradelaw_add.tpl'));
                    for ($idx = 0; $idx < 14; $idx++) {
                        $objTransform->find('tr', $idx)->insertBefore('<!--{if $order == \'o' . ($idx+1) . '\'}-->')->insertAfter('<!--{/if}-->')->find('th')->appendFirst('<input type="hidden" name="plg_customtradelaw_order[]" value="<!--{$order}-->" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<!--{$order}-->"<!--{if in_array($order, $arrDisp)}--> checked<!--{/if}--> />'.PHP_EOL)->end(2);
                    }

                    $objTransform->select('.btn-area')->insertBefore(file_get_contents($this->template_dir . 'plg_customtradelaw_admin_tradelaw_button.tpl'));
                }
                break;
        }
        $source = $objTransform->getHTML();
    }

    /**
     * パラメーター情報の初期化
     *
     * @param string $class ページクラス名
     * @param SC_FormParam_Ex $objFormParam FormParamオブジェクト
     * @return void
     */
    function formParamConstruct($class, SC_FormParam_Ex $objFormParam) {
        if ($class != 'LC_Page_Admin_Basis_Tradelaw') return;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            foreach ($_POST as $key => $value) {
                if (preg_match('/^(plg_customtradelaw_name_|plg_customtradelaw_value_)a([0-9]+)/', $key, $m)) {
                    $objDb = new SC_Helper_DB_Ex();
                    $objDb->sfColumnExists('dtb_baseinfo', $m[1] . 'a' . $m[2], 'TEXT', '', true);
                    if ($m[1] == 'plg_customtradelaw_name_') $objFormParam->addParam("追加項目名a".$m[2], "plg_customtradelaw_name_a".$m[2], STEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
                    if ($m[1] == 'plg_customtradelaw_value_') $objFormParam->addParam("追加項目値a".$m[2], "plg_customtradelaw_value_a".$m[2], MTEXT_LEN, 'KVa', array("MAX_LENGTH_CHECK"));
                }
            }
        } else {
            // 初期表示
            $objQuery = SC_Query_Ex::getSingletonInstance();
            $AddId = $objQuery->getCol('item_no', 'plg_customtradelaw_add', 'del_flag = ?', array(false));
            foreach ($AddId as $id) {
                $objFormParam->addParam("追加項目名a".$id, "plg_customtradelaw_name_a".$id, STEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
                $objFormParam->addParam("追加項目値a".$id, "plg_customtradelaw_value_a".$id, MTEXT_LEN, 'KVa', array("EXIST_CHECK", "MAX_LENGTH_CHECK"));
            }
        }

    }

    /**
     * LC_Page_Admin_Basis_Tradelaw->action()の直後
     *
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function tradelawActionAfter(LC_Page_Ex $objPage) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $arrOrder = $_POST['plg_customtradelaw_order'];
            $item_no = $_POST['plg_customtradelaw_item_no'];
            if (count($objPage->arrErr) == 0) {
                $this->registerDB($objPage, $arrOrder);
            }
            // 表示/非表示
            if (array_key_exists('plg_customtradelaw_disp', $_POST)) {
                $arrDisp = $_POST['plg_customtradelaw_disp'];
            } else {
                $arrDisp = array();
            }
        } else {
            $objQuery = SC_Query_Ex::getSingletonInstance();
            // 並び順
            $arrOrder = explode(',', $objQuery->get('item_order', 'plg_customtradelaw_order', 'id = ?', array(1)));
            // 追加項目番号最大値
            $item_no = $objQuery->max('item_no', 'plg_customtradelaw_add') + 1;
            // 表示/非表示
            $arrDisp = $objQuery->getCol('item_no', 'plg_customtradelaw_add', 'del_flag = ? AND disp = ?', array(false, true));
            foreach ($arrDisp as $key => $value) {
                $arrDisp[$key] = 'a' . $value;
            }
            $arrDisp = array_merge($arrDisp, $objQuery->getCol('name', 'plg_customtradelaw_org', 'disp = ?', array(true)));
        }
        $objPage->arrOrder = $arrOrder;
        $objPage->item_no = $item_no;
        $objPage->arrDisp = $arrDisp;
        $objPage->plg_customtradelaw_stextlen = STEXT_LEN;
        $objPage->plg_customtradelaw_mtextlen = MTEXT_LEN;

        // あらかじめバージョンを調べておく
        $objPage->eccube_gte_2_13_3= $this->isEccubeHigherThan("2.13.3", true);
    }

    /**
     * DB登録関数
     *
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param array $arrOrder 並び順
     * @return void
     */
    function registerDB(LC_Page_Ex $objPage, $arrOrder) {
        // 現在の登録状態を読み出しておく
        $col = 'id, item_no, disp, del_flag, create_date, update_date';
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrItem = $objQuery->select($col, 'plg_customtradelaw_add', 'del_flag = ?', array(false));

        // 表示/非表示設定
        if (array_key_exists('plg_customtradelaw_disp', $_POST)) {
            $arrDisp = $_POST['plg_customtradelaw_disp'];
        } else {
            $arrDisp = array();
        }

        $objQuery->begin();

        // 新規追加分
        // →並び順の中にあって、DBにない項目
        foreach ($arrOrder as $item_no) {
            if (preg_match('/^a([0-9]+)/', $item_no, $m)) {
                if (!$objQuery->exists('plg_customtradelaw_add', 'item_no = ?', array($m[1]))) {
                    $arrInsertAdd = array(
                        'id' => $objQuery->nextVal('plg_customtradelaw_add_id'),
                        'item_no' => $m[1],
                        'disp' => in_array($m[0], $arrDisp),
                        'del_flag' => false,
                        'create_date' => 'CURRENT_TIMESTAMP',
                        'update_date' => 'CURRENT_TIMESTAMP'
                    );
                    $objQuery->insert('plg_customtradelaw_add', $arrInsertAdd);
                }
            }
        }

        // 更新分
        // 登録済みの項目に対してチェックする
        foreach ($arrItem as $item) {
            // 並び順の中ない場合、削除されたということ
            if (!in_array('a' . $item['item_no'], $arrOrder)) {
                $item['del_flag'] = true;
                $item['update_date'] = 'CURRENT_TIMESTAMP';

                // 店舗基本情報からカラム削除
                $objQuery->exec('ALTER TABLE dtb_baseinfo DROP COLUMN plg_customtradelaw_name_a'.$item['item_no'].';');
                $objQuery->exec('ALTER TABLE dtb_baseinfo DROP COLUMN plg_customtradelaw_value_a'.$item['item_no'].';');
            } else {
                $item['disp'] = in_array('a' . $item['item_no'], $arrDisp);
                $item['update_date'] = 'CURRENT_TIMESTAMP';
            }
            $objQuery->update('plg_customtradelaw_add', $item, 'id = ?', array($item['id']));
        }

        // デフォルト項目の表示/非表示
        for ($idx = 1; $idx <= 14; $idx++) {
            $disp = in_array('o' . $idx, $arrDisp);
            $objQuery->update('plg_customtradelaw_org', array('disp' => $disp), 'id = ?', array($idx));
        }

        // 並び順
        $objQuery->update(plg_customtradelaw_order, array('item_order' => implode(',', $arrOrder)), 'id = ?', array(1));

        $objQuery->commit();
    }

    /**
     * 店舗基本情報並べ替え表示
     *
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function orderActionAfter(LC_Page_Ex $objPage) {
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $arrDispAdd = $objQuery->getCol('item_no', 'plg_customtradelaw_add', 'del_flag = ? AND disp = ?', array(false, true));
        foreach ($arrDispAdd as $key => $value) {
            $arrDispAdd[$key] = 'a' . $value;
        }
        $arrDispOrg = $objQuery->getCol('name', 'plg_customtradelaw_org', 'disp = ?', array(true));
        // 並び順
        $arrOrder = explode(',', $objQuery->get('item_order', 'plg_customtradelaw_order', 'id = ?', array(1)));
        foreach ($arrOrder as $key => $value) {
            if (!in_array($value, $arrDispAdd) && !in_array($value, $arrDispOrg)) unset($arrOrder[$key]);
        }

        $objPage->arrOrderItem = $arrOrder;
    }

    // 渡されたバージョンが ECCUBE_VERSION 以前であれば true を返す。
    // $include_equal: 同一バージョンでも true とするかどうか
    function isEccubeHigherThan($version, $include_equal = false) {

        // バージョン文字列から数字以外を除去
        $current_version    = preg_replace("/\D/", "", ECCUBE_VERSION);
        $compared_version   = preg_replace("/\D/", "", $version);

        // 文字列の長さを測る
        $current_version_length = strlen($current_version);
        $compared_version_length= strlen($compared_version);
        $version_length = $current_version_length > $compared_version_length ?
            $current_version_length : $compared_version_length;

        // 左詰めして長い方の長さでそろうように右を0で埋め、数値として比較する
        $current_version_int    = (int)sprintf("%-0" . $version_length . "s", $current_version);
        $compared_version_int   = (int)sprintf("%-0" . $version_length . "s", $compared_version);

        if ($include_equal) {
            return $current_version_int >= $compared_version_int;
        } else {
            return $current_version_int >  $compared_version_int;
        }
    }
}

?>
