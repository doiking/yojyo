<!--{*
 * templates/default/bloc/yfc_cvs.tpl
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 *}-->
<script type="text/javascript">//<![CDATA[
var send = true;

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

//]]>
</script>

        <div id="payment_form_loading" style="<!--{if !$tpl_is_loding}-->display:none;<!--{/if}-->">
            <div class="information">
                <p>決済処理中です。そのまま、お待ち下さい。</p>
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
                <p>下記項目をご選択ください。<br />
                入力後、一番下の「決済する」ボタンをクリックしてください。</p>
                <!--{assign var=key1 value="payment"}-->
                <!--{assign var=key2 value="cvs"}-->
                <p class="attention"><!--{$arrErr[$key1]}--><!--{$arrErr[$key2]}--></p>
            </div>
            <table summary="コンビニ選択">
                <colgroup width="20%"></colgroup>
                <colgroup width="80%"></colgroup>
                <tr>
                    <th class="alignC">選択<span class="attention">※</span></th>
                    <th class="alignC">お支払いをするコンビニの種類</th>
                </tr>
                <!--{foreach from=$arrPaymentInfo.conveni item=data key=id name=cvsloop}-->
                <tr>
                    <th class="alignC">
                        <input type="radio" id="cvs_id_<!--{$data|h}-->" name="<!--{$key2}-->" value="<!--{$data|h}-->" style="<!--{$arrErr[$key2]|sfGetErrorColor}-->" <!--{$data|sfGetChecked:$arrForm[$key2].value}--> />
                    </th>
                    <td>
                        <label for="cvs_id_<!--{$data|h}-->">
                            <!--{$arrCVS[$data]|h}-->
                            <img style="vertical-align: bottom;" src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->cvs_logo_<!--{$data|h}-->.jpg" />
                        </label>
                    </td>
                </tr>
                <!--{/foreach}-->
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
                    <!--{if $tpl_btn_next}-->
                        <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_next.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_next.jpg" alt="次へ" border="0" name="next" id="next" />
                    <!--{else}-->
                        <input type="image" onclick="return fnCheckSubmit('next');" onmouseover="chgImgImageSubmit('<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_regist_on.jpg',this)" onmouseout="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_regist.jpg',this)" src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->btn_regist.jpg" alt="決済完了ページへ"  name="next" id="next" />
                    <!--{/if}-->
                    </li>
                </ul>
            </div>

       </div><!--{* /payment_form_body *}-->
