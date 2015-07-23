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
 * @copyright   Copyright (c) 2015 BlackChacal
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class BlackChacal_Prometheus_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * System configuration section key
     */
    const XML_PATH_SECTION              = 'prometheus';

    /**
     * System configuration group keys
     */
    const XML_PATH_GENERAL_GROUP        = 'general';
    const XML_PATH_DEF_EXTENSION_GROUP  = 'default_extension';

    /**
     * System configuration paths for "General" tab
     */
    const XML_PATH_GENERAL_ACTIVE       = 'prometheus/general/enabled';
    const XML_PATH_GENERAL_HELP         = 'prometheus/general/help';


    /**
     * Returns the configuration options of Prometheus based on group and field.
     *
     * @param  string $group Configuration path - group
     * @param  string $field Configuration path - field
     * @param  mixed  $store Store data
     * @return mixed
     */
    public function getConfig($group, $field, $store = null)
    {
        $configPath = self::XML_PATH_SECTION.'/'.$group.'/'.$field;
        return Mage::getStoreConfig($configPath, $store);
    }

    /**
     * Checks if the Prometheus extension is active or not. Returns true if active,
     * false otherwise.
     *
     * @return boolean
     */
    public function isPrometheusActive()
    {
        return Mage::getStoreConfig(self::XML_PATH_GENERAL_ACTIVE);
    }

    /**
     * Checks if the Help tab on the module edit page is active or not. Returns
     * true if active, false otherwise.
     *
     * @return boolean
     */
    public function isHelpTabActive()
    {
        return Mage::getStoreConfig(self::XML_PATH_GENERAL_HELP);
    }
}