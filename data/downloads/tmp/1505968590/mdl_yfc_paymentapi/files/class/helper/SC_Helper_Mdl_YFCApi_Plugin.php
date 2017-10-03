<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
// }}}
/**
 * 決済モジュール用 プラグインヘルパークラス
 */
class SC_Helper_Mdl_YFCApi_Plugin{
    public static function installPluginFromPluginInfo($plugin_code, $is_enable = false, $priority = '0') {
        if (file_exists(MDL_YFCAPI_PATH . 'plugins/' . $plugin_code . '/plugin_info.php')) {
            $plugin_info_text = file_get_contents(MDL_YFCAPI_PATH . 'plugins/' . $plugin_code . '/plugin_info.php');
            $plugin_info_text = str_replace('plugin_info', 'plugin_info_' . $plugin_code, $plugin_info_text);
            $plugin_info_text = str_replace(array('<?php', '?>'), '', $plugin_info_text);
            eval($plugin_info_text);
            $objReflection = new ReflectionClass('plugin_info_' . $plugin_code);
            $arrPluginInfo = SC_Helper_Mdl_YFCApi_Plugin_Ex::getPluginInfo($objReflection);
            return SC_Helper_Mdl_YFCApi_Plugin_Ex::installPlugin($arrPluginInfo, $is_enable, $priority);
        }
        return false;
    }

    /**
     * プラグイン情報を取得します.
     *
     * @param ReflectionClass $objReflection
     * @return array プラグイン情報の配列
     */
    public static function getPluginInfo(ReflectionClass $objReflection) {
        $arrStaticProps = $objReflection->getStaticProperties();
        $arrConstants   = $objReflection->getConstants();

        $arrPluginInfoKey = array(
            'PLUGIN_CODE',
            'PLUGIN_NAME',
            'CLASS_NAME',
            'PLUGIN_VERSION',
            'COMPLIANT_VERSION',
            'AUTHOR',
            'DESCRIPTION',
            'PLUGIN_SITE_URL',
            'AUTHOR_SITE_URL',
            'HOOK_POINTS',
        );
        $arrPluginInfo = array();
        foreach ($arrPluginInfoKey as $key) {
            // クラス変数での定義を優先
            if (isset($arrStaticProps[$key])) {
                $arrPluginInfo[$key] = $arrStaticProps[$key];
                // クラス変数定義がなければ, クラス定数での定義を読み込み.
            } elseif ($arrConstants[$key]) {
                $arrPluginInfo[$key] = $arrConstants[$key];
            } else {
                $arrPluginInfo[$key] = null;
            }
        }
        return $arrPluginInfo;
    }

    public static function installPlugin($arrPluginInfo, $is_enable = false, $plugin_priority = '0') {
        // プラグインコード
        $plugin_code = $arrPluginInfo['PLUGIN_CODE'];
        // プラグイン名
        $plugin_name = $arrPluginInfo['PLUGIN_NAME'];

        $plugin_id = SC_Helper_Mdl_YFCApi_Plugin_Ex::getPluginId($plugin_code);
        $plugin_id = SC_Helper_Mdl_YFCApi_Plugin_Ex::registerPluginData($plugin_id, $arrPluginInfo, $is_enable, $plugin_priority);

        $plugin_dir_path = PLUGIN_UPLOAD_REALDIR . $plugin_code . '/';
        $plugin_html_dir = PLUGIN_HTML_REALDIR . $plugin_code;
        if (!file_exists(PLUGIN_UPLOAD_REALDIR)) {
            mkdir(PLUGIN_UPLOAD_REALDIR, 0777);
        }
        if (!file_exists($plugin_dir_path)) {
            mkdir($plugin_dir_path, 0777);
        }
        SC_Utils_Ex::copyDirectory(MDL_YFCAPI_PATH . 'plugins/' . $plugin_code . '/', $plugin_dir_path);

        $plugin = SC_Plugin_Util_Ex::getPluginByPluginId($plugin_id);
        $plugin_class_file_path = $plugin_dir_path . $plugin['class_name'] . '.php';
        require_once($plugin_class_file_path);

        if (!file_exists(PLUGIN_HTML_REALDIR)) {
            mkdir(PLUGIN_HTML_REALDIR, 0777);
        }
        if (!file_exists($plugin_html_dir)) {
            mkdir($plugin_html_dir, 0777);
        }

        if (method_exists($plugin['class_name'], 'install') === true) {
            call_user_func(array($plugin['class_name'], 'install'), $plugin);
        }

        if ($is_enable && method_exists($plugin['class_name'], 'enable') === true) {
            call_user_func(array($plugin['class_name'], 'enable'), $plugin);
        }

        return $plugin_id;
    }

    /**
     * プラグイン情報をDB登録.
     *
     * @param array $arrPluginInfo プラグイン情報を格納した連想配列.
     * @return array エラー情報を格納した連想配列.
     */
    public static function registerPluginData($plugin_id, $arrPluginInfo, $is_enable = false, $priority = '0') {
        // プラグイン情報をDB登録.
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        $arr_sqlval_plugin = array();
        $arr_sqlval_plugin['plugin_name'] = $arrPluginInfo['PLUGIN_NAME'];
        $arr_sqlval_plugin['plugin_code'] = $arrPluginInfo['PLUGIN_CODE'];
        $arr_sqlval_plugin['class_name'] = $arrPluginInfo['CLASS_NAME'];
        $arr_sqlval_plugin['author'] = $arrPluginInfo['AUTHOR'];
        // AUTHOR_SITE_URLが定義されているか判定.
        $author_site_url = $arrPluginInfo['AUTHOR_SITE_URL'];
        if ($author_site_url !== null) {
            $arr_sqlval_plugin['author_site_url'] = $author_site_url;
        }
        // PLUGIN_SITE_URLが定義されているか判定.
        $plugin_site_url = $arrPluginInfo['PLUGIN_SITE_URL'];
        if ($plugin_site_url !== null) {
            $arr_sqlval_plugin['plugin_site_url'] = $plugin_site_url;
        }
        $arr_sqlval_plugin['plugin_version'] = $arrPluginInfo['PLUGIN_VERSION'];
        $arr_sqlval_plugin['compliant_version'] = $arrPluginInfo['COMPLIANT_VERSION'];
        $arr_sqlval_plugin['plugin_description'] = $arrPluginInfo['DESCRIPTION'];
        $arr_sqlval_plugin['priority'] = $priority;
        $arr_sqlval_plugin['enable'] = $is_enable ? PLUGIN_ENABLE_TRUE : PLUGIN_ENABLE_FALSE;
        $arr_sqlval_plugin['update_date'] = 'CURRENT_TIMESTAMP';

        if (SC_Utils_Ex::isBlank($plugin_id)) {
            $plugin_id = $objQuery->nextVal('dtb_plugin_plugin_id');
            $arr_sqlval_plugin['plugin_id'] = $plugin_id;
            $objQuery->insert('dtb_plugin', $arr_sqlval_plugin);
        } else {
            $objQuery->update('dtb_plugin', $arr_sqlval_plugin, 'plugin_id = ?', array($plugin_id));
        }

        // フックポイントをDB登録.
        $hook_point = $arrPluginInfo['HOOK_POINTS'];
        if ($hook_point !== null) {
            // 一回削除する
            $objQuery->delete('dtb_plugin_hookpoint', 'plugin_id = ?', array($plugin_id));
            // フックポイントが配列で定義されている場合
            if (is_array($hook_point)) {
                foreach ($hook_point as $h) {
                    $arr_sqlval_plugin_hookpoint = array();
                    $id = $objQuery->nextVal('dtb_plugin_hookpoint_plugin_hookpoint_id');
                    $arr_sqlval_plugin_hookpoint['plugin_hookpoint_id'] = $id;
                    $arr_sqlval_plugin_hookpoint['plugin_id'] = $plugin_id;
                    $arr_sqlval_plugin_hookpoint['hook_point'] = $h[0];
                    $arr_sqlval_plugin_hookpoint['callback'] = $h[1];
                    $arr_sqlval_plugin_hookpoint['update_date'] = 'CURRENT_TIMESTAMP';
                    $objQuery->insert('dtb_plugin_hookpoint', $arr_sqlval_plugin_hookpoint);
                }
                // 文字列定義の場合
            } else {
                $array_hook_point = explode(',', $hook_point);
                foreach ($array_hook_point as $h) {
                    $arr_sqlval_plugin_hookpoint = array();
                    $id = $objQuery->nextVal('dtb_plugin_hookpoint_plugin_hookpoint_id');
                    $arr_sqlval_plugin_hookpoint['plugin_hookpoint_id'] = $id;
                    $arr_sqlval_plugin_hookpoint['plugin_id'] = $plugin_id;
                    $arr_sqlval_plugin_hookpoint['hook_point'] = $h;
                    $arr_sqlval_plugin_hookpoint['update_date'] = 'CURRENT_TIMESTAMP';
                    $objQuery->insert('dtb_plugin_hookpoint', $arr_sqlval_plugin_hookpoint);
                }
            }
        }
        $objQuery->commit();
        return $plugin_id;
    }

    /**
     * 既にインストールされているプラグインのIDを取得します。
     *
     * @param string $plugin_code プラグインコード
     * @return integer インストール済の場合 plugin_id インストールされていない場合false
     */
    public static function getPluginId($plugin_code) {
        $plugin = SC_Plugin_Util_Ex::getPluginByPluginCode($plugin_code);
        if (!empty($plugin)) {
            return $plugin['plugin_id'];
        }
        return false;
    }

    public static function setBlocData($plugin_id, $bloc_data, $device_type_id, $bloc_name, $filename, $php_path = "", $is_force = false) {
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        $bloc_dir = $objLayout->getTemplatePath($device_type_id) . BLOC_DIR;
        $tpl_path = $filename . '.tpl';

        $where = 'filename = ?';
        $arrval = array($filename);

        $arrExists = $objLayout->getBlocs($device_type_id, $where, $arrval);
        $exists_file = $bloc_dir . $arrExists[0]['filename'] . '.tpl';
        if (file_exists($exists_file)) {
            if ($is_force) {
                @copy($exists_file, $exists_file . '.bak.' . date('YmdHis'));
                unlink($exists_file);
            } else {
                return $arrExists[0]['bloc_id'];
            }
        }

        $sqlval_bloc = array();
        $sqlval_bloc['device_type_id'] = $device_type_id;
        $sqlval_bloc['bloc_name'] = $bloc_name;
        $sqlval_bloc['tpl_path'] = $filename . '.tpl';
        $sqlval_bloc['filename'] = $filename;
        $sqlval_bloc['update_date'] = "CURRENT_TIMESTAMP";
        if (!SC_Utils_Ex::isBlank($php_path)) {
            $sqlval_bloc['php_path'] = $php_path;
        }
        $sqlval_bloc['deletable_flg'] = 0;
        $sqlval_bloc['plugin_id'] = $plugin_id;
        $objQuery->setOrder('');

        if (SC_Utils_Ex::isBlank($arrExists[0]['bloc_id'])) {
            $sqlval_bloc['bloc_id'] = $objQuery->max('bloc_id', "dtb_bloc", "device_type_id = ?", array($device_type_id)) + 1;
            $sqlval_bloc['create_date'] = "CURRENT_TIMESTAMP";
            $objQuery->insert("dtb_bloc", $sqlval_bloc);
            $bloc_id = $sqlval_bloc['bloc_id'];
        } else {
            $objQuery->update("dtb_bloc", $sqlval_bloc, "device_type_id = ? AND bloc_id = ?", array($device_type_id, $arrExists[0]['bloc_id']));
            $bloc_id = $arrExists[0]['bloc_id'];
        }

        $bloc_path = $bloc_dir . $tpl_path;
        if (!SC_Helper_FileManager_Ex::sfWriteFile($bloc_path, $bloc_data)) {
            $objQuery->rollback();
            return false;
        }

        $objQuery->commit();
        return $bloc_id;
    }

    public static function setPageData($tpl_data, $page_name, $url, $filename, $device_type_id, $is_force = false) {
        $objLayout = new SC_Helper_PageLayout_Ex();
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();

        $tpl_dir = $objLayout->getTemplatePath($device_type_id);
        $tpl_path = $filename . '.tpl';

        $arrExists = $objLayout->getPageProperties($device_type_id, null, 'device_type_id = ? and filename = ?', array($device_type_id, $filename));

        $exists_file = $tpl_dir . $arrExists[0]['filename'] . '.tpl';
        if (file_exists($exists_file)) {
            if ($is_force) {
                @copy($exists_file, $exists_file . '.bak.' . date('YmdHis'));
                unlink($exists_file);
            } else {
                return $arrExists[0]['page_id'];
            }
        }
        $table = 'dtb_pagelayout';
        $arrValues = array();
        $arrValues['device_type_id'] = $device_type_id;
        $arrValues['header_chk'] = 1;
        $arrValues['footer_chk'] = 1;
        $arrValues['page_name'] = $page_name;
        $arrValues['url'] = $url;
        $arrValues['filename'] = $filename;
        $arrValues['edit_flg'] = '2';
        $arrValues['update_url'] = $_SERVER['HTTP_REFERER'];
        $arrValues['update_date'] = 'CURRENT_TIMESTAMP';
        $objQuery->setOrder('');
        if (SC_Utils_Ex::isBlank($arrExists[0]['page_id'])) {
            $arrValues['page_id'] = 1 + $objQuery->max('page_id', $table, 'device_type_id = ?', array($arrValues['device_type_id']));
            $arrValues['create_date'] = 'CURRENT_TIMESTAMP';
            $objQuery->insert($table, $arrValues);
            $page_id = $arrValues['page_id'];
        } else {
            $objQuery->update($table, $arrValues, 'page_id = ? AND device_type_id = ?', array($arrExists[0]['page_id'], $arrValues['device_type_id']));
            $page_id = $arrExists[0]['page_id'];
        }

        $tpl_path = $tpl_dir . $filename . '.tpl';

        if (!SC_Helper_FileManager_Ex::sfWriteFile($tpl_path, $tpl_data)) {
            $objQuery->rollback();
            return false;
        }
        $objQuery->commit();
        return $page_id;
    }
}

