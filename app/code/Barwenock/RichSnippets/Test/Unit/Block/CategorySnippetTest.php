<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Test\Unit\Block;

class CategorySnippetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Barwenock\RichSnippets\Helper\Adminhtml\Config|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configHelperMock;

    /**
     * @var \Barwenock\RichSnippets\Model\ProductSnippet|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productSnippetMock;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $collectionFactoryMock;

    /**
     * @var \Magento\Catalog\Model\Category|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $categoryMock;

    /**
     * @var \Barwenock\RichSnippets\Block\CategorySnippet
     */
    private $categorySnippet;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json|\PHPUnit\Framework\MockObject\MockObject
     */
    private $jsonSerializerMock;

    /**
     * @var \Magento\Catalog\Model\Layer\Resolver|\PHPUnit\Framework\MockObject\MockObject
     */
    private $layerResolverMock;

    /**
     * @var \Magento\Catalog\Model\Layer|\PHPUnit\Framework\MockObject\MockObject
     */
    private $layerMock;

    protected function setUp(): void
    {
        $context = $this->createMock(\Magento\Framework\View\Element\Template\Context::class);
        $this->jsonSerializerMock = $this
            ->createMock(\Magento\Framework\Serialize\Serializer\Json::class);
        $this->layerResolverMock = $this->createMock(\Magento\Catalog\Model\Layer\Resolver::class);
        $this->layerMock = $this->createMock(\Magento\Catalog\Model\Layer::class);
        $this->configHelperMock = $this
            ->createMock(\Barwenock\RichSnippets\Helper\Adminhtml\Config::class);
        $this->productSnippetMock = $this
            ->createMock(\Barwenock\RichSnippets\Model\ProductSnippet::class);
        $this->collectionFactoryMock = $this
            ->getMockBuilder(\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categoryMock = $this->getMockBuilder(\Magento\Catalog\Model\Category::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->categorySnippet = new \Barwenock\RichSnippets\Block\CategorySnippet(
            $context,
            $this->jsonSerializerMock,
            $this->layerResolverMock,
            $this->configHelperMock,
            $this->productSnippetMock
        );
    }

    public function testGetCategorySnippet(): void
    {
        $this->categoryMock->method('getName')->willReturn('Category Name');
        $this->categoryMock->method('getImageUrl')->willReturn('https://example.com/image.jpg');
        $this->categoryMock->method('getUrl')->willReturn('https://example.com/category');

        $this->layerResolverMock->expects($this->once())
            ->method('get')
            ->willReturn($this->layerMock); // Return the mock layer object
        $this->layerMock->expects($this->once())
            ->method('getCurrentCategory')
            ->willReturn($this->categoryMock);

        $expectedCategoryData = [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => 'https://example.com/category',
            ],
            'name' => 'Category Name',
            'description' => '',
            'image' => 'https://example.com/image.jpg',
        ];

        $this->jsonSerializerMock->expects($this->once())
            ->method('serialize')
            ->with($expectedCategoryData)
            ->willReturn('{"json": "data"}');

        $result = $this->categorySnippet->getCategorySnippet();

        $this->assertEquals('{"json": "data"}', $result);
    }

    public function testGetCategorySnippetWithNoCategory(): void
    {
        $this->layerResolverMock->expects($this->once())
            ->method('get')
            ->willReturn($this->layerMock); // Return the mock layer object
        $this->layerMock->expects($this->once())
            ->method('getCurrentCategory')
            ->willReturn(null);

        $result = $this->categorySnippet->getCategorySnippet();

        $this->assertEquals('', $result);
    }

    public function testGetProductsCategorySnippet()
    {
        // Mock data
        $productMock1 = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productMock1->method('getName')->willReturn('Product 1');

        $productMock2 = $this->getMockBuilder(\Magento\Catalog\Model\Product::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productMock2->method('getName')->willReturn('Product 2');

        $productCollectionMock = $this
            ->getMockBuilder(\Magento\Catalog\Model\ResourceModel\Product\Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $productCollectionMock->method('addAttributeToSelect')->willReturnSelf();
        $productCollectionMock->method('getItems')->willReturn([$productMock1, $productMock2]);

        // Mock dependencies
        $this->collectionFactoryMock->method('create')->willReturn($productCollectionMock);
        $this->categoryMock->method('getProductCollection')->willReturn($productCollectionMock);

        // Mock productSnippet behavior
        $this->productSnippetMock->expects($this->exactly(2))
            ->method('productSnippetCreate')
            ->withConsecutive([$productMock1], [$productMock2])
            ->willReturnOnConsecutiveCalls(['name' => 'Product 1'], ['name' => 'Product 2']);

        // Mock jsonSerializer behavior
        $this->jsonSerializerMock->expects($this->once())
            ->method('serialize')
            ->with([['name' => 'Product 1'], ['name' => 'Product 2']])
            ->willReturn('{"products":[{"name":"Product 1"},{"name":"Product 2"}]}');

        $this->layerResolverMock->expects($this->once())
            ->method('get')
            ->willReturn($this->layerMock); // Return the mock layer object
        $this->layerMock->expects($this->once())
            ->method('getCurrentCategory')
            ->willReturn($this->categoryMock);

        // Call the method to test
        $result = $this->categorySnippet->getProductsCategorySnippet();

        // Assertions
        $this->assertEquals('{"products":[{"name":"Product 1"},{"name":"Product 2"}]}', $result);
    }
}
