<!--{*
 * templates/admin/order/order_edit_payment_form_add.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<!--{if $arrForm.status.value != $smarty.const.ORDER_PENDING}-->
<!--{if $plg_yfcapi_payid}-->

<script type="text/javascript">//<![CDATA[
var def_payment_id = $('select[name="payment_id"]').val();

$(function() {
    $('select[name=payment_id]').attr('onchange','');
    $('select[name=payment_id]').unbind();
    $('select[name=payment_id]').change(
        function() {
            $('select[name=payment_id]').val(def_payment_id);
            alert('お支払い方法の変更は無効になります。');
        }
    );
});


//]]>
</script>


<!--{/if}-->
<!--{/if}-->

