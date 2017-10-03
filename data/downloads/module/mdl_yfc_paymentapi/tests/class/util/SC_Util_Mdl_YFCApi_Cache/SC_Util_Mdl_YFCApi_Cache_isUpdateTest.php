<?php

$HOME = realpath(dirname(__FILE__)) . "/../../../..";
require_once($HOME . "/tests/class/Common_TestCase.php");

/**
 * SC_Util_Mdl_YFCApi_Cache::isUpdate()のテストクラス.
 *
 * @version $Id:$
 */
class SC_Util_Mdl_YFCApi_Cache_isUpdateTest extends Common_TestCase
{


  protected function setUp()
  {
    parent::setUp();
    //テスト用コードファイル作成
    touch(MDL_YFCAPI_CODE_PATH."test-is_update.txt");
    //キャッシュ生成（※データ取得）
    $arrCode = SC_Util_Mdl_YFCApi_Cache_Ex::getCodeData('test-is_update');
  }

  protected function tearDown()
  {
    parent::tearDown();
    //テスト用コードファイルの削除
    @unlink(MDL_YFCAPI_CODE_PATH."test-is_update.txt");
    //キャッシュ削除
    @unlink(MDL_YFCAPI_CACHE_PATH."test-is_update.json");
  }

  /////////////////////////////////////////
  public function testIsUpdate_キャッシュよりTSVファイルが新しい場合_trueを返す()
    {

        //テスト用コードファイル削除
        @unlink(MDL_YFCAPI_CODE_PATH."test-is_update.txt");
        //1秒スリープ
        sleep(1);
        //テスト用コードファイル作成
        touch(MDL_YFCAPI_CODE_PATH."test-is_update.txt");

        $this->expected = true;
        $objCache = new SC_Util_Mdl_YFCApi_Cache_Ex_isUpdate();
        $this->actual = $objCache->isUpdate('test-is_update');

        $this->verify("TSVファイル更新済み");
  }

  public function testIsUpdate_TSVファイルよりキャッシュが新しい場合_falseを返す()
    {

        $this->expected = false;
        $objCache = new SC_Util_Mdl_YFCApi_Cache_Ex_isUpdate();
        $this->actual = $objCache->isUpdate('test-is_update');

        $this->verify("TSVファイル未更新");
  }
  //////////////////////////////////////////
}
//テスト用サブクラス(PHP5.3.2以下の環境が存在するため)
class SC_Util_Mdl_YFCApi_Cache_Ex_isUpdate extends SC_Util_Mdl_YFCApi_Cache_Ex{
    public function isUpdate($codename){
        return parent::isUpdate($codename);
    }
}
