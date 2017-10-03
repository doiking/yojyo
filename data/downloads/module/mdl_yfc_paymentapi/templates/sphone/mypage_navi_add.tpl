<!--{*
 * templates/sphone/mypage_navi_add.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<!--{if !$is_link_load}--> <!--{* 多重ロードを防ぐ *}-->

<script type="text/javascript">//<![CDATA[
    $(function(){
        var li = $(document.createElement('li'));
        var a = $(document.createElement('a'));
        var span = $(document.createElement('span'));
        a.attr('href', 'change_card.php');
        a.attr('rel', 'external');
        a.addClass('ui-link');
        a.text('カード情報編集');
        li.addClass('nav_change_card');
        li.append(a);
        $('nav#mypage_nav li:last').after(li);
    });

//]]></script>

<!--{/if}-->
<!--{assign var="is_link_load" value="1"}-->

