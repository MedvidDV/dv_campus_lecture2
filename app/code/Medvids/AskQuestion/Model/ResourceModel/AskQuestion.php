<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Model\ResourceModel;

class AskQuestion extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('medvids_ask_question', 'question_id');
    }
}
