<?php
/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Razoyo\CarProfile\Api\ApiCarsRepositoryInterface;
use Razoyo\CarProfile\Api\CustomerCarRepositoryInterface;
use Razoyo\CarProfile\Api\Data\CustomerCarInterfaceFactory;
use Razoyo\CarProfile\Api\Data\CustomerCarInterface;
use Razoyo\CarProfile\Model\ResourceModel\CustomerCar as ResourceCustomerCar;

/**
 * Repository for customer_car extension attribute
 */
class CustomerCarRepository implements CustomerCarRepositoryInterface
{

    /**
     * @param ResourceCustomerCar $resource
     * @param CustomerCarInterfaceFactory $carInterfaceFactory
     * @param ApiCarsRepositoryInterface $apiCarsRepository
     */
    public function __construct(
        private readonly ResourceCustomerCar $resource,
        private readonly CustomerCarInterfaceFactory $carInterfaceFactory,
        private readonly ApiCarsRepositoryInterface $apiCarsRepository
    ) { }

    /**
     * @inheritDoc
     */
    public function save(CustomerCarInterface $customerCar): CustomerCarInterface
    {
        try {
            /** @var CustomerCar $customerCar */
            $this->resource->save($customerCar);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the customer car: %1',
                $exception->getMessage()
            ));
        }
        return $customerCar;
    }

    /**
     * @inheritDoc
     */
    public function get(int $customerId): CustomerCarInterface
    {
        /** @var CustomerCar $customerCar */
        $customerCar = $this->carInterfaceFactory->create();
        $this->resource->load($customerCar, $customerId, 'customer_id');
        return $customerCar;
    }

    /**
     * @inheritDoc
     */
    public function delete(CustomerCarInterface $customerCar): bool
    {
        try {
            /** @var CustomerCar $customerCar */
            $this->resource->delete($customerCar);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the customer car: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function saveByCarId(int $customerId, string $carId): CustomerCarInterface
    {
        /** @var CustomerCar $customerCar */
        $customerCar = $this->get($customerId);

        $apiCar = $this->apiCarsRepository->getCarById($carId);
        $apiCar->setEntityId($customerCar->getEntityId())
            ->setCustomerId($customerId)
            ->setExtId($carId);

        return $this->save($apiCar);
    }
}
