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
     * Extension directories
     */
    const MODULE_DIR                    = 'community/BlackChacal/Prometheus';

    /**
     * System configuration section key
     */
    const XML_PATH_SECTION              = 'blackchacal_prometheus';

    /**
     * System configuration group keys
     */
    const XML_PATH_GENERAL_GROUP        = 'general';
    const XML_PATH_DEF_EXTENSION_GROUP  = 'default_extension';

    /**
     * System configuration paths for "General" tab
     */
    const XML_PATH_GENERAL_ACTIVE       = 'blackchacal_prometheus/general/enabled';
    const XML_PATH_GENERAL_HELP         = 'blackchacal_prometheus/general/help';

    /**
     * General constants
     */
    const LOG_FILENAME                  = 'blackchacal_prometheus.log';


    /**
     * Returns the configuration options of Prometheus based on group and field.
     *
     * @param  string $group Configuration path - group
     * @param  string $field Configuration path - field
     * @param  mixed  $store Store data
     * @return mixed
     */
    public function getConfig($field, $group = 'default_extension', $store = null)
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
        return (bool)Mage::getStoreConfig(self::XML_PATH_GENERAL_ACTIVE);
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

    /**
     * Returns the filename for Prometheus log.
     *
     * @return string
     */
    public function getLogFilename()
    {
        return self::LOG_FILENAME;
    }

    /**
     * Returns the End-of-line character.
     *
     * @return string
     */
    public function getEol()
    {
        return "\n";
    }

    /**
     * Return module dir in relation to app/code folder.
     * @return string
     */
    public function getModuleDir()
    {
        return self::MODULE_DIR;
    }

    /**
     * Return app/etc/modules directory.
     *
     * @return string
     */
    public function getModulesConfigDir()
    {
        return Mage::getBaseDir('etc').DS.'modules';
    }

    /**
     * Return directory for extension file creation sources.
     *
     * @return string
     */
    public function getSourceFilesDir()
    {
        return Mage::getBaseDir('code').DS.$this->getModuleDir().DS.'etc'.DS.'source';
    }

    /**
     * Remove all characters that aren't words, numbers, underscore and spaces.
     *
     * @param $str
     */
    public function escapeStrings($str)
    {
        return preg_replace('/[^ \w+]/', '', $str);
    }

    /**
     * Remove all characters that aren't numbers and ".".
     *
     * @param $str
     */
    public function escapeVersionStrings($str)
    {
        return preg_replace('/[^0-9.+]/', '', $str);
    }

    /**
     * Remove all characters that aren't numbers.
     *
     * @param $str
     */
    public function escapeNumberStrings($str)
    {
        return preg_replace('/[^0-9+]/', '', $str);
    }

    /**
     * Escapes the extension model strings for invalid characters before saving to db.
     *
     * @param $model BlackChacal_Prometheus_Model_Extension
     * @return mixed
     */
    public function escapeExtensionModel($model)
    {
        $validModel = $model;
        $validModel->setNamespace($this->escapeStrings($model->getNamespace()));
        $validModel->setName($this->escapeStrings($model->getName()));
        $validModel->setVersion($this->escapeVersionStrings($model->getVersion()));
        $validModel->setAuthorEmail(!Zend_Validate::is($model->getAuthorEmail(), 'EmailAddress') ? '' : $model->getAuthorEmail());
        $validModel->setConfigNodeCode($this->escapeStrings($model->getConfigNodeCode()));
        $validModel->setConfigTabName($this->escapeStrings($model->getConfigTabName()));
        $validModel->setConfigTabLabel($this->escapeStrings($model->getConfigTabLabel()));
        $validModel->setConfigTabPosition($this->escapeNumberStrings($model->getConfigTabPosition()));
        $validModel->setConfigSectionName($this->escapeStrings($model->getConfigSectionName()));
        $validModel->setConfigSectionLabel($this->escapeStrings($model->getConfigSectionLabel()));
        $validModel->setAdminMenuName($this->escapeStrings($model->getAdminMenuName()));
        $validModel->setAdminMenuTitle($this->escapeStrings($model->getAdminMenuTitle()));
        $validModel->setAdminMenuAction($this->escapeStrings($model->getAdminMenuAction()));
        $validModel->setAdminMenuPosition($this->escapeNumberStrings($model->getAdminMenuPosition()));

        return $validModel;
    }
}
