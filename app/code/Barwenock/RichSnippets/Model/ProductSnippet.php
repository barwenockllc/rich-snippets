<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Model;

class ProductSnippet
{
    /**
     * @var \Magento\Catalog\Block\Product\ImageFactory
     */
    protected $imageFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Barwenock\RichSnippets\Helper\Adminhtml\Config
     */
    protected $adminConfig;

    /**
     * @var \Magento\Review\Model\ReviewFactory
     */
    protected $reviewFactory;

    /**
     * @param \Magento\Catalog\Block\Product\ImageFactory $imageFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Barwenock\RichSnippets\Helper\Adminhtml\Config $adminConfig
     * @param \Magento\Review\Model\ReviewFactory $reviewFactory
     */
    public function __construct(
        \Magento\Catalog\Block\Product\ImageFactory $imageFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Barwenock\RichSnippets\Helper\Adminhtml\Config $adminConfig,
        \Magento\Review\Model\ReviewFactory $reviewFactory
    ) {
        $this->imageFactory = $imageFactory;
        $this->storeManager = $storeManager;
        $this->urlBuilder = $urlBuilder;
        $this->adminConfig = $adminConfig;
        $this->reviewFactory = $reviewFactory;
    }

    /**
     * Product snippet create
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function productSnippetCreate($product)
    {
        $productSnippet = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $product->getName(),
            'image' => $this->getImage($product, 'product_base_image'),
            'description' => $product->getData($this->adminConfig->getProductSnippetDescription()),
            'sku' => $product->getSku()
        ];

        $productSnippetBrand = $this->adminConfig->getProductSnippetBrand();
        if ($productSnippetBrand != 0) {
            $productSnippet['brand'] = [
                "@type" => "Brand",
                "name" => $product->getData($productSnippetBrand)
            ];
        }

        if ($this->adminConfig->getProductSnippetRatingStatus() === 1) {
            $this->reviewFactory->create()->getEntitySummary($product, $this->storeManager->getStore()->getId());

            if ($product->getRatingSummary()->getReviewsCount() !== null) {
                $productSnippet['aggregateRating'] = [
                    "@type" => "AggregateRating",
                    "ratingValue"=> $this->convertRating($product->getRatingSummary()->getRatingSummary()),
                    "reviewCount"=> $product->getRatingSummary()->getReviewsCount()
                ];
            }
        }

        $productSnippet['offers'] = [
            "@type" => "Offer",
            "priceCurrency" => $this->storeManager->getStore()->getCurrentCurrencyCode(),
            "url" => $this->urlBuilder->getCurrentUrl(),
            "priceValidUntil" => $this->adminConfig->getProductSnippetPriceValidUntil()
        ];

        if ($product->isAvailable()) {
            $productSnippet['offers']['availability'] = "https://schema.org/InStock";
        } else {
            $productSnippet['offers']['availability'] = "https://schema.org/OutOfStock";
        }

        // Check if priceValidUntil is not null before adding it to the array
        if ($this->adminConfig->getProductSnippetPriceValidUntil() !== null) {
            $productSnippet['offers']['priceValidUntil'] = $this->adminConfig->getProductSnippetPriceValidUntil();
        }

        $productSnippet['offers']['price'] = $product->getFinalPrice();

        return $productSnippet;
    }

    /**
     * Get product image url
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return string
     */
    protected function getImage($product, $imageId, $attributes = []): string
    {
        return $this->imageFactory->create($product, $imageId, $attributes)->getImageUrl();
    }

    /**
     * Convert rating
     *
     * @param string $originalRating
     * @param int $originalMax
     * @param int $newMax
     * @return mixed
     */
    protected function convertRating($originalRating, $originalMax = 100, $newMax = 5)
    {
        // Ensure the original rating is within the valid range
        $originalRating = max(0, min($originalRating, $originalMax));

        // Perform the linear mapping
        $newRating = ($originalRating / $originalMax) * $newMax;

        // Round to one decimal place
        $newRating = round($newRating, 1);

        // Ensure the new rating is within the desired range
        return max(1, min($newRating, $newMax));
    }
}
