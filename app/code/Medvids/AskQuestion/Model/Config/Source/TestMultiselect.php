<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Model\Config\Source;

class TestMultiselect implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @inheritDoc
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'red', 'label' => 'Red'],
            ['value' => 'yellow', 'label' => 'Yellow'],
            ['value' => 'green', 'label' => 'Green'],
            ['value' => 'black', 'label' => 'Black'],
            ['value' => 'blue', 'label' => 'Blue'],
            ['value' => 'white', 'label' => 'White']
        ];
    }
}
