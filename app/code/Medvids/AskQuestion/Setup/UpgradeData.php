<?php

namespace Medvids\AskQuestion\Setup;

use Magento\Framework\DB\Transaction;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Store\Model\Store;
use Medvids\AskQuestion\Model\AskQuestion;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Medvids\AskQuestion\Model\AskQuestionFactory
     */
    private $askQuestionFactory;

    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    private $transactionFactory;

    /**
     * UpgradeSchema constructor.
     * @param \Medvids\AskQuestion\Model\AskQuestionFactory $askQuestionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    public function __construct(
        \Medvids\AskQuestion\Model\AskQuestionFactory $askQuestionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory
    ) {
        $this->askQuestionFactory = $askQuestionFactory;
        $this->transactionFactory = $transactionFactory;
    }

    /**
     * @inheritDoc
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $statuses = [AskQuestion::STATUS_PENDING, AskQuestion::STATUS_ANSWERED];
            /** @var Transaction $transaction */
            $transaction = $this->transactionFactory->create();

            for ($i = 1; $i <= 5; $i++) {
                /** @var AskQuestion $askQuestion */
                $askQuestion = $this->askQuestionFactory->create();
                $askQuestion->setName("Customer #$i")
                    ->setEmail("customer$i@example.com")
                    ->setPhone("+1(800)$i$i$i-$i$i$i$i")
                    ->setProductName("Product#$i")
                    ->setProductSku("product_sku_#$i$i$i")
                    ->setQuestion("Question from customer #$i")
                    ->setStatus($statuses[array_rand($statuses)])
                    ->setStoreId(Store::DISTRO_STORE_ID);
                $transaction->addObject($askQuestion);
            }

            $transaction->save();
        }
    }
}
