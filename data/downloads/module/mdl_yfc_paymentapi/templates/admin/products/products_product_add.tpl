<!--{*
 * templates/admin/products/products_product_add.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<!--{if $plg_use_option == '0' && $plg_advance_sale == '0'}-->
<tr>
    <th>
        予約商品出荷予定日<br />
        ※商品種別『予約商品』の場合は必ず設定して下さい
    </th>
    <td>
        <span class="attention"><!--{$plg_yfcapi_reserve_date_error_msg}--></span>
        <input type="text" name="plg_yfcapi_reserve_date" value="<!--{$arrForm.plg_yfcapi_reserve_date|h}-->" size="10" class="box10" maxlength="8" style="<!--{if $plg_yfcapi_reserve_date_error_msg != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>
        <span class="attention"> 半角数字8文字 (例)20140401</span>
    </td>
</tr>
<!--{/if}-->