<?php
/**
 * Copyright(c)2014, Yamato Financial Co.,Ltd. All rights reserved.
 */
// {{{ requires
require_once(MDL_YFCAPI_PAGE_HELPEREX_PATH . 'LC_PageHelper_Mdl_YFCApi_Base_Ex.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_CVS_Ex.php');
require_once(MDL_YFCAPI_CLASSEX_PATH . 'client_extends/SC_Mdl_YFCApi_Client_Util_Ex.php');
// }}}
/**
 * 決済モジュール 決済画面ヘルパー：コンビニ決済
 */
class LC_PageHelper_Mdl_YFCApi_CVS extends LC_PageHelper_Mdl_YFCApi_Base_Ex {

    /**
     * パラメーター情報の初期化を行う.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param array $arrPaymentInfo
     * @param array $arrOrder 受注情報
     * @return void
     */
    function initParam(&$objFormParam, &$arrPaymentInfo, &$arrOrder) {
        $objFormParam->addParam("コンビニ選択", "cvs", INT_LEN, 'n', array("EXIST_CHECK", "MAX_LENGTH_CHECK", "NUM_CHECK"));
    }

    /**
     * 入力内容のチェックを行なう.
     *
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @return array 入力チェック結果の配列
     */
    function checkError(&$objFormParam) {
        $arrParam = $objFormParam->getHashArray();
        $objErr = new SC_CheckError_Ex($arrParam);
        $objErr->arrErr = $objFormParam->checkError();
        return $objErr->arrErr;
    }


    /**
     * 画面モード毎のアクションを行う
     *
     * @param string $mode Mode値
     * @param SC_FormParam_Ex $objFormParam SC_FormParam_Ex インスタンス
     * @param array $arrOrder 受注情報
     * @param LC_Page $objPage 呼出元ページオブジェクト
     * @return void
     */
    function modeAction($mode, &$objFormParam, &$arrOrder, &$objPage) {
        $objPage->arrCVS = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('cvs');

        $objPurchase = new SC_Helper_Purchase_Ex();

        switch($mode) {
        case 'next':
            $objPage->arrErr = $this->checkError($objFormParam);
            if (SC_Utils_Ex::isBlank($objPage->arrErr)) {
                // 決済実行
                $objClient = new SC_Mdl_YFCApi_Client_CVS_Ex();
                $result = $objClient->doPaymentRequest($arrOrder, $objFormParam->getHashArray(), $objPage->arrPaymentInfo);

                if ($result) {
                    $order_status = ORDER_PAY_WAIT;
                    //決済ステータスを「決済依頼済み」で記録
                    $sqlval[MDL_YFCAPI_ORDER_COL_PAYSTATUS] = MDL_YFCAPI_ACTION_STATUS_SEND_REQUEST;
                    $objQuery =& SC_Query_Ex::getSingletonInstance();
                    $objQuery->begin();
                    $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], $order_status, null, null, $sqlval);
                    $objQuery->commit();
                    $objPurchase->sendOrderMail($arrOrder['order_id']);
                    SC_Response_Ex::sendRedirect(SHOPPING_COMPLETE_URLPATH);
                    $objPage->actionExit();
                } else {
                    $arrErr = $objClient->getError();
                    $objPage->arrErr['payment'] = '※ 決済でエラーが発生しました。<br />' . implode('<br />', $arrErr);

                    //決済ステータスを「決済中断」に変更する
                    $sqlval[MDL_YFCAPI_ORDER_COL_PAYSTATUS] = MDL_YFCAPI_ACTION_STATUS_NG_TRANSACTION;
                    $objQuery =& SC_Query_Ex::getSingletonInstance();
                    $objQuery->begin();
                    $objPurchase->sfUpdateOrderStatus($arrOrder['order_id'], null, null, null, $sqlval);
                    $objQuery->commit();
                }
            }
        break;
        case 'return':
            $objPurchase->rollbackOrder($arrOrder['order_id'], ORDER_CANCEL, true);
            SC_Response_Ex::sendRedirect(SHOPPING_CONFIRM_URLPATH);
            SC_Response_Ex::actionExit();
        break;
        default:
        break;
        }
    }

    /**
     * 画面に設定するテンプレート名を返す
     *
     * @return string テンプレートファイル名
     */
    function getFormBloc() {
        $objMdl =& SC_Mdl_YFCApi_Ex::getInstance();
        $arrBlocId = $objMdl->getSubData('bloc_setting');
        $device_type_id = SC_Display_Ex::detectDevice();
        $bloc_id =  $arrBlocId['yfc_cvs'][ $device_type_id ];
        if ($bloc_id) {
            $objLayout = new SC_Helper_PageLayout_Ex();
            $arrBloc = $objLayout->getBlocs($device_type_id, 'bloc_id = ?', array($bloc_id), true);
            return $arrBloc[0]['tpl_path'];
        }
        return '';
    }

}
