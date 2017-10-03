<!--{*
 * templates/default/load_payment_module.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<script type="text/javascript">//<![CDATA[

//]]>
</script>

<div id="undercolumn">
    <div id="undercolumn_shopping">
        <p class="flow_area">
            <img src="<!--{$TPL_URLPATH}-->img/picture/img_flow_03.jpg" alt="購入手続きの流れ" />
        </p>
        <h2 class="title"><!--{$tpl_title|h}--></h2>


        <form name="form1" id="form1" method="POST" action="<!--{$tpl_url}-->" autocomplete="off">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="register" />
        <input type="hidden" name="uniqid" value="<!--{$tpl_uniqid}-->" />

        <div id="payment_form">
        <!--{if $tpl_form_bloc_path != ""}-->
            <!--{include file=$tpl_form_bloc_path}-->
        <!--{/if}-->
        </div>

        </form>
    </div>
</div>
