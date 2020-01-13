<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Model;

use Medvids\AskQuestion\Model\ResourceModel\AskQuestion as AskQuestionResource;

/**
 * @method int|string getQuestionId()
 * @method string getName()
 * @method $this setName(string $name)
 * @method string getEmail()
 * @method $this setEmail(string $email)
 * @method string getPhone()
 * @method $this setPhone(string $phone)
 * @method string getProductName()
 * @method $this setProductName(string $productName)
 * @method string getProductSKU()
 * @method $this setProductSku(string $productSKU)
 * @method string getQuestion()
 * @method $this setQuestion(string $question)
 * @method string getCreatedAt()
 * @method string getStatus()
 * @method $this setStatus(string $status)
 * @method int|string getStoreId()
 * @method $this setStoreId(string $storeId)
 */
class AskQuestion extends \Magento\Framework\Model\AbstractModel
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ANSWERED = 'answered';

    /**
     * @var string
     */
    protected $_eventPrefix = 'medvids_askquestion';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(AskQuestionResource::class);
    }
}
