<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model\CustomerPrice;

use ePuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use ePuzzle\CustomerPrice\Api\Data\CustomerPriceInterfaceFactory;
use ePuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Get the customer price by ID
 */
class GetById
{
    private CustomerPriceInterfaceFactory $entityFactory;
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
