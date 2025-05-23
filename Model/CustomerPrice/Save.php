<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use EPuzzle\CustomerPrice\Model\CustomerPrice;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice as Resource;
use Exception;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Save the customer price
 */
class Save
{
    /**
     * Save
     *
     * @param Resource $resource
     */
    public function __construct(
        private readonly Resource $resource
    ) {
    }

    /**
     * Save the customer price
     *
     * @param CustomerPriceInterface $entity
     * @return int
     * @throws CouldNotSaveException
     */
    public function execute(CustomerPriceInterface $entity): int
    {
        /** @var CustomerPrice $entity */
        try {
            $this->resource->save($entity);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the customer price: %error', ['error' => $exception->getMessage()]),
                $exception
            );
        }

        return (int)$entity->getItemId();
    }
}
