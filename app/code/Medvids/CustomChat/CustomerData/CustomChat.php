<?php
declare(strict_types=1);

namespace Medvids\CustomChat\CustomerData;

use Magento\Framework\Exception\LocalizedException;

class CustomChat implements \Magento\Customer\CustomerData\SectionSourceInterface
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
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var \Magento\Framework\DB\TransactionFactory
     */
    private $transactionFactory;

    /**
     * CustomChat constructor.
     * @param \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory $collectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    public function __construct(
        \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\DB\TransactionFactory $transactionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->timezone = $timezone;
        $this->logger = $logger;
        $this->customerSession = $customerSession;
        $this->transactionFactory = $transactionFactory;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function getSectionData(): array
    {
        $messages = [];

        try {
            $chatHash = $this->customerSession->getData('chat_hash') ?? [];

            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomerData();

                if (!array_key_exists('user_hash', $chatHash)) {
                    /** @var string $customerChatHash getting user last message hash  */
                    $chatHash['user_hash'] = $this->collectionFactory->create()
                            ->addFieldToFilter('author_id', $customer->getId())
                            ->addFieldToFilter('author_type', 'customer')
                            ->setPageSize(1)
                            ->getFirstItem()
                            ->getData('chat_hash')
                        ?? uniqid('chat_', true);
                }

                if (array_key_exists('guest_hash', $chatHash)) {
                    $guestMessageCollection = $this->collectionFactory->create();

                    /** @var \Magento\Framework\DB\Transaction $transaction */
                    $transaction = $this->transactionFactory->create();
                    $guestMessages = $guestMessageCollection
                        ->addFieldToFilter('chat_hash', $chatHash['guest_hash'])
                        ->addFieldToFilter('author_type', 'user');

                    /** @var \Medvids\CustomChat\Model\CustomChat $guestMessage */
                    foreach ($guestMessages as $guestMessage) {
                        $message = $guestMessage->setChatHash($chatHash['user_hash'])
                            ->setAuthorName($customer->getFirstname() . ' ' . $customer->getLastname())
                            ->setAuthorId($customer->getId());
                        $transaction->addObject($message);
                    }
                    $transaction->save();

                    unset($chatHash['guest_hash']);
                }
            }

            if ($chatHash) {
                $this->customerSession->setChatHash($chatHash);

                $messageCollection = $this->collectionFactory->create();

                $messageCollection->addFieldToFilter(
                    'website_id',
                    $this->storeManager->getWebsite()->getId()
                );
                $messageCollection->addFieldToFilter(
                    'chat_hash',
                    $chatHash['guest_hash'] ?? $chatHash['user_hash']
                );

                if ($messageCollection->getSize() > self::ROW_PER_PAGE) {
                    $messageCollection->getSelect()
                        ->limit(self::ROW_PER_PAGE, $messageCollection->getSize() - self::ROW_PER_PAGE);
                }

                /** @var \Medvids\CustomChat\Model\CustomChat $message */
                foreach ($messageCollection as $message) {
                    $messages[] = [
                        'authorType' => $message->getAuthorType(),
                        'message' => $message->getMessage(),
                        'createdAt' => $this->timezone->date($message->getCreatedAt())->format('H:i')
                    ];
                }
            }

        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
        }

        return ['messages' => $messages];
    }
}
