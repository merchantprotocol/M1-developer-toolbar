<?php

/**
 * Class MP_Debug_Test_Model_Query
 *
 * @category MP
 * @package  MP_Subscription
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 * @covers MP_Debug_Model_Query
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Query extends EcomDev_PHPUnit_Test_Case
{

    public function test()
    {
        $zendQuery = $this->getMock('Zend_Db_Profiler_Query',
            array('getQueryType', 'getQuery', 'getQueryParams', 'getElapsedSecs'), array(), '', false);
        $zendQuery->expects($this->any())->method('getQueryType')->willReturn(Zend_Db_Profiler::SELECT);
        $zendQuery->expects($this->any())->method('getQuery')->willReturn('raw query');
        $zendQuery->expects($this->any())->method('getQueryParams')->willReturn(array(
            ':store_id' => 10,
            ':customer_id' => 5
        ));
        $zendQuery->expects($this->any())->method('getElapsedSecs')->willReturn(0.123);

        $model = Mage::getModel('mp_debug/query');
        $model->init($zendQuery, 'stack trace');

        $this->assertNotFalse($model);
        $this->assertInstanceOf('MP_Debug_Model_Query', $model);
        $this->assertEquals(32, $model->getQueryType());
        $this->assertEquals('raw query', $model->getQuery());
        $this->assertCount(2, $model->getQueryParams());
        $this->assertEquals(5, $model->getQueryParams()[':customer_id']);
        $this->assertEquals(0.123, $model->getElapsedSecs());
        $this->assertEquals('stack trace', $model->getStacktrace());
    }

}
