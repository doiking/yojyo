<?php

$HOME = realpath(dirname(__FILE__)) . "/../../../..";
require_once($HOME . "/tests/class/Common_TestCase.php");

/**
 * SC_Util_Mdl_YFCApi::getFormatedDate()のテストクラス.
 *
 * @version $Id:$
 */
class SC_Util_Mdl_YFCApi_getFormatedDateTest extends Common_TestCase
{


    protected function setUp()
    {
        //parent::setUp();
    }

    protected function tearDown()
    {
        //parent::tearDown();
    }

    public function testGetCodeDat_関数に渡す日時が空文字だった場合_空文字が返される()
    {
        $date = '';
        $this->expected = '';
        $this->actual = SC_Util_Mdl_YFCApi_Ex::getFormatedDate($date);

        $this->verify("関数に渡す日時が空文字だった場合の確認");
    }

    public function testGetCodeDat_関数に渡す日時が2015年03月02日でフォーマット指定が無い場合_フォーマットがデフォルトのYmdで20150302が返される()
    {
        $date = '2015-03-02';
        $this->expected = '20150302';
        $this->actual = SC_Util_Mdl_YFCApi_Ex::getFormatedDate($date);

        $this->verify("関数に渡す日時が2015年03月02日でフォーマット指定が無い場合の確認");
    }

    public function testGetCodeDat_関数に渡す日時が2015ハイフン03ハイフン02でフォーマット指定が空文字の場合_2015ハイフン03ハイフン02が返される()
    {
        $date = '2015-03-02';
        $format = '';
        $this->expected = '2015-03-02';
        $this->actual = SC_Util_Mdl_YFCApi_Ex::getFormatedDate($date, $format);

        $this->verify("関数に渡す日時が2015年03月02日でフォーマット指定が空文字の場合の確認");
    }

    public function testGetCodeDat_関数に渡す日時が2015年03月02日15時20分30秒でフォーマットYmdHisの場合_20150302152030が返される()
    {
        $date = '2015-03-02 15:20:30';
        $format = 'YmdHis';
        $this->expected = '20150302152030';
        $this->actual = SC_Util_Mdl_YFCApi_Ex::getFormatedDate($date, $format);

        $this->verify("関数に渡す日時が2015年03月02日15時20分30秒でフォーマットYmdHisの場合の確認");
    }

}