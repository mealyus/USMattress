<?php
namespace USMattress\Gallery\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Catalog\Model\ResourceModel\Product\Gallery;
use Magento\Eav\Setup\EavSetupFactory;

class UpgradeSchema implements UpgradeSchemaInterface
{
    private $eavSetupFactory;


     public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        $installer->startSetup();

        if(version_compare($context->getVersion(), '1.0.1', '<')) {
            $installer->getConnection()->addColumn (
                    $installer->getTable(Gallery::GALLERY_TABLE), 'image_caption', [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => '',
                    'length' => 600,
                    'comment' => 'Image Caption'                
                ]
            );
            $installer->getConnection()->addColumn (
                    $installer->getTable(Gallery::GALLERY_TABLE), 'caption_class', [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'default' => '',
                    'length' => 600,
                    'comment' => 'Caption class(es) , seperated'                
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $eavSetup = $this->eavSetupFactory->create();
            $eavSetup->addAttribute (
                \Magento\Catalog\Model\Product::ENTITY,
                'video_thumbnail',
                [
                    'group' => 'General',
                    'type' => 'varchar',
                    'label' => 'Video Thumbnail',
                    'input' => 'media_image',
                    'required' => false,
                    'sort_order' => 30,
                    'frontend' => \Magento\Catalog\Model\Product\Attribute\Frontend\Image::class,
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'used_in_product_listing' => true,
                    'user_defined' => true,
                    'visible' => true,
                    'visible_on_front' => true
                ]
            );

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'youtube_video',
                [
                    'group' => 'General',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Product Video',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'backend' => '',
                    'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => 0,
                    'searchable' => false,
                    'filterable' => true,
                    'comparable' => false,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'is_wysiwyg_enabled'      => false,
                    'unique' => false,
                    'apply_to' => ''
                ]
            );

            $id = $eavSetup->getAttributeId(
                \Magento\Catalog\Model\Product::ENTITY,
                'video_thumbnail'
            );

            $attributeSetId = $eavSetup->getDefaultAttributeSetId(\Magento\Catalog\Model\Product::ENTITY);
            $eavSetup->addAttributeToGroup(\Magento\Catalog\Model\Product::ENTITY, $attributeSetId, 'image-management', $id, 10);
        }

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $eavSetup = $this->eavSetupFactory->create();
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'youtube_thumbnail',
                [
                    'type' => 'varchar',
                    'backend' => 'USMattress\Gallery\Model\Attribute\Product\Image',
                    'frontend' => '',
                    'label' => 'Youtube Thumbnail',
                    'input' => 'image',
                    'class' => '',
                    'source' => '',
                    'group' => 'General',
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => 0,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => ''
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.0.4', '<')) {
            $eavSetup = $this->eavSetupFactory->create();
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'html_thumbnail',
                [
                    'type' => 'varchar',
                    'backend' => 'USMattress\Gallery\Model\Attribute\Product\Image',
                    'frontend' => '',
                    'label' => 'HTML Slide Thumbnail',
                    'input' => 'image',
                    'class' => '',
                    'source' => '',
                    'group' => 'General',
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => 0,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => ''
                ]
            );

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'cms_slide_id',
                [
                    'group' => 'General',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'HTML CMS Block ID for Gallery',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'backend' => '',
                    'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => 0,
                    'searchable' => false,
                    'filterable' => true,
                    'comparable' => false,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'is_wysiwyg_enabled'      => false,
                    'unique' => false,
                    'apply_to' => ''
                ]
            );
        }

        $installer->endSetup();
    }
}