<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Model;

use Medvids\AskQuestion\Model\ResourceModel\AskQuestion as AskQuestionResource;

/**
 * @method int|string getQuestionId()
 * @method string getName()
 * @method AskQuestion setName(string $name)
 * @method string getEmail()
 * @method AskQuestion setEmail(string $email)
 * @method string getPhone()
 * @method AskQuestion setPhone(string $phone)
 * @method string getProductName()
 * @method AskQuestion setProductName(string $productName)
 * @method string getProductSKU()
 * @method AskQuestion setProductSku(string $productSKU)
 * @method string getQuestion()
 * @method AskQuestion setQuestion(string $question)
 * @method string getCreatedAt()
 * @method string getStatus()
 * @method AskQuestion setStatus(string $status)
 * @method int|string getStoreId()
 * @method AskQuestion setStoreId(string $storeId)
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
