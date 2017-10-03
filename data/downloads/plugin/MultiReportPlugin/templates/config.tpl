<!--{*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
*}-->

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<script type="text/javascript">//<![CDATA[




//]]>
</script>

<h2><!--{$tpl_subtitle}--></h2>

<h3>帳票一覧</h3>
<form name="form2" id="form2" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="id" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<table border="0" cellspacing="1" cellpadding="8" summary=" ">
                    <col width="80%" />
                    <col width="10%" />
                    <col width="10%" />
<tr>
<th class="center">帳票の種類</th><th class="center">編集</th><th class="center">削除</th>

<!--{*帳票一覧出力*}-->
<!--{foreach from=$arrReport item=report}-->
<tr>
<td><!--{$report.name|h}--></td>
<td><a href="?" onclick="eccube.fnFormModeSubmit('form2', 'pre_edit', 'id', '<!--{$report.id}-->'); return false;">編集</a></td>
<td><a href="?" onclick="eccube.fnFormModeSubmit('form2', 'delete', 'id', '<!--{$report.id}-->'); return false;">削除</a></td>
</tr>
<!--{/foreach}-->
</tr>
</table>
</form>
<!--{if $arrForm.id}-->
<h3>編集</h3>
新規登録は<a href="?plugin_id=<!--{$plug_id|h}-->">こちら</a>から
<!--{else}-->
<h3>新規登録</h3>
<!--{/if}-->

<!--{*エラー表示*}-->
<!--{foreach from=$arrErr item=e}-->
<span class="attention"><!--{$e}--></span>
<!--{/foreach}-->

<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="id" value="<!--{$arrForm.id}-->">
<table border="0" cellspacing="1" cellpadding="8" summary=" ">
    <tr>
        <td bgcolor="#f3f3f3">帳票の種類<span class="red">※</span></td>
        <td>
        	<!--{assign var=key value="name"}-->
        	<input type="text" name="name" size="35" value="<!--{$arrForm[$key]|h}-->" maxlength="20">
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">帳票タイトル<span class="red">※</span></td>
        <td>
        	<!--{assign var=key value="report_title"}-->
        	<input type="text" name="report_title" size="40" value="<!--{$arrForm[$key]|h}-->" maxlength="40">
        </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">帳票名メッセージ</td>
            <td>
                <!--{assign var=key value="message1"}-->
                1行目：<input type="text" name="message1" size="45" value="<!--{$arrForm[$key]|h}-->" maxlength="30"/><br />
                <!--{assign var=key value="message2"}-->
                2行目：<input type="text" name="message2" size="45" value="<!--{$arrForm[$key]|h}-->" maxlength="30"/><br />
                <!--{assign var=key value="message3"}-->
                3行目：<input type="text" name="message3" size="45" value="<!--{$arrForm[$key]|h}-->" maxlength="30"/><br />
                <span style="font-size: 80%;">※未入力時は表示されません。</span><br />
            </td>
    </tr>
    <tr>
        <td bgcolor="#f3f3f3">備考</td>
            <td>
                <!--{assign var=key value="notes1"}-->
                1行目：<input type="text" name="notes1" size="45" value="<!--{$arrForm[$key]|h}-->" maxlength="50"/><br />
                <!--{assign var=key value="notes2"}-->
                2行目：<input type="text" name="notes2" size="45" value="<!--{$arrForm[$key]|h}-->" maxlength="50"/><br />
                <!--{assign var=key value="notes3"}-->
                3行目：<input type="text" name="notes3" size="45" value="<!--{$arrForm[$key]|h}-->" maxlength="50"/><br />
                <span style="font-size: 80%;">※未入力時は表示されません。</span><br />
            </td>
    </tr>
</table>

<div class="btn-area">
    <ul>
        <li>
            <a class="btn-action" href="javascript:;" onclick="document.form1.submit();return false;"><span class="btn-next">この内容で登録する</span></a>
        </li>
    </ul>
</div>

</form>
<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
