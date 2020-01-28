<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Model\Config\Source;

class TestSelect implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @inheritDoc
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'us', 'label' => 'United States'],
            ['value' => 'ua', 'label' => 'Ukraine'],
            ['value' => 'de', 'label' => 'Germany'],
            ['value' => 'au', 'label' => 'Australia'],
            ['value' => 'gb', 'label' => 'Grate Britain'],
            ['value' => 'fr', 'label' => 'France']
        ];
    }
}
