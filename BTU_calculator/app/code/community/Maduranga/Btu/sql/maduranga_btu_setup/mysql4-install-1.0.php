<?php
/**
 * Created by PhpStorm.
 * User: maduranga
 * Date: 6/8/17
 * Time: 11:25 PM
 */


$installer = $this;
$installer->startSetup();

$attribute  = array(
    'group'                     => 'General Information',
    'input'                     => 'select',
    'type'                      => 'int',
    'label'                     => 'Enable BTU calculator ',
    'source'                    => 'eav/entity_attribute_source_boolean',
    'global'                    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'                   => 1,
    'required'                  => 0,
    'visible_on_front'          => 1,
    'is_html_allowed_on_front'  => 0,
    'is_configurable'           => 0,
    'searchable'                => 0,
    'filterable'                => 0,
    'comparable'                => 0,
    'unique'                    => false,
    'user_defined'              => false,
    'default'           => '0',
    'is_user_defined'           => false,
    'used_in_product_listing'   => true
);
$installer->addAttribute('catalog_category', 'btu_enable', $attribute);
$installer->endSetup();