<!--{*
 * templates/admin/config_yfcapi_utils.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->

<h1><span class="title">クロネコヤマト　カード・後払い一体型決済補助機能プラグイン</span></h1>
<p>
<br />■B2基本設定<br /><br />
</p>
<form name="yfc_form" id="yfc_form" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
  <input type="hidden" name="mode" value="edit">
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />

  <table class="form">
    <colgroup width="20%"></colgroup>
    <colgroup width="40%"></colgroup>
    <tr>
      <th>
        <!--{assign var=key value="claim_customer_code"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <input
            type="text"
            name="<!--{$key}-->"
            style="ime-mode:disabled;<!--{$arrErr[$key]|sfGetErrorColor}-->"
            value="<!--{$arrForm[$key].value}-->"
            maxlength="<!--{$arrForm[$key].length}-->"
            size="30"
            class="box30" />
          <span class="attention">半角数字<!--{$arrForm[$key].length}-->文字</span>
        </span>
      </td>
    </tr>
    <tr>
      <th>
        <!--{assign var=key value="claim_type_code"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <input
            type="text"
            name="<!--{$key}-->"
            style="ime-mode:disabled;<!--{$arrErr[$key]|sfGetErrorColor}-->"
            value="<!--{$arrForm[$key].value}-->"
            maxlength="<!--{$arrForm[$key].length}-->"
            size="6"
            class="box6" />
          <span class="attention">半角数字<!--{$arrForm[$key].length}-->文字</span>
        </span>
      </td>
    </tr>
    <tr>
      <th>
        <!--{assign var=key value="transportation_no"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <input
            type="text"
            name="<!--{$key}-->"
            style="ime-mode:disabled;<!--{$arrErr[$key]|sfGetErrorColor}-->"
            value="<!--{$arrForm[$key].value}-->"
            maxlength="<!--{$arrForm[$key].length}-->"
            size="6"
            class="box6" />
          <span class="attention">半角数字<!--{$arrForm[$key].length}-->文字</span>
        </span>
      </td>
    </tr>
  </table>

<p>
<br />■B2動作設定<br /><br />
</p>

  <table class="form">
    <colgroup width="20%"></colgroup>
    <colgroup width="40%"></colgroup>
    <tr>
      <th>
        <!--{assign var=key value="header_output"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrB2HeaderOutput selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="deliv_slip_type"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <table>
        <!--{foreach key=payment_id item=payment_name from=$arrPayment}-->
          <tr>
            <td style="border:0;"><!--{$payment_name|h}--></td>
            <td style="border:0;">
              <select name="<!--{$key}-->[<!--{$payment_id}-->]" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                <!--{if $payment_id == $smarty.const.MDL_YFCAPI_ECCUBE_PAYMENT_DAIBIKI}-->
                  <!--{html_options options=$arrB2DelivSlipType selected=$arrForm[$key].value[$payment_id]|default:$smarty.const.MDL_YFCAPI_DELIV_SLIP_TYPE_CORECT}-->
                <!--{else}-->
                  <!--{html_options options=$arrB2DelivSlipType selected=$arrForm[$key].value[$payment_id]}-->
                <!--{/if}-->
              </select>
            </td>
          </tr>
        <!--{/foreach}-->
        </table>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="cool_kb"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <table>
        <!--{foreach key=deliv_id item=deliv_name from=$arrDeliv}-->
          <tr>
            <td style="border:0;"><!--{$deliv_name|h}--></td>
            <td style="border:0;">
              <select name="<!--{$key}-->[<!--{$deliv_id}-->]" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                <!--{html_options options=$arrB2CoolKb selected=$arrForm[$key].value[$deliv_id]}-->
              </select>
            </td>
          </tr>
        <!--{/foreach}-->
        </table>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="tel_hyphenation"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrB2Hyphenation selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="zip_hyphenation"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrB2Hyphenation selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="service_deliv_mail_enable"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrB2Enable selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="service_deliv_mail_message"}-->
        <!--{$arrTitle[$key]}--><br />
        <span class="red">※「お届け予定eメール」を利用する場合は必須</span>
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
          <textarea
            name="<!--{$arrForm[$key].keyname}-->"
            cols="40"
            rows="4"
            class="area40"
            maxlength="<!--{$arrForm[$key].length}-->"
            style="<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm[$key].value|h}--></textarea>
          <span class="attention">(上限<!--{$arrForm[$key].length}-->文字)</span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="service_complete_mail_enable"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrB2Enable selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="service_complete_mail_message"}-->
        <!--{$arrTitle[$key]}--><br />
        <span class="red">※「お届け完了eメール」を利用する場合は必須</span>
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
          <textarea
            name="<!--{$arrForm[$key].keyname}-->"
            cols="40"
            rows="4"
            class="area40"
            maxlength="<!--{$arrForm[$key].length}-->"
            style="<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm[$key].value|h}--></textarea>
          <span class="attention">(上限<!--{$arrForm[$key].length}-->文字)</span>
      </td>
    </tr>


    <tr>
      <th>
        <!--{assign var=key value="output_order_type"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrB2OutputOrderType selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="posting_plan_mail_enable"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrB2Enable selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="posting_plan_mail_message"}-->
        <!--{$arrTitle[$key]}--><br />
        <span class="red">※「投函予定メール」を利用する場合は必須（ネコポスのみ）</span>
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
          <textarea
            name="<!--{$arrForm[$key].keyname}-->"
            cols="40"
            rows="4"
            class="area40"
            maxlength="<!--{$arrForm[$key].length}-->"
            style="<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm[$key].value|h}--></textarea>
          <span class="attention">(上限<!--{$arrForm[$key].length}-->文字)</span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="posting_complete_deliv_mail_enable"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrB2Enable selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="posting_complete_deliv_mail_message"}-->
        <!--{$arrTitle[$key]}--><br />
        <span class="red">※「投函完了メール(注文者宛)」を利用する場合は必須（ネコポスのみ）</span>
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
          <textarea
            name="<!--{$arrForm[$key].keyname}-->"
            cols="40"
            rows="4"
            class="area40"
            maxlength="<!--{$arrForm[$key].length}-->"
            style="<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm[$key].value|h}--></textarea>
          <span class="attention">(上限<!--{$arrForm[$key].length}-->文字)</span>
      </td>
    </tr>

  </table>

<p>
<br />■B2取込設定<br /><br />
</p>

  <table class="form">
    <colgroup width="20%"></colgroup>
    <colgroup width="40%"></colgroup>
    <tr>
      <th>
        <!--{assign var=key value="use_b2_format"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrB2UseFormat selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>

    <tr>
      <th>
        <!--{assign var=key value="use_b2_shipping_entry"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrB2Enable selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>
  </table>

<p>
<br />■Webコレクト動作設定<br /><br />
</p>

  <table class="form">
    <colgroup width="20%"></colgroup>
    <colgroup width="40%"></colgroup>

    <tr>
      <th>
        <!--{assign var=key value="delivery_service_code"}-->
        <!--{$arrTitle[$key]}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <table>
        <!--{foreach key=deliv_id item=deliv_name from=$arrDeliv}-->
          <tr>
            <td style="border:0;"><!--{$deliv_name|h}--></td>
            <td style="border:0;">
              <select name="<!--{$key}-->[<!--{$deliv_id}-->]" style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                <!--{html_options options=$arrDeliveryServiceCode selected=$arrForm[$key].value[$deliv_id]}-->
              </select>
            </td>
          </tr>
        <!--{/foreach}-->
        </table>
      </td>
    </tr>
  </table>

  <div class="btn-area">
    <ul>
      <li><a class="btn-action" href="javascript:;" onclick="fnFormModeSubmit('yfc_form', 'register', '', ''); return false;"><span class="btn-next">登録</span></a></li>&nbsp;&nbsp;&nbsp;
      <li><a class="btn-action" href="javascript:;" onClick="window.close(); return false;"><span class="btn-next">閉じる</span></a></li>
    </ul>
  </div>

</form>

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->
