<?php
declare(strict_types=1);

namespace Medvids\DealerAccountCreate\Block\Widget;

/**
 * @inheritDoc
 */
class Fax extends \Magento\Customer\Block\Widget\Fax
{
    /**
     * @return void
     */
    public function _construct()
    {
        parent::_construct();

        // default template location
        $this->setTemplate('Medvids_DealerAccountCreate::widget/fax.phtml');
    }
}
