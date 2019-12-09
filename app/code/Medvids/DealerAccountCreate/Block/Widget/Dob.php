<?php
declare(strict_types=1);

namespace Medvids\DealerAccountCreate\Block\Widget;

/**
 * @inheritDoc
 */

class Dob extends \Magento\Customer\Block\Widget\Dob
{
    /**
     * @return void
     */
    public function _construct(): void
    {
        parent::_construct();
        $this->setTemplate('Medvids_DealerAccountCreate::widget/dob.phtml');
    }
    /**
     * Create correct date field
     *
     * @return string
     */
    public function getFieldHtml(): string
    {
        $this->dateElement->setData([
            'extra_params' => $this->getHtmlExtraParams(),
            'id' => $this->getHtmlId(),
            'name' => 'dob',
            'class' => $this->getHtmlClass(),
            'value' => $this->getValue(),
            'date_format' => $this->getDateFormat(),
            'image' => $this->getViewFileUrl('Magento_Theme::calendar.png'),
            'years_range' => '-120y:c+nn',
            'max_date' => '-1d',
            'change_month' => 'true',
            'change_year' => 'true',
            'show_on' => 'both',
            'first_day' => $this->getFirstDay()
        ]);
        return $this->dateElement->getHtml();
    }

    /**
     * Return id
     *
     * @return string
     */
    public function getHtmlId(): string
    {
        return 'dob_dealer';
    }
}
