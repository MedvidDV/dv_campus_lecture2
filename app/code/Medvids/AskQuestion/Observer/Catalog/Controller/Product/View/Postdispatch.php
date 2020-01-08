<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Observer\Catalog\Controller\Product\View;

class Postdispatch implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Catalog\Helper\Data
     */
    private $helper;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * Predispatch constructor.
     * @param \Magento\Catalog\Helper\Data $helper
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Catalog\Helper\Data $helper,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->helper = $helper;
        $this->resultPageFactory = $resultPageFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $attributeValue = $this->helper->getProduct()->getAllowQuestion();
            if ($attributeValue) {
                $page = $this->resultPageFactory->create();
                return $page->addHandle('ask_question_form');
            }

            return $this;
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $this;
    }
}
