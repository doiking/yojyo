<!--{*
 * templates/admin/order/order_edit_buyer_csv_form.tpl
 * Copyright(c)2015, YAMATO CREDIT & FINANCE CO.,LTD. All Rights Reserved.
 *}-->
<!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_DEFERRED}-->
  <form name="buyer_csv" id="buyer_csv" method="post" action="?">
    <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
    <input type="hidden" name="mode" value="" />
    <input type="hidden" name="order_id" value="<!--{$arrForm.order_id.value|h}-->" />
  </form>
<!--{/if}-->