<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Model\Config\Source;

class CronFrequency implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @inheritDoc
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => '*/30 * * * *', 'label' => 'Every 30 Minutes'],
            ['value' => '* */1 * * *', 'label' => 'Every 1 hour'],
            ['value' => '* */2 * * *', 'label' => 'Every 2 hours'],
            ['value' => '* */3 * * *', 'label' => 'Every 3 hours'],
            ['value' => '* */6 * * *', 'label' => 'Every 6 hours'],
            ['value' => '* */12 * * *', 'label' => 'Every 12 hours'],
            ['value' => '* * */1 * *', 'label' => 'Everyday'],
            ['value' => '* * */2 * *', 'label' => 'Every 2 days'],
            ['value' => '* * */3 * *', 'label' => 'Every 3 days'],
        ];
    }
}
