<?php

namespace Medvids\DealerAccountCreate\Block\Widget;

class Telephone extends \Magento\Customer\Block\Widget\Telephone
{
    /**
     * @return void
     */
    public function _construct()
    {
        parent::_construct();

        // default template location
        $this->setTemplate('Medvids_DealerAccountCreate::widget/telephone.phtml');
    }
}
