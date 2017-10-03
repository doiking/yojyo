<?php
/*
 * Tetra_NewProducts_Side
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

require_once CLASS_REALDIR . 'pages/frontparts/bloc/LC_Page_FrontParts_Bloc.php';

class LC_Page_FrontParts_Bloc_Tetra_NewProducts_Side extends LC_Page_FrontParts_Bloc {

    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    public function process()
    {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    public function action()
    {

        //新着商品情報
        $this->arrNewProducts = $this->GetNewProducts();

        //商品レビュー情報を取得
        $this->arrReviewList = $this->getReviewList();

        //商品ステータスを取得
        $this->arrProductStatus = $this->getProductStatus();

        // メーカー情報を取得
        $this->arrMakerList = $this->getMakerList();

        // プラグイン情報を取得
        $this->arrPlugin = $this->getPluginData();


    }

    /**
     * 商品検索.
     *
     * @return array $arrNewProducts 検索結果配列
     */
    public function GetNewProducts()
    {

        //商品データ取得とリミットの設定
        $arrNewProductList = $this -> getList();

        $response = array();
        if (count($arrNewProductList) > 0) {
            // 商品一覧を取得
            $objQuery =& SC_Query_Ex::getSingletonInstance();
            $objProduct = new SC_Product_Ex();
            // where条件生成&セット
            $arrProductId = array();

            foreach ($arrNewProductList as $key => $val) {
                $arrProductId[] = $val['product_id'];
            }

            $arrProducts = $objProduct->getListByProductIds($objQuery, $arrProductId);

            // 税込金額を設定する
            SC_Product_Ex::setIncTaxToProducts($arrProducts);

            // 新着商品情報にマージ
            foreach ($arrNewProductList as $key => $value) {
                if (isset($arrProducts[$value['product_id']])) {
                    $product = $arrProducts[$value['product_id']];
                    if ($product['status'] == 1 && (!NOSTOCK_HIDDEN || ($product['stock_max'] >= 1 || $product['stock_unlimited_max'] == 1))) {
                        $response[] = array_merge($value, $arrProducts[$value['product_id']]);
                    }
                } else {
                    // 削除済み商品は除外
                    unset($arrNewProductList[$key]);
                }
            }
        }

        return $response;
    }


    /**
     * 商品情報を取得
     *
     * @return array $arrNewProducts 検索結果配列
     */
    public function getList($dispNumber = 0, $pageNumber = 0, $has_deleted = false)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = '*';
        $where = '';
        if (!$has_deleted) {
            $where .= 'del_flg = 0';
        }
        $table = 'dtb_products';
        $objQuery->setOrder('create_date DESC');

        // プラグインの設定情報を取得
        $arrPlugin = array();
        $arrPlugin = SC_Plugin_Util_Ex::getPluginByPluginCode("Tetra_NewProducts_Side");
        $dispNumber = $arrPlugin['free_field2']; 


        if ($dispNumber > 0) {
            if ($pageNumber > 0) {
                $objQuery->setLimitOffset($dispNumber, (($pageNumber - 1) * $dispNumber));
            } else {
                $objQuery->setLimit($dispNumber);
            }
        }
        $arrRet = $objQuery->select($col, $table, $where);

        return $arrRet;
    }

    /**
     * 商品のレビューと商品情報を結合した情報を取得
     * 商品ごとに集計、小数点以下切り捨て
     *
     * @return array $arrReviewList 検索結果配列
     */
    public function getReviewList($dispNumber = 0, $pageNumber = 0, $has_deleted = false)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = 'product_id,TRUNC(AVG(recommend_level),0) AS recommend_level,COUNT(*) AS recommend_count';
        $table = 'dtb_review';
        $where = '';
        $objQuery->setGroupBy('product_id');
        $objQuery->setOrder('product_id DESC');

        if ($dispNumber > 0) {
            if ($pageNumber > 0) {
                $objQuery->setLimitOffset($dispNumber, (($pageNumber - 1) * $dispNumber));
            } else {
                $objQuery->setLimit($dispNumber);
            }
        }
        $arrRet = $objQuery->select($col, $table, $where);

        // データの中身の表示テスト
        // echo "<pre>";
        // var_dump($arrRet);
        // echo "</pre>";

        return $arrRet;
    }

    /**
     * 商品ステータスIDの配列を取得する.
     *
     * @return array 商品IDごとのステータス一覧
     */
    public function getProductStatus()
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $cols = 'product_id, product_status_id';
        $from = 'dtb_product_status';
        $where = 'del_flg = 0';
        $productStatus = $objQuery->select($cols, $from, $where);
        $results = array();
        foreach ($productStatus as $status) {
            $results[$status['product_id']][] = $status['product_status_id'];
        }

        return $productStatus;
    }

    /**
     * メーカー情報の取得
     *
     * @return array $arrMakerList 検索結果配列
     */
    public function getMakerList($dispNumber = 0, $pageNumber = 0, $has_deleted = false)
    {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = '*';
        $table = 'dtb_maker';
        $where = '';

        if ($dispNumber > 0) {
            if ($pageNumber > 0) {
                $objQuery->setLimitOffset($dispNumber, (($pageNumber - 1) * $dispNumber));
            } else {
                $objQuery->setLimit($dispNumber);
            }
        }
        $arrRet = $objQuery->select($col, $table, $where);

        return $arrRet;
    }

    /**
     * プラグインDBからプラグイン情報取得
     *
     * @return $arrPlugin プラグイン設定から取得した情報
     */
    public function getPluginData()
    {
        // プラグインの設定情報を取得
        $arrPlugin = SC_Plugin_Util_Ex::getPluginByPluginCode("Tetra_NewProducts_Side");

        return $arrPlugin;
    }
}
?>
