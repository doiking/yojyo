<?php
/*
 * Tetra_SearchBar
 * Copyright(c) 2015 TetraThemes All Rights Reserved.
 *
 * http://tetra-themes.net/
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

/**
 * プラグインのメインクラス
 *
 * @package TetraThemes
 * @author TetraThemes
 * @version $Id: $
 */
class Tetra_SearchBar extends SC_Plugin_Base {

    /**
     * コンストラクタ
     *
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

        // プラグイン
        if(copy(PLUGIN_UPLOAD_REALDIR . "Tetra_SearchBar/logo.png", PLUGIN_HTML_REALDIR . "Tetra_SearchBar/logo.png") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "Tetra_SearchBar/Tetra_SearchBar.php", PLUGIN_HTML_REALDIR . "Tetra_SearchBar/Tetra_SearchBar.php") === false);

        // ブロック
        if(copy(PLUGIN_UPLOAD_REALDIR . "Tetra_SearchBar/templates/plg_Tetra_SearchBar.tpl", TEMPLATE_REALDIR . "frontparts/bloc/plg_Tetra_SearchBar.tpl") === false);
        if(copy(PLUGIN_UPLOAD_REALDIR . "Tetra_SearchBar/bloc/plg_Tetra_SearchBar.php", HTML_REALDIR . "frontparts/bloc/plg_Tetra_SearchBar.php") === false);
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

        if(SC_Helper_FileManager_Ex::deleteFile(HTML_REALDIR  . "frontparts/bloc/plg_Tetra_SearchBar.php") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(TEMPLATE_REALDIR . "frontparts/bloc/plg_Tetra_SearchBar.tpl") === false);
        if(SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "Tetra_SearchBar") === false);

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

        // ブロック登録
        Tetra_SearchBar::insertBloc($arrPlugin);

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

        // ブロック削除
        Tetra_SearchBar::deleteBloc($arrPlugin);

    }

    function insertBloc($arrPlugin) {
        $objQuery = SC_Query_Ex::getSingletonInstance();

        // ブロック情報のセット
        $sqlval_bloc                    = array();
        $sqlval_bloc['device_type_id']  = DEVICE_TYPE_PC;
        $sqlval_bloc['bloc_id']         = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = " . DEVICE_TYPE_PC) + 1;
        $sqlval_bloc['bloc_name']       = $arrPlugin['plugin_name'];
        $sqlval_bloc['tpl_path']        = "plg_Tetra_SearchBar.tpl";
        $sqlval_bloc['filename']        = "plg_Tetra_SearchBar";
        $sqlval_bloc['create_date']     = "CURRENT_TIMESTAMP";
        $sqlval_bloc['update_date']     = "CURRENT_TIMESTAMP";
        $sqlval_bloc['php_path']        = "frontparts/bloc/plg_Tetra_SearchBar.php";
        $sqlval_bloc['deletable_flg']   = 0;
        $sqlval_bloc['plugin_id']       = $arrPlugin['plugin_id'];

        // ブロック追加
        $objQuery->insert("dtb_bloc", $sqlval_bloc);
    }

    function deleteBloc($arrPlugin) {
        $objQuery   = SC_Query_Ex::getSingletonInstance();

        // ブロック情報のセット
        $arrBlocId  = $objQuery->getCol('bloc_id', "dtb_bloc", "device_type_id = ? AND filename = ?", array(DEVICE_TYPE_PC , "plg_Tetra_SearchBar"));
        $bloc_id    = (int) $arrBlocId[0];
        $where      = "bloc_id = ? AND device_type_id = ?";

        // DELETE実行
        $objQuery->delete("dtb_bloc", $where, array($bloc_id, DEVICE_TYPE_PC));
        $objQuery->delete("dtb_blocposition", $where, array($bloc_id, DEVICE_TYPE_PC));
    }

}
?>
