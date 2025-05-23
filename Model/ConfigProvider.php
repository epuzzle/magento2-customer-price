<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model;

use EPuzzle\CustomerPrice\Pricing\Price\CustomerPrice\PriceCollectorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Getting config values for the customer price module
 */
class ConfigProvider
{
    /**
     * @var bool|null
     */
    private ?bool $isConfigured = null;

    /**
     * ConfigProvider
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param PriceCollectorInterface[] $collectors
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private array $collectors = [],
    ) {
    }

    /**
     * Is enabled the customer price functionality?
     *
     * @param int|null $websiteId
     * @return bool
     */
    public function isEnabled(?int $websiteId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            'epuzzle_customer_price/general/enabled',
            ScopeInterface::SCOPE_WEBSITES,
            $websiteId
        );
    }

    /**
     * Is configured the module?
     *
     * @param int|null $websiteId
     * @return bool
     */
    public function isConfigured(?int $websiteId = null): bool
    {
        if (null === $this->isConfigured) {
            $this->isConfigured = $this->isEnabled($websiteId);
            if ($this->isConfigured) {
                $collector = $this->getCollectorByType();
                $this->isConfigured = $collector instanceof PriceCollectorInterface;
            }
        }

        return $this->isConfigured;
    }

    /**
     * Get the collector type for the customer prices
     *
     * @param int|null $websiteId
     * @return string
     */
    public function getCollectorType(?int $websiteId = null): string
    {
        return $this->scopeConfig->getValue(
            'epuzzle_customer_price/apply/collector_type',
            ScopeInterface::SCOPE_WEBSITES,
            $websiteId
        );
    }

    /**
     * Get the customer price collector by the type
     *
     * @param string|null $type
     * @return PriceCollectorInterface|null
     */
    public function getCollectorByType(?string $type = null): ?PriceCollectorInterface
    {
        $type = $type ?: $this->getCollectorType();

        return $this->collectors[$type] ?? null;
    }
}
