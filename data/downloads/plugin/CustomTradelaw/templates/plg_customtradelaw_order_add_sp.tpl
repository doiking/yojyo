
<!--{if $order|regex_replace:'/^a[0-9]+/i':'add' eq 'add'}-->
    <!--{assign var=key value="plg_customtradelaw_name_$order"}-->
    <dt><!--{$arrOrder[$key]|h}--></dt>
    <!--{assign var=key value="plg_customtradelaw_value_$order"}-->
    <dd><!--{$arrOrder[$key]|h|nl2br}--></dd>
<!--{/if}-->
<!--{/foreach}-->
