<?php
/**
 * BlackChacal_Prometheus
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Prometheus to newer
 * versions in the future. If you wish to customize Prometheus for your
 * needs please contact the author for more information.
 *
 * @category    BlackChacal
 * @package     BlackChacal_Prometheus
 * @copyright   Copyright (c) 2015 BlackChacal <ribeiro.tonet@gmail.com>
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * @var Mage_Core_Model_Resource_Setup $installer
 */
$installer = $this;

// Insert the table creation scripts between startSetup() and endSetup() calls.
$installer->startSetup();

// Create "prometheus_general_data" table associated to "Extension" model.
$table = $installer->getConnection()
    ->newTable($installer->getTable('blackchacal_prometheus/extension'))
    ->addColumn('extension_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
    ), 'Id')
    ->addColumn('namespace', Varien_Db_Ddl_Table::TYPE_VARCHAR, 30, array(
        'nullable' => false,
    ), 'Namespace')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 60, array(
        'nullable' => false,
    ), 'Name')
    ->addColumn('codepool', Varien_Db_Ddl_Table::TYPE_VARCHAR, 10, array(
        'nullable' => false,
    ), 'Code Pool')
    ->addColumn('version', Varien_Db_Ddl_Table::TYPE_VARCHAR, 10, array(
        'nullable' => false,
    ), 'Version')
    ->addColumn('license', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
    ), 'License')
    ->addColumn('author_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
        'nullable' => true,
    ), 'Author Name')
    ->addColumn('author_email', Varien_Db_Ddl_Table::TYPE_VARCHAR, 150, array(
        'nullable' => true,
    ), 'Author Email')
    ->addColumn('type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
        'nullable' => true,
    ), 'Type')
    ->addColumn('action', Varien_Db_Ddl_Table::TYPE_VARCHAR, 10, array(
        'nullable' => false,
    ), 'Action')
    ->addColumn('rewrite', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => false,
    ), 'Rewrite')
    ->addColumn('installed', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, array(
        'nullable' => false,
    ), 'Installed')
    ->addColumn('config_node_code', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
        'nullable' => false,
    ), 'Configuration Node Code')
    ->addColumn('config_tab_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 60, array(
        'nullable' => false,
    ), 'Configuration Tab Type')
    ->addColumn('config_tab_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 60, array(
        'nullable' => false,
    ), 'Configuration Tab Name')
    ->addColumn('config_tab_label', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
        'nullable' => true,
    ), 'Configuration Tab Label')
    ->addColumn('config_tab_position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => true,
    ), 'Configuration Tab Position')
    ->addColumn('config_section_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
        'nullable' => false,
    ), 'Configuration Section Name')
    ->addColumn('config_section_label', Varien_Db_Ddl_Table::TYPE_VARCHAR, 150, array(
        'nullable' => false,
    ), 'Configuration Section Label')
    ->addColumn('admin_menu_parent', Varien_Db_Ddl_Table::TYPE_VARCHAR, 60, array(
        'nullable' => true,
    ), 'Admin Menu Parent')
    ->addColumn('admin_menu_name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 60, array(
        'nullable' => true,
    ), 'Admin Menu Name')
    ->addColumn('admin_menu_title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 100, array(
        'nullable' => true,
    ), 'Admin Menu Title')
    ->addColumn('admin_menu_action', Varien_Db_Ddl_Table::TYPE_VARCHAR, 60, array(
        'nullable' => true,
    ), 'Admin Menu Action')
    ->addColumn('admin_menu_position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => true,
    ), 'Admin Menu Position');
$installer->getConnection()->createTable($table);

// Create "prometheus_config_group_data" table associated to "Config_Group" model.
$table = $installer->getConnection()
    ->newTable($installer->getTable('blackchacal_prometheus/config_group'))
        ->addColumn('group_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Id')
        ->addColumn('extension_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
        ), 'Extension Id')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 60, array(
            'nullable' => false,
        ), 'Group Name')
        ->addColumn('label', Varien_Db_Ddl_Table::TYPE_VARCHAR, 150, array(
            'nullable' => false,
        ), 'Group Label')
        ->addColumn('frontend_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
            'nullable' => false,
        ), 'Frontend Type')
        ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
        ), 'Sort Order')
        ->addColumn('scope', Varien_Db_Ddl_Table::TYPE_VARCHAR, 10, array(
            'nullable' => false,
        ), 'Group Scope')
        ->addForeignKey(
            $installer->getFkName('blackchacal_prometheus/config_group', 'extension_id', 'blackchacal_prometheus/extension', 'extension_id'),
            'extension_id',
            $installer->getTable('blackchacal_prometheus/extension'),
            'extension_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        );
$installer->getConnection()->createTable($table);

// Create "prometheus_config_option_data" table associated to "Config_Option" model.
$table = $installer->getConnection()
    ->newTable($installer->getTable('blackchacal_prometheus/config_option'))
        ->addColumn('option_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ), 'Id')
        ->addColumn('group_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned' => true,
            'nullable' => false,
        ), 'Group Id')
        ->addColumn('name', Varien_Db_Ddl_Table::TYPE_VARCHAR, 60, array(
            'nullable' => false,
        ), 'Group Name')
        ->addColumn('label', Varien_Db_Ddl_Table::TYPE_VARCHAR, 150, array(
            'nullable' => false,
        ), 'Group Label')
        ->addColumn('frontend_type', Varien_Db_Ddl_Table::TYPE_VARCHAR, 50, array(
            'nullable' => false,
        ), 'Frontend Type')
        ->addColumn('source_model', Varien_Db_Ddl_Table::TYPE_VARCHAR, 150, array(
            'nullable' => true,
        ), 'Source Model')
        ->addColumn('backend_model', Varien_Db_Ddl_Table::TYPE_VARCHAR, 150, array(
            'nullable' => true,
        ), 'Backend Model')
        ->addColumn('upload_dir', Varien_Db_Ddl_Table::TYPE_VARCHAR, 150, array(
            'nullable' => true,
        ), 'Upload Directory')
        ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable' => false,
        ), 'Sort Order')
        ->addColumn('scope', Varien_Db_Ddl_Table::TYPE_VARCHAR, 10, array(
            'nullable' => false,
        ), 'Group Scope')
        ->addColumn('depends', Varien_Db_Ddl_Table::TYPE_VARCHAR, 150, array(
            'nullable' => true,
        ), 'Dependency')
        ->addForeignKey(
            $installer->getFkName('blackchacal_prometheus/config_option', 'group_id', 'blackchacal_prometheus/config_group', 'group_id'),
            'group_id',
            $installer->getTable('blackchacal_prometheus/config_group'),
            'group_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        );
$installer->getConnection()->createTable($table);

$installer->endSetup();
