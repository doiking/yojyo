<?php /* Smarty version 2.6.27, created on 2017-09-22 08:29:53
         compiled from basis/tradelaw.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'script_escape', 'basis/tradelaw.tpl', 8, false),array('modifier', 'h', 'basis/tradelaw.tpl', 17, false),array('modifier', 'sfGetErrorColor', 'basis/tradelaw.tpl', 17, false),array('modifier', 'regex_replace', 'basis/tradelaw.tpl', 140, false),array('function', 'html_options', 'basis/tradelaw.tpl', 49, false),)), $this); ?>



<ul>
    <li>「項目追加」「削除」ボタンで項目の追加/削除ができます。<span class="attention">(EC-CUBE既定の項目削除は行えません。)</span></li>
    <li>項目をドラッグすることで並べ替えをすることができます。<span class="attention">(EC-CUBE既定の項目も並べ替えることができます。)</span></li>
    <li>チェックボックスのON/OFFで表示/非表示を切り替えることができます。<span class="attention">(EC-CUBE既定の項目も非表示にすることができます。)</span></li>
</ul><form name="form1" enctype="multipart/form-data" id="form1" method="post" action=""><input type="hidden" name="plg_customtradelaw_item_no" id="plg_customtradelaw_item_no" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['item_no'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
    <input type="hidden" name="<?php echo ((is_array($_tmp=@TRANSACTION_ID_NAME)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['transactionid'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"><input type="hidden" name="mode" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['tpl_mode'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"><div id="basis" class="contents-main">
        <table class="form"><?php $_from = ((is_array($_tmp=$this->_tpl_vars['arrOrder'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['order']):
?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o1'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
販売業者<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_company'); ?>
                    <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <?php endif; ?>
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="60" class="box60"><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                </td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o2'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
運営責任者<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_manager'); ?>
                    <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <?php endif; ?>
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="60" class="box60"><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                </td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o3'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
郵便番号<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key1', 'law_zip01'); ?>
                    <?php $this->assign('key2', 'law_zip02'); ?>
                    <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key1']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) || ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key1']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <?php endif; ?>
                    〒
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key1']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key1']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key1']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key1']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="6" class="box6">
                    -
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key2']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key2']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key2']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="6" class="box6"><a class="btn-normal" href="javascript:;" name="address_input" onclick="eccube.getAddress('<?php echo ((is_array($_tmp=@INPUT_ZIP_URLPATH)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
', 'law_zip01', 'law_zip02', 'law_pref', 'law_addr01'); return false;">所在地入力</a>
                </td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o4'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
所在地<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_pref'); ?>
                    <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <?php endif; ?>
                    <select class="top" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
"><option value="" selected>都道府県を選択</option><?php echo smarty_function_html_options(array('options' => ((is_array($_tmp=$this->_tpl_vars['arrPref'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)),'selected' => ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))), $this);?>
</select><br><?php $this->assign('key', 'law_addr01'); ?><?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <?php endif; ?>
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" size="60" class="box60" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
"><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                    <br><?php echo ((is_array($_tmp=@SAMPLE_ADDRESS1)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
<br><?php $this->assign('key', 'law_addr02'); ?><?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?><span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <?php endif; ?>
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" size="60" class="box60" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
"><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                    <br><?php echo ((is_array($_tmp=@SAMPLE_ADDRESS2)) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o5'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
TEL<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key1', 'law_tel01'); ?>
                    <?php $this->assign('key2', 'law_tel02'); ?>
                    <?php $this->assign('key3', 'law_tel03'); ?>
                    <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key1']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) || ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) || ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key3']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key1']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key3']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <?php endif; ?>
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key1']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key1']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key1']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key1']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="6" class="box6"> -
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key2']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key2']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key2']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="6" class="box6"> -
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key3']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key3']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key3']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key3']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="6" class="box6"></td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o6'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
FAX</th>
                <td>
                    <?php $this->assign('key1', 'law_fax01'); ?>
                    <?php $this->assign('key2', 'law_fax02'); ?>
                    <?php $this->assign('key3', 'law_fax03'); ?>
                    <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key1']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) || ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) || ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key3']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key1']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                        <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key3']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <?php endif; ?>
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key1']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key1']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key1']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key1']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="6" class="box6"> -
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key2']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key2']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key2']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key2']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="6" class="box6"> -
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key3']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key3']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key3']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key3']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="6" class="box6"></td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o7'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
メールアドレス<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_email'); ?>
                    <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="60" class="box60"></td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o8'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
URL<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_url'); ?>
                    <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
" size="60" class="box60"><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                </td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o9'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
商品代金以外の必要料金<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_term01'); ?>
                    <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <textarea name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" cols="60" rows="8" class="area60" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
"><?php echo "\n"; ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</textarea><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                </td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o10'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
注文方法<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_term02'); ?>
                    <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <textarea name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" cols="60" rows="8" class="area60" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
"><?php echo "\n"; ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</textarea><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                </td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o11'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
支払方法<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_term03'); ?>
                    <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <textarea name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" cols="60" rows="8" class="area60" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
"><?php echo "\n"; ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</textarea><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                </td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o12'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
支払期限<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_term04'); ?>
                    <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <textarea name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" cols="60" rows="8" class="area60" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
"><?php echo "\n"; ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</textarea><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                </td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o13'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
引き渡し時期<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_term05'); ?>
                    <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <textarea name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" cols="60" rows="8" class="area60" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
"><?php echo "\n"; ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</textarea><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                </td>
            </tr><?php endif; ?><?php if (((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) == 'o14'): ?><tr><th><input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" /><input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
返品・交換について<span class="attention"> *</span></th>
                <td>
                    <?php $this->assign('key', 'law_term06'); ?>
                    <span class="attention"><?php echo ((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
</span>
                    <textarea name="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['keyname'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" cols="60" rows="8" class="area60" style="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('sfGetErrorColor', true, $_tmp) : SC_Utils_Ex::sfGetErrorColor($_tmp)); ?>
"><?php echo "\n"; ?>
<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</textarea><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
                </td>
            </tr><?php endif; ?>
<?php if (((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, '/^a[0-9]+/i', 'add') : smarty_modifier_regex_replace($_tmp, '/^a[0-9]+/i', 'add')) == 'add'): ?>
    <tr class="add_item">
      <th>
        <?php $this->assign('key', "plg_customtradelaw_name_".($this->_tpl_vars['order'])); ?>
        <input type="hidden" name="plg_customtradelaw_order[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" />
        <?php if (((is_array($_tmp=$this->_tpl_vars['arrErr'][$this->_tpl_vars['key']])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp))): ?>
        <span class="attention">※項目名が入力されていません。</span><br />
        <?php endif; ?>
        <input type="checkbox" name="plg_customtradelaw_disp[]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
"<?php if (in_array ( ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) , ((is_array($_tmp=$this->_tpl_vars['arrDisp'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)) )): ?> checked<?php endif; ?> />
        <input type="text" name="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" size="30" value="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
" /><br />
        <button type="button" class="delete_item" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
">削除</button>
      </th>
      <td>
        <?php $this->assign('key', "plg_customtradelaw_value_".($this->_tpl_vars['order'])); ?>
        <textarea name="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" maxlength="<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
" cols="60" rows="8" class="area60"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['value'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)))) ? $this->_run_mod_handler('h', true, $_tmp) : smarty_modifier_h($_tmp)); ?>
</textarea><span class="attention"> (上限<?php echo ((is_array($_tmp=$this->_tpl_vars['arrForm'][$this->_tpl_vars['key']]['length'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
文字)</span>
      </td>
    </tr>
<?php endif; ?>
<?php endforeach; endif; unset($_from); ?>
</table>
<button type="button" id="plg_customtradelaw_add_item">項目追加</button>
<div class="btn-area">
            <ul><li><a class="btn-action" href="javascript:;" onclick="eccube.fnFormModeSubmit('form1', '<?php echo ((is_array($_tmp=$this->_tpl_vars['tpl_mode'])) ? $this->_run_mod_handler('script_escape', true, $_tmp) : smarty_modifier_script_escape($_tmp)); ?>
', '', ''); return false;"><span class="btn-next">この内容で登録する</span></a></li>
            </ul></div>
    </div>
</form>