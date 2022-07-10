<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model;

use EPuzzle\CustomerPrice\Api\CustomerPriceRepositoryInterface;
use EPuzzle\CustomerPrice\Api\Data;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Used to CRUD the customer prices
 */
class CustomerPriceRepository implements CustomerPriceRepositoryInterface
{
    /**
     * @var CustomerPrice\GetById
     */
    private CustomerPrice\GetById $getById;

    /**
     * @var CustomerPrice\Save
     */
    private CustomerPrice\Save $save;

    /**
     * @var CustomerPrice\DeleteById
     */
    private CustomerPrice\DeleteById $deleteById;

    /**
     * @var CustomerPrice\Delete
     */
    private CustomerPrice\Delete $delete;

    /**
     * @var CustomerPrice\GetList
     */
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
