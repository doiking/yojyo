<?php
/*
 * ChangePDFLogo
 * Copyright (C) 2013 S-cubism All rights reserved.
 * http://ec-cube.ec-orange.jp/
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
 * プラグイン のアップデート用クラス.
 *
 * @package CategoryContents
 * @author LOCKON CO.,LTD.
 * @version $Id: $
 */
class plugin_update{
   /**
     * アップデート
     * updateはアップデート時に実行されます.
     * 引数にはdtb_pluginのプラグイン情報が渡されます.
     *
     * @param array $arrPlugin プラグイン情報の連想配列(dtb_plugin)
     * @return void
     */
    function update($arrPlugin) {
        // バージョン0.1からのアップデート
        if($arrPlugin['plugin_version'] == "0.1"){
           plugin_update::update01($arrPlugin);
        }
    }

    /**
     * 0.1のアップデートを実行します.
     * @param type $param
     */
    function update01($arrPlugin) {
        // 変更のあったファイルを上書きします.
        copy(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR . "/CategoryContents.php", PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/CategoryContents.php");
        // dtb_pluhinを更新します.
        plugin_update::updateDtbPluginVersion($arrPlugin['plugin_id'], "1.1");
    }

    /**
     * dtb_pluginを更新します.
     *
     * @param int $plugin_id プラグインID
     * @return void
     */
    function updateDtbPluginVersion ($plugin_id, $plugin_version) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $sqlval = array();
        $table = "dtb_plugin";
        $sqlval['plugin_version'] = $plugin_version;
        $sqlval['compliant_version'] = "2.12.1, 2.12.1";
        $sqlval['update_date'] = 'CURRENT_TIMESTAMP';
        $where = "plugin_id = ?";
        $objQuery->update($table, $sqlval, $where, array($plugin_id));
    }
}
?>
