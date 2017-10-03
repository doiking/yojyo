<!--{*
 * templates/admin/config.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->

<h1><span class="title">クロネコヤマト カード・後払い一体型決済モジュール〈埋込型〉</span></h1>
<p>
<br />クロネコｗｅｂコレクトをご利用いただく場合は、ヤマトフィナンシャル株式会社とご契約いただく必要がございます。<br />
クロネコ代金後払いをご利用いただく場合は、ヤマトクレジットファイナンス株式会社とご契約いただく必要がございます。<br /><br />
■クロネコｗｅｂコレクトのご契約をご希望の場合は<a href="http://www.yamatofinancial.jp/wc/index.html" target="_blank">こちら</a><br />
■クロネコ代金後払いのご契約をご希望の場合は<a href="http://www.yamato-credit-finance.co.jp/service/deferred.html" target="_blank">こちら</a><br />
■設定などのお問合せは<a href="http://www.yamatofinancial.jp/contact/index.html" target="_blank">こちら</a><br />
<br />

</p>
<form name="yfc_form" id="yfc_form" method="post" action="<!--{$smarty.server.REQUEST_URI|escape}-->">
  <input type="hidden" name="mode" value="edit">
  <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
  <table class="form">
    <colgroup width="20%"></colgroup>
    <colgroup width="80%"></colgroup>
    <!--{if $arrErr.err != ""}-->
    <tr>
      <td colspan="2"><span class="attention"><!--{$arrErr.err}--></span></td>
    </tr>
    <!--{/if}-->
    <tr>
      <th>
        <!--{assign var=key value="exec_mode"}-->
        <!--{$arrForm[$key].disp_name}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrExecMode selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>
    <tr>
      <th>
        <!--{assign var=key value="shop_id"}-->
        <!--{$arrForm[$key].disp_name}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled;<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" maxlength="<!--{$arrForm[$key].length}-->" size="13" >
      </td>
    </tr>
    <tr>
      <th>
        <!--{assign var=key value="enable_payment_type"}-->
        <!--{$arrForm[$key].disp_name}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <!--{html_checkboxes name=$key options=$arrPayments selected=$arrForm[$key].value separator='<br />'}-->
      </td>
    </tr>
    <tr>
      <th>
        <!--{assign var=key value="is_tpl_init"}-->
        <!--{$arrForm[$key].disp_name}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <input type="checkbox" name="<!--{$key}-->" value="1" <!--{if $arrForm[$key].value == 1}-->checked="checked"<!--{/if}--> id="<!--{$key}-->" /><label for="<!--{$key}-->">決済用テンプレートを初期化する。</label>
      </td>
    </tr>
    <tr>
      <th>
        <!--{assign var=key value="is_file_copy"}-->
        <!--{$arrForm[$key].disp_name}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <input type="checkbox" name="<!--{$key}-->" value="1" <!--{if $arrForm[$key].value == 1}-->checked="checked"<!--{/if}--> id="<!--{$key}-->" /><label for="<!--{$key}-->">ファイルのコピーをする。</label><br />
        <span class="red">※設定内容の登録時に下記のファイルが上書きされます。<br />
        初回導入時はファイルのコピーを行うことでモジュールの機能を実現します。<br /><br />
        下記ファイルのバックアップを推奨します。<br />
        すでにカスタマイズを行っている場合はご注意ください。<br />
        <!--{foreach from=$arrUpdateFile item="arrSource"}-->
          <br /><!--{$arrSource.disp}-->
        <!--{/foreach}-->
        </span>
      </td>
    </tr>
    <tr>
      <th>決済結果受取URL</th>
      <td>
        <!--{$smarty.const.MDL_YFCAPI_SETTLEMENT_URL}--><br />
        <span>*クロネコwebコレクト画面よりログイン頂き、「加盟店情報・動作環境設定」＞決済結果データ受信設定で「POSTで受信する」を選択後、決済結果受取URLに設定してください。</span>
      </td>
    </tr>
    <tr>
      <th>
        <!--{assign var=key value="use_option"}-->
        <!--{$arrForm[$key].disp_name}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrUseOption selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>
  </table>
  <p>
    以下はオプションサービスをご利用の方のみご入力下さい。<br /><br />
  </p>

  <table class="form">
    <colgroup width="20%"></colgroup>
    <colgroup width="80%"></colgroup>
    <tr>
      <th>
        <!--{assign var=key value="access_key"}-->
        <!--{$arrForm[$key].disp_name}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <input type="text" name="<!--{$key}-->" style="ime-mode:disabled;<!--{$arrErr[$key]|sfGetErrorColor}-->" value="<!--{$arrForm[$key].value}-->" maxlength="<!--{$arrForm[$key].length}-->" size="13" >
      </td>
    </tr>
    <tr>
      <th>
        <!--{assign var=key value="advance_sale"}-->
        <!--{$arrForm[$key].disp_name}-->
      </th>
      <td>
        <!--{if $arrErr[$key]}-->
        <div class="attention"><!--{$arrErr[$key]}--></div>
        <!--{/if}-->
        <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
          <!--{html_radios name=$key options=$arrAdvanceSale selected=$arrForm[$key].value separator='&nbsp;'}-->
        </span>
      </td>
    </tr>


  </table>
    <p>
        以下は「クロネコ代金後払い」をご利用の方のみご入力下さい。<br /><br />
    </p>

    <table class="form">
        <colgroup width="20%"></colgroup>
        <colgroup width="80%"></colgroup>
        <tr>
            <th>
                <!--{assign var=key value="ycf_str_code"}-->
                <!--{$arrForm[$key].disp_name}-->
            </th>
            <td>
                <!--{if $arrErr[$key]}-->
                <div class="attention"><!--{$arrErr[$key]}--></div>
                <!--{/if}-->
                <input type="text"
                       name="<!--{$key}-->"
                       style="ime-mode:disabled;<!--{$arrErr[$key]|sfGetErrorColor}-->"
                       value="<!--{$arrForm[$key].value}-->"
                       maxlength="<!--{$arrForm[$key].length}-->"
                       size="13"/>
            </td>
        </tr>
        <tr>
            <th>
                <!--{assign var=key value="ycf_str_password"}-->
                <!--{$arrForm[$key].disp_name}-->
            </th>
            <td>
                <!--{if $arrErr[$key]}-->
                <div class="attention"><!--{$arrErr[$key]}--></div>
                <!--{/if}-->
                <input type="text"
                       name="<!--{$key}-->"
                       style="ime-mode:disabled;<!--{$arrErr[$key]|sfGetErrorColor}-->"
                       value="<!--{$arrForm[$key].value}-->"
                       maxlength="<!--{$arrForm[$key].length}-->"
                       size="13"/>
            </td>
        </tr>
        <tr>
            <th>
                <!--{assign var=key value="ycf_send_div"}-->
                <!--{$arrForm[$key].disp_name}-->
            </th>
            <td>
                <!--{if $arrErr[$key]}-->
                <div class="attention"><!--{$arrErr[$key]}--></div>
                <!--{/if}-->
                <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                    <!--{html_radios name=$key options=$arrSendDiv selected=$arrForm[$key].value separator='&nbsp;'}-->
                </span>
            </td>
        </tr>
        <tr>
            <th>
                <!--{assign var=key value="ycf_ship_ymd"}-->
                <!--{$arrForm[$key].disp_name}-->
            </th>
            <td>
                <!--{if $arrErr[$key]}-->
                <div class="attention"><!--{$arrErr[$key]}--></div>
                <!--{/if}-->
                <input type="text"
                       name="<!--{$key}-->"
                       style="ime-mode:disabled;<!--{$arrErr[$key]|sfGetErrorColor}-->"
                       value="<!--{$arrForm[$key].value|default:3}-->"
                       maxlength="<!--{$arrForm[$key].length}-->"
                       size="13"/>日<br/>
                <span>当該日を経過して伝票番号が未登録の場合に「受注日＋出荷予定日」という設定でアラートを表示します。</span>
            </td>
        </tr>
        <tr>
            <th>
                <!--{assign var=key value="ycf_deliv_disp"}-->
                <!--{$arrForm[$key].disp_name}-->
            </th>
            <td>
                <!--{if $arrErr[$key]}-->
                <div class="attention"><!--{$arrErr[$key]}--></div>
                <!--{/if}-->
                <span style="<!--{$arrErr[$key]|sfGetErrorColor}-->">
                    <!--{html_radios name=$key options=$arrDelivDisp selected=$arrForm[$key].value separator='&nbsp;'}-->
                </span>
            </td>
        </tr>
        <tr>
          <th>
            <!--{assign var=key value="ycf_invoice_reissue_mail_address"}-->
            <!--{$arrForm[$key].disp_name}-->
          </th>
          <td>
            <!--{if $arrErr[$key]}-->
            <div class="attention"><!--{$arrErr[$key]}--></div>
            <!--{/if}-->
            <input type="text"
                   name="<!--{$key}-->"
                   style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
                   value="<!--{$arrForm[$key].value}-->"
                   maxlength="<!--{$arrForm[$key].length}-->"
                   size="50"/><br/>
            <span>請求書再発行時に通知メールを送信するアドレスを設定してください。</span>
          </td>
        </tr>
        <tr>
          <th>
            <!--{assign var=key value="ycf_invoice_reissue_mail_header"}-->
            <!--{$arrForm[$key].disp_name}-->
          </th>
          <td>
            <!--{if $arrErr[$key]}-->
            <div class="attention"><!--{$arrErr[$key]}--></div>
            <!--{/if}-->
            <textarea 
                   name="<!--{$key}-->"
                   style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
                   rows="5" cols="60"><!--{$arrForm[$key].value}--></textarea><br/>
            <span>請求書再発行時の通知メールのヘッダーを設定してください。</span>
          </td>
        </tr>
      <tr>
        <th>
          <!--{assign var=key value="ycf_invoice_reissue_mail_footer"}-->
          <!--{$arrForm[$key].disp_name}-->
        </th>
        <td>
          <!--{if $arrErr[$key]}-->
          <div class="attention"><!--{$arrErr[$key]}--></div>
          <!--{/if}-->
          <textarea
                  name="<!--{$key}-->"
                  style="<!--{$arrErr[$key]|sfGetErrorColor}-->"
                  rows="5" cols="60"><!--{$arrForm[$key].value}--></textarea><br/>
          <span>請求書再発行時の通知メールのフッターを設定してください。</span>
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
