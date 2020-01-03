<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class Answer extends \Magento\Framework\App\Action\Action
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $request = $this->getRequest();
        $id = $request->getParam('id');

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend(__('Answer Question'));
        $resultBlock = $resultPage->getLayout()->getBlock('ask.question.response');
        $resultBlock->setData('questionId', $id);

        return $resultPage;
    }
}
