<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Block;

class CategorySnippet extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver
     */
    protected $layerResolver;

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
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Magento\Catalog\Model\Layer\Resolver $layerResolver
     * @param \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper
     * @param \Barwenock\RichSnippets\Model\ProductSnippet $productSnippet
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper,
        \Barwenock\RichSnippets\Model\ProductSnippet $productSnippet,
        array $data = []
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->layerResolver = $layerResolver;
        $this->configHelper = $configHelper;
        $this->productSnippet = $productSnippet;
        parent::__construct($context, $data);
    }

    /**
     * Get category snippet
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCategorySnippet(): string
    {
        $category = $this->layerResolver->get()->getCurrentCategory();

        if ($category) {
            $categoryData = [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $category->getUrl()
                ],
                'name' => $category->getName(),
                'description' => null,
                'image' => $category->getImageUrl()
            ];
        } else {
            return '';
        }

        return $this->jsonSerializer->serialize($categoryData);
    }

    /**
     * Get category snippet status
     *
     * @return int
     */
    public function getCategorySnippetStatus()
    {
        return $this->configHelper->getCategorySnippetStatus();
    }

    /**
     * Get product category snippet
     *
     * @return bool|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductsCategorySnippet()
    {
        $products = $this->layerResolver->get()->getCurrentCategory()->getProductCollection()
            ->addAttributeToSelect('name')->getItems();

        $productSnippet = [];
        foreach ($products as $product) {
            $productSnippet[] = $this->productSnippet->productSnippetCreate($product);
        }

        return $this->jsonSerializer->serialize($productSnippet);
    }
}
