<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @param string $optionString
     * @param null $storeCode
     * @return string
     */
    public function getConfig(string $optionString, $storeCode = null): string
    {
        return $this->scopeConfig->getValue($optionString, ScopeInterface::SCOPE_STORE, $storeCode);
    }
}
