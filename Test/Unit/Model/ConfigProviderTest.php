<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model;

use EPuzzle\CustomerPrice\Model\ConfigProvider;
use Magento\Framework\App\Config\ScopeConfigInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see ConfigProvider
 */
class ConfigProviderTest extends TestCase
{
    /**
     * @var ScopeConfigInterface|MockObject
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @var ConfigProvider
     */
    private ConfigProvider $configProvider;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->configProvider = new ConfigProvider($this->scopeConfig);
    }

    /**
     * @see ConfigProvider::isEnabled()
     */
    public function testIsEnabled(): void
    {
        $expectedValue = true;
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('epuzzle_customer_price/general/enabled')
            ->willReturn($expectedValue);

        $this->assertEquals($expectedValue, $this->configProvider->isEnabled());
    }
}
