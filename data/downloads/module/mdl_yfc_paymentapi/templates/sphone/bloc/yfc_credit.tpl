<!--{*
 * templates/sphone/bloc/yfc_credit.tpl
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
function fnAutoLoadSubmit() {
    var mode = 'load';
    send = false;
    fnModeSubmit('load','','');
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
        <section id="payment_form_loading" style="<!--{if !$tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information end">
                <p>決済処理中です。しばらくお待ち下さい。</p>
            </div>
            <div class="bubbleBox hot">
                <div class="bubble_announce clearfix">
                    <img src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->loading.gif" />
                </div>
            </div>
        </section>
        <section id="payment_form_body" style="<!--{if $tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information end">
                <p>下記項目にご入力ください。「<span class="attention">※</span>」印は入力必須項目です。<br />
                        入力後、一番下の「決済する」ボタンをクリックしてください。</p>
                <!--{assign var=key value="payment"}-->
                <p class="attention"><!--{$arrErr[$key]}--></p>
            </div>
                <h3 class="subtitle">クレジットカード情報入力</h3>
                <dl class="form_entry">
                    <!--{if $tpl_is_luggage_card == true}-->
                    <dt>
                        選択<span class="attention">※</span>
                    </dt>
                    <dd>
                    <!--{assign var=key value="use_registed_card"}-->
                    <span class="attention"><!--{$arrErr[$key]}--></span>
                    <!--{assign var=key1 value="cardKey"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input type="checkbox" id="<!--{$key}-->" name="<!--{$key}-->" value="1" <!--{if $arrForm[$key].value != ""}-->checked="checked"<!--{/if}--> onclick="fnDispTarget()" />
                    <label for="<!--{$key}-->"><span class="fb">登録カードを利用する</span></label><br />
                    <!--{foreach from=$arrResults.cardData item=data}-->
                    <input type="radio" name="card_key" id="<!--{$data.cardKey|h}-->" value="<!--{$data.cardKey|h}-->"
                        <!--{if $arrForm[$key1].value==$data.cardKey}-->checked="checked"<!--{/if}-->
                        <!--{if $tpl_plg_target_seq==$data.cardKey}-->checked="checked"<!--{/if}-->
                    />
                    <label for="<!--{$data.cardKey|h}-->">
                    カード番号：<!--{$data.maskingCardNo|h}-->&nbsp;&nbsp;
                    有効期限：<!--{$data.cardExp|substr:0:2|h}-->/<!--{$data.cardExp|substr:2:2|h}-->
                    <!--{if $data.subscriptionFlg|h =='1'}-->※予約販売利用有り<!--{/if}--><br />
                    </label>
                    <!--{/foreach}-->
                    <div class="btn_area">
                        <a rel="external" href="javascript:void(fnCheckSubmit('deleteCard'));" class="btn_sub"/>選択したカードを削除する</a>
                    </div>
                    </dd>
                    <!--{/if}-->
                    <dt class="disp_target">
                        カード番号<span class="attention">※</span>
                    </dt>
                    <dd class="disp_target">
                    <!--{assign var=key1 value="card_no"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="16" class="box120" />
                    </dd>

                    <dt class="disp_target">
                        カード有効期限<span class="attention">※</span>
                    </dt>
                    <dd class="disp_target">
                    <!--{assign var=key1 value="card_exp_month"}-->
                    <!--{assign var=key2 value="card_exp_year"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <span class="attention"><!--{$arrErr[$key2]}--></span>
                    <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->"  class="boxShort data-role-none">
                    <option value="">&minus;&minus;</option>
                    <!--{html_options options=$arrMonth selected=$arrForm[$key1].value|default:''}-->
                    </select>月
                    &nbsp;/&nbsp;
                    20<select name="<!--{$key2}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" class="boxShort data-role-none">
                    <option value="">&minus;&minus;</option>
                    <!--{html_options options=$arrYear selected=$arrForm[$key2].value|default:''}-->
                    </select>年
                    </dd>

                    <dt class="disp_target">
                        カード名義<span class="attention">※</span>
                    </dt>
                    <dd class="disp_target">
                        <!--{assign var=key value="card_owner"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" size="40" class="box120" />
                        <p class="mini">
                            <span class="attention">カードに記載の名前をご記入下さい。ご本人名義のカードをご使用ください。</span>半角英文字入力（例：YAMADA TAROU）
                            <span>
                            <br />入力例<br />
                                ○YAMADA TAROU&nbsp;&nbsp;×YAMADATAROU&nbsp;&nbsp;×Yamada Tarou
                            </span>
                        </p>
                    </dd>
                    <dt>
                        セキュリティコード<span class="attention">※</span>
                    </dt>
                    <dd>
                        <!--{assign var=key value="security_code"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->"  size="4" class="box60" />
                        <p class="mini"><span class="attention">主にカード裏面の署名欄に記載されている末尾３桁～４桁の数字をご記入下さい。</span>半角入力 (例: 123)</p>
                        <img src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->security_code_info.png" />
                    </dd>
                    <dt>
                        支払い方法<span class="attention">※</span>
                    </dt>
                    <dd>
                        <!--{assign var=key1 value="pay_way"}-->
                        <span class="attention"><!--{$arrErr[$key1]}--></span>
                        <select name="<!--{$key1}-->" style="<!--{$arrErr[$key1]|sfGetErrorColor}-->" class="boxShort data-role-none">
                        <!--{html_options options=$arrPayMethod selected=$arrForm[$key1].value|default:''}-->
                        </select>
                    </dd>
                <!--{if $tpl_is_regist_card == true || $tpl_is_reserve == true}-->
                    <dt class="disp_target">
                        カード情報登録
                    </dt>
                    <dd class="disp_target">
                    <!--{if $tpl_is_reserve == true}-->
                        <input type="hidden" id="register" name="register_card" value="1" />
                        <span class="attention">予約商品販売はカードお預かりが必須になります。</span><br />
                        カード情報を登録すると次回より入力無しで購入出来ます。<br />
                        カード情報は当店では保管いたしません。<br />
                        委託する決済代行会社にて安全に保管されます。<br />
                    <!--{else}-->
                        <!--{assign var=key value="register_card"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input onclick="fnCheckMaxRegist();" type="checkbox" id="<!--{$key}-->" name="<!--{$key}-->" value="1" <!--{if $arrForm[$key].value != ""}-->checked<!--{/if}--> />
                        <label for="<!--{$key}-->"><span class="fb">このカードを登録する。</span></label>
                        <p class="mini">カード情報を登録すると次回より入力無しで購入出来ます。<br />カード情報は当店では保管いたしません。<br />委託する決済代行会社にて安全に保管されます。</p>
                    <!--{/if}-->
                    </dd>
                <!--{/if}-->
                </dl>

            <p>
                        以上の内容で間違いなければ、下記「決済する」ボタンをクリックしてください。<br />
                        <span class="attention">※画面が切り替るまで少々時間がかかる場合がございますが、そのままお待ちください。</span>
            </p>

            <div class="btn_area">
                <ul class="btn_btm">

                        <li>
                            <a rel="external" href="javascript:void(fnCheckSubmit('next'));" class="btn"/>決済する</a>
                        </li>

                        <li>
                            <a rel="external" href="javascript:void(fnCheckSubmit('return'));" class="btn_back"/>戻る</a>
                        </li>
                </ul>
            </div>
       </section><!--{* /payment_form_body *}-->

