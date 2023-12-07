<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Block;

class ProductBreadcrumbsSnippet extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Helper\Data
     */
    protected $catalogData;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    /**
     * @var \Barwenock\RichSnippets\Helper\Breadcrumbs
     */
    protected $breadcrumbsHelper;

    /**
     * @var \Barwenock\RichSnippets\Helper\Adminhtml\Config
     */
    protected $configHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Helper\Data $catalogData
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Barwenock\RichSnippets\Helper\Breadcrumbs $breadcrumbsHelper
     * @param \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Helper\Data                     $catalogData,
        \Magento\Framework\Serialize\Serializer\Json     $jsonSerializer,
        \Barwenock\RichSnippets\Helper\Breadcrumbs       $breadcrumbsHelper,
        \Barwenock\RichSnippets\Helper\Adminhtml\Config  $configHelper,
        array                                            $data = []
    ) {
        $this->catalogData = $catalogData;
        $this->jsonSerializer = $jsonSerializer;
        $this->breadcrumbsHelper = $breadcrumbsHelper;
        $this->configHelper = $configHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get breadcrumbs snippet
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBreadcrumbsSnippet(): string
    {
        $crumbs[] = ['label' => 'Home', 'link' => $this->_storeManager->getStore()->getBaseUrl()];

        $path = $this->catalogData->getBreadcrumbPath();
        foreach ($path as $breadcrumb) {
            $crumbs[] = ['label' => $breadcrumb['label'], 'link' => $breadcrumb['link'] ?? ''];
        }

        return $this->jsonSerializer->serialize($this->breadcrumbsHelper->createBreadcrumbsSnippet($crumbs));
    }

    /**
     * Get breadcrumbs snippet status
     *
     * @return int
     */
    public function getBreadcrumbsSnippetStatus()
    {
        return $this->configHelper->getBreadcrumbsSnippetStatus();
    }
}
