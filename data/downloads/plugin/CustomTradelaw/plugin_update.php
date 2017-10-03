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

require_once 'plugin_info.php';

/**
 * プラグイン のアップデート用クラス.
 *
 * @package CustomTradelaw
 * @author SUNATMARK CO.,LTD.
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
        $plugin_dir_path = PLUGIN_UPLOAD_REALDIR . 'CustomTradelaw/';
        SC_Utils_Ex::copyDirectory(DOWNLOADS_TEMP_PLUGIN_UPDATE_DIR, $plugin_dir_path);

        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        // テーブル名・シーケンス名を小文字に統一
        if (in_array($arrPlugin['plugin_version'], array('1.0'))) {
            $arrSql = array(
                "ALTER TABLE plg_CustomTradelaw_org RENAME TO plg_customtradelaw_org_tmp",
                "ALTER TABLE plg_customtradelaw_org_tmp RENAME TO plg_customtradelaw_org",
                "ALTER TABLE plg_CustomTradelaw_add RENAME TO plg_customtradelaw_add_tmp",
                "ALTER TABLE plg_customtradelaw_add_tmp RENAME TO plg_customtradelaw_add",
                "ALTER TABLE plg_CustomTradelaw_order RENAME TO plg_customtradelaw_order_tmp",
                "ALTER TABLE plg_customtradelaw_order_tmp RENAME TO plg_customtradelaw_order"
            );
            if (DB_TYPE == 'pgsql') {
                $arrSql[] = "ALTER SEQUENCE plg_CustomTradelaw_add_id_seq RENAME TO plg_customtradelaw_add_id_seq_tmp";
                $arrSql[] = "ALTER SEQUENCE plg_customtradelaw_add_id_seq_tmp RENAME TO plg_customtradelaw_add_id_seq";
            } elseif (DB_TYPE == 'mysql') {
                $arrSql[] = "ALTER TABLE plg_CustomTradelaw_add_id_seq RENAME TO plg_customtradelaw_add_id_seq_tmp";
                $arrSql[] = "ALTER TABLE plg_customtradelaw_add_id_seq_tmp RENAME TO plg_customtradelaw_add_id_seq";
            }
            foreach ($arrSql as $sql) {
                $objQuery->query($sql);
            }
        }

        // バージョンの更新
        $arrUpdate = array(
            'plugin_name'        => plugin_info::$PLUGIN_NAME,
            'plugin_version'     => plugin_info::$PLUGIN_VERSION,
            'compliant_version'  => plugin_info::$COMPLIANT_VERSION,
            'plugin_description' => plugin_info::$DESCRIPTION
        );
        $objQuery->update('dtb_plugin', $arrUpdate, 'plugin_code = ?', array(plugin_info::$PLUGIN_CODE));

        $objQuery->commit();

        SC_Utils_Ex::copyDirectory(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . '/front/', PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . '/');
    }
}
?>