<?php
declare(strict_types=1);

namespace Signify\ProductCustomAttributeGraphQL\Model\Resolver\Product;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Query\Resolver\BatchResponse;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Signify\ProductCustomAttributeGraphQL\Model\Product\Attribute\Source\AdditionalType as SourceAdditionalType;

class AdditionalType implements ResolverInterface
{
    /**
     * @param CollectionFactory $collectionFactory
     * @param SourceAdditionalType $sourceAdditionalType
     */
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly SourceAdditionalType $sourceAdditionalType
    ) {
    }

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
        $productData = $this->collectionFactory->create()
            ->addIdFilter($product->getId())
            ->addFieldToSelect('additional_type')
            ->getFirstItem();
        return $this->sourceAdditionalType->getOptionText($productData->getData('additional_type')) ?? null;
    }
}
