<?php
/*
 *  @author     The S Group <support@sashas.org>
 *  @copyright  2024 Endeavour Inc. (https://www.sashas.org)
 *  @license     http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Controller\CarProfile;

use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;

/**
 * My car profile controller
 */
class Index implements HttpGetActionInterface
{

    /**
     * @param ResultFactory $resultFactory
     * @param Session $customerSession
     * @param Url $customerUrl
     */
    public function __construct(
        private readonly ResultFactory $resultFactory,
        private readonly Session $customerSession,
        private readonly Url $customerUrl
    ) { }


    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|(\Magento\Framework\Controller\Result\Redirect&\Magento\Framework\Controller\ResultInterface)|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page|(\Magento\Framework\View\Result\Page&\Magento\Framework\Controller\ResultInterface)
     */
    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->customerUrl->getLoginUrl());
            return $resultRedirect;
        }

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('My Car Profile'));
        return $resultPage;
    }
}
