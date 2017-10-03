<?php

$HOME = realpath(dirname(__FILE__)) . "/../../../..";
require_once($HOME . "/tests/class/Common_TestCase.php");

/**
 * SC_Util_Mdl_YFCApi_Cache::createCache()のテストクラス.
 *
 * @version $Id:$
 */
class SC_Util_Mdl_YFCApi_Cache_createCacheTest extends Common_TestCase
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
  public function testCreateCache_コード名が存在する場合_trueを返す()
    {
        $this->expected = true;
        $objCache = new SC_Util_Mdl_YFCApi_Cache_Ex_createCache();
        $this->actual = $objCache->createCache('status');

        $this->verify("存在コード名呼出");
  }

  public function testCreateCache_コード名が存在しない場合trueを返す()
    {
        $this->expected = true;
        $objCache = new SC_Util_Mdl_YFCApi_Cache_Ex_createCache();
        $this->actual = $objCache->createCache('test-code_not_exists');

        $this->verify("存在しないコード名呼出");
    }

  //////////////////////////////////////////
}
//テスト用サブクラス(PHP5.3.2以下の環境が存在するため)
class SC_Util_Mdl_YFCApi_Cache_Ex_createCache extends SC_Util_Mdl_YFCApi_Cache_Ex{
    public function createCache($codename){
        return parent::createCache($codename);
    }
}
