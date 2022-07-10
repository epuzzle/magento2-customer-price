<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Test\Unit\Model\CustomerPrice;

use ePuzzle\CustomerPrice\Model\CustomerPrice;
use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;
use PHPUnit\Framework\TestCase;

/**
 * @see CustomerPrice\DeleteById
 */
class DeleteByIdTest extends TestCase
{
    private CustomerPrice $entity;
    private CustomerPrice\GetById $getById;
    private CustomerPrice\DeleteById $deleteById;

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
        $this->getById = $this->getMockBuilder(CustomerPrice\GetById::class)
            ->disableOriginalConstructor()
            ->getMock();
        $delete = $this->getMockBuilder(CustomerPrice\Delete::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->deleteById = new CustomerPrice\DeleteById($this->getById, $delete);
    }

    /**
     * @throws CouldNotDeleteException
     * @see CustomerPrice\DeleteById::execute()
     */
    public function testExecute(): void
    {
        $this->getById->expects($this->once())
            ->method('execute')
            ->with($this->entity->getItemId())
            ->willReturn($this->entity);
        $this->deleteById->execute($this->entity->getItemId());
    }

    /**
     * @throws CouldNotDeleteException
     * @see CustomerPrice\DeleteById::execute()
     */
    public function testExecuteWithException(): void
    {
        $this->getById->expects($this->once())
            ->method('execute')
            ->with($this->entity->getItemId())
            ->willThrowException(new Exception('error'));
        $this->expectException(CouldNotDeleteException::class);
        $this->deleteById->execute($this->entity->getItemId());
    }
}
