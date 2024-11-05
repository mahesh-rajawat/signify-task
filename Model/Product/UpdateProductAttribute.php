<?php
declare(strict_types=1);

namespace Signify\ProductCustomAttributeGraphQL\Model\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Action as ProductAction;

class UpdateProductAttribute
{
    private const ATTRIBUTE_CODE = 'additional_type';
    private const DEFAULT_STORE = '0';

    /**
     * @param CollectionFactory $collectionFactory
     * @param ProductAction $productAction
     */
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly ProductAction $productAction
    ) {
    }

    /**
     * Update all products `additional_type` attribute to 'exclusive'
     *
     * I am assuming a single store website so updating it for default scope
     *
     * @param string $value
     * @return void
     */
    public function execute(string $value = 'new'): void
    {
        $productCollection = $this->collectionFactory->create();
        $productIds = $productCollection->getAllIds();

        // Update all products attribute value
        $this->productAction->updateAttributes(
            $productIds,
            [self::ATTRIBUTE_CODE => $value],
            self::DEFAULT_STORE
        );
    }
}
