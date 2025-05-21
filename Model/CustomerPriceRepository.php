<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model;

use EPuzzle\CustomerPrice\Api\CustomerPriceRepositoryInterface;
use EPuzzle\CustomerPrice\Api\Data;
use EPuzzle\CustomerPrice\Api\Data\CustomerPriceSearchResultsInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Used to CRUD the customer prices
 */
class CustomerPriceRepository implements CustomerPriceRepositoryInterface
{
    /**
     * CustomerPriceRepository
     *
     * @param CustomerPrice\GetById $getById
     * @param CustomerPrice\Save $save
     * @param CustomerPrice\DeleteById $deleteById
     * @param CustomerPrice\Delete $delete
     * @param CustomerPrice\GetList $getList
     * @param CustomerPriceFactory $entityFactory
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     */
    public function __construct(
        private CustomerPrice\GetById $getById,
        private CustomerPrice\Save $save,
        private CustomerPrice\DeleteById $deleteById,
        private CustomerPrice\Delete $delete,
        private CustomerPrice\GetList $getList,
        private CustomerPriceFactory $entityFactory,
        private SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
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
    public function getList(SearchCriteriaInterface $searchCriteria): CustomerPriceSearchResultsInterface
    {
        return $this->getList->execute($searchCriteria);
    }

    /**
     * @inheritDoc
     */
    public function create(): Data\CustomerPriceInterface
    {
        return $this->entityFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function createSearchCriteriaBuilder(array $data = []): SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilderFactory->create($data);
    }
}
