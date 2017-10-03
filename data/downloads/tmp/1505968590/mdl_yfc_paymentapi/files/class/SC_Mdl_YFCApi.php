<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
/*
 * モジュール設定情報
 */
class SC_Mdl_YFCApi {
    /** サブデータを保持する変数 */
    var $subData = null;

    /** モジュール情報 */
    var $moduleInfo = array(
        'paymentName' => MDL_YFCAPI_SERVICE_NAME,
        'moduleName'  => MDL_YFCAPI_MODULE_NAME,
        'moduleCode'  => MDL_YFCAPI_CODE,
        'moduleVersion' => MDL_YFCAPI_VERSION,
    );

    /**
     * テーブル拡張設定.拡張したいテーブル情報を配列で記述する.
     * $updateTable = array(
     *     array(
     *       'name' => 'テーブル名',
     *       'cols' => array(
     *          array('name' => 'カラム名', 'type' => '型名'),
     *          array('name' => 'カラム名', 'type' => '型名'),
     *       ),
     *     ),
     *     array(
     *       ...
     *     ),
     *     array(
     *       ...
     *     ),
     * );
     */
    var $updateTable = array(
        // dtb_paymentの更新
        array(
            'name' => 'dtb_payment',
            'cols'  => array(
                array('name' => 'module_code', 'type' => 'text'),
            ),
        ),
    );

    /**
     * アップデートファイル
     *
     * @var array
     */
    var $updateFile = array();

    /**
     * SC_Mdl_YFCApi_Ex::install()を呼んだ際にdtb_moduleのsub_dataカラムへ登録される値
     * シリアライズされて保存される.
     *
     * master_settings => 初期データなど
     * user_settings => 設定情報など、ユーザの入力によるデータ
     */
    var $installSubData = array(
        // 初期データなどを保持する
        'master_settings' => array(
        ),
        // 設定情報など、ユーザの入力によるデータを保持する
        'user_settings' => array(
        ),
    );

    /**
     * SC_Mdl_YFCApiのインスタンスを取得する
     *
     * @return SC_Mdl_YFCApi
     */
    function &getInstance() {
        static $_objSC_Mdl_YFCApi;
        if (empty($_objSC_Mdl_YFCApi)) {
            $_objSC_Mdl_YFCApi = new SC_Mdl_YFCApi_Ex();
        }
        $_objSC_Mdl_YFCApi->init();
        return $_objSC_Mdl_YFCApi;
    }

    /**
     * 初期化処理.
     */
    function init() {
        foreach ($this->moduleInfo as $k => $v) {
            $this->$k = $v;
        }
    }

    /**
     * モジュール表示用名称を取得する
     *
     * @return string
     */
    function getName() {
        return $this->moduleName;
    }

    /**
     * 支払い方法名(決済モジュールの場合のみ)
     *
     * @return string
     */
    function getPaymentName() {
        return $this->paymentName;
    }

    /**
     * モジュールコードを取得する
     *
     * @param boolean $toLower trueの場合は小文字へ変換する.デフォルトはfalse.
     * @return string
     */
    function getCode($toLower = false) {
        $moduleCode = $this->moduleCode;
        return $toLower ? strtolower($moduleCode) : $moduleCode;
    }

    /**
     * モジュールバージョンを取得する
     *
     * @return string
     */
    function getVersion() {
        return $this->moduleVersion;
    }

    /**
     * サブデータを取得する.
     *
     * @param string $key
     * @return array|null
     */
    function getSubData($key = null) {
        if (isset($this->subData)) {
            if(is_null($key)) {
                return $this->subData;
            } else {
                return $this->subData[$key];
            }
        }

        $moduleCode = $this->getCode(true);
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $ret = $objQuery->get(
            'sub_data', 'dtb_module', 'module_code = ?', array($moduleCode)
        );

        if (isset($ret)) {
            $this->subData = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array($ret);
            if (is_null($key)) {
                return $this->subData;
            } else {
                return $this->subData[$key];
            }
        }
        return null;
    }

    /**
     * サブデータをDBへ登録する
     * $keyがnullの時は全データを上書きする
     *
     * @param mixed $data
     * @param string $key
     */
    function registerSubData($data, $key = null) {
        $subData = $this->getSubData();

        if (is_null($key)) {
            $subData = $data;
        } else {
            $subData[$key] = $data;
        }

        $arrUpdate = array('sub_data' => SC_Utils_Ex::jsonEncode($subData));
        $objQuery = SC_Query_Ex::getSingletonInstance();
        $objQuery->update('dtb_module', $arrUpdate, 'module_code = ?', array($this->getCode(true)));

        $this->subData = $subData;
    }

    function getUserSettings($key = null) {
        $subData = $this->getSubData();
        $returnData = null;

        if (is_null($key)) {
            $returnData = isset($subData['user_settings'])
                ? $subData['user_settings']
                : null;
        } else {
            $returnData = isset($subData['user_settings'][$key])
                ? $subData['user_settings'][$key]
                : null;
        }

        return $returnData;
    }

    function registerUserSettings($data) {
        $this->registerSubData($data, 'user_settings');
    }

    /**
     * ログを出力.
     *
     * @param string $msg
     * @param bool $raw
     */
    function printLog($msg, $raw = false) {
        require_once CLASS_EX_REALDIR . 'SC_Customer_Ex.php';
        $objCustomer = new SC_Customer_Ex();
        $userId = $objCustomer->getValue('customer_id');

        // パスワード等をマスクする
        if (!$raw && (is_array($msg) || is_object($msg))) {
            $msg = SC_Util_Mdl_YFCApi_Ex::setMaskData($msg);
            $msg = print_r($msg, true);
        }

        $path = DATA_REALDIR . 'logs/' . $this->getCode(true) . '_' .  date('Ymd') .  '.log';
        GC_Utils_Ex::gfPrintLog("user=$userId: " . $msg, $path);
    }

    /**
     * デバッグログを出力.
     *
     * @param string $msg
     * @param bool $raw
     */
    function printDebugLog($msg, $raw = false) {
        if (DEBUG_MODE === true) {
            $this->printLog($msg, $raw);
        }
    }

    /**
     * インストール処理
     *
     * @param boolean $force true時、上書き登録を行う
     */
    function install($force = false) {
        // カラムの更新
        $this->updateTable();

        $subData = $this->getSubData();
        if (is_null($subData) || $force) {
            $this->registerSubdata(
                $this->installSubData['master_settings'],
                'master_settings'
            );
        }
    }

    /**
     * カラムの更新を行う.
     *
     */
    function updateTable() {
        $objDB = new SC_Helper_DB_Ex();
        foreach ($this->updateTable as $table) {
            foreach($table['cols'] as $col) {
                $objDB->sfColumnExists(
                    $table['name'], $col['name'], $col['type'], "", $add = true
                );
            }
        }
    }

    /**
     * 3Dセキュアが有効かどうかを判定する
     * 管理画面設定で3Dセキュア認証を有効にしている
     * かつ ブラウザのUserAgentがモバイルでない場合にtrueを返す
     *
     * @return boolean
     */
    function isEnable3DSecure() {
        $is3DSecure = $this->getUserSettings('use3d');
        if ($is3DSecure && !SC_MobileUserAgent::isMobile()) {
            return true;
        }
        return false;
    }

    /**
     * 会員IDが有効かどうかを判定する
     *
     * @return boolean
     */
    function isEnableCustomerRegister() {
        $objCustomer = new SC_Customer_Ex();
        $loggedIn = $objCustomer->isLoginSuccess(true);
        $useCustomerRegist = $this->getUserSettings('useCustomerRegister');
        if ($loggedIn && $useCustomerRegist) {
            return true;
        }
        return false;
    }

    /**
     * ファイルをコピーする
     *
     * @param array $arrUpdateFile
     * @return void
     */
    function updateFile($arrUpdateFile) {
        $this->copyFiles($arrUpdateFile);
    }

    // 再帰的にパスを作成する。(php4にはrecursiveがない)
    // http://www.php.net/manual/ja/function.mkdir.php
    function mkdirp($pathname, $mode) {
        is_dir(dirname($pathname)) || $this->mkdirp(dirname($pathname), $mode);
        return is_dir($pathname) || mkdir($pathname, $mode);
    }

    function copyFiles($files) {
        $this->failedCopyFile = array();

        foreach($files as $file) {
            $dst_file = $file['dst'];
            $src_file = MDL_YFCAPI_PATH . 'copy/' . $file['src'];
            // ファイルがない、またはファイルはあるが異なる場合
            if(!file_exists($dst_file) || sha1_file($src_file) != sha1_file($dst_file)) {
                if(is_writable($dst_file) || is_writable(dirname($dst_file)) || $this->mkdirp(dirname($dst_file), 0777)) {
                    // backupを作成
                    if (file_exists($dst_file)) {
                        copy($dst_file, $dst_file . '.mdl_Mdl_YFCApi_paymentapi' . date(".YmdHis"));
                    }

                    if (!copy($src_file, $dst_file)) {
                        $this->failedCopyFile[] = $dst_file;
                    }
                } else {
                    $this->failedCopyFile[] = $dst_file;
                }
            }
        }
    }

    /**
     * コピーに失敗したファイルを取得する
     * 
     * @return array
     */
    function getFailedCopyFile() {
        return $this->failedCopyFile;
    }
}
