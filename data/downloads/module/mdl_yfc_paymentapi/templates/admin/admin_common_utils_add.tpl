<!--{*
 * templates/admin/admin_common_utils_add.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<script type="text/javascript">//<![CDATA[
    $(function(){
        var li = $(document.createElement('li'));
        var a = $(document.createElement('a'));
        var span = $(document.createElement('span'));
        a.attr('href', '<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/upload_csv_b2.php');
        li.addClass('navi-order-upload_csv_b2');
        span.text('送り状番号登録');
        a.append(span);
        li.append(a);
        $('li#navi-order ul:first').append(li);
    });
//]]></script>