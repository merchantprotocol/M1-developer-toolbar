<?php

/**
 * Class MP_Debug_Test_Model_Model
 *
 * @category MP
 * @package  MP_Subscription
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 * @covers MP_Debug_Model_Model
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Model extends EcomDev_PHPUnit_Test_Case
{

    public function testConstruct()
    {
        $model = Mage::getModel('mp_debug/model');
        $this->assertNotFalse($model);
        $this->assertInstanceOf('MP_Debug_Model_Model', $model);
    }


    public function testInit()
    {
        $magentoModel = $this->getModelMock('catalog/product', array('getResourceName'));
        $magentoModel->expects($this->any())->method('getResourceName')->willReturn('catalog_product');

        $model = Mage::getModel('mp_debug/model');
        $model->init($magentoModel);
        $this->assertContains('Mage_Catalog_Model_Product', $model->getClass());
        $this->assertEquals('catalog_product', $model->getResource());
        $this->assertEquals(0, $model->getCount());

        $model->incrementCount();
        $this->assertEquals(1, $model->getCount());

        $model->incrementCount();
        $this->assertEquals(2, $model->getCount());
    }

}
