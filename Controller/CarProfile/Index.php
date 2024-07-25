<?php
/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Controller\CarProfile;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Razoyo\CarProfile\Api\CustomerCarRepositoryInterface;
use Razoyo\CarProfile\Api\Data\CustomerCarInterface;
use Razoyo\CarProfile\Model\ApiCarsRepository;
use Razoyo\CarProfile\Model\CustomerCar;
use Razoyo\CarProfile\Api\ApiCarsRepositoryInterface;

class Index implements HttpGetActionInterface
{

    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly Session $customerSession,
        private readonly Url $customerUrl,
        private readonly ApiCarsRepositoryInterface $apiCarsRepository,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CustomerCarRepositoryInterface $customerCarRepository
    ) { }


    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->customerUrl->getLoginUrl());
            return $resultRedirect;
        }

        //TEST
        //@todo customer_id when  varnish cache requires context
        $res = $this->apiCarsRepository->getCarMakes();
        $res2 = $this->apiCarsRepository->getCarsByMake('Toyota');
        $res2 = $this->apiCarsRepository->getCarsByMake('Toyota');
        $res3 = $this->apiCarsRepository->getCarsByMake('Honda');
        /** @var CustomerCarInterface $item */
        foreach ($res2 as $item) {
            $car = $this->apiCarsRepository->getCarById($item->getId());
            var_dump($car->getImage());
        }

        foreach ($res3 as $item) {
            $car = $this->apiCarsRepository->getCarById($item->getId());
            var_dump($car->getId());
        }
//        var_dump($res);
        $customer = $this->customerRepository->getById($this->customerSession->getCustomerId());
        /** @var CustomerCar $customerCar */
        $customerCar = $customer->getExtensionAttributes()->getCustomerCar();
        if ($customerCar){
//            var_dump($customerCar->getData());
        }


        die('asd');
        //TEST

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('My Car'));

        return $resultPage;
    }
}
