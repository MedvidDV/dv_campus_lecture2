<?php
declare(strict_types=1);

namespace Medvids\CustomChat\Controller\Collection;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Messages extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpGetActionInterface
{
    public const ROW_PER_PAGE = 10;

    /**
     * @var \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;

    /**
     * Messages constructor.
     * @param \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory $collectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->timezone = $timezone;
    }

    /**
     * @inheritDoc
     * @throws LocalizedException
     */
    public function execute()
    {
        $messages = [];
        try {
            /** @var \Medvids\CustomChat\Model\ResourceModel\CustomChat\Collection $messageCollection */
            $messageCollection = $this->collectionFactory->create();
            $messageCollection->addFieldToFilter(
                'website_id',
                ['eq' => $this->storeManager->getWebsite()->getId()]
            );

            if ($messageCollection->getSize() > self::ROW_PER_PAGE) {
                $messageCollection->getSelect()
                    ->limit(self::ROW_PER_PAGE, $messageCollection->getSize()-self::ROW_PER_PAGE);
            }

            /** @var \Medvids\CustomChat\Model\CustomChat $message */
            foreach ($messageCollection as $key => $message) {
                $messages[] = [
                    'authorType' => $message->getAuthorType(),
                    'message' => $message->getMessage(),
                    'createdAt' => $this->timezone->date($message->getCreatedAt())->format('H:i')
                ];
            }
            $data = ['messages' => $messages];
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
            throw $e;
        }

        $controllerResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $controllerResult->setData($data);
    }
}
