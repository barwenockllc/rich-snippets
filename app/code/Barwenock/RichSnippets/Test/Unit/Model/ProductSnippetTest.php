<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Test\Unit\Model;

class ProductSnippetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Review\Model\ReviewFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $reviewFactory;

    /**
     * @var \Barwenock\RichSnippets\Model\ProductSnippet
     */
    private $productSnippetModel;

    /**
     * @var \Magento\Catalog\Block\Product\ImageFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $imageFactoryMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManagerMock;

    /**
     * @var \Magento\Framework\UrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $urlBuilderMock;

    /**
     * @var \Barwenock\RichSnippets\Helper\Adminhtml\Config|\PHPUnit\Framework\MockObject\MockObject
     */
    private $adminConfigMock;

    protected function setUp(): void
    {
        $this->imageFactoryMock = $this->createMock(\Magento\Catalog\Block\Product\ImageFactory::class);
        $this->storeManagerMock = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);
        $this->urlBuilderMock = $this->createMock(\Magento\Framework\UrlInterface::class);
        $this->adminConfigMock = $this
            ->createMock(\Barwenock\RichSnippets\Helper\Adminhtml\Config::class);
        $this->reviewFactory = $this->createMock(\Magento\Review\Model\ReviewFactory::class);

        $this->productSnippetModel = $this
            ->getMockBuilder(\Barwenock\RichSnippets\Model\ProductSnippet::class)
            ->setConstructorArgs([
                'imageFactory' => $this->imageFactoryMock,
                'storeManager' => $this->storeManagerMock,
                'urlBuilder' => $this->urlBuilderMock,
                'adminConfig' => $this->adminConfigMock,
                'reviewFactory' => $this->reviewFactory
            ])
            ->onlyMethods(['getImage'])
            ->getMock();
    }

    public function testProductSnippetCreate()
    {
        // Mock Product
        $productMock = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Mock AdminConfig method calls
        $this->adminConfigMock->expects($this->any())
            ->method('getProductSnippetDescription')
            ->willReturn('description_attribute_code');

        $this->adminConfigMock->expects($this->any())
            ->method('getProductSnippetBrand')
            ->willReturn('brand_attribute_code');

        $this->adminConfigMock->expects($this->any())
            ->method('getProductSnippetRatingStatus')
            ->willReturn(0);

        // Mock other dependencies
        $this->imageFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->createMock(\Magento\Catalog\Block\Product\Image::class));

        $this->storeManagerMock->expects($this->any())
            ->method('getStore')
            ->willReturn($this->createMock(\Magento\Store\Model\Store::class));

        $this->urlBuilderMock->expects($this->any())
            ->method('getCurrentUrl')
            ->willReturn('https://example.com/product-url');

        $this->productSnippetModel->method('getImage')->willReturn('product_base_image');

        // Call the method to test
        $result = $this->productSnippetModel->productSnippetCreate($productMock);

        // Assertions
        $this->assertEquals('https://schema.org/', $result['@context']);
        $this->assertEquals('Product', $result['@type']);
        $this->assertEquals($productMock->getName(), $result['name']);
    }
}
