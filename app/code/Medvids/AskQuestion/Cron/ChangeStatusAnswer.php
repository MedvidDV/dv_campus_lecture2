<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Cron;

use Medvids\AskQuestion\Model\AskQuestion;

class ChangeStatusAnswer
{
    public const LIFETIME = 3; // days

    /**
     * @var \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     */
    private $timezone;

    /**
     * @var \Magento\Framework\DB\Adapter\Pdo\Mysql $mysql
     */
    private $mysql;

    /**
     * ChangeStatusAnswer constructor.
     * @param \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collectionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Framework\DB\Adapter\Pdo\Mysql $mysql
     */
    public function __construct(
        \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collectionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\DB\Adapter\Pdo\Mysql $mysql
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->transactionFactory = $transactionFactory;
        $this->timezone = $timezone;
        $this->mysql = $mysql;
    }

    /**
     * @throws \Exception
     */
    public function execute(): void
    {
        /** @var \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\Collection $collection */
        $collection = $this->collectionFactory->create();
        $lifeTimeCondition = '-' . self::LIFETIME . ' days';
        $dayCondition = $this->timezone->date()
            ->add(\DateInterval::createFromDateString($lifeTimeCondition))
            ->format($this->mysql::DATETIME_FORMAT);

        $collection->addFieldToFilter('status', ['neq' => AskQuestion::STATUS_ANSWERED])
             ->addFieldToFilter('created_at', ['lt' => $dayCondition]);

        $transaction = $this->transactionFactory->create();

        /** @var AskQuestion $question */
        foreach ($collection->getItems() as $question) {
            $question->setStatus(AskQuestion::STATUS_ANSWERED);
            $transaction->addObject($question);
        }
        $transaction->save();
    }
}
