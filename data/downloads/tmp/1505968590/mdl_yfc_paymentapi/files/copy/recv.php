<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once('../../../require.php');
require_once(MODULE_REALDIR . 'mdl_yfc_paymentapi/inc/include.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . "page_extends/LC_Page_Mdl_YFCApi_Recv_Ex.php");
// }}}
$objPage = new LC_Page_Mdl_YFCApi_Recv_Ex();
$objPage->init();
$objPage->process();