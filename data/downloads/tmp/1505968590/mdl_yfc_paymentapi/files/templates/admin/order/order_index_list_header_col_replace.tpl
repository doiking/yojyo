<!--{*
 * templates/admin/order/order_index_list_header_col_replace.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
        <col width="8%" />
        <col width="4%" />
        <col width="10%" />
        <col width="7%" />
        <col width="9%" />
        <col width="9%" />
        <col width="9%" />
        <col width="9%" />
        <col width="4%" />
        <col width="9%" />
        <col width="4%" />

        <col width="9%" />
        <col width="9%" />
        <!--{if $plg_yfcapi_error != ""}-->
        <tr><td colspan="13" style="border:none;"><span class="attention">※決済操作でエラーが発生しました。<br /><!--{$plg_yfcapi_error}--></span></td></tr>
        <!--{/if}-->