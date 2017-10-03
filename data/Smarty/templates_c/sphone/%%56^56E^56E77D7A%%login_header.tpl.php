<?php /* Smarty version 2.6.27, created on 2017-09-26 11:01:36
         compiled from /home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/login_header.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/login_header.tpl', 24, false),array('modifier', 'h', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/login_header.tpl', 25, false),array('modifier', 'n2s', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/login_header.tpl', 27, false),array('modifier', 'default', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/login_header.tpl', 27, false),)), $this); ?>

<nav class="header_navi">
    <?php if (((is_array($_tmp=$this->_tpl_vars['tpl_login'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
        <p class="guest">ようこそ <a href="<?php echo ((is_array($_tmp=@HTTPS_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
mypage/login.php" rel="external"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tpl_name1'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
 <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tpl_name2'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
さん</a></p>
        <?php if (((is_array($_tmp=@USE_POINT)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) !== false): ?>
            <p>所持ポイント<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tpl_user_point'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('n2s', true, $_tmp) : smarty_modifier_n2s($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
pt</p>
        <?php endif; ?>
    <?php else: ?>
        <p class="guest">ようこそ ゲストさん</p>
        <p><a rel="external" href="<?php echo ((is_array($_tmp=@HTTPS_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
entry/kiyaku.php">新規会員登録</a></p>
    <?php endif; ?>
</nav>