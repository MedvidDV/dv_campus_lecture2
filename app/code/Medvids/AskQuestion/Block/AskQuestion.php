<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Block;

class AskQuestion extends \Magento\Catalog\Block\Product\View
{
    /**
     * @return string
     */
    protected function _toHtml(): string
    {
        return $this->getProduct()->getData('allow_question')
            ? parent::_toHtml()
            : '';
    }
}
