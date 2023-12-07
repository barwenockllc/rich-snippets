<?php
/**
 * @author Barwenock
 * @copyright Copyright (c) Barwenock
 * @package Rich Snippets for Magento 2
 */

declare(strict_types=1);

namespace Barwenock\RichSnippets\Model\Config\Source;

class ProductDescriptionOption implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Product snippet description option array
     *
     * @return array[]
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'short_description',
                'label' => __('Product Short Description'),
            ],
            [
                'value' => 'description',
                'label' => __('Product Description'),
            ],
            [
                'value' => 'meta_description',
                'label' => __('Product Meta Description'),
            ]
        ];
    }
}
