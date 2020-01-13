<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\UI\Component\Listing\Columns;

class Actions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;
    /**
     * @var \Magento\Framework\Escaper
     */
    private $escaper;

    /**
     * Actions constructor.
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Escaper $escaper,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->escaper = $escaper;
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource):array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['question_id'])) {
                    $title = $this->escaper->escapeHtml($item['question_id']);
                    $item[$this->getData('name')] = [
                        'answer' => [
                            'href' => $this->urlBuilder->getUrl(
                                'askquestionadmin/index/answer',
                                [
                                    'id' => $item['question_id']
                                ]
                            ),
                            'label' => __('Answer')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                'askquestionadmin/index/delete',
                                [
                                    'id' => $item['question_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete question #%1', $title),
                                'message' => __('Are you sure you want to delete a question#%1?', $title)
                            ]
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
