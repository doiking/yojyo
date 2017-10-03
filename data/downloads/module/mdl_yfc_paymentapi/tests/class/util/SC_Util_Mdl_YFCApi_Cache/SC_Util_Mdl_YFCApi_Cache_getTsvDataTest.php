<?php

$HOME = realpath(dirname(__FILE__)) . "/../../../..";
require_once($HOME . "/tests/class/Common_TestCase.php");

/**
 * SC_Util_Mdl_YFCApi_Cache::getTsvData()のテストクラス.
 *
 * @version $Id:$
 */
class SC_Util_Mdl_YFCApi_Cache_getTsvDataTest extends Common_TestCase
{


  protected function setUp()
  {
    parent::setUp();
  }

  protected function tearDown()
  {
    parent::tearDown();
  }

  /////////////////////////////////////////
  /*
   *  基本的にtrueしか返さない,
   *  falseが帰る場合はパーミッションまたは書き込み失敗時のみ
   */
  public function testGetTsvData_コード名が存在する場合_コードの配列を返す()
    {
        $this->expected = array(
            '0' => '決済依頼済み',
            '1' => '決済申込完了',
            '2' => '入金完了（速報）',
            '3' => '入金完了（確報）',
            '4' => '与信完了',
            '5' => '予約受付完了',
            '11' => '購入者都合エラー',
            '12' => '加盟店都合エラー',
            '13' => '決済機関都合エラー',
            '14' => 'その他システムエラー',
            '15' => '予約販売与信エラー',
            '16' => '決済依頼取消エラー',
            '20' => '決済中断',
            '21' => '決済手続き中',
            '30' => '精算確定待ち',
            '31' => '精算確定',
            '40' => '取消',
            '17' => '金額変更ＮＧ',
            '50' => '3Dセキュア認証中'
        );
        $objCache = new SC_Util_Mdl_YFCApi_Cache_Ex_getTsvData();
        $this->actual = $objCache->getTsvData('status');

        $this->verify("存在コード名呼出");
  }

  public function testGetTsvData_コード名が存在しない場合空の配列を返す()
    {
        $this->expected = array();
        $objCache = new SC_Util_Mdl_YFCApi_Cache_Ex_getTsvData();
        $this->actual = $objCache->getTsvData('test-code_not_exists');

        $this->verify("存在しないコード名呼出");
    }

  //////////////////////////////////////////
}
//テスト用サブクラス(PHP5.3.2以下の環境が存在するため)
class SC_Util_Mdl_YFCApi_Cache_Ex_getTsvData extends SC_Util_Mdl_YFCApi_Cache_Ex{
    public function getTsvData($codename){
        return parent::getTsvData($codename);
    }
}
