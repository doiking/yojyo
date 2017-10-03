<?php /* Smarty version 2.6.27, created on 2017-09-22 14:39:01
         compiled from order/mail_view.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', 'order/mail_view.tpl', 28, false),array('modifier', 'h', 'order/mail_view.tpl', 28, false),array('modifier', 'nl2br', 'order/mail_view.tpl', 31, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@TEMPLATE_ADMIN_REALDIR)."admin_popup_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<table class="form">
    <tr>
        <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tpl_subject'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</td>
    </tr>
    <tr>
        <td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tpl_body'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    </tr>
</table>

<div class="btn-area">
        <ul>
            <li><a class="btn-action" href="javascript:;" onclick="window.close(); return false;"><span class="btn-next">ウインドウを閉じる</span></a></li>
        </ul>
    </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@TEMPLATE_ADMIN_REALDIR)."admin_popup_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>