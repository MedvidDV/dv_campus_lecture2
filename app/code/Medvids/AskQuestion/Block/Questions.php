<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Block;

use Medvids\AskQuestion\Model\ResourceModel\AskQuestion\Collection;

class Questions extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory
     */
    private $collectionFactory;

    /**
     * Questions constructor.
     * @param \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collectionFactory
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Medvids\AskQuestion\Model\ResourceModel\AskQuestion\CollectionFactory $collectionFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return Collection
     */
    public function getQuestionCollection(): Collection
    {
        $collection = $this->collectionFactory->create();
        return $collection->setOrder('created_at', 'DESC');
    }
}
