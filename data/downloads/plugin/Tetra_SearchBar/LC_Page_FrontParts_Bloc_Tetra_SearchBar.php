<?php
/*
 * Tetra_SearchBar
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

class LC_Page_FrontParts_Bloc_Tetra_SearchBar extends LC_Page_FrontParts_Bloc {

    /**
     * Page を初期化する.
     *
     * @return void
     */
    public function init()
    {
        parent::init();
        $masterData = new SC_DB_MasterData_Ex();
        $this->arrProductType = $masterData->getMasterData('mtb_product_type'); //商品種類を取得
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

        // 商品ID取得
        $product_id = $this -> lfGetProductId();
        // カテゴリID取得
        $category_id = $this -> lfGetCategoryId();
        // メーカーID取得
        $maker_id = $this -> lfGetMakerId();
        // 選択中のカテゴリIDを判定する
        $this->category_id = $this->lfGetSelectedCategoryId($product_id, $category_id);
        // カテゴリ検索用選択リスト
        $this->arrCatList = $this->lfGetCategoryList();
        // 選択中のメーカーIDを判定する
        $this->maker_id = $this->lfGetSelectedMakerId($product_id, $maker_id);
        // メーカー検索用選択リスト
        $this->arrMakerList = $this->lfGetMakerList();

        // cart
        $objCart = new SC_CartSession_Ex();
        $this->isMultiple = $objCart->isMultiple();
        $this->hasDownload = $objCart->hasProductType(PRODUCT_TYPE_DOWNLOAD);
        // 旧仕様との互換のため、不自然なセットとなっている
        $this->arrCartList = array(0 => $this->lfGetCartData($objCart));

        // echo '<pre>';
        // var_dump($this->arrSearchBar);
        // echo '</pre>';
    }

    /**
     * カートの情報を取得する
     *
     * @param  SC_CartSession $objCart カートセッション管理クラス
     * @return array          カートデータ配列
     */
    public function lfGetCartData(&$objCart)
    {
        $arrCartKeys = $objCart->getKeys();
        foreach ($arrCartKeys as $cart_key) {
            // 購入金額合計
            $products_total += $objCart->getAllProductsTotal($cart_key);
            // 合計数量
            $total_quantity += $objCart->getTotalQuantity($cart_key);

            // 送料無料チェック
            if (!$this->isMultiple && !$this->hasDownload) {
                $is_deliv_free = $objCart->isDelivFree($cart_key);
            }
        }

        $arrCartList = array();

        $arrCartList['ProductsTotal'] = $products_total;
        $arrCartList['TotalQuantity'] = $total_quantity;

        // 店舗情報の取得
        $arrInfo = SC_Helper_DB_Ex::sfGetBasisData();
        $arrCartList['free_rule'] = $arrInfo['free_rule'];

        // 送料無料までの金額
        if ($is_deliv_free) {
            $arrCartList['deliv_free'] = 0;
        } else {
            $deliv_free = $arrInfo['free_rule'] - $products_total;
            $arrCartList['deliv_free'] = $deliv_free;
        }

        return $arrCartList;
    }

    /**
     * 商品IDを取得する.
     *
     * @return string $product_id 商品ID
     */
    public function lfGetProductId()
    {
        $product_id = '';
        if (isset($_GET['product_id']) && $_GET['product_id'] != '' && is_numeric($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
        }

        return $product_id;
    }

    /**
     * カテゴリIDを取得する.
     *
     * @return string $category_id カテゴリID
     */
    public function lfGetCategoryId()
    {
        $category_id = '';
        if (isset($_GET['category_id']) && $_GET['category_id'] != '' && is_numeric($_GET['category_id'])) {
            $category_id = $_GET['category_id'];
        }

        return $category_id;
    }

    /**
     * メーカーIDを取得する.
     *
     * @return string $maker_id メーカーID
     */
    public function lfGetMakerId()
    {
        $maker_id = '';
        if (isset($_GET['maker_id']) && $_GET['maker_id'] != '' && is_numeric($_GET['maker_id'])) {
            $maker_id = $_GET['maker_id'];
        }

        return $maker_id;
    }

    /**
     * 選択中のカテゴリIDを取得する
     *
     * @param string $product_id
     * @param string $category_id
     * @return array $arrCategoryId 選択中のカテゴリID
     */
    public function lfGetSelectedCategoryId($product_id, $category_id)
    {
        // 選択中のカテゴリIDを判定する
        $objDb = new SC_Helper_DB_Ex();
        $arrCategoryId = $objDb->sfGetCategoryId($product_id, $category_id);

        return $arrCategoryId;
    }

    /**
     * 選択中のメーカーIDを取得する
     *
     * @param string $product_id
     * @param string $maker_id
     * @return array $arrMakerId 選択中のメーカーID
     */
    public function lfGetSelectedMakerId($product_id, $maker_id)
    {
        // 選択中のメーカーIDを判定する
        $objDb = new SC_Helper_DB_Ex();
        $arrMakerId = $objDb->sfGetMakerId($product_id, $maker_id);

        return $arrMakerId;
    }

    /**
     * カテゴリ検索用選択リストを取得する
     *
     * @return array $arrCategoryList カテゴリ検索用選択リスト
     */
    public function lfGetCategoryList()
    {
        $objDb = new SC_Helper_DB_Ex();
        // カテゴリ検索用選択リスト
        $arrCategoryList = $objDb->sfGetCategoryList('', true, '　');
        if (is_array($arrCategoryList)) {
            // 文字サイズを制限する
            foreach ($arrCategoryList as $key => $val) {
                $truncate_str = SC_Utils_Ex::sfCutString($val, SEARCH_CATEGORY_LEN, false);
                $arrCategoryList[$key] = preg_replace('/　/u', '&nbsp;&nbsp;', $truncate_str);
            }
        }

        return $arrCategoryList;
    }

    /**
     * メーカー検索用選択リストを取得する
     *
     * @return array $arrMakerList メーカー検索用選択リスト
     */
    public function lfGetMakerList()
    {
        $objDb = new SC_Helper_DB_Ex();
        // メーカー検索用選択リスト
        $arrMakerList = $objDb->sfGetMakerList('', true);
        if (is_array($arrMakerList)) {
            // 文字サイズを制限する
            foreach ($arrMakerList as $key => $val) {
                $arrMakerList[$key] = SC_Utils_Ex::sfCutString($val, SEARCH_CATEGORY_LEN, false);
            }
        }

        return $arrMakerList;
    }
}
?>
