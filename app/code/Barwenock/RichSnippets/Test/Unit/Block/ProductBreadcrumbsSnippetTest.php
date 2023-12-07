<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Test\Unit\Block;

class ProductBreadcrumbsSnippetTest extends \PHPUnit\Framework\TestCase
{
   /**
    * @var \PHPUnit\Framework\MockObject\MockObject
    */
    private $adminConfig;

    /**
     * @var \Barwenock\RichSnippets\Block\ProductBreadcrumbsSnippet
     */
    private $productBreadcrumbsSnippet;

    /**
     * @var \Magento\Catalog\Helper\Data|\PHPUnit\Framework\MockObject\MockObject
     */
    private $catalogDataHelperMock;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json|\PHPUnit\Framework\MockObject\MockObject
     */
    private $jsonSerializerMock;

    /**
     * @var \Barwenock\RichSnippets\Helper\Breadcrumbs|\PHPUnit\Framework\MockObject\MockObject
     */
    private $breadcrumbsHelperMock;

    /**
     * @var \Magento\Framework\View\Element\Template\Context|\PHPUnit\Framework\MockObject\MockObject
     */
    private $contextMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManagerMock;

    protected function setUp(): void
    {
        $this->catalogDataHelperMock = $this->createMock(\Magento\Catalog\Helper\Data::class);
        $this->jsonSerializerMock = $this
            ->createMock(\Magento\Framework\Serialize\Serializer\Json::class);
        $this->breadcrumbsHelperMock = $this
            ->createMock(\Barwenock\RichSnippets\Helper\Breadcrumbs::class);
        $this->contextMock = $this->createMock(\Magento\Framework\View\Element\Template\Context::class);
        $this->storeManagerMock = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);
        $this->adminConfig = $this->createMock(\Barwenock\RichSnippets\Helper\Adminhtml\Config::class);
        $this->contextMock->method('getStoreManager')->willReturn($this->storeManagerMock);

        $this->productBreadcrumbsSnippet = new \Barwenock\RichSnippets\Block\ProductBreadcrumbsSnippet(
            $this->contextMock,
            $this->catalogDataHelperMock,
            $this->jsonSerializerMock,
            $this->breadcrumbsHelperMock,
            $this->adminConfig
        );
    }

    public function testGetBreadcrumbsSnippet(): void
    {
        // Mock the store
        $storeMock = $this->createMock(\Magento\Store\Model\Store::class);
        $storeMock->method('getBaseUrl')->willReturn('https://example.com/');

        // Mock the store manager to return the store mock
        $this->storeManagerMock->method('getStore')->willReturn($storeMock);

        // Mock the breadcrumb path
        $this->catalogDataHelperMock->method('getBreadcrumbPath')->willReturn([
            ['label' => 'Category 1', 'link' => 'https://example.com/category1'],
            ['label' => 'Category 2', 'link' => 'https://example.com/category2'],
        ]);

        // Mock the JSON serializer
        $this->jsonSerializerMock->expects($this->once())
            ->method('serialize')
            ->willReturn('{"json": "data"}');

        // Mock the breadcrumbs helper
        $this->breadcrumbsHelperMock->expects($this->once())
            ->method('createBreadcrumbsSnippet')
            ->willReturn(['formatted' => 'breadcrumbs']);

        // Perform the test
        $result = $this->productBreadcrumbsSnippet->getBreadcrumbsSnippet();

        // Assertions
        $this->assertEquals('{"json": "data"}', $result);
    }
}
