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
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    private $productResource;

    /**
     * UpgradeSchema constructor.
     * @param \Medvids\AskQuestion\Model\AskQuestionFactory $askQuestionFactory
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     */
    public function __construct(
        \Medvids\AskQuestion\Model\AskQuestionFactory $askQuestionFactory,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product $productResource
    ) {
        $this->askQuestionFactory = $askQuestionFactory;
        $this->transactionFactory = $transactionFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->collectionFactory = $collectionFactory;
        $this->productResource = $productResource;
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

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'allow_question',
                [
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Allow Question',
                    'input' => 'boolean',
                    'class' => '',
                    'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => true,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => ''
                ]
            );

            // Update existing products to have 'allow_question' switched to true
            /* @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
            $collection = $this->collectionFactory->create();
            $collection->getItems();

            /** @var \Magento\Catalog\Model\Product $product */
            foreach ($collection as $product) {
                try {
                    $product->setAllowQuestion(true);
                    $this->productResource->saveAttribute($product, 'allow_question');
                } catch (\Exception $e) {
                    $e->getMessage();
                }
            }
        }
    }
}
