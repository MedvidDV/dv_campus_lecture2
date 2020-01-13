<?php
declare(strict_types=1);

namespace Medvids\CustomChat\Controller\Submit;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Index extends \Magento\Framework\App\Action\Action implements
    \Magento\Framework\App\Action\HttpPostActionInterface
{
    const STATUS_ERROR = 'Error';
    const STATUS_SUCCESS = 'Success';

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator;
     */
    private $formKeyValidator;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterfaceFactory
     */
    private $customerRepositoryFactory;

    /**
     * @var \Medvids\CustomChat\Model\CustomChatFactory
     */
    private $customChatMessageFactory;

    /**
     * @param \Magento\Customer\Api\CustomerRepositoryInterfaceFactory $customerRepositoryFactory
     * @param \Medvids\CustomChat\Model\CustomChatFactory $customChatMessageFactory
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Customer\Api\CustomerRepositoryInterfaceFactory $customerRepositoryFactory,
        \Medvids\CustomChat\Model\CustomChatFactory $customChatMessageFactory,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $formKeyValidator;
        $this->customerRepositoryFactory = $customerRepositoryFactory;
        $this->customChatMessageFactory = $customChatMessageFactory;
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

            $customerId = $request->getParam('author_id');

            /** @var \Magento\Customer\Api\CustomerRepositoryInterface $customerEntity */
            $customerEntity = $this->customerRepositoryFactory->create();
            $customer = $customerEntity->getById($customerId);

            /** @var \Medvids\CustomChat\Model\CustomChat $customChatMessage */
            $customChatMessage = $this->customChatMessageFactory->create();
            $customChatMessage->setAuthorType($request->getParam('author_type'))
                ->setAuthorName($request->getParam('author_name'))
                ->setAuthorId($customerId)
                ->setAuthorName($customer->getFirstname() . ' ' . $customer->getLastname())
                ->setMessage($request->getParam('author_message'))
                ->setWebsiteId($request->getParam('website_id'))
                ->setChatHash('123456789');
            $customChatMessage->save();

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
