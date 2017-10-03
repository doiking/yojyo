<?php
/*
 * Tetra_SellRanking_Side
 * Copyright(c) 2015 TetraThemes All Rights Reserved.
 *
 * http://tetra-themes.net/
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

// {{{ requires
require_once PLUGIN_UPLOAD_REALDIR .  'Tetra_SellRanking_Side/LC_Page_Plugin_Tetra_SellRanking_Side_Config.php';

// }}}
// {{{ generate page
$objPage = new LC_Page_Plugin_Tetra_SellRanking_Side_Config();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
?>
