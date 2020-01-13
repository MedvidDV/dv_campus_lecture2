<?php
declare(strict_types=1);

namespace Medvids\CustomChat\Model\ResourceModel\CustomChat;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'message_id';

    protected function _construct()
    {
        $this->_init(
            \Medvids\CustomChat\Model\CustomChat::class,
            \Medvids\CustomChat\Model\ResourceModel\CustomChat::class
        );
    }
}
