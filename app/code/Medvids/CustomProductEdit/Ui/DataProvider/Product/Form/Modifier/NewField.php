<?php

namespace Medvids\CustomProductEdit\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Element\Checkbox;
use Magento\Ui\Component\Form\Element\ColorPicker;
use Magento\Ui\Component\Form\Element\DataType\Date;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Element\Select;
use Magento\Ui\Component\Form\Element\DataType\Text;

class NewField extends AbstractModifier
{
    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * NewField constructor.
     * @param LocatorInterface $locator
     */
    public function __construct(
        LocatorInterface $locator
    ) {
        $this->locator = $locator;
    }

    /**
     * @inheritdoc
     * return array
     */
    public function modifyData(array $data): array
    {

        return $data;
    }

    /**
     * @inheritdoc
     * return array
     */
    public function modifyMeta(array $meta): array
    {
        $meta = array_replace_recursive(
            $meta,
            [
                'custom_fieldset' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('Custom Fieldset'),
                                'componentType' => Fieldset::NAME,
                                'dataScope' => 'data.product.custom_fieldset',
                                'collapsible' => true,
                                'sortOrder' => 5
                            ]
                        ]
                    ],
                    'children' => $this->getCustomFields()
                ]
            ]
        );
        return $meta;
    }

    /**
     * Generate custom field
     * @return array
     */
    public function getCustomFields(): array
    {
        return [
            'custom_select' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Custom Field'),
                            'componentType' => Field::NAME,
                            'formElement' => Select::NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => 10,
                            'options' => [
                                ['value' => '0', 'label' => __('No')],
                                ['value' => '1', 'label' => __('Yes')]
                            ]
                        ]
                    ]
                ]
            ],
            'custom_toggle' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Custom Toggle'),
                            'componentType' => Field::NAME,
                            'formElement' => Checkbox::NAME,
                            'prefer' => 'toggle',
                            'sortOrder' => 20,
                            'value' => '1',
                            'valueMap' => [
                                'false' => '0',
                                'true' => '1',
                            ]
                        ]
                    ]
                ]
            ],
            'custom_text_field' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Custom Text Field'),
                            'componentType' => Field::NAME,
                            'formElement' => Input::NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => 30
                        ]
                    ]
                ]
            ],
            'custom_color_field' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Custom Color Field'),
                            'componentType' => Field::NAME,
                            'formElement' => ColorPicker::NAME,
                            'colorFormat' => 'rgb',
                            'colorPickerMode' => 'full',
                            'sortOrder' => 40
                        ]
                    ]
                ]
            ],
            'custom_date_field' => [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'label' => __('Custom Date Field'),
                            'componentType' => Field::NAME,
                            'formElement' => Date::NAME,
                            'dataType' => Text::NAME,
                            'sortOrder' => 50
                        ]
                    ]
                ]
            ]
        ];
    }
}
