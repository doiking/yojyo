<?php
/*
*
* MultiReportPlugin
* Copyright(c) 2014 Cyber-Will Inc. All Rights Reserved.
*
* http://www.cyber-will.co.jp/
*
* This library is free software; you can redistribute it and/or
* modify it under the terms of the GNU Lesser General Public
* License as published by the Free Software Foundation; either
* version 2.1 of the License, or (at your option) any later version.
*
* This library is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
* Lesser General Public License for more details.
*
* You should have received a copy of the GNU Lesser General Public
* License along with this library; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// {{{ requires
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

/**
 * 複数帳票プラグインの設定クラス
 *
 * @package MultiReportPlugin
 * @author Cyber-Will Inc.
 * @version $Id: $
 */
class LC_Page_Plugin_MultiReportPlugin_Config extends LC_Page_Admin_Ex {
    
    var $arrForm = array();

    /**
     * 初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();
        $this->tpl_mainpage = PLUGIN_UPLOAD_REALDIR ."MultiReportPlugin/templates/config.tpl";
        $this->tpl_subtitle = "複数帳票プラグイン　設定";
    }

    /**
     * プロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $col = "*";
        $from = "plg_dtb_report";
        $objFormParam = new SC_FormParam_Ex();
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        $objFormParam->convParam();
        
        $arrForm = array();
        
        switch ($this->getMode()) {
        case 'edit':
            $arrForm = $objFormParam->getHashArray();
            $this->arrErr = $objFormParam->checkError();

            // エラーなしの場合にはデータを更新
            if (count($this->arrErr) == 0) {

                if ($arrForm['id'] !== "") {
                    // idが入っていればデータ更新
                    $this->updateData($arrForm);
                    $this->tpl_onload = "alert('更新が完了しました。');";
                } else {
                    // idが入っていなければ新規登録
                    $arrForm["id"] = $this->insertData($arrForm);
                    $this->tpl_onload = "alert('新規登録が完了しました。');";
                }

            }
            break;
        case 'pre_edit':
            $arrForm = $objFormParam->getHashArray();
            $arrForm = $objQuery->getRow($col, $from, 'id = ?', array($arrForm['id']));
            break;
        case 'delete':
            // 削除処理
            $arrForm = $objFormParam->getHashArray();
            $objQuery->delete($from, 'id = ?', array($arrForm['id']));
            unset($arrForm['id']);
            break;
        default:
            break;
        }
        // フォーム一覧を取得
        $this->arrReport = $objQuery->select($col, $from);

        $this->arrForm = $arrForm;
        $this->plug_id = $_GET['plugin_id'];
        $this->setTemplate($this->tpl_mainpage);
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }
    
    /**
     * パラメーター情報の初期化
     *
     * @param object $objFormParam SC_FormParamインスタンス
     * @return void
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam('id', 'id', INT_LEN, '', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('帳票の種類', 'name', 20, '', array('EXIST_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('帳票タイトル', 'report_title', 40, '', array('EXIST_CHECK','MAX_LENGTH_CHECK'));
        $objFormParam->addParam('帳票名メッセージ1', 'message1', 30, '', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('帳票名メッセージ2', 'message2', 30, '', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('帳票名メッセージ3', 'message3', 30, '', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('備考1', 'notes1', 50, '', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('備考2', 'notes2', 50, '', array('MAX_LENGTH_CHECK'));
        $objFormParam->addParam('備考3', 'notes3', 50, '', array('MAX_LENGTH_CHECK'));
        
    }

    /**
     *
     * @param type $arrData
     * @return type 
     */
    function updateData($arrData) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        // UPDATEする値を作成する。
        $sqlval = $objQuery->extractOnlyColsOf('plg_dtb_report', $arrData);
        unset($sqlval['id']);
        $where = "id = ?";
        // UPDATEの実行
        $objQuery->update('plg_dtb_report', $sqlval, $where, array($arrData['id']));
        $objQuery->commit();
    }

    /**
     *
     * @param type $arrData
     * @return type 
     */
    function insertData($arrData) {
        $objQuery =& SC_Query_Ex::getSingletonInstance();
        $objQuery->begin();
        
        // idのmaxを取得
        $arrData['id'] = $objQuery->max("id", "plg_dtb_report") + 1;
        
        
        // INSERTする値を作成する。
        $objQuery->insert('plg_dtb_report', $arrData);
        $objQuery->commit();
        return $arrData['id'];
    }

}
?>
