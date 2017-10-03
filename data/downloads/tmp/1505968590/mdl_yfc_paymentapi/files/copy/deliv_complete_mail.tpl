<!--{*
 * templates/mail_templates/deliv_complete_mail.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<!--{$arrOrder.order_name01}--> <!--{$arrOrder.order_name02}--> 様

<!--{$tpl_header}-->

<!--{if $arrOrder.memo03 == $smarty.const.MDL_YFCAPI_PAYID_CREDIT && count($arrShipping) >= 1}-->
************************************************
　クロネコヤマトの荷物お問い合わせシステム
************************************************

<!--{foreach item=shipping name=shipping from=$arrShipping}-->
◎お届け先<!--{if count($arrShipping) > 1}--><!--{$smarty.foreach.shipping.iteration}--><!--{/if}-->

荷物のお問合せ番号（宅急便伝票番号）：<!--{$shipping.plg_yfcapi_deliv_slip}-->
追跡URL：<!--{$shipping.plg_yfcapi_deliv_slip_url}-->

<!--{/foreach}-->
<!--{/if}-->
<!--{if $arrOrder.memo03 == $smarty.const.MDL_YFCAPI_PAYID_DEFERRED && count($arrShipping) >= 1}-->
<!--{ycf_is_deliv_disp key='is_deliv_disp'}-->
<!--{if $is_deliv_disp}-->
************************************************
　クロネコヤマトの荷物お問い合わせシステム
************************************************

追跡URL：<!--{$smarty.const.MDL_YFCAPI_DEFERRED_DELIV_SLIP_URL}-->
<!--{foreach item=shipping name=shipping from=$arrShipping}-->
◎お届け先<!--{if count($arrShipping) > 1}--><!--{$smarty.foreach.shipping.iteration}--><!--{/if}-->

荷物のお問合せ番号（宅急便伝票番号）：<!--{$shipping.plg_yfcapi_deliv_slip}-->

<!--{/foreach}-->
<!--{/if}-->
<!--{/if}-->
************************************************
　ご請求金額
************************************************

ご注文番号：<!--{$arrOrder.order_id}-->
お支払い合計：￥<!--{$arrOrder.payment_total|number_format|default:0}-->
お支払い方法：<!--{$arrOrder.payment_method}-->
メッセージ：<!--{$Message_tmp}-->

************************************************
　ご注文商品明細
************************************************

<!--{section name=cnt loop=$arrOrderDetail}-->
商品コード: <!--{$arrOrderDetail[cnt].product_code}-->
商品名: <!--{$arrOrderDetail[cnt].product_name}--> <!--{$arrOrderDetail[cnt].classcategory_name1}--> <!--{$arrOrderDetail[cnt].classcategory_name2}-->
単価：￥<!--{$arrOrderDetail[cnt].price|sfCalcIncTax:$arrOrderDetail[cnt].tax_rate:$arrOrderDetail[cnt].tax_rule|number_format}-->
数量：<!--{$arrOrderDetail[cnt].quantity}-->

<!--{/section}-->
-------------------------------------------------
小　計 ￥<!--{$arrOrder.subtotal|number_format|default:0}--> <!--{if 0 < $arrOrder.tax}-->(うち消費税 ￥<!--{$arrOrder.tax|number_format|default:0}-->)<!--{/if}-->

<!--{if $arrOrder.use_point > 0}-->
値引き ￥<!--{$arrOrder.use_point*$smarty.const.POINT_VALUE+$arrOrder.discount|number_format|default:0}-->
<!--{/if}-->
送　料 ￥<!--{$arrOrder.deliv_fee|number_format|default:0}-->
手数料 ￥<!--{$arrOrder.charge|number_format|default:0}-->
============================================
合　計 ￥<!--{$arrOrder.payment_total|number_format|default:0}-->

************************************************
　ご注文者情報
************************************************
　お名前　：<!--{$arrOrder.order_name01}--> <!--{$arrOrder.order_name02}-->　様
<!--{if $arrOrder.order_company_name != ""}-->
　会社名　：<!--{$arrOrder.order_company_name}-->
<!--{/if}-->
<!--{if $smarty.const.FORM_COUNTRY_ENABLE}-->
　国　　　：<!--{$arrCountry[$arrOrder.order_country_id]}-->
　ZIPCODE ：<!--{$arrOrder.order_zipcode}-->
<!--{/if}-->
　郵便番号：〒<!--{$arrOrder.order_zip01}-->-<!--{$arrOrder.order_zip02}-->
　住所　　：<!--{$arrPref[$arrOrder.order_pref]}--><!--{$arrOrder.order_addr01}--><!--{$arrOrder.order_addr02}-->
　電話番号：<!--{$arrOrder.order_tel01}-->-<!--{$arrOrder.order_tel02}-->-<!--{$arrOrder.order_tel03}-->
　FAX番号 ：<!--{if $arrOrder.order_fax01 > 0}--><!--{$arrOrder.order_fax01}-->-<!--{$arrOrder.order_fax02}-->-<!--{$arrOrder.order_fax03}--><!--{/if}-->

　メールアドレス：<!--{$arrOrder.order_email}-->

<!--{if count($arrShipping) >= 1}-->
************************************************
　配送情報
************************************************

<!--{foreach item=shipping name=shipping from=$arrShipping}-->
◎お届け先<!--{if count($arrShipping) > 1}--><!--{$smarty.foreach.shipping.iteration}--><!--{/if}-->

　お名前　：<!--{$shipping.shipping_name01}--> <!--{$shipping.shipping_name02}-->　様
<!--{if $shipping.shipping_company_name != ""}-->
　会社名　：<!--{$shipping.shipping_company_name}-->
<!--{/if}-->
<!--{if $smarty.const.FORM_COUNTRY_ENABLE}-->
　国　　　：<!--{$arrCountry[$shipping.shipping_country_id]}-->
　ZIPCODE ：<!--{$shipping.shipping_zipcode}-->
<!--{/if}-->
　郵便番号：〒<!--{$shipping.shipping_zip01}-->-<!--{$shipping.shipping_zip02}-->
　住所　　：<!--{$arrPref[$shipping.shipping_pref]}--><!--{$shipping.shipping_addr01}--><!--{$shipping.shipping_addr02}-->
　電話番号：<!--{$shipping.shipping_tel01}-->-<!--{$shipping.shipping_tel02}-->-<!--{$shipping.shipping_tel03}-->
　FAX番号 ：<!--{if $shipping.shipping_fax01 > 0}--><!--{$shipping.shipping_fax01}-->-<!--{$shipping.shipping_fax02}-->-<!--{$shipping.shipping_fax03}--><!--{else}-->　<!--{/if}-->

　お届け日：<!--{$shipping.shipping_date|date_format:"%Y/%m/%d"|default:"指定なし"}-->
　お届け時間：<!--{$shipping.shipping_time|default:"指定なし"}-->

<!--{foreach item=item name=item from=$shipping.shipment_item}-->
商品コード: <!--{$item.product_code}-->
商品名: <!--{$item.product_name}--> <!--{$item.classcategory_name1}--> <!--{$item.classcategory_name2}-->
単価：￥<!--{$item.price|sfCalcIncTax:$item.tax_rate:$item.tax_rule|number_format}-->
数量：<!--{$item.quantity|number_format}-->

<!--{/foreach}-->
<!--{/foreach}-->
<!--{/if}-->
<!--{if $arrOrder.customer_id && $smarty.const.USE_POINT !== false}-->
============================================
<!--{* ご注文前のポイント {$tpl_user_point} pt *}-->
ご使用ポイント <!--{$arrOrder.use_point|default:0|number_format}--> pt
今回加算される予定のポイント <!--{$arrOrder.add_point|default:0|number_format}--> pt
現在の所持ポイント <!--{$arrCustomer.point|default:0|number_format}--> pt
<!--{/if}-->
<!--{$tpl_footer}-->
