<!--{*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2014 LOCKON CO.,LTD. All Rights Reserved.
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
 *}-->

<!--{strip}-->
    <!--{if count($arrBestProducts) > 0}-->
        <div class="block_outer ui-section margin-section">
            <div id="recommend_area">
                <div class="ui-headline section-title">
                    <h2 class="title">おすすめ商品
                        <!--{*
                        * タイトルに画像を使うときはコメントアウトを解除
                        * <img src="<!--{$TPL_URLPATH}-->img/title/tit_bloc_recommend.png" alt="*" class="title_icon" />
                        *}-->
                    </h2>
                </div>
                <div class="block_body ui-section-body">
                    <div class="row">
                        <!--{foreach from=$arrBestProducts item=arrProduct name="recommend_products"}-->
                            <div class="col-xs-6 col-sm-4">
                                <div class="product_item ui-box item-card">
                                    <div class="productImage box-image">
                                        <a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->">
                                            <img src="<!--{$smarty.const.IMAGE_SAVE_URLPATH}--><!--{$arrProduct.main_list_image|sfNoImageMainList|h}-->" class="img-responsive" alt="<!--{$arrProduct.name|h}-->" />
                                        </a>
                                    </div>
                                    <div class="productContents box-body">
                                        <div class="ui-headline box-title">
                                            <h3 class="title"><a href="<!--{$smarty.const.P_DETAIL_URLPATH}--><!--{$arrProduct.product_id|u}-->"><!--{$arrProduct.name|h}--></a></h3>
                                        </div>
                                        <p class="sale_price item-price">
                                            <!--{if $arrProduct.price01_min_inctax != 0}-->
                                              <del><!--{$arrProduct.price01_min_inctax|number_format}--> </del>
                                            <!--{/if}-->
                                            <span class="price">
                                              &yen;<!--{$arrProduct.price02_min_inctax|number_format}-->
                                            </span>
                                        </p>
                                        <p class="mini comment description"><!--{$arrProduct.comment|h|nl2br}--></p>
                                    </div>
                                </div>
                            </div>
                            <!--{* ２カラムで改行（mobileで有効） *}-->
                            <!--{if $smarty.foreach.recommend_products.iteration % 2 === 0}-->
                                <div class="clearfix visible-xs"></div>
                            <!--{/if}-->
                            <!--{* ３カラムで改行（PC/tabletで有効） *}-->
                            <!--{if $smarty.foreach.recommend_products.iteration % 3 === 0}-->
                                <div class="clearfix hidden-xs"></div>
                            <!--{/if}-->
                        <!--{/foreach}-->
                    </div>
                </div>
            </div>
        </div>
    <!--{/if}-->
<!--{/strip}-->
