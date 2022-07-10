<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete the customer price
 */
class Delete
{
    /**
     * @var CustomerPrice
     */
    private CustomerPrice $resource;

    /**
     * Delete
     *
     * @param CustomerPrice $resource
     */
    public function __construct(
        CustomerPrice $resource
    ) {
        $this->resource = $resource;
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
