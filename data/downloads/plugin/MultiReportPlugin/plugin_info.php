<?php

class plugin_info{
    static $PLUGIN_CODE       = "MultiReportPlugin";
    static $PLUGIN_NAME       = "複数帳票プラグイン";
    static $CLASS_NAME        = "MultiReportPlugin";
    static $PLUGIN_VERSION    = "1.0.0";
    static $COMPLIANT_VERSION = "2.12,2.13";
    static $AUTHOR            = "株式会社サイバーウィル";
    static $DESCRIPTION       = "複数の帳票を管理できるようになるプラグインです。";
    static $PLUGIN_SITE_URL   = "";
    static $AUTHOR_SITE_URL   = "";
    static $HOOK_POINTS       =  array('LC_Page_Admin_Order_Pdf_action_after');
    static $LICENSE           = "LGPL";
}

