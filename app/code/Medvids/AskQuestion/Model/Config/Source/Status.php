<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_ANSWERED = 'answered';
    /**
     * @inheritDoc
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'label' => __('Pending'),
                'value' => self::STATUS_PENDING
                ],
            [
                'label' => __('Answered'),
                'value' => self::STATUS_ANSWERED
            ]
        ];
    }
}
