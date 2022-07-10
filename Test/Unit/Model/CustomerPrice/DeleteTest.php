<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Model\CustomerPrice;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPrice\Delete
 */
class DeleteTest extends TestCase
{
    /**
     * @var CustomerPrice|MockObject
     */
    private CustomerPrice $entity;

    /**
     * @var CustomerPriceResource|MockObject
     */
    private CustomerPriceResource $resource;

    /**
     * @var CustomerPrice\Delete
     */
    private CustomerPrice\Delete $delete;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->entity = $this->getMockBuilder(CustomerPrice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entity->expects($this->any())
            ->method('getItemId')
            ->willReturn(1);
        $this->resource = $this->getMockBuilder(CustomerPriceResource::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->delete = new CustomerPrice\Delete($this->resource);
    }

    /**
     * @throws CouldNotDeleteException
     *
     * @see CustomerPrice\Delete::execute()
     */
    public function testExecute(): void
    {
        $this->resource->expects($this->once())
            ->method('delete')
            ->with($this->entity)
            ->willReturnSelf();
        $this->delete->execute($this->entity);
    }

    /**
     * @throws CouldNotDeleteException
     *
     * @see CustomerPrice\Delete::execute()
     */
    public function testExecuteWithException(): void
    {
        $this->resource->expects($this->once())
            ->method('delete')
            ->willThrowException(new Exception('error'));
        $this->expectException(CouldNotDeleteException::class);
        $this->delete->execute($this->entity);
    }
}
