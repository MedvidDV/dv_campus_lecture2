<?php
declare(strict_types=1);

namespace Medvids\DemoController\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;

class Data extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->getLayout()->getBlock('demo-result')
            ->setData('$firstName', $this->getRequest()->getParam('first_name', null));
        $resultPage->getLayout()->getBlock('demo-result')
            ->setData('$lastName', $this->getRequest()->getParam('last_name', null));
        $resultPage->getLayout()->getBlock('demo-result')
            ->setData('$repository', $this->getRequest()->getParam('repository', null));

        return $resultPage;
    }
}
