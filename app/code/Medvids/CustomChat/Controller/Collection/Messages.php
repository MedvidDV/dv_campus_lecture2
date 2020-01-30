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
     * @var \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory $collectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    private $logger;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     */
    private $timezone;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Magento\Framework\App\Action\Context $context
     */
    private $context;

    /**
     * @var \Magento\Framework\DB\TransactionFactory $transactionFactory
     */
    private $transactionFactory;

    /**
     * Messages constructor.
     * @param \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory $collectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\DB\TransactionFactory $transactionFactory
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Medvids\CustomChat\Model\ResourceModel\CustomChat\CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\DB\TransactionFactory $transactionFactory,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
        $this->timezone = $timezone;
        $this->customerSession = $customerSession;
        $this->transactionFactory = $transactionFactory;
        $this->context = $context;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function execute()
    {
        $messages = [];
        try {
            $chat_hash = $this->customerSession->getData('chat_hash') ?: [];

            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomerData();

                if (!array_key_exists('user_hash', $chat_hash)) {

                    /** @var string $user_chat_hash getting user last message hash  */
                    $user_chat_hash = $this->collectionFactory->create()
                        ->addFieldToSelect('chat_hash')
                        ->addFieldToFilter('author_id', ['eq' => $customer->getId()])
                        ->addFieldToFilter('author_type', ['eq' => 'user'])
                        ->getLastItem()
                        ->getData('chat_hash');

                    $chat_hash['user_hash'] = $user_chat_hash;
                }

                if (array_key_exists('guest_hash', $chat_hash)) {

                    $guestMessageCollection = $this->collectionFactory->create();

                    /** @var \Magento\Framework\DB\Transaction $transaction */
                    $transaction = $this->transactionFactory->create();
                    $guestMessages = $guestMessageCollection
                        ->addFieldToFilter('chat_hash', ['eq' => $chat_hash['guest_hash']])
                        ->addFieldToFilter('author_type', ['eq' => 'user']);

                    /** @var \Medvids\CustomChat\Model\CustomChat $guestMessage */
                    foreach ($guestMessages as $guestMessage) {
                        $message = $guestMessage->setChatHash($chat_hash['user_hash'])
                            ->setAuthorName($customer->getFirstname() . ' ' . $customer->getLastname())
                            ->setAuthorId($customer->getId());
                        $transaction->addObject($message);
                    }
                    $transaction->save();

                    unset($chat_hash['guest_hash']);
                }

            } else {
                if (!array_key_exists('guest_hash', $chat_hash)) {
                    $chat_hash['guest_hash'] = uniqid('chat_', true);
                }
            }

            $messageCollection = $this->collectionFactory->create();

            $this->customerSession->setChatHash($chat_hash);

            $messageCollection->addFieldToFilter(
                'website_id',
                ['eq' => $this->storeManager->getWebsite()->getId()]
            )->addFieldToFilter(
                'chat_hash',
                ['eq' => $chat_hash]
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
            $data = [
                'messages' => [
                    [
                        'authorType' => 'admin',
                        'message' => __('How may I help you?')
                    ]
                ]
            ];
        }

        $controllerResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $controllerResult->setData($data);
    }
}
