<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Cron;

class ChangeStatusAnswer
{
    public const LIFETIME = 3; // days

    /**
     * @var \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    private $transactionFactory;
    /**
     * @var \Magento\Framework\Intl\DateTimeFactory
     */
    private $timeFactory;

    /**
     * ChangeStatusAnswer constructor.
     * @param \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Framework\Intl\DateTimeFactory $timeFactory
     */
    public function __construct(
        \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collectionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Framework\Intl\DateTimeFactory $timeFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->transactionFactory = $transactionFactory;
        $this->timeFactory = $timeFactory;
    }

    /**
     * @throws \Exception
     */
    public function execute(): void
    {
        $collection = $this->collectionFactory->create();
        $transaction = $this->transactionFactory->create();

        /** @var \Medvids\AskQuestion\Model\AskQuestion $question */
        foreach ($collection->getItems() as $question) {
            $createdAt = $this->timeFactory->create($question->getCreatedAt());
            $currentTime = $this->timeFactory->create();

            if ((int) $currentTime->diff($createdAt)->days > self::LIFETIME) {
                $question->setStatus(\Medvids\AskQuestion\Model\AskQuestion::STATUS_ANSWERED);
                $transaction->addObject($question);
            }
        }
        $transaction->save();
    }
}
