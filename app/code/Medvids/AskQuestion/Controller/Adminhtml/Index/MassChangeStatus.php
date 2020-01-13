<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Controller\Adminhtml\Index;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;

class MassChangeStatus extends \Magento\Backend\App\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Medvids_AskQuestion::save';

    /**
     * @var \Magento\Ui\Component\MassAction\Filter $filter
     */
    private $filter;

    /**
     * @var \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collectionFactory
     */
    public function __construct(
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collectionFactory,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Ui\Component\MassAction\Filter $filter
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->transactionFactory = $transactionFactory;
        parent::__construct($context);
    }

    /**
     * Execute action
     *
     * @return Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute(): Redirect
    {
        /** @var \Magento\Framework\DB\Transaction $transaction */
        $transaction = $this->transactionFactory->create();

        /** @var \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collection */
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        /** @var \Medvids\AskQuestion\Model\AskQuestion $item */
        foreach ($collection as $item) {
            $item->setStatus(\Medvids\AskQuestion\Model\AskQuestion::STATUS_ANSWERED);
            $transaction->addObject($item);
        }

        $transaction->save();

        $this->messageManager->addSuccessMessage(
            __('A total of %1 record(s) have been update.', $collection->getSize())
        );

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
