<!--{*
 * templates/admin/order/order_edit_button_alert.tpl
 * Copyright(c)2015, YAMATO CREDIT & FINANCE CO.,LTD. All Rights Reserved.
 *}-->
<!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_DEFERRED}-->
<!--{if $plg_yfcapi_pay_statusid == '' || $plg_yfcapi_pay_statusid == MDL_YFCAPI_DEFERRED_AUTH_CANCEL || $plg_yfcapi_pay_statusid == MDL_YFCAPI_DEFERRED_PAID || $plg_use_option == '1'}-->
<script type="text/javascript">//<![CDATA[
    $(function() {
        $('a[name=add_product]').attr('onclick','');
        $('a[name=add_product]').unbind();
        $('a[name=add_product]').click(
                function() {
                    alert('商品の追加はできません。');
                }
        );

        $('a[name=change]').attr('onclick','');
        $('a[name=change]').unbind();
        $('a[name=change]').click(
                function() {
                    alert('商品の変更はできません。');
                }
        );

        $('a[name=delete]').attr('onclick','');
        $('a[name=delete]').unbind();
        $('a[name=delete]').click(
                function() {
                    alert('商品の削除はできません。');
                }
        );
    });
//]]>
</script>
<!--{/if}-->
<!--{/if}-->