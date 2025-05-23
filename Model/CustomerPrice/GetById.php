<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use EPuzzle\CustomerPrice\Model\CustomerPriceFactory;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Get the customer price by ID
 */
class GetById
{
    /**
     * GetById
     *
     * @param CustomerPriceFactory $entityFactory
     * @param CustomerPrice $resource
     */
    public function __construct(
        private readonly CustomerPriceFactory $entityFactory,
        private readonly CustomerPrice $resource
    ) {
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
