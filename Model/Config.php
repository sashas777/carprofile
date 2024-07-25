<?php
/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Configuration provider
 */
class Config
{
    private const XML_PATH_STATUS = 'razoyo/car_profile/status';
    private const XML_PATH_API_URL = 'razoyo/car_profile/api_url';

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly StoreManagerInterface $storeManager
    ) { }

    /**
     * @return string
     */
    public function getApiUrl(): string
    {
        return $this->getValue( static::XML_PATH_API_URL);
    }

    /**
     * @return bool
     */
    public function getStatus(): bool
    {
        return (bool)$this->getValue( static::XML_PATH_STATUS);
    }

    /**
     * @param string $path
     * @return string|null
     */
    private function getValue(string $path): ?string
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStoreId(): int
    {
        return (int) $this->storeManager->getStore()->getId();
    }
}
