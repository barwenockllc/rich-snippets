<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\ViewModel;

class BreadcrumbsSnippet implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
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
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Barwenock\RichSnippets\Helper\Breadcrumbs $breadcrumbsHelper
     * @param \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper
     */
    public function __construct(
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \Barwenock\RichSnippets\Helper\Breadcrumbs $breadcrumbsHelper,
        \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->breadcrumbsHelper = $breadcrumbsHelper;
        $this->configHelper = $configHelper;
    }

    /**
     * Get breadcrumbs snippet
     *
     * @param array|null $crumbs
     * @return string
     */
    public function getBreadcrumbsSnippet(?array $crumbs): string
    {
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
