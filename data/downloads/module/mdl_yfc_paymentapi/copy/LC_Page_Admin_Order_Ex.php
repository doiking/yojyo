<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2013 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

require_once CLASS_REALDIR . 'pages/admin/order/LC_Page_Admin_Order.php';

/**
 * 受注管理 のページクラス(拡張).
 *
 * LC_Page_Admin_Order をカスタマイズする場合はこのクラスを編集する.
 *
 * @package Page
 * @author LOCKON CO.,LTD.
 * @version $Id: LC_Page_Admin_Order_Ex.php 22926 2013-06-29 16:24:23Z Seasoft $
 */
class LC_Page_Admin_Order_Ex extends LC_Page_Admin_Order
{
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init()
    {
        parent::init();
        //商品種別ID（検索用）
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrProductsType = $masterData->getMasterData('mtb_product_type');
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process()
    {
        parent::process();
    }

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    public function lfInitParam(&$objFormParam)
    {
        parent::lfInitParam($objFormParam);
        // 商品種別
        $objFormParam->addParam('商品種別', 'search_product_type_id', INT_LEN, 'n', array('MAX_LENGTH_CHECK', 'NUM_CHECK'));
    }

    /**
     * クエリを構築する.
     *
     * 検索条件のキーに応じた WHERE 句と, クエリパラメーターを構築する.
     * クエリパラメーターは, SC_FormParam の入力値から取得する.
     *
     * 構築内容は, 引数の $where 及び $arrValues にそれぞれ追加される.
     *
     * @param  string       $key          検索条件のキー
     * @param  string       $where        構築する WHERE 句
     * @param  array        $arrValues    構築するクエリパラメーター
     * @param  SC_FormParam $objFormParam SC_FormParam インスタンス
     * @return void
     */
    public function buildQuery($key, &$where, &$arrValues, &$objFormParam)
    {
        $dbFactory = SC_DB_DBFactory_Ex::getInstance();
        switch ($key) {
            case 'search_product_name':
                $where .= ' AND EXISTS (SELECT 1 FROM dtb_order_detail od WHERE od.order_id = dtb_order.order_id AND od.product_name LIKE ?)';
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_order_name':
                $where .= ' AND ' . $dbFactory->concatColumn(array('order_name01', 'order_name02')) . ' LIKE ?';
                $arrValues[] = sprintf('%%%s%%', preg_replace('/[ 　]/u', '', $objFormParam->getValue($key)));
                break;
            case 'search_order_kana':
                $where .= ' AND ' . $dbFactory->concatColumn(array('order_kana01', 'order_kana02')) . ' LIKE ?';
                $arrValues[] = sprintf('%%%s%%', preg_replace('/[ 　]/u', '', $objFormParam->getValue($key)));
                break;
            case 'search_order_id1':
                $where .= ' AND order_id >= ?';    //dtb_order AS oとするための対応
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_order_id2':
                $where .= ' AND order_id <= ?';    //dtb_order AS oとするための対応
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_order_sex':
                $tmp_where = '';
                foreach ($objFormParam->getValue($key) as $element) {
                    if ($element != '') {
                        if (SC_Utils_Ex::isBlank($tmp_where)) {
                            $tmp_where .= ' AND (order_sex = ?';
                        } else {
                            $tmp_where .= ' OR order_sex = ?';
                        }
                        $arrValues[] = $element;
                    }
                }

                if (!SC_Utils_Ex::isBlank($tmp_where)) {
                    $tmp_where .= ')';
                    $where .= " $tmp_where ";
                }
                break;
            case 'search_order_tel':
                $where .= ' AND (' . $dbFactory->concatColumn(array('order_tel01', 'order_tel02', 'order_tel03')) . ' LIKE ?)';
                $arrValues[] = SC_SelectSql_Ex::addSearchStr(preg_replace('/[()-]+/','', $objFormParam->getValue($key)));
                break;
            case 'search_order_email':
                $where .= ' AND order_email LIKE ?';
                $arrValues[] = sprintf('%%%s%%', $objFormParam->getValue($key));
                break;
            case 'search_payment_id':
                $tmp_where = '';
                foreach ($objFormParam->getValue($key) as $element) {
                    if ($element != '') {
                        if ($tmp_where == '') {
                            $tmp_where .= ' AND (payment_id = ?';
                        } else {
                            $tmp_where .= ' OR payment_id = ?';
                        }
                        $arrValues[] = $element;
                    }
                }

                if (!SC_Utils_Ex::isBlank($tmp_where)) {
                    $tmp_where .= ')';
                    $where .= " $tmp_where ";
                }
                break;
            case 'search_total1':
                $where .= ' AND total >= ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_total2':
                $where .= ' AND total <= ?';
                $arrValues[] = sprintf('%d', $objFormParam->getValue($key));
                break;
            case 'search_sorderyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_sorderyear'),
                                                    $objFormParam->getValue('search_sordermonth'),
                                                    $objFormParam->getValue('search_sorderday'));
                $where.= ' AND create_date >= ?';
                $arrValues[] = $date;
                break;
            case 'search_eorderyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_eorderyear'),
                                                    $objFormParam->getValue('search_eordermonth'),
                                                    $objFormParam->getValue('search_eorderday'), true);
                $where.= ' AND create_date <= ?';
                $arrValues[] = $date;
                break;
            case 'search_supdateyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_supdateyear'),
                                                    $objFormParam->getValue('search_supdatemonth'),
                                                    $objFormParam->getValue('search_supdateday'));
                $where.= ' AND update_date >= ?';
                $arrValues[] = $date;
                break;
            case 'search_eupdateyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_eupdateyear'),
                                                    $objFormParam->getValue('search_eupdatemonth'),
                                                    $objFormParam->getValue('search_eupdateday'), true);
                $where.= ' AND update_date <= ?';
                $arrValues[] = $date;
                break;
            case 'search_sbirthyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_sbirthyear'),
                                                    $objFormParam->getValue('search_sbirthmonth'),
                                                    $objFormParam->getValue('search_sbirthday'));
                $where.= ' AND order_birth >= ?';
                $arrValues[] = $date;
                break;
            case 'search_ebirthyear':
                $date = SC_Utils_Ex::sfGetTimestamp($objFormParam->getValue('search_ebirthyear'),
                                                    $objFormParam->getValue('search_ebirthmonth'),
                                                    $objFormParam->getValue('search_ebirthday'), true);
                $where.= ' AND order_birth <= ?';
                $arrValues[] = $date;
                break;
            case 'search_order_status':
                $where.= ' AND status = ?';
                $arrValues[] = $objFormParam->getValue($key);
                break;
            //商品種別ID
            case 'search_product_type_id':
                $tmp_where = '';
                foreach ($objFormParam->getValue($key) as $element) {
                    if ($element != '') {
                        if ($tmp_where == '') {
                            $tmp_where  = ' AND EXISTS (SELECT 1 FROM (';
                            $tmp_where .= 'SELECT odt.*, pc.product_type_id FROM dtb_order_detail AS odt INNER JOIN dtb_products_class AS pc ON odt.product_class_id = pc.product_class_id) WORK';
                            $tmp_where .= ' WHERE WORK.order_id = dtb_order.order_id';
                            $tmp_where .= ' AND (product_type_id = ?';
                        } else {
                            $tmp_where .= ' OR product_type_id = ?';
                        }
                        $arrValues[] = $element;
                    }
                }

                if (!SC_Utils_Ex::isBlank($tmp_where)) {
                    $tmp_where .= '))';
                    $where .= " $tmp_where ";
                }
                break;
            default:
                break;
        }
    }

    /**
     * 受注を検索する.
     *
     * @param  string  $where     検索条件の WHERE 句
     * @param  array   $arrValues 検索条件のパラメーター
     * @param  integer $limit     表示件数
     * @param  integer $offset    開始件数
     * @param  string  $order     検索結果の並び順
     * @return array   受注の検索結果
     */
    public function findOrders($where, $arrValues, $limit, $offset, $order)
    {
        //del_flgはdtb_orderに対応させる
        $where = preg_replace("/del_flg/", "dtb_order.del_flg", $where);
        
        //update_dateはdtb_orderに対応させる
        $order = preg_replace("/update_date/", "dtb_order.update_date", $order);
        
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        if ($limit != 0) {
            $objQuery->setLimitOffset($limit, $offset);
        }
        $objQuery->setOrder($order);

        //送り状番号登録状態を付与
        $col = '*,';
        $col .= 'CASE WHEN EXISTS (SELECT 1 FROM dtb_order i INNER JOIN dtb_shipping USING(order_id) WHERE i.order_id = dtb_order.order_id AND plg_yfcapi_deliv_slip IS NULL) THEN 0 ELSE 1 END AS plg_col_slip_on';
        return $objQuery->select($col, 'dtb_order', $where, $arrValues);
    }
}
