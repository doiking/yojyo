<?php
/**
 * プラグイン の情報クラス.
 */
class plugin_info{
    /** プラグインコード(必須)：プラグインを識別する為キーで、他のプラグインと重複しない一意な値である必要がありま. */
    static $PLUGIN_CODE       = "YfcApiCore";
    /** プラグイン名(必須)：EC-CUBE上で表示されるプラグイン名. */
    static $PLUGIN_NAME       = "クロネコヤマト　カード・後払い一体型決済コア機能プラグイン";
    /** クラス名(必須)：プラグインのクラス（拡張子は含まない） */
    static $CLASS_NAME        = "YfcApiCore";
    /** プラグインバージョン(必須)：プラグインのバージョン. */
    static $PLUGIN_VERSION    = "1.0";
    /** 対応バージョン(必須)：対応するEC-CUBEバージョン. */
    static $COMPLIANT_VERSION = "2.13.0";
    /** 作者(必須)：プラグイン作者. */
    static $AUTHOR            = "ヤマトフィナンシャル株式会社";
    /** 説明(必須)：プラグインの説明. */
    static $DESCRIPTION       = "クロネコヤマト　カード・後払い一体型決済モジュールの動作に必要なプラグインです。決済を利用する場合に、本プラグインの無効化や削除はしないで下さい。";
    /** プラグインURL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $PLUGIN_SITE_URL   = "https://www.yamatofinancial.jp/";
    /** プラグイン作者URL：プラグイン毎に設定出来るURL（説明ページなど） */
    static $AUTHOR_SITE_URL   = "https://www.yamatofinancial.jp/";
    /** フックポイント：フックポイントとコールバック関数を定義します */
    static $HOOK_POINTS       = array(
        array("LC_Page_Admin_Order_Edit_action_before", 'hookActionBefore'),
        array("LC_Page_Admin_Order_Edit_action_after", 'hookActionAfter'),
        array("LC_Page_Admin_Basis_PaymentInput_action_before", 'hookActionBefore'),
        array("LC_Page_Admin_Basis_PaymentInput_action_after", 'hookActionAfter'),
        array("LC_Page_Admin_Order_action_before", 'hookActionBefore'),
        array("LC_Page_Shopping_Complete_action_before", 'hookActionBefore'),
        array("prefilterTransform", 'prefilterTransform'),
        array("SC_FormParam_construct", 'addParam')
    );
}
