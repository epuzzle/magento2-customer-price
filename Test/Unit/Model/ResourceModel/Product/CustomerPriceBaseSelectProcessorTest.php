<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model\ResourceModel\Product;

use EPuzzle\CustomerPrice\Model\Customer\CustomerProviderInterface;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use EPuzzle\CustomerPrice\Model\ResourceModel\Product\CustomerPriceBaseSelectProcessor;
use Magento\Framework\DB\Select;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * @see CustomerPriceBaseSelectProcessor
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class CustomerPriceBaseSelectProcessorTest extends TestCase
{
    /**
     * @var CustomerProviderInterface|MockObject
     */
    private CustomerProviderInterface $customerProvider;

    /**
     * @var CustomerPrice|MockObject
     */
    private CustomerPrice $resource;

    /**
     * @var LoggerInterface|MockObject
     */
    private LoggerInterface $logger;

    /**
     * @var CustomerPriceBaseSelectProcessor
     */
    private CustomerPriceBaseSelectProcessor $customerPriceBaseSelectProcessor;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->customerProvider = $this->createMock(CustomerProviderInterface::class);
        $this->resource = $this->createMock(CustomerPrice::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->customerPriceBaseSelectProcessor = new CustomerPriceBaseSelectProcessor(
            $this->customerProvider,
            $this->resource,
            $this->logger
        );
    }

    /**
     * @see CustomerPriceBaseSelectProcessor::process()
     */
    public function testProcess(): void
    {
        $select = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'from'])
            ->getMock();
        $this->customerProvider->expects($this->once())
            ->method('getCustomerId')
            ->willReturn(1);
        $this->assertEquals($select, $this->customerPriceBaseSelectProcessor->process($select));
    }
}
