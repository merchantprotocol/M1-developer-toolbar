<?php

/**
 * Class MP_Debug_Test_Helper_Config
 *
 * @category MP
 * @package  MP_Subscription
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 *
 * @covers MP_Debug_Helper_Config
 * @codeCoverageIgnore
 */
class MP_Debug_Test_Helper_Config extends EcomDev_PHPUnit_Test_Case
{
    /** @var MP_Debug_Helper_Config */
    protected $helper;

    protected function setUp()
    {
        $this->helper = Mage::helper('mp_debug/config');
    }


    public function testGetMagentoVersion()
    {
        $actual = $this->helper->getMagentoVersion();
        $this->assertNotNull($actual);
    }


    public function testGetExtensionRequirements()
    {
        $actual = $this->helper->getExtensionRequirements();
        $this->assertNotNull($actual);
        $this->assertContains('simplexml', $actual);
    }


    public function testGetExtensionStatus()
    {
        $helper = $this->getHelperMock('mp_debug/config', array('getExtensionRequirements'));
        $helper->expects($this->once())->method('getExtensionRequirements')->willReturn(array('mcrypt', 'xdebug'));

        $actual = $helper->getExtensionStatus();
        $this->assertNotNull($actual);
        $this->assertCount(2, $actual);
        $this->assertArrayHasKey('mcrypt', $actual);
        $this->assertTrue($actual['mcrypt']);
    }


    public function testGetModules()
    {
        $helper = $this->getHelperMock('mp_debug/config', array('getMagentoVersion'));
        $helper->expects($this->any())->method('getMagentoVersion')->willReturn('1.3.0');

        $modules = $helper->getModules();
        $this->assertNotNull($modules);
        $this->assertGreaterThan(2, count($modules));

        $this->assertEquals('Magento', $modules[0]['module']);
        $this->assertEquals('core', $modules[0]['codePool']);
        $this->assertTrue($modules[0]['active']);
        $this->assertEquals('1.3.0', $modules[0]['version']);

        $this->assertEquals('Mage_Core', $modules[1]['module']);
        $this->assertEquals('core', $modules[1]['codePool']);
        $this->assertTrue($modules[1]['active']);
        $this->assertNotNull($modules[1]['version']);
    }

}
