<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Getting config values from the store configuration
 */
class ConfigProvider
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * ConfigProvider
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Is enabled the customer price functionality?
     *
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag('epuzzle_customer_price/general/enabled');
    }
}
