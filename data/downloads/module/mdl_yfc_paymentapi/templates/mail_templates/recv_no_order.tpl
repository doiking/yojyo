<!--{*
 * templates/mail_templates/recv_no_order.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->

受注情報に存在しない注文番号の決済結果を受信しました。

注文番号: <!--{$arrParam.order_no|h}-->
決済金額: <!--{$arrParam.settle_price|h}-->円
決済日時: <!--{$arrParam.settle_date|h}-->

大変お手数ですが、ご確認お願い致します。

クロネコWebコレクトから結果通知プログラムURLに結果を返却した際、EC-CUBE側
（dtb_order）に該当データが存在しないため「不一致」となり、
本メールが送信されています。

まずは、EC-CUBE管理画面とクロネコWebコレクトの管理画面とで
決済データをご確認いただき、決済結果に相違がないことをご確認ください。

