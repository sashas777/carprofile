<?php
/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Plugin;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\Data\CustomerSearchResultsInterface;
use Razoyo\CarProfile\Api\CustomerCarRepositoryInterface;
use Razoyo\CarProfile\Model\Config;

/**
 * Plugin for customer car extension attribute.
 */
class CustomerRepositoryPlugin
{
    /**
     * @param CustomerCarRepositoryInterface $customerCarRepository
     * @param Config $config
     */
    public function __construct(
        private readonly CustomerCarRepositoryInterface $customerCarRepository,
        private readonly Config $config
    ) {  }

    /**
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     */
    public function afterGet(
        CustomerRepositoryInterface $subject,
        CustomerInterface $result,
    ): CustomerInterface {

        if (!$this->isEnabled()) {
            return $result;
        }
        $customerCar = $this->customerCarRepository->get((int)$result->getId());

        if (!$customerCar->getId()) {
            return $result;
        }
        $extensionAttributes = $result->getExtensionAttributes();
        $extensionAttributes->setData('customer_car', $customerCar);
        $result->setExtensionAttributes($extensionAttributes);

        return $result;
    }

    /**
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @return CustomerInterface
     */
    public function afterGetById(
        CustomerRepositoryInterface $subject,
        CustomerInterface $result,
    ): CustomerInterface {

        if (!$this->isEnabled()) {
            return $result;
        }
        $customerCar = $this->customerCarRepository->get((int)$result->getId());

        if (!$customerCar->getId()) {
            return $result;
        }
        $extensionAttributes = $result->getExtensionAttributes();
        $extensionAttributes->setData('customer_car', $customerCar);
        $result->setExtensionAttributes($extensionAttributes);

        return $result;
    }

    /**
     * @param CustomerRepositoryInterface $subject
     * @param CustomerSearchResultsInterface $searchResults
     * @return CustomerSearchResultsInterface
     */
    public function afterGetList(
        CustomerRepositoryInterface $subject,
        CustomerSearchResultsInterface $searchResults
    ) : CustomerSearchResultsInterface {

        if (!$this->isEnabled()) {
            return $searchResults;
        }

        $customers = [];
        foreach ($searchResults->getItems() as $entity) {
            $customerCar = $this->customerCarRepository->get((int)$entity->getId());

            if (!$customerCar->getId()) {
               continue;
            }
            $extensionAttributes = $entity->getExtensionAttributes();
            $extensionAttributes->setData('customer_car', $customerCar);
            $entity->setExtensionAttributes($extensionAttributes);

            $customers[] = $entity;
        }
        $searchResults->setItems($customers);
        return $searchResults;
    }

    /**
     * @return bool
     */
    private function isEnabled(): bool
    {
        if (!$this->config->getStatus()) {
            return false;
        }
        return true;
    }
}
