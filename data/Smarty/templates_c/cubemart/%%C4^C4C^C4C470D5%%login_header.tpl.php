<?php /* Smarty version 2.6.27, created on 2017-09-22 08:31:44
         compiled from /home/www.yojyo.jp/html/../data/Smarty/templates/cubemart/frontparts/bloc/login_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', '/home/www.yojyo.jp/html/../data/Smarty/templates/cubemart/frontparts/bloc/login_header.tpl', 1, false),array('modifier', 'h', '/home/www.yojyo.jp/html/../data/Smarty/templates/cubemart/frontparts/bloc/login_header.tpl', 47, false),)), $this); ?>
<?php if (! ((is_array($_tmp=$this->_tpl_vars['tpl_login'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
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
<?php endif; ?>
<?php echo '<div class="block_outer ui-section pull-right"><div id="header_login_area" class="clearfix"><form name="header_login_form" id="header_login_form" method="post" action="'; ?><?php echo ((is_array($_tmp=@HTTPS_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?><?php echo 'frontparts/login_check.php"'; ?><?php if (! ((is_array($_tmp=$this->_tpl_vars['tpl_login'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><?php echo ' onsubmit="return eccube.checkLoginFormInputted(\'header_login_form\')"'; ?><?php endif; ?><?php echo '><input type="hidden" name="mode" value="login" /><input type="hidden" name="'; ?><?php echo ((is_array($_tmp=@TRANSACTION_ID_NAME)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?><?php echo '" value="'; ?><?php echo ((is_array($_tmp=$this->_tpl_vars['transactionid'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?><?php echo '" /><input type="hidden" name="url" value="'; ?><?php echo ((is_array($_tmp=((is_array($_tmp=$_SERVER['REQUEST_URI'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?><?php echo '" /><div class="block_body clearfix">'; ?><?php if (((is_array($_tmp=$this->_tpl_vars['tpl_login'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><?php echo '<ul class="formlist list-inline"><li class="mypage"><a href="'; ?><?php echo ((is_array($_tmp=@HTTPS_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?><?php echo 'mypage/login.php"><i class="fa fa-user fa-fw"></i>MYページ</a>'; ?><?php echo '</li><li>'; ?><?php if (! ((is_array($_tmp=$this->_tpl_vars['tpl_disable_logout'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><?php echo '<a href="#" onclick="eccube.fnFormModeSubmit(\'header_login_form\', \'logout\', \'\', \'\'); return false;"><i class="fa fa-lock fa-fw"></i>ログアウト</a><!-- <input type="submit" class="btn btn-border btn-styled btn-sm" onclick="eccube.fnFormModeSubmit(\'header_login_form\', \'logout\', \'\', \'\'); return false;" value="ログアウト" /> -->'; ?><?php echo ''; ?><?php endif; ?><?php echo '</li><li class="hidden-xs"><a href="'; ?><?php echo ((is_array($_tmp=@CART_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?><?php echo '"><i class="fa fa-shopping-cart fa-fw"></i>カゴの中を見る</a>'; ?><?php echo '</li></ul>'; ?><?php else: ?><?php echo '<ul class="formlist list-inline"><li class="entry"><a href="'; ?><?php echo ((is_array($_tmp=@ROOT_URLPATH)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?><?php echo 'entry/kiyaku.php"><i class="fa fa-lock fa-fw"></i>会員登録</a>'; ?><?php echo '</li><li class="mypage"><a href="'; ?><?php echo ((is_array($_tmp=@HTTPS_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?><?php echo 'mypage/login.php"><i class="fa fa-user fa-fw"></i>ログイン</a></li><li class="hidden-xs"><a href="'; ?><?php echo ((is_array($_tmp=@CART_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?><?php echo '"><i class="fa fa-shopping-cart fa-fw"></i>カゴの中を見る</a>'; ?><?php echo '</li></ul>'; ?><?php endif; ?><?php echo '</div></form></div></div><div class="description pull-left">養生用品なら -養生用品の通販は養生.jp‎</div>'; ?>
