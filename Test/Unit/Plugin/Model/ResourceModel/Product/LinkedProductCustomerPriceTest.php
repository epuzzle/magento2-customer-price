<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Plugin\Model\ResourceModel\Product;

use EPuzzle\CustomerPrice\Model\ConfigProvider;
use EPuzzle\CustomerPrice\Model\ResourceModel\Product\CustomerPriceBaseSelectProcessor;
use EPuzzle\CustomerPrice\Plugin\Model\ResourceModel\Product\LinkedProductSelectBuilder\LinkedProductCustomerPrice;
use Magento\Catalog\Model\ResourceModel\Product\LinkedProductSelectBuilderInterface;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see LinkedProductCustomerPrice
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class LinkedProductCustomerPriceTest extends TestCase
{
    /**
     * @var CustomerPriceBaseSelectProcessor|MockObject
     */
    private CustomerPriceBaseSelectProcessor $customerPriceBaseSelectProcessor;

    /**
     * @var ConfigProvider|MockObject
     */
    private ConfigProvider $configProvider;

    /**
     * @var LinkedProductSelectBuilderInterface|MockObject
     */
    private LinkedProductSelectBuilderInterface $linkedProductSelectBuilder;

    /**
     * @var LinkedProductCustomerPrice
     */
    private LinkedProductCustomerPrice $linkedProductCustomerPrice;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->customerPriceBaseSelectProcessor = $this->createMock(
            CustomerPriceBaseSelectProcessor::class
        );
        $this->configProvider = $this->createMock(ConfigProvider::class);
        $this->linkedProductSelectBuilder = $this->createMock(LinkedProductSelectBuilderInterface::class);
        $this->linkedProductCustomerPrice = new LinkedProductCustomerPrice(
            $this->customerPriceBaseSelectProcessor,
            $this->configProvider
        );
    }

    /**
     * @see LinkedProductCustomerPrice::afterBuild()
     */
    public function testAfterBuild(): void
    {
        $select = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'from'])
            ->getMock();
        $selects = [$select];
        $this->configProvider->expects($this->once())
            ->method('isConfigured')
            ->willReturn(true);
        $this->assertEquals($selects, $this->linkedProductCustomerPrice->afterBuild(
            $this->linkedProductSelectBuilder,
            $selects
        ));
    }
}
