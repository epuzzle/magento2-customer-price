<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterfaceFactory;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Get the customer price by ID
 */
class GetById
{
    /**
     * @var CustomerPriceInterfaceFactory
     */
    private CustomerPriceInterfaceFactory $entityFactory;

    /**
     * @var CustomerPrice
     */
    private CustomerPrice $resource;

    /**
     * GetById
     *
     * @param CustomerPriceInterfaceFactory $entityFactory
     * @param CustomerPrice $resource
     */
    public function __construct(
        CustomerPriceInterfaceFactory $entityFactory,
        CustomerPrice $resource
    ) {
        $this->entityFactory = $entityFactory;
        $this->resource = $resource;
    }

    /**
     * Get the customer price by ID
     *
     * @param int $itemId
     * @return CustomerPriceInterface
     * @throws NoSuchEntityException
     */
    public function execute(int $itemId): CustomerPriceInterface
    {
        $entity = $this->entityFactory->create();
        $this->resource->load($entity, $itemId);
        if (!$entity->getId()) {
            throw new NoSuchEntityException(__('Could not get the customer price.'));
        }
        return $entity;
    }
}
