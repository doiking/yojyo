<?php /* Smarty version 2.6.27, created on 2017-09-22 18:58:16
         compiled from /home/www.yojyo.jp/html/../data/Smarty/templates/sphone/order/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/order/index.tpl', 3, false),array('modifier', 'h', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/order/index.tpl', 3, false),array('modifier', 'escape', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/order/index.tpl', 20, false),array('modifier', 'nl2br', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/order/index.tpl', 26, false),array('modifier', 'regex_replace', '/home/www.yojyo.jp/html/../data/Smarty/templates/sphone/order/index.tpl', 43, false),)), $this); ?>


<section id="undercolumn"><!--☆特定商取引に関する法律に基づく表記 --><h2 class="title"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tpl_title'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</h2>
    <dl class="form_info"><?php $_from = ((is_array($_tmp=$this->_tpl_vars['arrOrderItem'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['order']):
?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o1'): ?><dt>販売業者</dt>
        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_company'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o2'): ?><dt>運営責任者</dt>
        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_manager'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o3'): ?><dt>住所</dt>
        <dd>〒<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_zip01'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_zip02'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
<br><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrPref'][$this->_tpl_vars['arrOrder']['law_pref']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_addr01'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_addr02'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o5'): ?><dt>電話番号</dt>
        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_tel01'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_tel02'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_tel03'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o6'): ?><dt>FAX番号</dt>
        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_fax01'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_fax02'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_fax03'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o7'): ?><dt>メールアドレス</dt>
        <dd><a href="mailto:<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_email'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'hex') : smarty_modifier_escape($_tmp, 'hex')); ?>
" rel="external"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_email'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'hexentity') : smarty_modifier_escape($_tmp, 'hexentity')); ?>
</a></dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o8'): ?><dt>URL</dt>
        <dd><a href="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_url'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" rel="external"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_url'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</a></dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o9'): ?><dt>商品以外の必要代金</dt>
        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term01'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o10'): ?><dt>注文方法</dt>
        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term02'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o11'): ?><dt>支払方法</dt>
        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term03'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o12'): ?><dt>支払期限</dt>
        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term04'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o13'): ?><dt>引渡し時期</dt>
        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term05'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</dd><?php endif; ?>

        <?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o14'): ?><dt>返品・交換について</dt>
        <dd><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term06'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</dd><?php endif; ?>
    
<?php if (((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, '/^a[0-9]+/i', 'add') : smarty_modifier_regex_replace($_tmp, '/^a[0-9]+/i', 'add')) == 'add'): ?>
    <?php $this->assign('key', "plg_customtradelaw_name_".($this->_tpl_vars['order'])); ?>
    <dt><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</dt>
    <?php $this->assign('key', "plg_customtradelaw_value_".($this->_tpl_vars['order'])); ?>
    <dd><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</dd>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</dl></section><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => 'frontparts/search_area.tpl', 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><!--▲CONTENTS-->