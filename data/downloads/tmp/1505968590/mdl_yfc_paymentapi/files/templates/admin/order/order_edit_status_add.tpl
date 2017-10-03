<!--{*
 * templates/admin/order/order_edit_status_add.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<!--{if $plg_yfcapi_payid}-->
<script type="text/javascript">
<!--
    function fnPlgYfcApiConfirm(mode, anchor, anchor_name) {
        if(window.confirm('決済操作を行います。\n受注データを編集している場合は先に保存して下さい。\nよろしいですか？')) {
            fnModeSubmit(mode, anchor, anchor_name);
        }
    }

//-->
</script>

    <!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_DEFERRED}-->
        <h2><!--{$smarty.const.MDL_YFCAPI_DEFERRED_SERVICE_NAME}-->決済情報</h2>
    <!--{else}-->
        <h2><!--{$smarty.const.MDL_YFCAPI_SERVICE_NAME}-->決済情報</h2>
    <!--{/if}-->
    <table class="form" id="plg_yfcapi_form">
        <tr>
            <th>決済種別</th>
            <td><!--{$plg_yfcapi_pay_name|h}-->(<!--{$plg_yfcapi_payid}-->)</td>
        </tr>
<!--{* ▼クロネコ代金後払い▼ *}-->
        <!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_DEFERRED}-->
        <tr>
            <th>与信結果</th>
            <td>
                <!--{if $plg_yfcapi_deferred_result != ''}-->
                    <!--{$plg_yfcapi_deferred_result|h}-->
                <!--{else}-->
                    不明な状態
                <!--{/if}-->
            </td>
        </tr>
        <!--{/if}-->
<!--{* ▲クロネコ代金後払い▲ *}-->
        <tr>
            <th>取引状況</th>
            <td>
                <!--{if $plg_yfcapi_pay_status != ''}-->
                    <!--{$plg_yfcapi_pay_status|h}-->
                <!--{else}-->
                    不明な状態
                <!--{/if}-->
            </td>
        </tr>
        <!--{if $plg_yfcapi_error}-->
        <tr>
            <th>決済操作エラー</th>
            <td class="attention"><!--{$plg_yfcapi_error|h}--></td>
        </tr>
        <!--{/if}-->
<!--{* ▼クロネコ代金後払い▼ *}-->
        <!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_DEFERRED}-->
        <tr>
            <th>送信日時</th>
            <td><!--{$arrPaymentData.requestDate|h}--></td>
        </tr>
        <tr>
            <th>決済金額</th>
            <td>
                <!--{$arrPaymentData.totalAmount|number_format|h}-->円
                <!--{if $arrPaymentData.totalAmount != $arrForm.payment_total.value}-->
                &nbsp;<span class="attention">
                ※決済金額とお支払い合計に差異があります。
                </span>
                <!--{/if}-->
            </td>
        </tr>
        <!--{/if}-->
<!--{* ▲クロネコ代金後払い▲ *}-->
<!--{* ▼クレジットカード決済▼ *}-->
        <!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_CREDIT}-->
        <tr>
            <th>与信承認番号</th>
            <td><!--{$arrPaymentData.crdCResCd|h}--></td>
        </tr>
        <tr>
            <th>支払方法</th>
            <td>
            <!--{if $plg_yfcapi_pay_method != ''}-->
                <!--{$plg_yfcapi_pay_method|h}-->
            <!--{else}-->
                不明な状態
            <!--{/if}-->
            </td>
        </tr>
        <tr>
            <th>最終送信日時</th>
            <td><!--{$arrPaymentData.returnDate|h}--></td>
        </tr>
        <!--{/if}-->
<!--{* ▲クレジットカード決済▲ *}-->
<!--{* ▼コンビニ決済▼ *}-->
        <!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_CVS}-->
        <tr>
            <th>支払い先コンビニ</th>
            <td><!--{$arrCVS[$plg_yfcapi_cvs_method]|h}--></td>
        </tr>
        <!--{* セブンイレブン *}-->
        <!--{if $plg_yfcapi_cvs_method == MDL_YFCAPI_CVS_METHOD_SEVENELEVEN}-->
        <tr>
            <th>払込票番号</th>
            <td><!--{$arrPaymentData.billingNo|h}--></td>
        </tr>
        <!--{/if}-->
        <!--{* ファミリーマート *}-->
        <!--{if $plg_yfcapi_cvs_method == MDL_YFCAPI_CVS_METHOD_FAMILYMART}-->
        <tr>
            <th>企業コード</th>
            <td><!--{$arrPaymentData.companyCode|h}--></td>
        </tr>
        <tr>
            <th>注文番号</th>
            <td><!--{$arrPaymentData.orderNoF|h}--></td>
        </tr>
        <!--{/if}-->
        <!--{* ローソン、サークルKサンクス、ミニストップ、セイコーマート *}-->
        <!--{if $plg_yfcapi_cvs_method == MDL_YFCAPI_CVS_METHOD_LAWSON || $plg_yfcapi_cvs_method == MDL_YFCAPI_CVS_METHOD_CIRCLEK || $plg_yfcapi_cvs_method == MDL_YFCAPI_CVS_METHOD_MINISTOP || $plg_yfcapi_cvs_method == MDL_YFCAPI_CVS_METHOD_SEICOMART}-->
        <tr>
            <th>受付番号</th>
            <td><!--{$arrPaymentData.econNo|h}--></td>
        </tr>
        <!--{/if}-->
        <!--{* 全コンビニ共通 *}-->
        <tr>
            <th>支払期限日</th>
            <td><!--{$arrPaymentData.expiredDate|h}--></td>
        </tr>
        <!--{/if}-->
<!--{* ▲コンビニ決済▲ *}-->
        <!--{if $arrPaymentData.settle_price != ""}-->
        <tr>
            <th>決済金額</th>
            <td>
            <!--{$arrPaymentData.settle_price|number_format|h}-->円
            <!--{if $arrPaymentData.settle_price != $arrForm.payment_total.value}-->
                &nbsp;<span class="attention">
                ※決済金額とお支払い合計に差異があります。
                </span>
            <!--{/if}-->
            </td>
        </tr>
        <!--{/if}-->

        <tr>
            <th>決済操作</th>
            <td>
                <!--{if $plg_yfcapi_payid != MDL_YFCAPI_PAYID_DEFERRED}-->
                    <!--{if $plg_yfcapi_slip_on && $plg_yfcapi_payid == MDL_YFCAPI_PAYID_CREDIT && $plg_yfcapi_pay_statusid == MDL_YFCAPI_ACTION_STATUS_COMP_AUTH}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('plg_yfcapi_shipment_entry','','');">出荷情報登録</a>&nbsp;
                    <!--{/if}-->
                    <!--{if $plg_yfcapi_slip_on && $plg_yfcapi_payid == MDL_YFCAPI_PAYID_CREDIT && $plg_yfcapi_pay_statusid == MDL_YFCAPI_ACTION_STATUS_WAIT_SETTLEMENT}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('plg_yfcapi_shipment_cancel','','');">出荷情報取消</a>&nbsp;
                    <!--{/if}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('plg_yfcapi_get_info','','');">取引情報照会</a>&nbsp;
                    <!--{if $plg_yfcapi_pay_statusid == MDL_YFCAPI_ACTION_STATUS_COMP_RESERVE && $plg_use_option == '0' && $plg_advance_sale == '0'}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('plg_yfcapi_change_date','','');">出荷予定日変更</a>&nbsp;
                    <!--{/if}-->
                    <!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_CREDIT && $plg_yfcapi_pay_statusid != MDL_YFCAPI_ACTION_STATUS_COMMIT_SETTLEMENT}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('plg_yfcapi_credit_cancel','','');">決済取消</a>
                    <!--{/if}-->
                    <!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_CREDIT && $plg_yfcapi_pay_statusid != MDL_YFCAPI_ACTION_STATUS_CANCEL && $plg_use_option == '0'}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('plg_yfcapi_change_price','','');">金額変更</a>
                    <!--{/if}-->
                <!--{else}-->
                    <!--{if $plg_yfcapi_deferred_result_code == MDL_YFCAPI_DEFERRED_AVAILABLE && $plg_yfcapi_slip_on}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('deferred_shipment_entry','','');">出荷情報登録</a>
                    <!--{/if}-->
                    <!--{if $plg_yfcapi_pay_statusid == MDL_YFCAPI_DEFERRED_REGIST_DELIV_SLIP && $plg_yfcapi_exist_last_deliv}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('deferred_shipment_cancel','','');">出荷情報取消</a>
                    <!--{/if}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('deferred_get_info','','');">取引状況取得</a>
                    <!--{if $plg_yfcapi_pay_statusid != '' && $plg_yfcapi_pay_statusid != MDL_YFCAPI_DEFERRED_AUTH_CANCEL}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('deferred_cancel_auth','','');">与信取消</a>
                    <!--{/if}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('deferred_get_auth','','');">与信結果取得</a>
                    <!--{if $plg_yfcapi_pay_statusid != '' && $plg_yfcapi_pay_statusid != MDL_YFCAPI_DEFERRED_AUTH_CANCEL && $plg_yfcapi_pay_statusid != MDL_YFCAPI_DEFERRED_PAID && $plg_yfcapi_deferred_result_code == MDL_YFCAPI_DEFERRED_AVAILABLE && $plg_use_option == '0'}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('deferred_change_price','','');">金額変更</a>
                    <!--{/if}-->
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('deferred_invoice_reissue','','');">請求内容変更・請求書再発行</a>
                    <a class="btn-normal" href="javascript:;" onclick="fnPlgYfcApiConfirm('deferred_invoice_reissue_withdrawn','','');">請求書再発行取下げ</a>
                <!--{/if}-->
            </td>
        </tr>
<!--{* ▼クロネコ代金後払い▼ *}-->
        <!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_DEFERRED}-->
        <tr>
            <th>買手情報一括登録CSV</th>
            <td><a class="btn-normal" href="javascript:;" onclick="eccube.fnFormModeSubmit('buyer_csv','deferred_buyer_csv','',''); return false;">CSVダウンロード</a></td>
        </tr>
        <!--{/if}-->
<!--{* ▲クロネコ代金後払い▲ *}-->
        <!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_CREDIT &&  $plg_use_option == '0' && $plg_advance_sale == '0'}-->
        <tr>
            <th>出荷予定日</th>
            <td>
                <span class="attention"><!--{$plg_yfcapi_scheduled_shipping_date_error_msg}--></span>
                <input type="text" name="plg_yfcapi_scheduled_shipping_date" value="<!--{$arrForm.plg_yfcapi_scheduled_shipping_date.value|h}-->" size="10" class="box10" maxlength="8" style="<!--{if $plg_yfcapi_scheduled_shipping_date_error_msg != ""}-->background-color: <!--{$smarty.const.ERR_COLOR}-->;<!--{/if}-->"/>
                <span class="attention"> 半角数字8文字 (例)20140401</span>
            </td>
        </tr>
        <!--{/if}-->
        <!--{if $arrPaymentData.payment_log != "" && is_array($arrPaymentData.payment_log) && count($arrPaymentData.payment_log) > 0}-->
        <tr>
            <th>決済ログ</th>
            <td>
            <a href="javascript:void();" onclick="$('#plg_yfcapi_log').slideToggle();">決済ログ表示・非表示</a>
            <br />
            <table id="plg_yfcapi_log" style="display:none;" class="list">
                <tr>
                    <th>時間</th>
                    <th>内容</th>
                </tr>
            <!--{foreach from=$arrPaymentData.payment_log item=data key=key}-->
                <!--{foreach from=$data item=sdata key=skey}-->
                <tr>
                    <td>
                    <!--{$skey|h}-->
                    </td>
                    <td>
                    <!--{foreach from=$sdata item=val key=vkey}-->
                       <!--{if $val != ""}-->
                       <!--{$vkey|h}-->=
                         <!--{if is_array($val)}-->
                            <!--{$val|var_dump|h}-->
                         <!--{else}-->
                            <!--{$val|h}-->
                         <!--{/if}-->,
                       <!--{/if}-->
                    <!--{/foreach}-->
                    </td>
                </tr>
                <!--{/foreach}-->
            <!--{/foreach}-->
            </table>
            </td>
        </tr>
        <!--{/if}-->

    </table>


    <h2>受注詳細</h2>
<!--{/if}-->