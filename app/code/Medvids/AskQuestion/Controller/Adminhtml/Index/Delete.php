<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

class Delete extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Medvids\AskQuestion\Model\AskQuestionFactory
     */
    private $askQuestionFactory;

    /**hello
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Delete constructor.
     * @param \Medvids\AskQuestion\Model\AskQuestionFactory $askQuestionFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Medvids\AskQuestion\Model\AskQuestionFactory $askQuestionFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->askQuestionFactory = $askQuestionFactory;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $id = $this->getRequest()->getParam('id');
        try {
            $question = $this->askQuestionFactory->create();
            $question->load($id);
            if ($question->getId()) {
                $question->delete();
                $this->messageManager->addSuccessMessage(__('Question #%1 deleted', $id));
            } else {
                $this->messageManager->addErrorMessage(__('Question #%1 not found', $id));
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(__('Question #%1 has not been deleted', $id));
        }
        return $resultRedirect->setPath('*/*/');
    }
}
