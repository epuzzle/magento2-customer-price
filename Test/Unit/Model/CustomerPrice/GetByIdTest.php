<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Test\Unit\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterfaceFactory;
use EPuzzle\CustomerPrice\Model\CustomerPrice;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice as CustomerPriceResource;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPrice\GetById
 */
class GetByIdTest extends TestCase
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
     * @var CustomerPrice\GetById
     */
    private CustomerPrice\GetById $getById;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->entity = $this->getMockBuilder(CustomerPrice::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityFactory = $this->getMockBuilder(CustomerPriceInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $entityFactory->expects($this->any())
            ->method('create')
            ->willReturn($this->entity);
        $this->resource = $this->getMockBuilder(CustomerPriceResource::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->getById = new CustomerPrice\GetById($entityFactory, $this->resource);
    }

    /**
     * @throws NoSuchEntityException
     *
     * @see CustomerPrice\GetById::execute()
     */
    public function testExecute(): void
    {
        $this->entity->expects($this->once())
            ->method('getItemId')
            ->willReturn(1);
        $this->entity->expects($this->once())
            ->method('getId')
            ->willReturn(1);
        $itemId = $this->entity->getItemId();
        $this->resource->expects($this->once())
            ->method('load')
            ->with($this->entity, $itemId)
            ->willReturnSelf();
        $this->assertEquals($this->entity, $this->getById->execute($itemId));
    }

    /**
     * @see CustomerPrice\GetById::execute()
     */
    public function testExecuteWithException(): void
    {
        $this->entity->expects($this->once())
            ->method('getItemId')
            ->willReturn(1);
        $this->entity->expects($this->once())
            ->method('getId')
            ->willReturn(null);
        $itemId = $this->entity->getItemId();
        $this->resource->expects($this->once())
            ->method('load')
            ->with($this->entity, $itemId)
            ->willReturnSelf();
        $this->expectException(NoSuchEntityException::class);
        $this->getById->execute($itemId);
    }
}
