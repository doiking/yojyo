<!--{*
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
 *}-->
<!--{strip}-->
<div class="block_outer">
  <div class="plg_Tetra_SearchBar">
    <div class="ui-nav nav-search navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#global-nav">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <span class="navbar-brand visible-xs">商品検索</span>
        </div>
        <div class="collapse navbar-collapse" id="global-nav">
          <form class="navbar-form" role="search" method="get" action="<!--{$smarty.const.ROOT_URLPATH}-->products/list.php">
            <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
            <input type="hidden" name="mode" value="search" />
            <select name="category_id" class="form-control">
                <option label="全ての商品" value="">全ての商品</option>
                <!--{html_options options=$arrCatList selected=$category_id}-->
            </select>
            <div class="input-group">
              <input type="text" name="name" class="form-control" maxlength="50" placeholder="商品名、カテゴリー名などで検索してください" value="<!--{$smarty.get.name|h}-->" />
              <span class="input-group-btn"><button type="submit" name="search" class="btn btn-default" /><i class="fa fa-search fa-fw"></i>検索</button></span>
            </div>
            <ul class="ui-list list-cart list-inline pull-right hidden-sm hidden-xs">
              <li>数量：<span class="num"><!--{$arrCartList.0.TotalQuantity|n2s|default:0}--></span></li>
              <li><a href="<!--{$smarty.const.CART_URL}-->" class="btn btn-styled btn-cart btn-sm">カートを見る</a></li>
            </ul>
            <ul class="list-inline visible-xs padding-row margin-none link-reverse">
              <li><a href="<!--{$smarty.const.CART_URL}-->"><i class="fa fa-shopping-cart fa-fw"></i>カートを見る</a></li>
            </ul>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!--{/strip}-->
