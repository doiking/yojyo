
<?php
    switch($arrPageLayout['device_type_id']){
        case 1:
            break;
        case 2:
            break;
        case 10:
            break;
        default:
            switch($_SERVER['PHP_SELF']){
                case ROOT_URLPATH . ADMIN_DIR . 'basis/tradelaw.php':

                    $plugin_htmldir = defined("PLUGIN_HTML_URLPATH") ? PLUGIN_HTML_URLPATH : ROOT_URLPATH . "plugin/";

                    if ($this->get_template_vars("eccube_gte_2_13_3") === true) {
                        // jquery-uiは 2.13.3 以降のバージョンには存在しないので自前のを使う
                        echo('<script type="text/javascript" charset="utf-8" src="' . $plugin_htmldir . 'CustomTradelaw/common/js/jquery-ui.min.js"></script>');
                    } else {
                        // 2.13.3 より前のバージョンには存在するのでそのまま使う
                        echo('<script type="text/javascript" charset="utf-8" src="' . ROOT_URLPATH . 'js/ui.core.js"></script>');
                        echo('<script type="text/javascript" charset="utf-8" src="' . $plugin_htmldir . 'CustomTradelaw/common/js/jquery.ui.widget.min.js"></script>');
                        echo('<script type="text/javascript" charset="utf-8" src="' . $plugin_htmldir . 'CustomTradelaw/common/js/jquery.ui.mouse.min.js"></script>');
                        echo('<script type="text/javascript" charset="utf-8" src="' . ROOT_URLPATH . 'js/ui.sortable.js"></script>');
                    }
                    echo('<script type="text/javascript" charset="utf-8" src="' . $plugin_htmldir . 'CustomTradelaw/common/js/plg_CustomTradelaw_jquery.js"></script>');
                    echo('<script type="text/javascript">var plg_customtradelaw_stextlen = ' . $this->get_template_vars('plg_customtradelaw_stextlen') . '; var plg_customtradelaw_mtextlen = ' . $this->get_template_vars('plg_customtradelaw_mtextlen') . ';</script>');
                    break;
                default:
                    break;
            }
            break;
    }
?>
