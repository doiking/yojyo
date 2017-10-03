<!--{*
 * templates/admin/order/order_index_add_products_type_search.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<!--{if $plg_use_option == '0' && $plg_advance_sale == '0'}-->
        <tr>
            <th>商品種別</th>
            <td colspan="3">
            <!--{assign var=key value="search_product_type_id"}-->
            <span class="attention"><!--{$arrErr[$key]|h}--></span>
            <!--{html_checkboxes name="$key" options=$arrProductsType selected=$arrForm[$key].value}-->
            </td>
        </tr>
<!--{/if}-->