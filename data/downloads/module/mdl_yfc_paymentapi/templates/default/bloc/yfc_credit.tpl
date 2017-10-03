<!--{*
 * templates/default/bloc/yfc_credit.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<script type="text/javascript">//<![CDATA[
var send = true;
function fnCheckMaxRegist() {
    if($("input:radio[name=card_key]").length == 3) {
        alert("カードお預かりは３件までとなっております。");
        $("input:checkbox[name=register_card]").attr("checked", false);
        return false;
    }
    return true;
}
function fnCheckConfirm(mode) {
    switch(mode){
        case 'deleteCard':
            if(!window.confirm('一度削除したカード情報は、元に戻せません。\n削除しても宜しいですか？')){
                return false;
            }
            break;
    }
    return true;
}
function fnCheckSubmit(mode) {
    if(!fnCheckConfirm(mode)) return false;
    $('#payment_form_body').slideToggle();
    $('#payment_form_loading').slideToggle();

    if(send) {
        send = false;
        fnModeSubmit(mode,'','');
        return false;
    } else {
        alert("只今、処理中です。しばらくお待ち下さい。");
        return false;
    }
}
function fnDispTarget() {
    if($("#use_registed_card:checked").val()) {
        $(".disp_target").hide();
        $('#register').val(0);
    } else {
        $(".disp_target").show();
        $('#register').val(1);
    }
    return false;
}
$(function() {
            <!--{$tpl_payment_onload}-->
});
//]]>
</script>
        <div id="payment_form_loading" style="<!--{if !$tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information">
                <p>決済処理中です。しばらくお待ち下さい。</p>
            </div>
            <table summary="">
                <tr>
                <td class="alignC">
                    <img src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->loading.gif" />
                </td>
                </tr>
            </table>
        </div>
        <div id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information">
                <p>下記項目にご入力ください。「<span class="attention">※</span>」印は入力必須項目です。<br />
                入力後、一番下の「決済する」ボタンをクリックしてください。</p>
                <!--{assign var=key value="payment"}-->
                <p class="attention"><!--{$arrErr[$key]}--></p>
            </div>
            <table summary="クレジットカード番号入力">
                <colgroup width="20%"></colgroup>
                <colgroup width="80%"></colgroup>
                <!--{if $tpl_is_luggage_card == true}-->
                <tr>
                    <th class="alignR">
                        選択<span class="attention">※</span>
                    </th>
                    <td>
                    <!--{assign var=key  value="use_registed_card"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <!--{assign var=key1 value="cardKey"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input
                      type="checkbox"
                      id="<!--{$key}-->"
                      name="<!--{$key}-->"
                      value="1"
                      <!--{if $arrForm[$key].value != ""}-->checked="checked"<!--{/if}-->
                      onclick="fnDispTarget()" />
                    <label for="<!--{$key}-->">登録カードを利用する</label><br />
                    <!--{foreach from=$arrResults.cardData item=data}-->
                    <input
                      type="radio"
                      name="card_key"
                      value="<!--{$data.cardKey|h}-->"
                      <!--{if $arrForm[$key1].value==$data.cardKey}-->checked="checked"<!--{/if}-->
                      <!--{if $tpl_plg_target_seq==$data.cardKey}-->checked="checked"<!--{/if}-->
                    />
                    カード番号: <!--{$data.maskingCardNo|h}-->&nbsp;&nbsp;
                    有効期限: <!--{$data.cardExp|substr:0:2|h}-->/<!--{$data.cardExp|substr:2:2|h}-->
                    <!--{if $data.subscriptionFlg|h =='1'}-->※予約販売利用有り<!--{/if}-->
                    <br />
                    <!--{/foreach}-->
                    <div class="btn_area">
                        <input type="image" onclick="return fnCheckSubmit('deleteCard');" onmouseover="chgImg('<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_carddelete_mini_on.jpg',this)" onmouseout="chgImg('<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_carddelete_mini.jpg',this)" src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_carddelete_mini.jpg" alt="削除" border="0" name="delete" id="delete"/>
                    </div>
                    </td>
                </tr>
                <!--{/if}-->
                <tr class="disp_target">
                    <th class="alignR">
                        カード番号<span class="attention">※</span>
                    </th>
                    <td>
                    <!--{assign var=key1 value="card_no"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="25" class="box145" />
                    </td>
                </tr>
                <tr class="disp_target">
                    <th class="alignR">
                        カード有効期限<span class="attention">※</span>
                    </th>
                    <td>
                    <!--{assign var=key1 value="card_exp_month"}-->
                    <!--{assign var=key2 value="card_exp_year"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                    <option value="">&minus;&minus;</option>
                    <!--{html_options options=$arrMonth selected=$arrForm[$key1].value}-->
                    </select>月
                    &nbsp;/&nbsp;
                    20<select name="<!--{$key2}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->">
                    <option value="">&minus;&minus;</option>
                    <!--{html_options options=$arrYear selected=$arrForm[$key2].value}-->
                    </select>年
                    </td>
                </tr>
                <tr class="disp_target">
                    <th class="alignR">
                        カード名義<span class="attention">※</span>
                    </th>
                    <td>
                        <!--{assign var=key value="card_owner"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" size="40" class="box160" />
                        <p class="mini">
                            <span class="attention">カードに記載の名前をご記入下さい。ご本人名義のカードをご使用ください。</span>半角英文字入力<br />
                            入力例
                            <table style="border: none;font-size: 90%;margin: 0;width: 200px;float: left;">
                              <tr>
                                <td style="border: none;padding: 0 12px 0 0;">○YAMADA&nbsp;TAROU</td>
                                <td style="border: none;padding: 0 12px 0 0;">×YAMADATAROU</td>
                              </tr>
                              <tr>
                                <td style="border: none;padding: 0 12px 0 0;">&nbsp;</td>
                                <td style="border: none;padding: 0 12px 0 0;">×Yamada&nbsp;Tarou</td>
                              </tr>
                            </table>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th class="alignR">
                        セキュリティコード<span class="attention">※</span>
                    </th>
                    <td>
                        <!--{assign var=key value="security_code"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->"  size="4" class="box60" />
                        <p class="mini"><span class="attention">主にカード裏面の署名欄に記載されている末尾３桁～４桁の数字をご記入下さい。</span>半角入力 (例: 123)</p>
                        <img src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->security_code_info.png" />
                    </td>
                </tr>
                <tr>
                    <th class="alignR">
                        支払い方法<span class="attention">※</span>
                    </th>
                    <td>
                        <!--{assign var=key1 value="pay_way"}-->
                        <span class="attention"><!--{$arrErr[$key1]}--></span>
                        <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->">
                        <!--{html_options options=$arrPayMethod selected=$arrForm[$key1].value}-->
                        </select>
                    </td>
                </tr>
                <!--{if $tpl_is_regist_card == true || $tpl_is_reserve == true}-->
                <tr class="disp_target">
                    <th class="alignR">
                        カード情報登録
                    </th>
                    <td>
                    <!--{if $tpl_is_reserve == true}-->
                        <input type="hidden" id="register" name="register_card" value="1" />
                        <span class="attention">予約商品販売はカードお預かりが必須になります。</span><br />
                        カード情報を登録すると次回より入力無しで購入出来ます。<br />
                        カード情報は当店では保管いたしません。<br />
                        委託する決済代行会社にて安全に保管されます。<br />
                    <!--{else}-->
                        <!--{assign var=key value="register_card"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>

                        <input
                          onclick="fnCheckMaxRegist();"
                          type="checkbox"
                          id="<!--{$key}-->"
                          name="<!--{$key}-->"
                          value="1"
                          <!--{if $arrForm[$key].value != ""}-->checked<!--{/if}--> />
                        <label for="<!--{$key}-->">このカードを登録する。</label>
                        <p class="mini">カード情報を登録すると次回より入力無しで購入出来ます。<br />カード情報は当店では保管いたしません。<br />委託する決済代行会社にて安全に保管されます。</p>
                    <!--{/if}-->
                    </td>
                </tr>
                <!--{/if}-->
            </table>

            <table>
                <tr>
                    <td>
                        以上の内容で間違いなければ、下記「決済する」ボタンをクリックしてください。<br />
                        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
                    </td>
                </tr>
            </table>

            <div class="btn_area">
                <ul>
                    <li>
                        <input type="image" onclick="return fnCheckSubmit('return');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back" id="back"/>
                    </li>
                    <li>
                        <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImgImageSubmit('<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_regist_on.jpg',this)" onmouseout="chgImgImageSubmit('<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_regist.jpg',this)" src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_regist.jpg" alt="決済する"  name="next" id="next" />
                    </li>
                </ul>
            </div>

       </div><!--{* /payment_form_body *}-->