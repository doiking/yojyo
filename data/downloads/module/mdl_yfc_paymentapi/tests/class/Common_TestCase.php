<?php
//実行時エラー出力
error_reporting(E_ALL);
ini_set('display_errors', 1);

//EC-CUBE
$HOME = realpath(dirname(__FILE__)) . "/../../../../../..";
//REPLACEクラスはEC-CUBE標準を利用する
require_once($HOME . "/tests/class/replace/SC_Display_Ex.php");
require_once($HOME . "/tests/class/replace/SC_Response_Ex.php");
require_once($HOME . "/tests/class/replace/SC_Utils_Ex.php");
require_once($HOME . "/tests/class/test/util/Test_Utils.php");
require_once($HOME . "/tests/class/test/util/User_Utils.php");

//その他必要なファイルを取得
//Module
$MDLDIR = realpath(dirname(__FILE__)) . '/../..';
require_once($MDLDIR . "/tests/require.php");

/**
 * 全テストケースの基底クラスです。
 * SC_Queryのテスト以外は基本的にこのクラスを継承して作成してください。
 *
 */
class Common_TestCase extends PHPUnit_Framework_TestCase
{

  /** SC_Query インスタンス */
  protected $objQuery;

  /** 期待値 */
  protected $expected;
  /** 実際の値 */
  protected $actual;

  protected function setUp()
  {
    $this->objQuery = SC_Query_Ex::getSingletonInstance('', true);
    $this->objQuery->begin();
  }

  protected function tearDown()
  {
    $this->objQuery->rollback();
    $this->objQuery = null;
  }

  /**
   * 各テストfunctionの末尾で呼び出し、期待値と実際の値の比較を行います。
   * 呼び出す前に、$expectedに期待値を、$actualに実際の値を導入してください。
   */
  protected function verify($message = null)
  {
    $this->assertEquals($this->expected, $this->actual, $message);
  }
}

