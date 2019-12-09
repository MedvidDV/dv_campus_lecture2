<?php
declare(strict_types=1);

namespace Medvids\DealerAccountCreate\Block\Widget;

/**
 * @inheritDoc
 */
class Company extends \Magento\Customer\Block\Widget\Company
{
    /**
     * @return void
     */
    public function _construct(): void
    {
        parent::_construct();

        // default template location
        $this->setTemplate('Medvids_DealerAccountCreate::widget/company.phtml');
    }
}
