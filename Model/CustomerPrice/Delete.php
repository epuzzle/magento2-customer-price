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
use ePuzzle\CustomerPrice\Model\ResourceModel\CustomerPrice;
use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete the customer price
 */
class Delete
{
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
