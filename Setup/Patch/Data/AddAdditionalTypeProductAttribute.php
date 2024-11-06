<?php
declare(strict_types=1);

namespace Signify\ProductCustomAttributeGraphQL\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;

class AddAdditionalTypeProductAttribute implements DataPatchInterface, PatchRevertableInterface
{
    private const ATTRIBUTE_CODE = 'additional_type';

    /**
     * Constructor
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly EavSetupFactory $eavSetupFactory,
        private readonly AttributeRepositoryInterface $attributeRepository
    ) {
    }

    /**
     * @inheritdoc
     */
    public function apply(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            self::ATTRIBUTE_CODE,
            [
                'type' => 'varchar',
                'label' => 'Additional Type',
                'input' => 'select',
                'source' => '',
                'frontend' => '',
                'required' => false,
                'backend' => '',
                'sort_order' => '30',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'default' => 'New',
                'visible' => true,
                'user_defined' => true,
                'visible_on_front' => false,
                'group' => 'General',
                'used_in_product_listing' => false,
                'is_used_in_grid' => false,
                'option' => ['values' => ["New", "Discount", "Exclusive"]]
            ]
        );
        $this->addAttributeDefaultValue($eavSetup);
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Add attribute default value to "New"
     *
     * @param EavSetup $eavSetup
     * @return void
     * @throws NoSuchEntityException
     */
    private function addAttributeDefaultValue(EavSetup $eavSetup): void
    {
        $attributeId = $eavSetup->getAttributeId(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            self::ATTRIBUTE_CODE
        );
        $attribute = $this->attributeRepository->get(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            $attributeId
        );
        // I am considering a single store so label text will be same, else custom source model can be use to
        // Add attribute options.
        $optionId = $attribute->getSource()->getOptionId('New');
        $eavSetup->updateAttribute(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            self::ATTRIBUTE_CODE,
            'default_value',
            $optionId
        );
    }

    /**
     * @inheritdoc
     */
    public function revert(): void
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(ProductAttributeInterface::ENTITY_TYPE_CODE, self::ATTRIBUTE_CODE);

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
