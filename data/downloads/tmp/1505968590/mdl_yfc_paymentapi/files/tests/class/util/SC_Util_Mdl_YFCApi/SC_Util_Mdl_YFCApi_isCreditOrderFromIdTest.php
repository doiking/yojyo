<?php

$HOME = realpath(dirname(__FILE__)) . "/../../../..";
require_once($HOME . "/tests/class/Common_TestCase.php");

/**
 * SC_Util_Mdl_YFCApi::isCreditOrderFromIdのテストクラス.
 *
 * @version $Id:$
 */
class SC_Util_Mdl_YFCApi_isCreditOrderFromIdTest extends Common_TestCase
{


    protected function setUp()
    {
        //parent::setUp();
    }

    protected function tearDown()
    {
        //parent::tearDown();
    }

    /**
     * ダミーデータ
     * order_id	payment_id	payment_method	module_code
     * 1	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 2	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 3	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 4	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 5	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 6	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 7	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 8	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 9	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 10	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 11	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 12	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 13	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 14	3	銀行振込	NULL
     * 15	5	クロネコ代金後払い決済	mdl_yfc_paymentapi
     * 16	6	クレジットカード決済	mdl_yfc_paymentapi
     * 17	6	クレジットカード決済	mdl_yfc_paymentapi
     * 18	6	クレジットカード決済	mdl_yfc_paymentapi
     * 19	6	クレジットカード決済	mdl_yfc_paymentapi
     * 20	6	クレジットカード決済	mdl_yfc_paymentapi
     * 21	6	クレジットカード決済	mdl_yfc_paymentapi
     * 22	6	クレジットカード決済	mdl_yfc_paymentapi
     * 23	6	クレジットカード決済	mdl_yfc_paymentapi
     * 24	6	クレジットカード決済	mdl_yfc_paymentapi
     * 25	6	クレジットカード決済	mdl_yfc_paymentapi
     * 26	6	クレジットカード決済	mdl_yfc_paymentapi
     * 27	6	クレジットカード決済	mdl_yfc_paymentapi
     * 28	6	クレジットカード決済	mdl_yfc_paymentapi
     * 29	7	コンビニ決済	mdl_yfc_paymentapi
     * 30	7	コンビニ決済	mdl_yfc_paymentapi
     * 31	7	コンビニ決済	mdl_yfc_paymentapi
     * 32	7	コンビニ決済	mdl_yfc_paymentapi
     * 33   8   別モジュール決済	other
     */
    public function test_クレジット購入だけの場合_trueが返る()
    {
        $this->assertEquals(true, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(16)));
        $this->assertEquals(true, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(17)));
        $this->assertEquals(true, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(18)));
        $this->assertEquals(true, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(16,17,18,19)));
    }

    public function test_クレジット購入だけではない場合_falseが返る()
    {
        $this->assertEquals(false, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(1)));
        $this->assertEquals(false, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(2)));
        $this->assertEquals(false, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(14)));
        $this->assertEquals(false, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(14,15)));
        $this->assertEquals(false, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(29,30)));
        $this->assertEquals(false, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(14,15,16)));
        $this->assertEquals(false, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(1,14,15,16,29)));
    }

    public function test_YFC決済ではない場合_falseが返る()
    {
        $this->assertEquals(false, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(33)));
    }

    public function test_YFCクレジット決済だけではない場合_falseが返る()
    {
        $this->assertEquals(false, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(16,33)));
    }

    public function test_YFC決済だけではない場合_falseが返る()
    {
        $this->assertEquals(false, SC_Util_Mdl_YFCApi_Ex::isCreditOrderFromId(array(1,14,15,16,29,33)));
    }
}