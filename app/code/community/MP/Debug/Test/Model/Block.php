<?php

/**
 * Class MP_Debug_Test_Model_Block
 *
 * @category MP
 * @package  MP_Subscription
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 * @covers MP_Debug_Model_Block
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Model_Block extends EcomDev_PHPUnit_Test_Case
{

    public function testConstruct()
    {
        $model = Mage::getModel('mp_debug/block');

        $this->assertNotFalse($model);
        $this->assertInstanceOf('MP_Debug_Model_Block', $model);
    }


    public function testInit()
    {
        $parentBlock = $this->getBlockMock('page/html_wrapper', array('getNameInLayout'));
        $parentBlock->expects($this->any())->method('getNameInLayout')->willReturn('my.account.wrapper');

        $block = $this->getBlockMock('wishlist/customer_wishlist', array('getParentBlock', 'getNameInLayout', 'getTemplateFile'));
        $block->expects($this->any())->method('getParentBlock')->willReturn($parentBlock);
        $block->expects($this->any())->method('getNameInLayout')->willReturn('customer.wishlist');
        $block->expects($this->any())->method('getTemplateFile')->willReturn('wishlist/view.phtml');

        $model = $this->getModelMock('mp_debug/block', array('startRendering'), false, array(), '', false);
        $model->init($block);

        $this->assertEquals('my.account.wrapper', $model->getParentName());
        $this->assertContains('Mage_Wishlist_Block_Customer_Wishlist', $model->getClass());
        $this->assertEquals('customer.wishlist', $model->getName());
        $this->assertEquals('wishlist/view.phtml', $model->getTemplateFile());
    }


    public function testInitWithoutTemplate()
    {
        $block = $this->getBlockMock('core/text_list', array('getNameInLayout'));
        $block->expects($this->any())->method('getNameInLayout')->willReturn('customer.wishlist.buttons');

        $model = $this->getModelMock('mp_debug/block', array('startRendering'), false, array(), '', false);
        $model->init($block);

        $this->assertContains('Mage_Core_Block_Text_List', $model->getClass());
        $this->assertEquals('customer.wishlist.buttons', $model->getName());
        $this->assertEquals('', $model->getTemplateFile());
    }


    /**
     */
    public function testStartRenderingTwice()
    {
        $block = $this->getBlockMock('wishlist/customer_wishlist', array('getNameInLayout', 'getTemplateFile'));

        /** @var MP_Debug_Model_Block $model */
        $model = $this->getModelMock('mp_debug/block', array('init'), false, array(), '', false);
        $model->expects($this->once())->method('init')->with($block);

        $model->startRendering($block);
        $this->assertTrue($model->isRendering());
        $this->assertEquals(1, $model->getRenderedCount());
        $this->assertNotNull($model->getRenderedAt());
        $this->assertNull($model->getRenderedCompletedAt());
        $this->assertEquals(0, $model->getRenderedDuration());
        $initialRenderAt = $model->getRenderedAt();

        $model->startRendering($block);
        $this->assertEquals(2, $model->getRenderedCount());
        $this->assertEquals($initialRenderAt, $model->getRenderedAt());
    }


    /**
     */
    public function testStartAndCompleteRendering()
    {
        $block = $this->getBlockMock('wishlist/customer_wishlist', array('getNameInLayout', 'getTemplateFile'));

        /** @var MP_Debug_Model_Block $model */
        $model = $this->getModelMock('mp_debug/block', array('init'), false, array(), '', false);
        $model->expects($this->once())->method('init')->with($block);


        $model->startRendering($block);
        $this->assertTrue($model->isRendering());
        $this->assertEquals(1, $model->getRenderedCount());
        $this->assertNotNull($model->getRenderedAt());
        $this->assertNull($model->getRenderedCompletedAt());
        $this->assertEquals(0, $model->getRenderedDuration());

        $model->completeRendering($block);
        $this->assertFalse($model->isRendering());
        $this->assertEquals(1, $model->getRenderedCount());
        $this->assertNotNull($model->getRenderedCompletedAt());
        $this->assertGreaterThan(0, $model->getRenderedDuration());
        $this->assertGreaterThan(0, $model->getTotalRenderingTime());
    }

}
