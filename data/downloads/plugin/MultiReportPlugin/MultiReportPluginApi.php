<?php
/*
*
* MultiReportPlugin
* Copyright(c) 2014 Cyber-Will Inc. All Rights Reserved.
*
* http://www.cyber-will.co.jp/
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

require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * MultiReportPluginAPI のページクラス.
 *
 * @package Page
 * @author Cyber-Will Inc.
 */
class MultiReportPluginApi extends LC_Page_Admin_Ex
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {

    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        $this->action();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_GET);
        $objFormParam->convParam();
        $arrErr = $objFormParam->checkError();
        if (count($arrErr) == 0) {
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $col = "*";
            $from = "plg_dtb_report";
            $where = "id = ?";
            $arrWhereVal = array($_GET['id']);
            $value = $objQuery->select($col, $from, $where, $arrWhereVal);
            if ($value) {
                echo json_encode($value);
            } else {
                echo "id_not_found_error";
            }
        } else {
            echo 'request_error';
        }
        exit;
    }

    /**
     * パラメーター情報の初期化
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam('id', 'id', INT_LEN, '', array('EXIST_CHECK', 'MAX_LENGTH_CHECK'));
    }

}
