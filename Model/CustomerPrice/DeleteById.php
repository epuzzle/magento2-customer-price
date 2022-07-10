<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\CustomerPrice;

use Exception;
use Magento\Framework\Exception\CouldNotDeleteException;

/**
 * Delete the customer price by ID
 */
class DeleteById
{
    /**
     * @var GetById
     */
    private GetById $getById;

    /**
     * @var Delete
     */
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
