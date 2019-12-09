<?php
declare(strict_types=1);

namespace Medvids\DealerAccountCreate\Block\Widget;

class Taxvat extends \Magento\Customer\Block\Widget\Taxvat
{
    /**
     * @return void
     */
    public function _construct(): void
    {
        parent::_construct();
        $this->setTemplate('Medvids_DealerAccountCreate::widget/taxvat.phtml');
    }
}
