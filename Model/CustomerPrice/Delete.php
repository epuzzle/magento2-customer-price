<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use EPuzzle\CustomerPrice\Model\CustomerPrice;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice as Resource;
use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete the customer price
 */
class Delete
{
    /**
     * Delete
     *
     * @param Resource $resource
     */
    public function __construct(
        private readonly Resource $resource
    ) {
    }

    /**
     * Delete the customer price
     *
     * @param CustomerPriceInterface $entity
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(CustomerPriceInterface $entity): void
    {
        /** @var CustomerPrice $entity */
        try {
            $this->resource->delete($entity);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the customer price: %error', ['error' => $exception->getMessage()]),
                $exception
            );
        }
    }
}
