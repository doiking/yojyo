<!--{*
 * templates/admin/products/products_product_confirm_add.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<!--{if $plg_use_option == '0' && $plg_advance_sale == '0'}-->
<tr>
    <th>予約商品出荷予定日</th>
    <td>
        <!--{if strlen($arrForm.plg_yfcapi_reserve_date) > 0}--><!--{$arrForm.plg_yfcapi_reserve_date}--><!--{else}-->未登録<!--{/if}-->
    </td>
</tr>
<!--{/if}-->