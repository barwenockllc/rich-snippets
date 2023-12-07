<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Model;

class OrganizationSnippet
{
    /**
     * Organization logo directory for logo
     */
    protected const ORGANISATION_SNIPPETS_LOGO = 'rich_snippets/default';

    /**
     * @var \Barwenock\RichSnippets\Helper\Adminhtml\Config
     */
    protected $configHelper;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper
     * @param \Magento\Framework\Filesystem\Io\File $filesystem
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Barwenock\RichSnippets\Helper\Adminhtml\Config $configHelper,
        \Magento\Framework\Filesystem\Io\File $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->configHelper = $configHelper;
        $this->filesystem = $filesystem;
        $this->directoryList = $directoryList;
        $this->storeManager = $storeManager;
    }

    /**
     * Organization snippet create
     *
     * @return array
     * @throws \Exception
     */
    public function organizationSnippetCreate()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'url' => $this->configHelper->getOrganizationSnippetUrl(),
            'logo' => $this->getImagePath(),
            'contactPoint' => [
                [
                    '@type' => 'ContactPoint',
                    'telephone' => $this->configHelper->getOrganizationSnippetContactPhone(),
                    'contactType' => 'Customer service'
                ]
            ],
            'sameAs' => [
                'https://www.facebook.com/detailk2/',
                'https://www.instagram.com/dk2inc/',
                'https://www.youtube.com/c/dk2inc'
            ],
            'name' => $this->configHelper->getOrganizationSnippetName(),
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->configHelper->getOrganizationSnippetStreet(),
                'addressLocality' => $this->configHelper->getOrganizationSnippetCity(),
                'addressRegion' => $this->configHelper->getOrganizationSnippetState(),
                'postalCode' => $this->configHelper->getOrganizationSnippetPostalCode(),
                'addressCountry' => $this->configHelper->getOrganizationSnippetCountry()
            ]
        ];
    }

    /**
     * Gets an image web path
     *
     * @return string|null
     * @throws \Exception
     */
    protected function getImagePath()
    {
        try {
            $directoryPath = $this->directoryList->getPath('media') . '/' . self::ORGANISATION_SNIPPETS_LOGO;

            $this->filesystem->cd($directoryPath);

            // Get the list of files in the directory
            $files = $this->filesystem->ls();

            if (!empty($files) && array_key_exists(0, $files)) {
                $imageName = $files[0]['text'];

                $mediaImagePath = $this->storeManager
                    ->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

                return $mediaImagePath . self::ORGANISATION_SNIPPETS_LOGO . '/' . $imageName;
            } else {
                return null;
            }
        } catch (\Exception $exception) {
            throw new \InvalidArgumentException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
