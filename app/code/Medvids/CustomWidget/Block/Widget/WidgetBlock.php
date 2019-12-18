<?php
declare(strict_types=1);

namespace Medvids\CustomWidget\Block\Widget;

class WidgetBlock extends \Magento\Cms\Block\Widget\Block
{
    protected $_template = 'Medvids_CustomWidget::widget/customwidget.phtml';

    /**
     * @var \Magento\Cms\Helper\Page $cmsPage
     */
    private $cmsPage;

    /**
     * @var string $href
     */
    private $href = '';

    /**
     * WidgetBlock constructor.
     * @param \Magento\Cms\Helper\Page $cmsPage
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider
     * @param \Magento\Cms\Model\BlockFactory $blockFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Cms\Helper\Page $cmsPage,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        array $data = []
    ) {
        parent::__construct($context, $filterProvider, $blockFactory, $data);
        $this->cmsPage = $cmsPage;
    }

    /**
     * Prepare page url. Use passed identifier
     * or retrieve such using passed page id.
     *
     * @return string
     */
    public function getHref(): string
    {
        if (!$this->href) {
            $this->href = '';
            if ($this->getData('href')) {
                $this->href = $this->getData('href');
            } elseif ($this->getData('page_id')) {
                $this->href = $this->cmsPage->getPageUrl($this->getData('page_id'));
            }
        }

        return $this->href;
    }
}
