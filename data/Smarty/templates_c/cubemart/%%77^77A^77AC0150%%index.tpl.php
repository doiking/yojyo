<?php /* Smarty version 2.6.27, created on 2017-09-22 08:31:48
         compiled from /home/www.yojyo.jp/html/../data/Smarty/templates/cubemart/order/index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', '/home/www.yojyo.jp/html/../data/Smarty/templates/cubemart/order/index.tpl', 6, false),array('modifier', 'h', '/home/www.yojyo.jp/html/../data/Smarty/templates/cubemart/order/index.tpl', 6, false),array('modifier', 'escape', '/home/www.yojyo.jp/html/../data/Smarty/templates/cubemart/order/index.tpl', 19, false),array('modifier', 'nl2br', '/home/www.yojyo.jp/html/../data/Smarty/templates/cubemart/order/index.tpl', 23, false),array('modifier', 'regex_replace', '/home/www.yojyo.jp/html/../data/Smarty/templates/cubemart/order/index.tpl', 35, false),)), $this); ?>


<div id="undercolumn">
    <div id="undercolumn_order" class="margin-section">
        <div class="ui-headline page-title">
            <h2 class="title"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tpl_title'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</h2>
        </div>
        <table class="table table-bordered ui-table" summary="特定商取引に関する法律に基づく表記"><col width="20%"><col width="80%"><?php $_from = ((is_array($_tmp=$this->_tpl_vars['arrOrderItem'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['order']):
?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o1'): ?><tr><th>販売業者</th>
                <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_company'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o2'): ?><tr><th>運営責任者</th>
                <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_manager'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o3'): ?><tr><th>住所</th>
                <td>〒<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_zip01'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_zip02'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
<br><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrPref'][$this->_tpl_vars['arrOrder']['law_pref']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_addr01'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_addr02'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o5'): ?><tr><th>電話番号</th>
                <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_tel01'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_tel02'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_tel03'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o6'): ?><tr><th>FAX番号</th>
                <td><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_fax01'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_fax02'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
-<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_fax03'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o7'): ?><tr><th>メールアドレス</th>
                <td><a href="mailto:<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_email'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'hex') : smarty_modifier_escape($_tmp, 'hex')); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_email'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'hexentity') : smarty_modifier_escape($_tmp, 'hexentity')); ?>
</a></td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o8'): ?><tr><th>URL</th>
                <td><a href="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_url'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_url'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</a></td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o9'): ?><tr><th>商品以外の必要代金</th>
                <td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term01'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o10'): ?><tr><th>注文方法</th>
                <td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term02'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o11'): ?><tr><th>支払方法</th>
                <td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term03'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o12'): ?><tr><th>支払期限</th>
                <td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term04'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o13'): ?><tr><th>引渡し時期</th>
                <td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term05'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o14'): ?><tr><th>返品・交換について</th>
                <td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder']['law_term06'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
            </tr><?php endif; ?>
<?php if (((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, '/^a[0-9]+/i', 'add') : smarty_modifier_regex_replace($_tmp, '/^a[0-9]+/i', 'add')) == 'add'): ?>
    <tr>
        <?php $this->assign('key', "plg_customtradelaw_name_".($this->_tpl_vars['order'])); ?>
        <th><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</th>
        <?php $this->assign('key', "plg_customtradelaw_value_".($this->_tpl_vars['order'])); ?>
        <td><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrOrder'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)))) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
    </tr>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</table></div>
</div>