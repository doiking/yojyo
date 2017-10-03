<!--{*
 * templates/admin/basis_payment_input_add.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->

<!--{if $plg_yfcapi_payid == MDL_YFCAPI_PAYID_CREDIT}-->
            <tr>
                <th>支払種別<span class="attention"> *</span></th>
                <td>
                    <!--{assign var=key value="credit_pay_methods"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <!--{html_checkboxes name="$key" options=$arrPayMethod|escape selected=$arrForm[$key].value}-->
                    <span style="font-size:80%"><br />
                    ※有効にする支払い種別を選択して下さい。<br />
                    </span>
                </td>
            </tr>
            <tr>
                <th>本人認証サービス(3Dセキュア)</th>
                <td>
                    <!--{assign var=key value="TdFlag"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <!--{html_radios name=$arrForm[$key].keyname options=$arrEnableFlags selected=$arrForm[$key].value separator="&nbsp;"}-->
                    <span style="font-size:80%"><br />
                    ※ご利用端末がPC、かつ3Dセキュア対象カードの場合、本人認証が実行されます。<br />
                    &nbsp;&nbsp;スマートフォンは3Dセキュア対象外となっております。
                    </span>
                </td>
            </tr>
<!--{elseif $plg_yfcapi_payid == MDL_YFCAPI_PAYID_CVS}-->
            <tr>
                <th>コンビニ選択<span class="attention"> *</span></th>
                <td>
                    <!--{assign var=key value="conveni"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <!--{html_checkboxes name="$key" options=$arrCVS|escape selected=$arrForm[$key].value}-->

                    <span style="font-size:80%"><br />
                    ご契約していて利用出来るコンビニを選択して下さい。
                    </span>
                </td>
            </tr>
<!--{elseif $plg_yfcapi_payid == MDL_YFCAPI_PAYID_EDY}-->
<!--{elseif $plg_yfcapi_payid == MDL_YFCAPI_PAYID_MOBILEEDY}-->
<!--{elseif $plg_yfcapi_payid == MDL_YFCAPI_PAYID_SUICA}-->
<!--{elseif $plg_yfcapi_payid == MDL_YFCAPI_PAYID_MOBILESUICA}-->
<!--{elseif $plg_yfcapi_payid == MDL_YFCAPI_PAYID_WAON}-->
<!--{elseif $plg_yfcapi_payid == MDL_YFCAPI_PAYID_MOBILEWAON}-->
<!--{elseif $plg_yfcapi_payid == MDL_YFCAPI_PAYID_NETBANK}-->
<!--{/if}-->

<!--{if $plg_yfcapi_payid != "" && $plg_yfcapi_payid != MDL_YFCAPI_PAYID_CVS}-->
<!--{* 共通設定項目 *}-->
            <tr>
                <th>決済完了案内タイトル</th>
                <td>
                    <!--{assign var=key value="order_mail_title1"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$arrForm[$key].keyname}-->" value="<!--{$arrForm[$key].value|h}-->" size="30" class="box30" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <span class="attention">（上限<!--{$arrForm[$key].length}-->文字）</span>
                    <span style="font-size:80%">
                    </span>
                </td>
            </tr>
            <tr>
                <th>決済完了案内本文</th>
                <td>
                    <!--{assign var=key value="order_mail_body1"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <textarea name="<!--{$arrForm[$key].keyname}-->" maxlength="<!--{$arrForm[$key].length}-->" cols="60" rows="4" class="area60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm[$key].value|h}--></textarea>
                    <span class="attention">（上限<!--{$arrForm[$key].length}-->文字）</span>
                </td>
            </tr>
<!--{elseif $plg_yfcapi_payid == MDL_YFCAPI_PAYID_CVS}-->
            <!--{foreach from=$arrCVS item=cvs_name key=cvs_key}-->
            <tr>
                <th><!--{$cvs_name|h}--><br />決済完了案内タイトル</th>
                <td>
                    <!--{assign var=key value="order_mail_title_`$cvs_key`"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <input type="text" name="<!--{$arrForm[$key].keyname}-->" value="<!--{$arrForm[$key].value|h}-->" size="30" class="box30" maxlength="<!--{$arrForm[$key].length}-->" style="<!--{$arrErr[$key]|sfGetErrorColor}-->" />
                    <span class="attention">（上限<!--{$arrForm[$key].length}-->文字）</span>
                </td>
            </tr>
            <tr>
                <th><!--{$cvs_name|h}--><br />決済完了案内本文</th>
                <td>
                    <!--{assign var=key value="order_mail_body_`$cvs_key`"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <textarea name="<!--{$arrForm[$key].keyname}-->" maxlength="<!--{$arrForm[$key].length}-->" cols="60" rows="4" class="area60" style="<!--{$arrErr[$key]|sfGetErrorColor}-->"><!--{"\n"}--><!--{$arrForm[$key].value|h}--></textarea>
                    <span class="attention">（上限<!--{$arrForm[$key].length}-->文字）</span>
                </td>
            </tr>
            <!--{/foreach}-->
<!--{/if}-->
