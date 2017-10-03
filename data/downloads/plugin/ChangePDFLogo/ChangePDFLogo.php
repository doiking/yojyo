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


/*
 * カテゴリ毎にコンテンツを設定する事ができます。
 */

class ChangePDFLogo extends SC_Plugin_Base {
    /**
     * コンストラクタ
     * プラグイン情報(dtb_plugin)をメンバ変数をセットします.
     * @param array $arrSelfInfo dtb_pluginの情報配列
     * @return void
     */
    public function __construct(array $arrSelfInfo) {
        parent::__construct($arrSelfInfo);
    }

    /**
     * インストール時に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function install($arrPlugin) {
        // ロゴファイルをhtmlディレクトリにコピーします.
        copy(PLUGIN_UPLOAD_REALDIR . $arrPlugin['plugin_code'] . "/logo.png", PLUGIN_HTML_REALDIR . $arrPlugin['plugin_code'] . "/logo.png");

        // デフォルトの納品書ロゴを、戻せるように一旦コピー
        if (copy(TEMPLATE_ADMIN_REALDIR . "pdf/logo.png", PLUGIN_HTML_REALDIR . "ChangePDFLogo/orig_logo.png") === false) {
            SC_Utils_Ex::sfDispSiteError(FREE_ERROR_MSG, '', false, PLUGIN_HTML_REALDIR.' に書き込めません。パーミッションをご確認ください。');
        }
    }

    /**
     * 削除時に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function uninstall($arrPlugin) {
        // ロゴファイルを元に戻す
        SC_Helper_FileManager_Ex::deleteFile(PLUGIN_HTML_REALDIR . "ChangePDFLogo/pdf_logo.png");

        // デフォルトの納品書ロゴに戻す（念入り）
        copy(PLUGIN_HTML_REALDIR . "ChangePDFLogo/orig_logo.png", TEMPLATE_ADMIN_REALDIR . "pdf/logo.png");
    }

    /**
     * 有効にした際に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function enable($arrPlugin) {
        // nop
    }

    /**
     * 無効にした際に実行される処理を記述します.
     * @param array $arrPlugin dtb_pluginの情報配列
     * @return void
     */
    function disable($arrPlugin) {
        // デフォルトの納品書ロゴに戻す
        copy(PLUGIN_HTML_REALDIR . "ChangePDFLogo/orig_logo.png", TEMPLATE_ADMIN_REALDIR . "pdf/logo.png");
    }


    /**
     * フックポイント: LC_Page_Admin_Basis_action_before
     *
     *
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */

    function changePDFLogoBefore($objPage) {

        // POSTデータの引き継ぎ
        $this->POSTCopy = $_POST;

        // 必要な変数を取得
        $objPage->logoKey = "pdf_logo";
        $path_to_temp = PLUGIN_HTML_REALDIR . "ChangePDFLogo/pdf_logo_temp.png";

        // modeが指定されているとき。
        if(isset($_POST['mode']) && !empty($_POST["mode"])) {

            switch ($_POST['mode']) {
                case 'reset_logo':  // 初期化

                    // tempロゴ画像を削除
                    if (file_exists($path_to_temp)) {
                        unlink($path_to_temp);
                    }

                    // デフォルトの納品書ロゴを対象に上書き
                    copy(PLUGIN_HTML_REALDIR . "ChangePDFLogo/orig_logo.png", TEMPLATE_ADMIN_REALDIR . "pdf/logo.png");

                    // 他の箇所にエラー文字が出ないようにする
                    $_POST = null;
                    break;
                case 'upload_logo':
                    $this->uploadImage($objPage, "pdf_logo");
                    // 画像のアップロード
                    $_POST = null;
                    break;
                default:
                    // その他。
                    break;
            }
        }
        // 納品書ロゴのtempへのパスを取得
        // # 存在しない場合は、作る
        if (!file_exists($path_to_temp)) {
            copy(TEMPLATE_ADMIN_REALDIR . "pdf/logo.png", PLUGIN_HTML_REALDIR . "ChangePDFLogo/pdf_logo_temp.png");
        }

        $objPage->path_to_PDF_logo = ROOT_URLPATH . 'plugin/' . "ChangePDFLogo/pdf_logo_temp.png";
    }

    /**
     * フックポイント: LC_Page_Admin_Basis_action_after
     * フォームに入っていた値を格納（$_POSTはnullにしてしまっているので）
     *
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @return void
     */
    function ChangePDFLogoAfter($objPage) {

        // modeが指定されているとき、フォームに値を入れ直す
        if(isset($this->POSTCopy['mode']) && !empty($this->POSTCopy["mode"])) {

            switch ($this->POSTCopy['mode']) {
                case 'reset_logo':  // 初期化
                case 'upload_logo':

                    $objFormParam = new SC_FormParam_Ex();
                    $objPage->lfInitParam($objFormParam, $this->POSTCopy);
                    $objFormParam->setParam($this->POSTCopy);
                    $objFormParam->convParam();

                    $post = $objFormParam->getHashArray();

                    $objPage->arrForm = $post;

                    break;

                case 'insert':
                case 'update':
                    if (count($objPage->arrErr) == 0) {
                        // 納品書ロゴを、tempのもので上書き
                        copy(PLUGIN_HTML_REALDIR . "ChangePDFLogo/pdf_logo_temp.png", TEMPLATE_ADMIN_REALDIR . "pdf/logo.png");
                    }
                    break;
                default:
                    // その他。
                    break;
            }
        }
    }

    /**
     * prefilterコールバック関数
     * テンプレートの変更処理を行います.
     *
     * @param string &$source テンプレートのHTMLソース
     * @param LC_Page_Ex $objPage ページオブジェクト
     * @param string $filename テンプレートのファイル名
     * @return void
     */
    function prefilterTransform(&$source, LC_Page_Ex $objPage, $filename) {
        // SC_Helper_Transformのインスタンスを生成.
        $objTransform = new SC_Helper_Transform($source);
        // 呼び出し元テンプレートを判定します.
        switch($objPage->arrPageLayout['device_type_id']){
            case DEVICE_TYPE_MOBILE:        // モバイル
            case DEVICE_TYPE_SMARTPHONE:    // スマホ
            case DEVICE_TYPE_PC:            // PC
                break;
            case DEVICE_TYPE_ADMIN:         // 管理画面
            default:

                // SHOPマスタ編集画面
                if (strpos($filename, 'basis/index.tpl') !== false) {
                    // divタグのclass=btn-area要素をプラグイン側で用意したテンプレートと置き換えます.
                    $template_dir = PLUGIN_UPLOAD_REALDIR . $this->arrSelfInfo['plugin_code'] . '/templates/';
                    $objTransform->select('div.btn-area')->insertBefore(file_get_contents($template_dir . 'logoPDFChange_admin_basis_index.tpl'));

                } else {
                    // 違うページに行く際、一時保存している画像を消す

                     $path_to_temp = PLUGIN_HTML_REALDIR . "ChangePDFLogo/pdf_logo_temp.png";

                    // tempロゴ画像を削除
                    if (file_exists($path_to_temp)) {
                        unlink($path_to_temp);
                    }
                }
                break;
        }

        // 変更を実行します
        $source = $objTransform->getHTML();
        // formタグに、画像アップ用のパラメータを追加
        $source = str_replace('<form name="form1"'
                            , '<form name="form1" enctype="multipart/form-data"'
                            , $source);

    }

    /**
     * アップロードファイルパラメーター情報の初期化
     * - 画像ファイル用
     *
     * @param object $objUpFile SC_UploadFileインスタンス
     * @return void
     */

    function uploadImage($objPage, $image_key) {
        //保存フォルダ
        $save_path = PLUGIN_HTML_REALDIR . "ChangePDFLogo/";
        $save_name = "pdf_logo_temp"; // 一時ファイル
        $enc_disp = "UTF-8";
        $enc_file = "UTF-8";

        //ファイルがアップロードされたら処理
        if (is_uploaded_file(@$_FILES[$image_key]["tmp_name"])) {
            //ファイルサイズチェック
            if($_FILES[$image_key]["size"] > 1024*1024){//1024=1KB
                $objPage->pdfLogoErr = "※ファイルサイズは1MBまでです";
                return;
            }
            $file_name = $save_path.$_FILES[$image_key]["name"];
            //拡張子取得
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            //拡張子チェック
            // if($extension == "jpg" or $extension == "jpeg" or $extension == "gif" or $extension == "png") {
            // MEMO: 今はpngだけ…
            if($extension == "png") {
                //ファイル名リネイム
                $file_name = $save_path . $save_name . "." . $extension;

                //ファイルの文字コード変換
                $det_enc = mb_detect_encoding($string, $enc_disp . ", ".$enc_file);
                if ($det_enc and $det_enc != $enc_file) {
                    $file_name = mb_convert_encoding($file_name, $enc_file, $det_enc);
                }
                // 保存ディレクトリにコピー
                copy($_FILES[$image_key]["tmp_name"], $file_name);
            }
            else {
                $objPage->pdfLogoErr = "※現在、png形式にのみ対応しています";
                return;
            }
        }

    }

}

?>
