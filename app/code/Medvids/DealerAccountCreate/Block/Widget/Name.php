<?php
declare(strict_types=1);

namespace Medvids\DealerAccountCreate\Block\Widget;

/**
 * @inheritDoc
 */
class Name extends \Magento\Customer\Block\Widget\Name
{
    /**
     * @return void
     */
    public function _construct(): void
    {
        parent::_construct();

        // default template location
        $this->setTemplate('Medvids_DealerAccountCreate::widget/name.phtml');
    }
}
