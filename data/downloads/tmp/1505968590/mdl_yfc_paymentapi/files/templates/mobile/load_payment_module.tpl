<!--{*
 * templates/mobile/load_payment_module.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->

<form name="form1" id="form1" method="post" action="<!--{$tpl_url}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" >
<input type="hidden" name="mode" value="next" >
<input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" >
<!--{foreach from=$arrForm item=data key=key}-->
<input type="hidden" name="<!--{$key}-->" value="<!--{$data.value|h}-->" >
<!--{/foreach}-->

<!--{if $tpl_form_bloc_path != ""}-->
<!--{include file=$tpl_form_bloc_path}-->
<!--{/if}-->

</form>
<br>
