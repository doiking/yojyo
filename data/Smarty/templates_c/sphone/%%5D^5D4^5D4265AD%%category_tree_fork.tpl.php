<?php /* Smarty version 2.6.27, created on 2017-09-26 11:01:36
         compiled from /home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/category_tree_fork.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/category_tree_fork.tpl', 23, false),array('modifier', 'h', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/category_tree_fork.tpl', 26, false),array('modifier', 'default', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/frontparts/bloc/category_tree_fork.tpl', 26, false),)), $this); ?>

<ul <?php if (((is_array($_tmp=$this->_tpl_vars['treeID'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) != ""): ?>id="<?php echo ((is_array($_tmp=$this->_tpl_vars['treeID'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php endif; ?> style="<?php if (((is_array($_tmp=$this->_tpl_vars['level'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) > 5 || ! ((is_array($_tmp=$this->_tpl_vars['display'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>display: none;<?php endif; ?>">
    <?php $_from = ((is_array($_tmp=$this->_tpl_vars['children'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['child']):
?>
        <li class="level<?php echo ((is_array($_tmp=$this->_tpl_vars['child']['level'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['child']['category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['tpl_category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> onmark<?php endif; ?>">
            <span class="category_header"></span><span class="category_body"><a rel="external" href="<?php echo ((is_array($_tmp=@ROOT_URLPATH)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
products/list.php?category_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['child']['category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['child']['category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['tpl_category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> class="onlink"<?php endif; ?>><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['child']['category_name'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
(<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['child']['product_count'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('default', true, $_tmp, 0) : smarty_modifier_default($_tmp, 0)); ?>
)</a></span>
            <?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['child']['category_id'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrParentID'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?>
                <?php $this->assign('disp_child', 1); ?>
            <?php else: ?>
                <?php $this->assign('disp_child', 0); ?>
            <?php endif; ?>
            <?php if (isset ( $this->_tpl_vars['child']['children'] )): ?>
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@SMARTPHONE_TEMPLATE_REALDIR)."frontparts/bloc/category_tree_fork.tpl", 'smarty_include_vars' => array('children' => ((is_array($_tmp=$this->_tpl_vars['child']['children'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'treeID' => "",'display' => ((is_array($_tmp=$this->_tpl_vars['disp_child'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'level' => ((is_array($_tmp=$this->_tpl_vars['child']['level'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            <?php endif; ?>
        </li>
    <?php endforeach; endif; unset($_from); ?>
</ul>