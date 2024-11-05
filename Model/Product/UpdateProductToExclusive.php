<?php
declare(strict_types=1);

namespace Signify\ProductCustomAttributeGraphQL\Model\Product;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Action as ProductAction;

class UpdateProductToExclusive
{
    private const ATTRIBUTE_VALUE = 'exclusive';
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
     * @return void
     */
    public function execute(): void
    {
        $productCollection = $this->collectionFactory->create();
        $productIds = $productCollection->getAllIds();

        // Update all products attribute value
        $this->productAction->updateAttributes(
            $productIds,
            [self::ATTRIBUTE_CODE => self::ATTRIBUTE_VALUE],
            self::DEFAULT_STORE
        );
    }
}
