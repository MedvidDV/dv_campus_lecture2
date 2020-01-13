<?php
declare(strict_types=1);

namespace Medvids\CustomChat\Model;

use Medvids\CustomChat\Model\ResourceModel\CustomChat as CustomChatResource;

/**
 * @method int|string  getMessageId();
 * @method string getAuthorType();
 * @method $this setAuthorType(string $authorType);
 * @method int getAuthorId();
 * @method $this setAuthorId(int $authorId);
 * @method string getAuthorName();
 * @method $this setAuthorName(string $authorName);
 * @method string getMessage();
 * @method $this setMessage(string $message);
 * @method int|string getWebsiteId();
 * @method $this setWebsiteId(int $websiteId);
 * @method string getChatHash();
 * @method $this setChatHash(string $chatHash);
 * @method string getCreatedAt();
 */
class CustomChat extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init(CustomChatResource::class);
    }
}
