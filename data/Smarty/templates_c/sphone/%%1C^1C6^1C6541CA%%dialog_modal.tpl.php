<?php /* Smarty version 2.6.27, created on 2017-09-26 11:01:36
         compiled from /home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/dialog_modal.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/dialog_modal.tpl', 1, false),)), $this); ?>
<div id="<?php echo ((is_array($_tmp=$this->_tpl_vars['dialog_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" class="dialog-modal">
    <div class="dialog-inner">
        <h2 class="title dialog-title"><?php echo ((is_array($_tmp=$this->_tpl_vars['dialog_title'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</h2>
        <div class="dialog-content"></div>
    </div>
</div>