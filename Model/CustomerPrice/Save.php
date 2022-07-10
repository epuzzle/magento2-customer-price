<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\CustomerPrice;

use EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface;
use EPuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Exception;
use Magento\Framework\Exception\CouldNotSaveException;

/**
 * Save the customer price
 */
class Save
{
    /**
     * @var CustomerPrice
     */
    private CustomerPrice $resource;

    /**
     * Save
     *
     * @param CustomerPrice $resource
     */
    public function __construct(
        CustomerPrice $resource
    ) {
        $this->resource = $resource;
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
