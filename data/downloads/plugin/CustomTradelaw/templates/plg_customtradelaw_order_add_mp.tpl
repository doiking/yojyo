<!--{strip}-->
<!--{foreach item=order from=$arrOrderItem name=orderItem}-->
<!--{if $order == 'o1'}-->
    [emoji:39]<font color="#800000">販売業者</font><br>
    <!--{$arrOrder.law_company|h}--><br>
<!--{/if}-->

<!--{if $order == 'o2'}-->
    [emoji:170]<font color="#800000">運営責任者</font><br>
    <!--{$arrOrder.law_manager|h}--><br>
<!--{/if}-->

<!--{if $order == 'o3'}-->
    [emoji:38]<font color="#800000">住所</font><br>
    〒<!--{$arrOrder.law_zip01|h}-->-<!--{$arrOrder.law_zip02|h}--><br>
    <!--{$arrPref[$arrOrder.law_pref]|h}--><!--{$arrOrder.law_addr01|h}--><br>
    <!--{$arrOrder.law_addr02|h}--><br>
<!--{/if}-->

<!--{if $order == 'o5'}-->
    [emoji:74]<font color="#800000">電話番号</font><br>
    <a href="tel:<!--{$arrOrder.law_tel01|h}-->-<!--{$arrOrder.law_tel02|h}-->-<!--{$arrOrder.law_tel03|h}-->"><!--{$arrOrder.law_tel01|h}-->-<!--{$arrOrder.law_tel02|h}-->-<!--{$arrOrder.law_tel03|h}--></a><br>
<!--{/if}-->

<!--{if $order == 'o6'}-->
    [emoji:107]<font color="#800000">FAX番号</font><br>
    <!--{$arrOrder.law_fax01|h}-->-<!--{$arrOrder.law_fax02|h}-->-<!--{$arrOrder.law_fax03|h}--><br>
<!--{/if}-->

<!--{if $order == 'o7'}-->
    [emoji:110]<font color="#800000">メールアドレス</font><br>
    <a href="mailto:<!--{$arrOrder.law_email|escape:'hex'}-->"><!--{$arrOrder.law_email|escape:'hexentity'}--></a><br>
<!--{/if}-->

<!--{if $order == 'o8'}-->
    [emoji:e11]<font color="#800000">サイトURL</font><br>
    <a href="<!--{$arrOrder.law_url|h}-->"><!--{$arrOrder.law_url|h}--></a><br>
<!--{/if}-->

<!--{if $order == 'o9'}-->
    [emoji:113]<font color="#800000">商品以外の必要代金</font><br>
    <!--{$arrOrder.law_term01|h|nl2br}--><br>
<!--{/if}-->

<!--{if $order == 'o10'}-->
    [emoji:146]<font color="#800000">注文方法</font><br>
    <!--{$arrOrder.law_term02|h|nl2br}--><br>
<!--{/if}-->

<!--{if $order == 'o11'}-->
    [emoji:42]<font color="#800000">支払方法</font><br>
    <!--{$arrOrder.law_term03|h|nl2br}--><br>
<!--{/if}-->

<!--{if $order == 'o12'}-->
    [emoji:176]<font color="#800000">支払期限</font><br>
    <!--{$arrOrder.law_term04|h|nl2br}--><br>
<!--{/if}-->

<!--{if $order == 'o13'}-->
    [emoji:72]<font color="#800000">引渡し時期</font><br>
    <!--{$arrOrder.law_term05|h|nl2br}--><br>
<!--{/if}-->

<!--{if $order == 'o14'}-->
    [emoji:e42]<font color="#800000">返品・交換について</font><br>
    <!--{$arrOrder.law_term06|h|nl2br}--><br>
<!--{/if}-->

<!--{if $order|regex_replace:'/^a[0-9]+/i':'add' eq 'add'}-->
    <!--{assign var=key value="plg_customtradelaw_name_$order"}-->
    <font color="#800000"><!--{$arrOrder[$key]|h}--></font><br>
    <!--{assign var=key value="plg_customtradelaw_value_$order"}-->
    <!--{$arrOrder[$key]|h|nl2br}--><br>
<!--{/if}-->

<!--{if not $smarty.foreach.orderItem.last}-->
  <!--{if $order != 'o4'}-->
    <hr>
  <!--{/if}-->
<!--{/if}-->
<!--{/foreach}-->
<!--{/strip}-->
