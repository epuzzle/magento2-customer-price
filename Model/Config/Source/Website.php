<?php

declare(strict_types=1);

namespace EPuzzle\CustomerPrice\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Api\Data\WebsiteInterface;
use Magento\Store\Model\ResourceModel\Website\CollectionFactory;

/**
 * Getting information about websites
 */
class Website implements OptionSourceInterface
{
    /**
     * Default website id
     *
     * Constant represents default website id
     */
    public const DEFAULT_WEBSITE_ID = 1;

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @var string[]
     */
    private array $options = [];

    /**
     * Website
     *
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [];
        foreach ($this->toArray() as $value => $label) {
            $options[] = ['value' => $value, 'label' => $label];
        }
        return $options;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray(): array
    {
        if (!empty($this->options)) {
            return $this->options;
        }

        /** @var WebsiteInterface $website */
        foreach ($this->collectionFactory->create() as $website) {
            $this->options[$website->getId()] = $website->getName();
        }

        return $this->options;
    }
}
