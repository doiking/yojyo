<?php

$HOME = realpath(dirname(__FILE__)) . "/../../../..";
require_once($HOME . "/tests/class/Common_TestCase.php");

/**
 * SC_Util_Mdl_YFCApi_Cache::getCode()のテストクラス.
 *
 * @version $Id:$
 */
class SC_Util_Mdl_YFCApi_Cache_getCodeTest extends Common_TestCase
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
  public function testGetCode_コードデータにコードが存在する場合_コードの文字列を返す()
    {
        $this->expected = '決済手続き中';
        $this->actual = SC_Util_Mdl_YFCApi_Cache_Ex::getCode('status','21');

        $this->verify("存在コード呼出");
  }

  public function testGetCode_コードデータにコードが存在しない場合_NULLを返す()
    {
        $this->expected = NULL;
        $this->actual = SC_Util_Mdl_YFCApi_Cache_Ex::getCode('status','100');

        $this->verify("存在しないコード呼出");
    }

  public function testGetCode_コードデータが存在しない場合_NULLを返す()
    {
        $this->expected = NULL;
        $this->actual = SC_Util_Mdl_YFCApi_Cache_Ex::getCode('test-code_not_exists','100');

        $this->verify("存在しないコード呼出");
    }
    
  //////////////////////////////////////////
}

