<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
// }}}
class SC_Util_Mdl_YFCApi_Cache {

    /**
     * キャッシュデータを取得する.
     * @param string $codename コード名
     * @return array コードデータ
     */
    public static function getCodeData($codename){
        $flag = true;
        $filepath = MDL_YFCAPI_CACHE_PATH . $codename . '.json';
        if (!file_exists($filepath)) {
            //キャッシュ生成
            $flag = SC_Util_Mdl_YFCApi_Cache_Ex::createCache($codename);
        }
        //コードファイルがキャッシュよりも新しい場合はキャッシュを更新
        if (SC_Util_Mdl_YFCApi_Cache_Ex::isUpdate($codename)) {
            //キャッシュ生成
            $flag = SC_Util_Mdl_YFCApi_Cache_Ex::createCache($codename);
        }
        if(!$flag){
            //決済モジュール基本クラス取得
            $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
            $objMdl->printLog("キャッシュ生成失敗：{$codename}");
            return array();
        }
        // キャッシュを読み込み
        $cacheData = SC_Util_Mdl_YFCApi_Ex::jsonDecode2Array(file_get_contents($filepath));
        return $cacheData;
    }
    /**
     * コード値を取得する.
     * @param string $codename コード名
     * @param string $code コード名
     * @return string コードデータ
     */
    public static function getCode($codename, $code){
        $arrData = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData($codename);
        return $arrData[$code];
    }
    /**
     * マスターデータのキャッシュを生成する.
     * @param string $codename コード名
     * @return bool キャッシュの生成に成功した場合 true
     */
    protected function createCache($codename){
        // TSVデータを取得
        $arrData = SC_Util_Mdl_YFCApi_Cache_Ex::getTsvData($codename);
        // キャッシュ名設定
        $path = MDL_YFCAPI_CACHE_PATH . $codename . '.json';
        // データをJSONエンコードする
        $data = SC_Utils_Ex::jsonEncode($arrData);
        // ファイルを書き出しモードで開く
        $handle = fopen($path, 'w');
        if (!$handle) {
            return false;
        }
        // ファイルの内容を書き出す.
        if (fwrite($handle, $data) === false) {
            fclose($handle);
            return false;
        }
        fclose($handle);
        return true;
    }

    /**
     * TSVデータ取得
     * @param string $codename コード名
     * @return array $arrCode コードリスト
     */
    protected function getTsvData($codename){
        $arrCode = array();
        $filepath = MDL_YFCAPI_CODE_PATH . $codename. '.txt';
        if(!file_exists($filepath)){
            //決済モジュール基本クラス取得
            $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
            $objMdl->printLog("TSVファイルが存在しません。：{$codename}");
            return array();
        }
        // TSVデータを取得
        $text = file_get_contents($filepath);
        // 改行コードを統一
        $text = preg_replace("/\r\n|\r|\n/", "\n", $text);
        $arrText = explode("\n", $text);
        foreach ($arrText as $line) {
            $arrLine = explode("\t", $line);
            $arrCode[$arrLine[0]] = $arrLine[1];
        }
        return $arrCode;
    }

    /**
     * コードファイルの更新確認
     *
     * @param string $codename コード名
     * @return bool TSVファイルのタイムスタンプが新しい場合 true
     */
    protected function isUpdate($codename){
        $filetimeTsv = @filemtime( MDL_YFCAPI_CODE_PATH . $codename. '.txt');
        $filetimeCache = @filemtime( MDL_YFCAPI_CACHE_PATH . $codename. '.json');
        return ($filetimeTsv > $filetimeCache)? true : false;
    }
}

