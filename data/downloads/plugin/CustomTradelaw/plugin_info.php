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


class plugin_info{
    static $PLUGIN_CODE         = "CustomTradelaw";             // プラグインコード
    static $PLUGIN_NAME         = "特定商取引法追加・並び替え"; // プラグイン名
    static $PLUGIN_VERSION      = "1.0.3";                      // プラグインバージョン
    static $COMPLIANT_VERSION   = "2.12.0～2.13.3";             // 対応バージョン
    static $AUTHOR              = "株式会社サンアットマーク";   // プラグイン作者
    static $DESCRIPTION         = "特定商取引法に基づく表記に自由に項目を追加できます。また、並べ替えや非表示設定もできます。"; // プラグインの説明
    static $PLUGIN_SITE_URL     = "http://www.sunatmark.co.jp/";// プラグインURL
    static $AUTHOR_SITE_URL     = "http://www.sunatmark.co.jp/";// プラグイン作者URL
    static $CLASS_NAME          = "CustomTradelaw";             // プラグインクラス名
    static $HOOK_POINTS         = array(                        // フックポイントとコールバック関数
        array("prefilterTransform", 'prefilterTransform'),
        array("SC_FormParam_construct", "formParamConstruct"),
        array("LC_Page_Admin_Basis_Tradelaw_action_after", 'tradelawActionAfter'),
        array("LC_Page_Order_action_after", 'orderActionAfter')
    );
    static $LICENSE             = "LGPL";                       // ライセンス
}

?>
