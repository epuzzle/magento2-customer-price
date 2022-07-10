<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Used to CRUD the customer prices
 */
interface CustomerPriceRepositoryInterface
{
    /**
     * Get the customer price by ID
     *
     * @param int $itemId
     * @return \EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $itemId): Data\CustomerPriceInterface;

    /**
     * Save the customer price
     *
     * @param \EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface $customerPrice
     * @return int
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(Data\CustomerPriceInterface $customerPrice): int;

    /**
     * Delete the customer price by ID
     *
     * @param int $itemId
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById(int $itemId): void;

    /**
     * Delete the customer price
     *
     * @param \EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface $customerPrice
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\CustomerPriceInterface $customerPrice): void;

    /**
     * Get a list of customer prices
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \EPuzzle\CustomerPrice\Api\Data\CustomerPriceInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array;
}
