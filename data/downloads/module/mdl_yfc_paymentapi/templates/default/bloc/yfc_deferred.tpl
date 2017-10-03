<!--{*
 * templates/default/bloc/yfc_deferred.tpl
 * Copyright(c)2015, YAMATO CREDIT & FINANCE CO.,LTD. All Rights Reserved.
 *}-->
<script type="text/javascript">//<![CDATA[
    $(function() {
        <!--{if $auto_submit}-->
        fnModeSubmit('next', '', '');
        <!--{/if}-->
    });
//]]></script>

<!--{if count($arrErr) > 0}-->
  <p class="attention">
    後払い決済の与信を行いましたが、大変申し訳ございません、今回のご注文分に関してはお取扱いできません。<br /><br />
    お手数ですが、下にある「戻る」ボタンで決済画面に戻っていただき、後払い以外の別の決済手段にて再度<br />
    ご注文いただけますようお願いいたします。<br />
    <br /><br />
    ※なお、ブラウザの「Back」ボタンを押して戻られますと、確認画面に戻ってしまいますのでご注意ください。
  </p>
  <br />
  <ul>
    <!--{foreach item=err from=$arrErr}-->
      <!--{if is_array($err)}-->
        <!--{foreach key=errKey item=errItem from=$err}-->
          <li>
            <!--{$errKey}-->：<!--{$errItem}-->
          </li>
        <!--{/foreach}-->
      <!--{else}-->
        <li>
          <strong><!--{$err}--></strong>
        </li>
      <!--{/if}-->
    <!--{/foreach}-->
  </ul>
  <div class="btn_area">
    <ul>
      <li>
        <input type="image" onclick="return fnModeSubmit('return', '', '');" onmouseover="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back_on.jpg',this)" onmouseout="chgImg('<!--{$TPL_URLPATH}-->img/button/btn_back.jpg',this)" src="<!--{$TPL_URLPATH}-->img/button/btn_back.jpg" alt="戻る" border="0" name="back" id="back"/>
      </li>
    </ul>
  </div>
<!--{else}-->
  <div id="payment_form_loading">
    <div class="information">
      <p>決済処理中です。しばらくお待ち下さい。</p>
    </div>
    <table>
      <tr>
        <td class="alignC">
          <img src="<!--{$smarty.const.MDL_YFCAPI_MEDIAFILE_URL}-->loading.gif" />
        </td>
      </tr>
    </table>
  </div>
<!--{/if}-->