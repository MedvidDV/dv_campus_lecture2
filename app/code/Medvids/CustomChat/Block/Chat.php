<?php
declare(strict_types=1);

namespace Medvids\CustomChat\Block;

use \Magento\Framework\Exception\LocalizedException;
use Medvids\CustomChat\Model\ResourceModel\CustomChat\Collection;

class Chat extends \Magento\Framework\View\Element\Template
{
    public const ROW_PER_PAGE = 10;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    private $logger;
    /**
     * @var \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory $collectionFactory
     */
    private $collectionFactory;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     */
    private $timezone;

    /**
     * Chat constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory $collectionFactory
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger,
        \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory $collectionFactory,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->storeManager = $storeManager;
        $this->_data = $data;
        $this->logger = $logger;
        $this->collectionFactory = $collectionFactory;
        $this->timezone = $timezone;
    }

    /**
     * @return \Medvids\CustomChat\Model\ResourceModel\CustomChat\Collection
     */
    public function getMessageCollection(): Collection
    {
        /** @var \Medvids\CustomChat\Model\ResourceModel\CustomChat\Collection $messageCollection */
        $messageCollection = $this->collectionFactory->create();
        $messageCollection->addFieldToFilter(
            'website_id',
            ['eq' => $this->getCurrentWebsiteId()]
        );

        if ($messageCollection->getSize() > self::ROW_PER_PAGE) {
            $messageCollection->getSelect()
                ->limit(self::ROW_PER_PAGE, $messageCollection->getSize()-self::ROW_PER_PAGE);
        }

        return $messageCollection;
    }

    /**
     * @return mixed
     */
    public function getCurrentWebsiteId()
    {
        try {
            return $this->storeManager->getWebsite()->getId();
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @param string $messageDate
     * @return string
     */
    public function getFormattedDate(string $messageDate): string
    {
        return $this->timezone
            ->date($messageDate)
            ->format('H:i');
    }
}
