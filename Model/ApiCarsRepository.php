<?php
/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Model;

use Psr\Log\LoggerInterface;
use Razoyo\CarProfile\Api\ApiCarsRepositoryInterface;
use Razoyo\CarProfile\Api\Data\CustomerCarInterface;
use Razoyo\CarProfile\Model\ApiClient\Adapter;
use Magento\Framework\Api\DataObjectHelper;
use Razoyo\CarProfile\Api\Data\CustomerCarInterfaceFactory;

/**
 * A repository for main API car requests
 */
class ApiCarsRepository implements ApiCarsRepositoryInterface
{
    private const MAKE_FILTER = 'make';
    private const CARS_URI_MAKES_KEY = 'makes';
    private const CARS_URI_CARS_KEY = 'cars';
    private const CARS_URI_CAR_KEY = 'car';
    private const CARS_URI = 'cars';

    /**
     * List of car makes using singleton pattern
     * Another option is cache, but we don't know lifetime
     * @var array
     */
    private array $carMakes = [];

    /**
     *  List of car models by makes using singleton pattern
     *  Another option is cache, but we don't know lifetime
     * @var array
     */
    private array $carModelsByMakes = [];

    /**
     *  List of cars by IDs using singleton pattern
     *  Another option is cache, but we don't know lifetime
     * @var array
     */
    private array $carInfoByIds = [];

    /**
     * @param Adapter $adapter
     * @param Config $config
     * @param DataObjectHelper $dataObjectHelper
     * @param CustomerCarInterfaceFactory $customerCarInterfaceFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Adapter $adapter,
        private readonly Config $config,
        private readonly DataObjectHelper $dataObjectHelper,
        private readonly CustomerCarInterfaceFactory $customerCarInterfaceFactory,
        private readonly LoggerInterface $logger
    ) { }

    /**
     * The most lightweight call used to update token or get car makes only
     * $forceUpdate is true when auth token need to be updated
     * @param $forceUpdate
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCarMakes($forceUpdate = false): array
    {
        $this->logger->debug(__METHOD__.' force_update: '.$forceUpdate);
        /**
         * Need only makes, not a car list.
         * Saves response time without full list of cars
         */
        $params = [static::MAKE_FILTER => 'non-existing-make'];

        /**
         * Reduce number of request when several customers use it at the same time
         */
        if (count($this->carMakes) == 0 || $forceUpdate === true) {
            $carMakesAndCars = $this->adapter->execute($this->getAPIUrl(), $params);
            if (array_key_exists(static::CARS_URI_MAKES_KEY, $carMakesAndCars)) {
                $this->carMakes = $carMakesAndCars[static::CARS_URI_MAKES_KEY];
            }
        }

        return $this->carMakes;
    }

    /**
     * Get array of customer car objects by car model
     * @param string $make
     * @return CustomerCarInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCarsByMake(string $make): array
    {
        $this->logger->debug(__METHOD__.' make:'.$make);
        $params = [static::MAKE_FILTER => $make];

        /**
         * Reduce number of request when several customers use it at the same time
         */
        if (!array_key_exists($make, $this->carModelsByMakes) ||
            count($this->carModelsByMakes[$make]) == 0
        ) {
            $carMakesAndCars = $this->adapter->execute($this->getAPIUrl(), $params);
            foreach ($carMakesAndCars[static::CARS_URI_CARS_KEY] as $car) {
                $this->carModelsByMakes[$make][]=$car;
            }
        }

        $cars = [];

        foreach ($this->carModelsByMakes[$make] as $car) {
            $customerCar = $this->customerCarInterfaceFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $customerCar,
                $car,
                CustomerCarInterface::class
            );
            $cars[] = $customerCar;
        }

        return $cars;
    }

    /**
     * Get car information by a car ID
     * @param string $carId
     * @return CustomerCarInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCarById(string $carId): CustomerCarInterface
    {
        $this->logger->debug(__METHOD__.' id: '.$carId);
        $url = $this->getAPIUrl().'/'.$carId;

        /**
         * Reduce number of request when several customers use it at the same time
         */
        if (!array_key_exists($carId, $this->carInfoByIds)) {
            $this->refreshToken();
            $carInfo = $this->adapter->execute($url, authRequired: true);
                $this->carInfoByIds[$carId] = $carInfo[static::CARS_URI_CAR_KEY];
        }

        $customerCar = $this->customerCarInterfaceFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $customerCar,
            $this->carInfoByIds[$carId],
            CustomerCarInterface::class
        );

        return $customerCar;
    }

    /**
     * Refresh token in case it expired
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function refreshToken(): void
    {
        if (!$this->adapter->isTokenValid()) {
            $this->logger->debug(__METHOD__);
            $this->getCarMakes(forceUpdate: true);
        }
    }

    /**
     * Get API Url from configuration
     * @return string
     */
    private function getAPIUrl(): string
    {
        return $this->config->getApiUrl().static::CARS_URI;
    }
}
