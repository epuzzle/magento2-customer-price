<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model;

use EPuzzle\CustomerPrice\Model\ConfigProvider;
use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice\PriceCollectorInterface;
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
        $collectors = ['default' => $this->createMock(PriceCollectorInterface::class)];
        $this->configProvider = new ConfigProvider($this->scopeConfig, $collectors);
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

    /**
     * @see ConfigProvider::isConfigured()
     */
    public function testIsConfigured(): void
    {
        $expectedValue = true;
        $this->scopeConfig->expects($this->once())
            ->method('isSetFlag')
            ->with('epuzzle_customer_price/general/enabled')
            ->willReturn($expectedValue);
        $this->scopeConfig->expects($this->once())
            ->method('getValue')
            ->with('epuzzle_customer_price/apply/collector_type')
            ->willReturn('default');

        $this->assertEquals($expectedValue, $this->configProvider->isConfigured());
    }
}
