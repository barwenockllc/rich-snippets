<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Helper\Adminhtml;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Get product snippet status
     *
     * @return int
     */
    public function getProductSnippetStatus()
    {
        return (int)$this->scopeConfig->getValue(
            'rich_snippets/product_snippet/status',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get category snippet status
     *
     * @return int
     */
    public function getCategorySnippetStatus()
    {
        return (int)$this->scopeConfig->getValue(
            'rich_snippets/category_snippet/status',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get organization snippet status
     *
     * @return int
     */
    public function getOrganizationSnippetStatus()
    {
        return (int)$this->scopeConfig->getValue(
            'rich_snippets/category_snippet/status',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get breadcrumbs snippet status
     *
     * @return int
     */
    public function getBreadcrumbsSnippetStatus()
    {
        return (int)$this->scopeConfig->getValue(
            'rich_snippets/breadcrumbs/status',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get product snippet brand
     *
     * @return mixed
     */
    public function getProductSnippetBrand()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/product_snippet/brand',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get product snippet description
     *
     * @return mixed
     */
    public function getProductSnippetDescription()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/product_snippet/description',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get product snippet rating status
     *
     * @return int
     */
    public function getProductSnippetRatingStatus()
    {
        return (int)$this->scopeConfig->getValue(
            'rich_snippets/product_snippet/rating',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get product snippet price valid until
     *
     * @return mixed
     */
    public function getProductSnippetPriceValidUntil()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/product_snippet/price_valid_until',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get organization snippet name
     *
     * @return mixed
     */
    public function getOrganizationSnippetName()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/organization_snippet/name',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get organization snippet url
     *
     * @return mixed
     */
    public function getOrganizationSnippetUrl()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/organization_snippet/url',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get organization snippet country
     *
     * @return mixed
     */
    public function getOrganizationSnippetCountry()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/organization_snippet/country',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get organization snippet state
     *
     * @return mixed
     */
    public function getOrganizationSnippetState()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/organization_snippet/state',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get organization snippet postal code
     *
     * @return mixed
     */
    public function getOrganizationSnippetPostalCode()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/organization_snippet/postal_code',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get organization snippet city
     *
     * @return mixed
     */
    public function getOrganizationSnippetCity()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/organization_snippet/city',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get organization snippet street
     *
     * @return mixed
     */
    public function getOrganizationSnippetStreet()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/organization_snippet/street',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get organization snippet contact phone
     *
     * @return mixed
     */
    public function getOrganizationSnippetContactPhone()
    {
        return $this->scopeConfig->getValue(
            'rich_snippets/organization_snippet/street',
            \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE
        );
    }
}
