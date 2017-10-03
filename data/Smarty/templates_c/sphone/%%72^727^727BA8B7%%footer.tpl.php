<?php /* Smarty version 2.6.27, created on 2017-09-22 18:58:16
         compiled from ./footer.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', './footer.tpl', 28, false),array('modifier', 'h', './footer.tpl', 28, false),)), $this); ?>

<!--▼ FOOTER-->
<footer class="global_footer">

    <nav class="guide_area">
        <p>
            <a rel="external" href="<?php echo ((is_array($_tmp=@HTTP_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
abouts/<?php echo ((is_array($_tmp=((is_array($_tmp=@DIR_INDEX_PATH)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
">当サイトについて</a>
            <a rel="external" href="<?php echo ((is_array($_tmp=@HTTPS_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
contact/<?php echo ((is_array($_tmp=((is_array($_tmp=@DIR_INDEX_PATH)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
">お問い合わせ</a><br />
            <a rel="external" href="<?php echo ((is_array($_tmp=@HTTP_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
order/<?php echo ((is_array($_tmp=((is_array($_tmp=@DIR_INDEX_PATH)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
">特定商取引法に基づく表記</a>
            <a rel="external" href="<?php echo ((is_array($_tmp=@HTTP_URL)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
guide/privacy.php">プライバシーポリシー</a>
        </p>
    </nav>

    <p class="copyright"><small>Copyright &copy; 2017 養生.jp All rights reserved.</small></p>

</footer>
<!--▲ FOOTER-->