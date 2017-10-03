<!--{*
/*
 * Tetra_SellRanking
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

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<h2><!--{$tpl_subtitle}--></h2>
<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit">
<p>売上ランキングプラグイン設定<br/>
    <br/>
</p>

<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr >
        <td bgcolor="#f3f3f3">タイトル<span class="red">※</span></td>
        <td>
            <!--{assign var=key value="rank_field1"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" />
        </td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">ランキング表示数<span class="red">※</span></td>
        <td>
            <!--{assign var=key value="rank_field2"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key]}-->" />
        </td>
    </tr>
    <tr >
        <td bgcolor="#f3f3f3">レビュー表示<span class="red">※</span></td>
        <td>
            <!--{assign var=key value="rank_field3"}-->
            <span class="red"><!--{$arrErr[$key]}--></span>
            <input type="radio" name="<!--{$key}-->" id="<!--{$key}-->" value="0"<!--{if $arrForm[$key] == 0}--> checked="checked"<!--{/if}--> /> ON
            <input type="radio" name="<!--{$key}-->" id="<!--{$key}-->" value="1"<!--{if $arrForm[$key] == 1}--> checked="checked"<!--{/if}--> /> OFF
        </td>
    </tr>
</table>

<div class="btn-area">
    <ul>
        <li>
            <a class="btn-action" href="javascript:void(0);" onclick="document.form1.submit();return false;"><span class="btn-next">この内容で登録する</span></a>
        </li>
    </ul>
</div>

</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
