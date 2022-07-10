<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model\CustomerPrice;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete the customer price by ID
 */
class DeleteById
{
    private GetById $getById;
    private Delete $delete;

    /**
     * DeleteById
     *
     * @param GetById $getById
     * @param Delete $delete
     */
    public function __construct(
        GetById $getById,
        Delete $delete
    ) {
        $this->getById = $getById;
        $this->delete = $delete;
    }

    /**
     * Delete the customer price by ID
     *
     * @param int $itemId
     * @return void
     * @throws CouldNotDeleteException
     */
    public function execute(int $itemId): void
    {
        try {
            $this->delete->execute(
                $this->getById->execute($itemId)
            );
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the customer price: %error', ['error' => $exception->getMessage()]),
                $exception
            );
        }
    }
}
