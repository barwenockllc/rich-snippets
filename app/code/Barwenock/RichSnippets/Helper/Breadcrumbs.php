<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Helper;

class Breadcrumbs extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Create breadcrumbs snippet
     *
     * @param array $crumbs
     * @return array
     */
    public function createBreadcrumbsSnippet(array $crumbs)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $this->getItemListElement($crumbs)
        ];
    }

    /**
     * Get an item element of breadcrumbs
     *
     * @param array $crumbs
     * @return array|string
     */
    protected function getItemListElement($crumbs)
    {
        $itemList = [];
        $position = 1;
        foreach ($crumbs as $crumb) {
            $itemList[] = [
                '@type' => 'ListItem',
                'position' => $position++,
                'item' => [
                    '@id' => $crumb['link'],
                    'name' => $crumb['label']
                ]
            ];
        }

        if ($itemList === []) {
            return '';
        } else {
            return $itemList;
        }
    }
}
