<?php
declare(strict_types=1);

namespace Medvids\CustomChat\Controller\Submit;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Index extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    public const STATUS_ERROR = 'Error';
    public const STATUS_SUCCESS = 'Success';

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator;
     */
    private $formKeyValidator;

    /**
     * @var \Medvids\CustomChat\Model\CustomChatFactory $customChatMessageFactory
     */
    private $customChatMessageFactory;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private $customerSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * @param \Medvids\CustomChat\Model\CustomChatFactory $customChatMessageFactory
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Medvids\CustomChat\Model\CustomChatFactory $customChatMessageFactory,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $formKeyValidator;
        $this->customChatMessageFactory = $customChatMessageFactory;
        $this->customerSession = $customerSession;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $request = $this->getRequest();

        try {
            if (!$this->formKeyValidator->validate($request) || $request->getParam('hideit')) {
                throw new LocalizedException(__('Something went wrong, Please contact us if the issue persists'));
            }

            $chatHash = $this->customerSession->getData('chat_hash') ?? [];

            if ($this->customerSession->isLoggedIn()) {
                $customer = $this->customerSession->getCustomerData();

                /** @var \Medvids\CustomChat\Model\CustomChat $customChatMessage */
                $customChatMessage = $this->customChatMessageFactory->create();
                $customChatMessage->setAuthorType($request->getParam('author_type'))
                    ->setAuthorId($customer->getId())
                    ->setAuthorName($customer->getFirstname() . ' ' . $customer->getLastname())
                    ->setMessage($request->getParam('author_message'))
                    ->setWebsiteId($this->storeManager->getWebsite()->getId());

                array_key_exists('user_hash', $chatHash) ?: $chatHash['user_hash'] = uniqid('chat_', true);

                $customChatMessage->setChatHash($chatHash['user_hash']);
                $customChatMessage->save();
            } else {

                /** @var \Medvids\CustomChat\Model\CustomChat $customChatMessage */
                $customChatMessage = $this->customChatMessageFactory->create();
                $customChatMessage->setAuthorType($request->getParam('author_type'))
                    ->setAuthorName('Anonymous')
                    ->setMessage($request->getParam('author_message'))
                    ->setWebsiteId($this->storeManager->getWebsite()->getId());

                array_key_exists('guest_hash', $chatHash) ?: $chatHash['guest_hash'] = uniqid('chat_', true);

                $customChatMessage->setChatHash($chatHash['guest_hash']);
                $customChatMessage->save();
            }
            $this->customerSession->setChatHash($chatHash);

            $data = [
                'status' => self::STATUS_SUCCESS,
                'title' => 'Message Sent',
                'message' => __('Will get back to you as soon as possible')
            ];
        } catch (\Exception $e) {
            $data = [
                'status' => self::STATUS_ERROR,
                'title' => 'Something went wrong',
                'message' => __('Message has not been sent. Please contact us if the issue persists.')
            ];
        }
        $controllerResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        return $controllerResult->setData($data);
    }
}
