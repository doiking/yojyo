<?php
/**
 * モジュール設定値「メールの追跡情報表示機能 」を利用する場合はtrue、利用しない場合はfalse
 *
 * @param array
 * @param Smarty
 */
function smarty_function_ycf_is_deliv_disp($params, &$smarty)
{
    //モジュール基本クラス取得
    $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
    //モジュール設定値取得
    $arrMdlSetting = $objMdl->getUserSettings();

    if ($arrMdlSetting['ycf_deliv_disp'] == '0') {
        $smarty->assign($params['key'], true);
        return;
    }

    $smarty->assign($params['key'], false);
    return;
}