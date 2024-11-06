<?php
declare(strict_types=1);

namespace Signify\ProductCustomAttributeGraphQL\Model\Resolver\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class AdditionalType implements ResolverInterface
{
    /**
     * Resolve multiple requests.
     *
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return string|null
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        /* @var $product ProductInterface */
        $product = $value['model'];
        return $product->getAttributeText('additional_type');
    }
}
