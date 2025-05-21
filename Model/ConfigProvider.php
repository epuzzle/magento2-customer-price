<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Getting config values from the store configuration
 */
class ConfigProvider
{
    /**
     * ConfigProvider
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Is enabled the customer price functionality?
     *
     * @param int|null $websiteId
     * @return bool
     */
    public function isEnabled(int $websiteId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            'epuzzle_customer_price/general/enabled',
            ScopeInterface::SCOPE_WEBSITES,
            $websiteId
        );
    }

    /**
     * Get the collector type for the customer prices
     *
     * @param int|null $websiteId
     * @return string
     */
    public function getCollectorType(int $websiteId = null): string
    {
        return $this->scopeConfig->getValue(
            'epuzzle_customer_price/apply/collector_type',
            ScopeInterface::SCOPE_WEBSITES,
            $websiteId
        );
    }
}
