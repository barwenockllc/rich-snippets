<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Test\Unit\Model;

class OrganizationSnippetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Barwenock\RichSnippets\Model\OrganizationSnippet
     */
    private $organizationSnippet;

    /**
     * @var \Barwenock\RichSnippets\Helper\Adminhtml\Config|\PHPUnit\Framework\MockObject\MockObject
     */
    private $configHelperMock;

    /**
     * @var \Magento\Framework\Filesystem\Io\File|\PHPUnit\Framework\MockObject\MockObject
     */
    private $filesystemMock;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList|\PHPUnit\Framework\MockObject\MockObject
     */
    private $directoryListMock;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $storeManagerMock;

    protected function setUp(): void
    {
        $this->configHelperMock = $this
            ->createMock(\Barwenock\RichSnippets\Helper\Adminhtml\Config::class);
        $this->filesystemMock = $this->createMock(\Magento\Framework\Filesystem\Io\File::class);
        $this->directoryListMock = $this
            ->createMock(\Magento\Framework\App\Filesystem\DirectoryList::class);
        $this->storeManagerMock = $this->createMock(\Magento\Store\Model\StoreManagerInterface::class);

        $this->organizationSnippet = new \Barwenock\RichSnippets\Model\OrganizationSnippet(
            $this->configHelperMock,
            $this->filesystemMock,
            $this->directoryListMock,
            $this->storeManagerMock
        );
    }

    public function testOrganizationSnippetCreate()
    {
        // Mock configuration values
        $this->configHelperMock->expects($this->once())
            ->method('getOrganizationSnippetUrl')
            ->willReturn('https://example.com');
        // Mock other config values...

        // Mock filesystem
        $this->filesystemMock->expects($this->once())
            ->method('ls')
            ->willReturn([
                ['text' => 'logo.jpg'], // Assuming a file is present
            ]);

        // Mock directory list
        $this->directoryListMock->expects($this->once())
            ->method('getPath')
            ->with('media')
            ->willReturn('/path/to/media');

        // Mock store manager
        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->createMock(\Magento\Store\Model\Store::class));

        // Call the method to test
        $result = $this->organizationSnippet->organizationSnippetCreate();

        // Assertions
        $this->assertEquals('https://schema.org', $result['@context']);
        $this->assertEquals('Organization', $result['@type']);
        $this->assertEquals('https://example.com', $result['url']);
        // Add more assertions based on your expectations
    }
}
