<!--{*
 * templates/admin/order/order_index_list_body_add.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
            <!--{assign var=plg_col_payid value=$smarty.const.MDL_YFCAPI_ORDER_COL_PAYID}-->
            <!--{assign var=plg_col_paystatus value=$smarty.const.MDL_YFCAPI_ORDER_COL_PAYSTATUS}-->
            <!--{assign var=plg_col_examresult value=$smarty.const.MDL_YFCAPI_ORDER_COL_EXAMRESULT}-->
            <!--{assign var=plg_col_slip_on value="plg_col_slip_on"}-->
            <td class="center">

                <!--{if $arrResults[cnt][$plg_col_slip_on] == '1' &&
                        (($arrResults[cnt][$plg_col_payid] == MDL_YFCAPI_PAYID_CREDIT && $arrResults[cnt][$plg_col_paystatus] == MDL_YFCAPI_ACTION_STATUS_COMP_AUTH) ||
                        ($arrResults[cnt][$plg_col_payid] == MDL_YFCAPI_PAYID_DEFERRED && $arrResults[cnt][$plg_col_examresult] == MDL_YFCAPI_DEFERRED_AVAILABLE))
                }-->
                <input type="checkbox" name="plg_yfcapi_shipment_entry_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="plg_yfcapi_shipment_entry_order_id_<!--{$arrResults[cnt].order_id}-->"/><label for="plg_yfcapi_shipment_entry_order_id_<!--{$arrResults[cnt].order_id}-->">一括登録</label><br>
                <a href="./" onclick="eccube.setModeAndSubmit('plg_yfcapi_shipment_entry', 'order_id', <!--{$arrResults[cnt].order_id}-->); return false;"><span class="icon_class">個別登録</span></a>
                <!--{else}-->
                    &minus;
                <!--{/if}-->
            </td>

            <td class="center">

                <!--{if ($arrResults[cnt][$plg_col_payid] == MDL_YFCAPI_PAYID_CREDIT && $arrResults[cnt][$plg_col_paystatus] != MDL_YFCAPI_ACTION_STATUS_COMMIT_SETTLEMENT) ||
                        ($arrResults[cnt][$plg_col_payid] == MDL_YFCAPI_PAYID_DEFERRED && $arrResults[cnt][$plg_col_paystatus] != MDL_YFCAPI_DEFERRED_AUTH_CANCEL)
                }-->
                <input type="checkbox" name="plg_yfcapi_credit_cancel_order_id[]" value="<!--{$arrResults[cnt].order_id}-->" id="plg_yfcapi_credit_cancel_id_<!--{$arrResults[cnt].order_id}-->"/><label for="plg_yfcapi_credit_cancel_<!--{$arrResults[cnt].order_id}-->">一括取消</label><br>
                <a href="./" onclick="eccube.setModeAndSubmit('plg_yfcapi_credit_cancel', 'order_id', <!--{$arrResults[cnt].order_id}-->); return false;"><span class="icon_class">個別取消</span></a>
                <!--{else}-->
                    &minus;
                <!--{/if}-->
            </td>