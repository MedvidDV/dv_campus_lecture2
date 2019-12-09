<?php

namespace Medvids\DealerAccountCreate\Block\Widget;

/**
 * @inheritDoc
 */
class Gender extends \Magento\Customer\Block\Widget\Gender
{
    /**
     * Initialize block
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('Medvids_DealerAccountCreate::widget/gender.phtml');
    }

}
