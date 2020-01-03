<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Controller\Submit;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\Store;

class Index extends \Magento\Framework\App\Action\Action
{
    public const STATUS_ERROR = 'Error';
    public const STATUS_SUCCESS = 'Success';

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * @var \Medvids\AskQuestion\Model\AskQuestionFactory
     */
    private $askQuestionFactory;

    /**
     * Index constructor.
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Medvids\AskQuestion\Model\AskQuestionFactory $askQuestionFactory
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Medvids\AskQuestion\Model\AskQuestionFactory $askQuestionFactory,
        \Magento\Framework\App\Action\Context $context
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $formKeyValidator;
        $this->askQuestionFactory = $askQuestionFactory;
    }

    public function execute()
    {
        $request = $this->getRequest();
        try {
            if (!$this->formKeyValidator->validate($request) || $request->getParam('hideit')) {
                throw new LocalizedException(__('Something went wrong, Please contact us if the issue persists'));
            }

            if (!$request->isAjax()) {
                throw new LocalizedException(__('Request is not valid'));
            }

            $askQuestion = $this->askQuestionFactory->create();
            $askQuestion->setName($request->getParam('name_question'))
                ->setEmail($request->getParam('email_question'))
                ->setPhone($request->getParam('tel_question'))
                ->setProductName($request->getParam('product_name'))
                ->setProductSku($request->getParam('product_sku'))
                ->setQuestion($request->getParam('question_question'))
                ->setStoreId($request->getParam('store_id_question'));
            $askQuestion->save();

            $data = [
                'status' => self::STATUS_SUCCESS,
                'message' => __('Form submitted')
            ];

        } catch (\Exception $e) {

            $data = [
                'status' => self::STATUS_ERROR,
                'message' => __('Something went wrong, Please contact us if the issue persists')
            ];
        }
        $controllerResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $controllerResult->setData($data);
    }
}
