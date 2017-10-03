<!--{*
 * templates/default/change_card.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<div id="mypagecolumn">

    <h2 class="title"><!--{$tpl_title|h}--></h2>
    <!--{include file=$tpl_navi}-->
    <div id="mycontents_area">
        <h3>現在登録されているカード情報</h3>
        <form name="form1" id="form1" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="deleteCard" />

        <!--{if !$arrData || $arrData.cardUnit == 0}-->
            <p>登録されているカード情報はありません。</p>
        <!--{else}-->
            <!--{if $arrErr}-->
            <div class="information">
                <p class="attention"><!--{$arrErr.card_key}--></p>
                <p class="attention"><!--{$arrErr.error}--></p>
            </div>
            <!--{/if}-->
            <!--{if $tpl_is_success}-->
            <div class="information">
                <p class="attention">正常に更新されました。</p>
            </div>
            <!--{/if}-->

            <!--{assign var=key1 value="card_key"}-->
            <table summary="クレジットカード選択">
                <colgroup width="10%"></colgroup>
                <colgroup width="5%"></colgroup>
                <colgroup width="85%"></colgroup>
                <tr>
                    <th class="alignC">選択<span class="attention">※</span></th>
                    <th class="alignC">キー</th>
                    <th class="alignC">登録カード番号選択</th>
                </tr>
                <!--{foreach from=$arrData.cardData item=data}-->
                <tr>
                    <th class="alignC">
                        <input type="radio" name="card_key" value="<!--{$data.cardKey|h}-->" <!--{if $arrForm[$key1].value==$data.cardKey}-->checked="checked"<!--{/if}--> />
                    </th>
                    <td class="alignC">
                        <!--{$data.cardKey|h}-->
                    </td>
                    <td>
                    カード番号: <!--{$data.maskingCardNo|h}-->&nbsp;&nbsp; 有効期限: <!--{$data.cardExp|substr:2:2|h}-->年<!--{$data.cardExp|substr:0:2|h}-->月
                    <!--{if $data.cardOwner != ''}-->&nbsp;&nbsp;カード名義：<!--{$data.cardOwner}--><!--{/if}-->
                    <!--{if $data.subscriptionFlg|h =='1'}--><br />※予約販売利用有り<!--{/if}-->
                    </td>
                </tr>
                <!--{/foreach}-->
            </table>

        <div class="btn_area">
            <ul>
                <li>
                    <input type="image" class="hover_change_image" src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_carddelete.jpg" alt="削除" name="delete" id="delete" />
                </li>
            </ul>
        </div>
        <!--{/if}-->
        </form>

        <h3>クレジットカード情報登録</h3>
        <form name="form2" id="form2" method="post" action="?">
        <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
        <input type="hidden" name="mode" value="registCard" />
        <div class="information">
            <p>下記項目にご入力ください。「<span class="attention">※</span>」印は入力必須項目です。<br />
                入力後、一番下の「登録する」ボタンをクリックしてください。</p>
            <!--{assign var=key value="error2"}-->
            <p class="attention"><!--{$arrErr[$key]}--></p>
        </div>

            <table summary="クレジットカード番号入力">
                <colgroup width="20%"></colgroup>
                <colgroup width="80%"></colgroup>
                <tr>
                    <th colspan="2" class="alignC"><!--{$tpl_title|h}-->カード情報登録</th>
                </tr>
                <tr>
                    <th class="alignR">
                        カード番号<span class="attention">※</span>
                    </th>
                    <td>
                    <!--{assign var=key1 value="card_no"}-->
                    <span class="attention"><!--{$arrErr[$key1]}--></span>
                    <input type="text" name="<!--{$key1}-->" value="<!--{$arrForm[$key1].value|h}-->" maxlength="<!--{$arrForm[$key1].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key1]|sfGetErrorColor}-->"  size="16" class="box140" />
                    </td>
                </tr>
                <tr>
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
                <tr>
                    <th class="alignR">
                        カード名義<span class="attention">※</span>
                    </th>
                    <td>
                        <!--{assign var=key value="card_owner"}-->
                        <span class="attention"><!--{$arrErr[$key]}--></span>
                        <input type="text" name="<!--{$key}-->" value="<!--{$arrForm[$key].value|h}-->" maxlength="<!--{$arrForm[$key].length}-->" style="ime-mode: disabled; <!--{$arrErr[$key]|sfGetErrorColor}-->" size="40" class="box160" />
                        <p class="mini"><span class="attention">カードに記載の名前をご記入下さい。ご本人名義のカードをご使用ください。</span>半角英文字入力（例：YAMADA TAROU）</p>
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
                        <p class="mini">※主にカード裏面の署名欄に記載されている末尾３桁～４桁の数字をご記入下さい。半角入力 (例: 123)</p>
                    </td>
                </tr>
            </table>

        <div class="btn_area">
            <ul>
                <li>
                    <input type="image" class="hover_change_image" src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_regist_card.jpg" alt="登録" name="regist" id="regist" />
                </li>
            </ul>
        </div>
        </form>
    </div>
</div>