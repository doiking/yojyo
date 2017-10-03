<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */

// {{{ requires
// }}}
/**
 * 決済モジュール 決済画面ヘルパー：ベース
 */
class LC_PageHelper_Mdl_YFCApi_Base {

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param array $arrPaymentInfo 決済設定情報
     * @param array $arrOrder 受注情報
     * @return void
     */
    function initParam(&$objFormParam, $arrPaymentInfo, &$arrOrder) {
    }

    /**
     * 入力内容のチェックを行なう.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @return array 入力チェック結果の配列
     */
    function checkError(&$objFormParam) {
        $arrParam = $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrParam);
        $objErr->arrErr = $objFormParam->checkError();

        return $objErr->arrErr;
    }


    /**
     * 画面モード毎のアクションを行う
     *
     * @param string $mode Mode値
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param array $arrOrder 受注情報
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @return void
     */
    function modeAction($mode, &$objFormParam, &$arrOrder, &$objPage) {
    }

    /**
     * 画面に設定するテンプレート名を返す
     *
     * @return string テンプレートファイル名
     */
    function getTemplate() {
        return "";
    }

    /**
     * 画面に設定するフォーム用ブロック名を返す
     *
     * @return string テンプレートブロック名
     */
    function getFormBloc() {
        return "";
    }
}
