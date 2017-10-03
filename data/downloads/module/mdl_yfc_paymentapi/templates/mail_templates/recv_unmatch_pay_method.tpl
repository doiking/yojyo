<!--{*
 * templates/mail_templates/recv_unmatch_pay_method.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->

受注情報で支払い方法が一致しない(コンビニの種類も含む)決済通知を受信しました。
対象の受注に対して通知データが処理出来ませんでした。

注文番号: <!--{$arrParam.order_no|h}-->
通知決済方法: <!--{$arrParam.settle_method|h}-->
受注決済方法: <!--{$arrOrder.payment_method|h}-->

利用金額: <!--{$arrParam.settle_price|h}-->円
決済日時: <!--{$arrParam.settle_date|h}-->

大変お手数ですが、ご確認お願い致します。

クロネコWebコレクトから結果通知プログラムURLに結果を返却した際、EC-CUBE側
の受注データの決済方法と、通知された決済の種類が異なるため「不一致」となり、
本メールが送信されています。
※コンビニ決済の場合はコンビニの種類までご確認お願いいたします。

まずは、EC-CUBE管理画面とクロネコWebコレクトの管理画面とで
決済データをご確認いただき、決済結果に相違がないことをご確認ください。

