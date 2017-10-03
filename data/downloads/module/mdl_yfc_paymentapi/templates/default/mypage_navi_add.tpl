<!--{*
 * templates/default/mypage_navi_add.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<!--{if !$is_link_load}--><!--{* 多重ロードを防ぐ *}-->

<script type="text/javascript">//<![CDATA[
    $(function(){
        var li = $(document.createElement('li'));
        var a = $(document.createElement('a'));
        var span = $(document.createElement('span'));
        span.text('カード情報編集');
        a.attr('href', 'change_card.php');
        a.append(span);
        li.append(a);
        $('div#mynavi_area li:last').after(li);
    });

//]]></script>

<!--{/if}-->
<!--{assign var="is_link_load" value="1"}-->