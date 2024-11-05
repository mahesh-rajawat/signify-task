<?php
declare(strict_types=1);

namespace Signify\ProductCustomAttributeGraphQL\Model\Product\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class AdditionalType extends AbstractSource
{
    /**
     * Get attribute options
     *
     * @return array
     */
    public function getAllOptions(): array
    {
        $this->_options = [
            ['value' => 'new', 'label' => __('New')],
            ['value' => 'discount', 'label' => __('Discount')],
            ['value' => 'exclusive', 'label' => __('Exclusive')]
        ];
        return $this->_options;
    }
}
