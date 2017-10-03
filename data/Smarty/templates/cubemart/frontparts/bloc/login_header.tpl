<!--{if !$tpl_login}-->
<script type="text/javascript">//<![CDATA[
    $(function(){
        var $login_email = $('#header_login_area input[name=login_email]');

        if (!$login_email.val()) {
            $login_email
                .val('メールアドレス')
                .css('color', '#AAA');
        }

        $login_email
            .focus(function() {
                if ($(this).val() == 'メールアドレス') {
                    $(this)
                        .val('')
                        .css('color', '#000');
                }
            })
            .blur(function() {
                if (!$(this).val()) {
                    $(this)
                        .val('メールアドレス')
                        .css('color', '#AAA');
                }
            });

        $('#header_login_form').submit(function() {
            if (!$login_email.val()
                || $login_email.val() == 'メールアドレス') {
                if ($('#header_login_area input[name=login_pass]').val()) {
                    alert('メールアドレス/パスワードを入力して下さい。');
                }
                return false;
            }
            return true;
        });
    });
//]]></script>
<!--{/if}-->
<!--{strip}-->
    <div class="block_outer ui-section pull-right">
        <div id="header_login_area" class="clearfix">
            <form name="header_login_form" id="header_login_form" method="post" action="<!--{$smarty.const.HTTPS_URL}-->frontparts/login_check.php"<!--{if !$tpl_login}--> onsubmit="return eccube.checkLoginFormInputted('header_login_form')"<!--{/if}-->>
                <input type="hidden" name="mode" value="login" />
                <input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
                <input type="hidden" name="url" value="<!--{$smarty.server.REQUEST_URI|h}-->" />
                <div class="block_body clearfix">
                    <!--{if $tpl_login}-->
                        <ul class="formlist list-inline">
                            <li class="mypage">
                                <a href="<!--{$smarty.const.HTTPS_URL}-->mypage/login.php"><i class="fa fa-user fa-fw"></i>MYページ</a>
                                <!--{*
                                * ボタンに画像を使うときはコメントアウトを解除
                                * <a href="<!--{$smarty.const.HTTPS_URL}-->mypage/login.php"><img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/common/btn_header_mypage.jpg" alt="MYページ" /></a>
                                *}-->
                            </li>
                            <li>
                                <!--{if !$tpl_disable_logout}-->
                                    <a href="#" onclick="eccube.fnFormModeSubmit('header_login_form', 'logout', '', ''); return false;"><i class="fa fa-lock fa-fw"></i>ログアウト</a>
                                    <!-- <input type="submit" class="btn btn-border btn-styled btn-sm" onclick="eccube.fnFormModeSubmit('header_login_form', 'logout', '', ''); return false;" value="ログアウト" /> -->
                                    <!--{*
                                    * ボタンに画像を使うときはコメントアウトを解除
                                    * <input type="image" class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/common/btn_header_logout.jpg" onclick="eccube.fnFormModeSubmit('header_login_form', 'logout', '', ''); return false;" alt="ログアウト" />
                                    *}-->
                                <!--{/if}-->
                            </li>
                            <li class="hidden-xs">
                            <a href="<!--{$smarty.const.CART_URL}-->"><i class="fa fa-shopping-cart fa-fw"></i>カゴの中を見る</a>
                                <!--{*
                                * ボタンに画像を使うときはコメントアウトを解除
                                * <a href="<!--{$smarty.const.CART_URL}-->"><img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/common/btn_header_cart.jpg" alt="カゴの中を見る" /></a>
                                *}-->
                            </li>
                        </ul>
                    <!--{else}-->
                        <ul class="formlist list-inline">
                            <li class="entry">
                                <a href="<!--{$smarty.const.ROOT_URLPATH}-->entry/kiyaku.php"><i class="fa fa-lock fa-fw"></i>会員登録</a>
                                <!--{*
                                * ボタンに画像を使うときはコメントアウトを解除
                                * <a href="<!--{$smarty.const.ROOT_URLPATH}-->entry/kiyaku.php"><img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/common/btn_header_entry.jpg" alt="会員登録" /></a>
                                *}-->
                            </li>
                            <li class="mypage">
                                <a href="<!--{$smarty.const.HTTPS_URL}-->mypage/login.php"><i class="fa fa-user fa-fw"></i>ログイン</a>
                            </li>
                            <li class="hidden-xs">
                                <a href="<!--{$smarty.const.CART_URL}-->"><i class="fa fa-shopping-cart fa-fw"></i>カゴの中を見る</a>
                                <!--{*
                                * ボタンに画像を使うときはコメントアウトを解除
                                * <a href="<!--{$smarty.const.CART_URL}-->"><img class="hover_change_image" src="<!--{$TPL_URLPATH}-->img/common/btn_header_cart.jpg" alt="カゴの中を見る" /></a>
                                *}-->
                            </li>
                        </ul>
                    <!--{/if}-->
                </div>
            </form>
        </div>
    </div>
    <div class="description pull-left">養生用品なら -養生用品の通販は養生.jp‎</div>
<!--{/strip}-->
