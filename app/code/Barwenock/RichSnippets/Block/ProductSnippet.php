<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Block;

class ProductSnippet extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Catalog\Model\Locator\RegistryLocator
     */
    protected $locator;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    /**
     * @var \Barwenock\RichSnippets\Helper\Adminhtml\Config
     */
    protected $configHelper;

    /**
     * @var \Barwenock\RichSnippets\Model\ProductSnippet
     */
    protected $productSnippet;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Catalog\Model\Locator\RegistryLocator $locator
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper
     * @param \Barwenock\RichSnippets\Model\ProductSnippet $productSnippet
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Locator\RegistryLocator  $locator,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper,
        \Barwenock\RichSnippets\Model\ProductSnippet $productSnippet,
        array $data = []
    ) {
        $this->locator = $locator;
        $this->jsonSerializer = $jsonSerializer;
        $this->configHelper = $configHelper;
        $this->productSnippet = $productSnippet;
        parent::__construct($context, $data);
    }

    /**
     * Get product snippet
     *
     * @return bool|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException|\Magento\Framework\Exception\NotFoundException
     */
    public function getProductSnippet()
    {
        $product = $this->locator->getProduct();

        $productSnippet = $this->productSnippet->productSnippetCreate($product);

        return $this->jsonSerializer->serialize($productSnippet);
    }

    /**
     * Get product snippet status
     *
     * @return int
     */
    public function getProductSnippetStatus()
    {
        return $this->configHelper->getProductSnippetStatus();
    }
}
