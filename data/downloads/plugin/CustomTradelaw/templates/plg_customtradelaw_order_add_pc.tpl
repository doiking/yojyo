
<!--{if $order|regex_replace:'/^a[0-9]+/i':'add' eq 'add'}-->
    <tr>
        <!--{assign var=key value="plg_customtradelaw_name_$order"}-->
        <th><!--{$arrOrder[$key]|h}--></th>
        <!--{assign var=key value="plg_customtradelaw_value_$order"}-->
        <td><!--{$arrOrder[$key]|h|nl2br}--></td>
    </tr>
<!--{/if}-->
<!--{/foreach}-->
