<?php

/**
 * Class MP_Debug_Test_Block_Config
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 * @covers MP_Debug_Block_Config
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Block_Config extends EcomDev_PHPUnit_Test_Case
{

    public function testGetMagentoVersion()
    {
        $helper = $this->getHelperMock('mp_debug/config', array('getMagentoVersion'));
        $helper->expects($this->once())->method('getMagentoVersion')->willReturn('1.2.3');
        $this->replaceByMock('helper', 'mp_debug/config', $helper);

        $block = $this->getBlockMock('mp_debug/config', array('toHtml'));
        $actual = $block->getMagentoVersion();
        $this->assertEquals('1.2.3', $actual);
    }


    public function testGetIsDeveloperMode()
    {
        $helper = $this->getHelperMock('mp_debug', array('getIsDeveloperMode'));
        $helper->expects($this->once())->method('getIsDeveloperMode')->willReturn(true);
        $this->replaceByMock('helper', 'mp_debug', $helper);

        $block = $this->getBlockMock('mp_debug/config', array('toHtml'));
        $actual = $block->isDeveloperMode();
        $this->assertTrue($actual);
    }


    public function testGetExtensionStatus()
    {
        $helper = $this->getHelperMock('mp_debug/config', array('getExtensionStatus'));
        $helper->expects($this->once())->method('getExtensionStatus')
            ->willReturn(array('xdebug' => true, 'mcrypt' => false));
        $this->replaceByMock('helper', 'mp_debug/config', $helper);

        $block = $this->getBlockMock('mp_debug/config', array('toHtml'));
        $actual = $block->getExtensionStatus();
        $this->assertCount(2, $actual);
        $this->assertArrayHasKey('xdebug', $actual);
        $this->assertTrue($actual['xdebug']);
        $this->assertArrayHasKey('mcrypt', $actual);
        $this->assertFalse($actual['mcrypt']);
    }


    public function testGetCurrentStore()
    {
        $website = $this->getModelMock('core/website', array('getName'));
        $website->expects($this->any())->method('getName')->willReturn('My Website');

        $store = $this->getModelMock('core/store', array('getWebsite', 'getName'));
        $store->expects($this->any())->method('getWebsite')->willReturn($website);
        $store->expects($this->any())->method('getName')->willReturn('My Store');

        $app = $this->getModelMock('core/app', array('getStore'));
        $app->expects($this->any())->method('getStore')->willReturn($store);

        $block = $this->getBlockMock('mp_debug/config', array('_getApp'));
        $block->expects($this->any())->method('_getApp')->willReturn($app);
        $actual = $block->getCurrentStore();

        $this->assertEquals('My Website / My Store', $actual);
    }

}
