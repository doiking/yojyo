<!--{*
/*
 * Tetra_NewProducts
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
<!--{if count($arrNewProducts) > 0}-->
<div class="clearfix"></div>
<div class="block_outer ui-section margin-section">
  <div id="plg_Tetra_NewProducts_area">
    <div class="ui-headline section-title">
      <h4 class="title"><!--{$arrPlugin.free_field1}--></h4>
    </div>
    <div class="ui-section-body">
      <div class="row">
        <!--{*
          新着商品取得数はデフォルトで8まで取得、変更する場合はプラグイン設定から行う
        *}-->
        <!--{foreach from=$arrNewProducts item=arrProduct key="key" name="new_products"}-->
        <div class="col-xs-6 col-sm-3">
          <div class="ui-box item-card">

            <div class="box-image">
              <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->">
                  <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct.main_list_image|sfNoImageMainList|h}-->" alt="<!--{$arrProduct.name|h}-->" class="img-responsive" />
              </a>
            </div>
            <!-- /item_image -->

            <div class="box-body">
              <ul class="status-icon list-inline">
                <!--{*
                  商品ステータスの表示
                  $arrProductStatusの値を検索し、現在のproduct_idと同じなら、そのidのステータスを出力
                *}-->
                <!--{foreach from=$arrProductStatus item=arrStatus key="key" name="status_list"}-->
                  <!--{if $arrStatus.product_id == $arrProduct.product_id}-->
                    <li>
                      <img src="<!--{$TPL_URLPATH}-->img/icon/ico_0<!--{$arrStatus.product_status_id}-->.gif" alt="">
                    </li>
                  <!--{/if}-->
                <!--{/foreach}-->
              </ul>
              <div class="ui-headline box-title">
                <p class="title">
                  <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->">
                    <!--{$arrProduct.name|h}-->
                  </a>
                </p>
              </div>

              <!--{*
                メーカー名の表示
              *}-->
              <!--{foreach from=$arrMakerList item=arrMaker key="key" name="maker_list"}-->
                <!--{if $arrProduct.maker_id == $arrMaker.maker_id }-->
                  <p class="maker"><!--{$arrMaker.name}--></p>
                <!--{/if}-->
              <!--{/foreach}-->

              <!--{*
                レビュー数の表示、プラグイン設定でONの場合のみ表示
                $arrReviewListの値を検索し、現在のproduct_idと同じなら、そのidのおすすめ度を出力
              *}-->
              <!--{if $arrPlugin.free_field3 == 0}-->
                <!--{foreach from=$arrReviewList item=arrReview key="key" name="review_list"}-->
                  <!--{if $arrReview.product_id == $arrProduct.product_id}-->
                    <p class="review">
                    <!--{if $arrReview.recommend_level==5}-->
                      ★★★★★
                    <!--{elseif $arrReview.recommend_level==4}-->
                      ★★★★☆
                    <!--{elseif $arrReview.recommend_level==3}-->
                      ★★★☆☆
                    <!--{elseif $arrReview.recommend_level==2}-->
                      ★★☆☆☆
                    <!--{elseif $arrReview.recommend_level==1}-->
                      ★☆☆☆☆
                    <!--{else}-->
                      ☆☆☆☆☆
                    <!--{/if}-->
                    <span class="count"> (<!--{$arrReview.recommend_count}-->)</span>
                  </p>
                  <!--{/if}-->
                <!--{/foreach}-->
              <!--{/if}-->

              <p class="sale_price item-price">
                <!--{if $arrProduct.price01_min_inctax != 0}-->
                  <del><!--{$arrProduct.price01_min_inctax|number_format}--> </del>
                <!--{/if}-->
                <span class="price">
                  &yen;<!--{$arrProduct.price02_min_inctax|number_format}-->
                </span>
              </p>
            </div>
            <!-- /item_meta -->

          </div>
        </div>
        <!--{* ２カラムで改行（mobileで有効） *}-->
        <!--{if $smarty.foreach.new_products.iteration % 2 === 0}-->
            <div class="clearfix visible-xs-block"></div>
        <!--{/if}-->
        <!--{* 4カラムで改行（PC/tabletで有効） *}-->
        <!--{if $smarty.foreach.new_products.iteration % 4 === 0}-->
            <div class="clearfix hidden-xs"></div>
        <!--{/if}-->
        <!--{/foreach}-->
      </div><!-- /row -->
    </div><!-- /conntent_panel -->
  </div>
</div>
<!--{/if}-->
<!--{/strip}-->
