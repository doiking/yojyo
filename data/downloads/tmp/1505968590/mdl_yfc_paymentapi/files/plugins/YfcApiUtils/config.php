<?php
// {{{ requires
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'page_extends/LC_Page_Admin_Plugin_YfcApiUtils_Config_Ex.php');

// }}}
// {{{ generate page
$objPage = new LC_Page_Admin_Plugin_YfcApiUtils_Config_Ex();

$objPage->init();
$objPage->process();
