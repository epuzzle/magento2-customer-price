<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Model;

use ePuzzle\CustomerPrice\Api\CustomerPriceRepositoryInterface;
use ePuzzle\CustomerPrice\Api\Data;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Used to CRUD the customer prices
 */
class CustomerPriceRepository implements CustomerPriceRepositoryInterface
{
    private CustomerPrice\GetById $getById;
    private CustomerPrice\Save $save;
    private CustomerPrice\DeleteById $deleteById;
    private CustomerPrice\Delete $delete;
    private CustomerPrice\GetList $getList;

    /**
     * CustomerPriceRepository
     *
     * @param CustomerPrice\GetById $getById
     * @param CustomerPrice\Save $save
     * @param CustomerPrice\DeleteById $deleteById
     * @param CustomerPrice\Delete $delete
     * @param CustomerPrice\GetList $getList
     */
    public function __construct(
        CustomerPrice\GetById $getById,
        CustomerPrice\Save $save,
        CustomerPrice\DeleteById $deleteById,
        CustomerPrice\Delete $delete,
        CustomerPrice\GetList $getList
    ) {
        $this->getById = $getById;
        $this->save = $save;
        $this->deleteById = $deleteById;
        $this->delete = $delete;
        $this->getList = $getList;
    }

    /**
     * @inheritDoc
     */
    public function get(int $itemId): Data\CustomerPriceInterface
    {
        return $this->getById->execute($itemId);
    }

    /**
     * @inheritDoc
     */
    public function save(Data\CustomerPriceInterface $customerPrice): int
    {
        return $this->save->execute($customerPrice);
    }

    /**
     * @inheritDoc
     */
    public function deleteById(int $itemId): void
    {
        $this->deleteById->execute($itemId);
    }

    /**
     * @inheritDoc
     */
    public function delete(Data\CustomerPriceInterface $customerPrice): void
    {
        $this->delete->execute($customerPrice);
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array
    {
        return $this->getList->execute($searchCriteria);
    }
}
