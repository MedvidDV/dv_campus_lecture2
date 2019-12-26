<?php
declare(strict_types=1);

namespace Medvids\RecursiveData\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\Page;

class Index extends \Magento\Framework\App\Action\Action implements HttpGetActionInterface
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);

        $resultPage->getLayout()->getBlock('recursive.data.result');

        return $resultPage;
    }
}
