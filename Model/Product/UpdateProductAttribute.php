<?php
declare(strict_types=1);

namespace Signify\ProductCustomAttributeGraphQL\Model\Product;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\Product\Action as ProductAction;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class UpdateProductAttribute
{
    private const ATTRIBUTE_VALUE = 'exclusive';
    private const ATTRIBUTE_CODE = 'additional_type';
    private const DEFAULT_STORE = '0';
    private const DEFAULT_BATCH_SIZE = 5000;

    /**
     * @var string|null
     */
    private ?string $attributeOptionId = null;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ProductAction $productAction
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(
        private readonly CollectionFactory $collectionFactory,
        private readonly ProductAction $productAction,
        private readonly AttributeRepositoryInterface $attributeRepository
    ) {
    }

    /**
     * Update all products `additional_type` attribute to 'exclusive'
     *
     * I am assuming a single store website so updating it for default scope
     *
     * @return void
     * @throws NoSuchEntityException
     */
    public function execute(): void
    {
        $productCollection = $this->collectionFactory->create();
        $this->walk($productCollection, function (array $productIds) {
            // Update products attribute value
            $this->productAction->updateAttributes(
                $productIds,
                [self::ATTRIBUTE_CODE => $this->getAttributeOptionValue()],
                self::DEFAULT_STORE
            );
        });
    }

    /**
     * Update products in batches of 5000
     *
     * @param Collection $collection
     * @param callable $callbackBatch
     */
    private function walk(Collection $collection, callable $callbackBatch): void
    {
        $offset = 0;
        do {
            $ids = $collection->getAllIds(self::DEFAULT_BATCH_SIZE, $offset);
            $callbackBatch($ids);

            $offset += self::DEFAULT_BATCH_SIZE;
            $collection->clear();
        } while (count($ids));
    }

    /**
     * Get the attribute value option by label.
     *
     * I am considering a single store so label text will be same.
     *
     * @return string|null
     * @throws NoSuchEntityException
     */
    private function getAttributeOptionValue(): ?string
    {
        if (!$this->attributeOptionId) {
            $attribute = $this->attributeRepository->get(
                ProductAttributeInterface::ENTITY_TYPE_CODE,
                self::ATTRIBUTE_CODE
            );
            $this->attributeOptionId = $attribute->getSource()->getOptionId('Exclusive');
        }

        return $this->attributeOptionId;
    }
}
