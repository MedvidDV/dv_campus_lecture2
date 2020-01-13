<?php
declare(strict_types=1);

namespace Medvids\CustomChat\Model\ResourceModel;

class CustomChat extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init('medvids_custom_chat', 'message_id');
    }
}
