<?php /* Smarty version 2.6.27, created on 2017-08-21 16:49:16
         compiled from shopping/nonmember_input.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', 'shopping/nonmember_input.tpl', 25, false),array('modifier', 'h', 'shopping/nonmember_input.tpl', 26, false),array('modifier', 'sfGetChecked', 'shopping/nonmember_input.tpl', 48, false),)), $this); ?>

<div id="undercolumn">
    <div id="undercolumn_customer">
        <p class="flow_area"><img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['TPL_URLPATH'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
img/picture/img_flow_01.jpg" alt="購入手続きの流れ" /></p>
        <h2 class="title"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['tpl_title'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</h2>

        <div class="information">
            <p>下記項目にご入力ください。「<span class="attention">※</span>」印は入力必須項目です。<br />
                <?php if (((is_array($_tmp=@USE_MULTIPLE_SHIPPING)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) !== false): ?>
                    入力後、一番下の「上記のお届け先のみに送る」<br/>
                    または「複数のお届け先に送る」ボタンをクリックしてください。
                <?php else: ?>
                    入力後、一番下の「次へ」ボタンをクリックしてください。
                <?php endif; ?>
            </p>
        </div>

        <form name="form1" id="form1" method="post" action="?">
            <input type="hidden" name="<?php echo ((is_array($_tmp=@TRANSACTION_ID_NAME)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['transactionid'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
            <input type="hidden" name="mode" value="nonmember_confirm" />
            <input type="hidden" name="uniqid" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['tpl_uniqid'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
            <table summary=" ">
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@TEMPLATE_REALDIR)."frontparts/form_personal_input.tpl", 'smarty_include_vars' => array('flgFields' => 2,'emailMobile' => false,'prefix' => 'order_')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                <tr>
                    <th colspan="2">
                    <?php $this->assign('key', 'deliv_check'); ?>
                    <input type="checkbox" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="1" onclick="eccube.toggleDeliveryForm();" <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetChecked', true, $_tmp, 1) : SC_Utils_Ex::sfGetChecked($_tmp, 1)); ?>
 id="deliv_label" />
                    <label for="deliv_label"><span class="attention">お届け先を指定</span>　※上記に入力された住所と同一の場合は省略可能です。</label>
                    </th>
                </tr>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@TEMPLATE_REALDIR)."frontparts/form_personal_input.tpl", 'smarty_include_vars' => array('flgFields' => 1,'emailMobile' => false,'prefix' => 'shipping_')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </table>

            <?php if (((is_array($_tmp=@USE_MULTIPLE_SHIPPING)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) !== false): ?>
                <p class="alignC">この商品を複数のお届け先に送りますか？</p>
            <?php endif; ?>
            <div class="btn_area">
                <ul>
                    <?php if (((is_array($_tmp=@USE_MULTIPLE_SHIPPING)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) !== false): ?>
                        <li>
                            <input type="image" class="hover_change_image" src="<?php echo ((is_array($_tmp=$this->_tpl_vars['TPL_URLPATH'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
img/button/btn_singular.jpg" alt="上記のお届け先のみに送る" name="singular" id="singular" />
                        </li>
                        <li>
                            <a href="javascript:;" onclick="eccube.setModeAndSubmit('multiple', '', ''); return false">
                                <img class="hover_change_image" src="<?php echo ((is_array($_tmp=$this->_tpl_vars['TPL_URLPATH'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
img/button/btn_multiple.jpg" alt="複数のお届け先に送る" />
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <input type="image" class="hover_change_image" src="<?php echo ((is_array($_tmp=$this->_tpl_vars['TPL_URLPATH'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
img/button/btn_next.jpg" alt="次へ" name="singular" id="singular" />
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </form>
    </div>
</div>