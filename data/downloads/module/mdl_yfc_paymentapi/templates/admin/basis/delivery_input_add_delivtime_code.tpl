<!--{*
 * templates/admin/basis/deliv_input_add_delivtime_code.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<!--{assign var=key_time_id value="plg_yfcapi_b2_time_id`$smarty.section.cnt.iteration`"}-->
<select name="<!--{$key_time_id}-->">
    <!--{html_options options=$arrB2DelivTimeCode selected=$arrForm[$key_time_id].value}-->
</select>