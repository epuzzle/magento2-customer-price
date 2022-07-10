<?php

/**
 * Customer prices for Magento 2 platform
 *
 * @author Dmytro Kaplin <dkaplin1994@gmail.com>
 * @license https://github.com/epuzzle/magento2-customer-price/blob/main/LICENSE
 */

declare(strict_types=1);

namespace ePuzzle\CustomerPrice\Api;

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
     * @return \ePuzzle\CustomerPrice\Api\Data\CustomerPriceInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(int $itemId): Data\CustomerPriceInterface;

    /**
     * Save the customer price
     *
     * @param \ePuzzle\CustomerPrice\Api\Data\CustomerPriceInterface $customerPrice
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
     * @param \ePuzzle\CustomerPrice\Api\Data\CustomerPriceInterface $customerPrice
     * @return void
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(Data\CustomerPriceInterface $customerPrice): void;

    /**
     * Get a list of customer prices
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \ePuzzle\CustomerPrice\Api\Data\CustomerPriceInterface[]
     */
    public function getList(SearchCriteriaInterface $searchCriteria): array;
}
