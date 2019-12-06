<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Controller\Submit;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;

class Index extends \Magento\Framework\App\Action\Action
{
    const STATUS_ERROR = 'Error';
    const STATUS_SUCCESS = 'Success';

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    private $formKeyValidator;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $formKeyValidator;
    }

    public function execute()
    {
        $request = $this->getRequest();
        try {
            if (!$this->formKeyValidator->validate($request) || $request->getParam('hideit')) {
                throw new LocalizedException(__('Something went wrong, Please contact us if the issue persists'));
            }
            $data = [
                'status' => self::STATUS_SUCCESS,
                'message' => 'Form submitted'
            ];

        } catch (LocalizedException $e) {
            $data = [
                'status' => self::STATUS_ERROR,
                'message' => $e->getMessage()
            ];
        }
        $controllerResult = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        return $controllerResult->setData($data);
    }
}
