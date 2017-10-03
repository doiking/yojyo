<!--{*
 * templates/mail_templates/recv_unmatch_settle_price.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->

受注情報で決済金額が一致しない決済通知を受信しました。
対象の受注に対して通知データが処理出来ませんでした。

注文番号: <!--{$arrParam.order_no|h}-->
通知決済金額: <!--{$arrParam.settle_price|h}-->円
受注支払金額: <!--{$arrOrder.payment_total|h}-->円

決済日時: <!--{$arrParam.settle_date|h}-->

大変お手数ですが、ご確認お願い致します。

クロネコWebコレクトから結果通知プログラムURLに結果を返却した際、EC-CUBE側
の受注データのお支払い合計金額と、通知された決済の決済金額が異なるため「不一致」となり、
本メールが送信されています。

まずは、EC-CUBE管理画面とクロネコWebコレクトの管理画面とで
決済データをご確認いただき、決済結果に相違がないことをご確認ください。

