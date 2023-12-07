<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Block;

class OrganizationSnippet extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $jsonSerializer;

    /**
     * @var \Barwenock\RichSnippets\Helper\Adminhtml\Config
     */
    protected $configHelper;

    /**
     * @var \Barwenock\RichSnippets\Model\OrganizationSnippet
     */
    protected $organizationSnippet;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper
     * @param \Barwenock\RichSnippets\Model\OrganizationSnippet $organizationSnippet
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper,
        \Barwenock\RichSnippets\Model\OrganizationSnippet $organizationSnippet,
        array $data = []
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->configHelper = $configHelper;
        $this->organizationSnippet = $organizationSnippet;
        parent::__construct($context, $data);
    }

    /**
     * Get organization snippet
     *
     * @return false|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException|\Exception
     */
    public function getOrganizationSnippet()
    {
        $organizationSnippet = $this->organizationSnippet->organizationSnippetCreate();

        return $this->jsonSerializer->serialize($organizationSnippet);
    }

    /**
     * Get organization snippet status
     *
     * @return int
     */
    public function getOrganizationSnippetStatus()
    {
        return $this->configHelper->getOrganizationSnippetStatus();
    }
}
