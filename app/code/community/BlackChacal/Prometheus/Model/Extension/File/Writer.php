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

class BlackChacal_Prometheus_Model_Extension_File_Writer extends BlackChacal_Prometheus_Model_Extension_Writer
{
    public function __construct()
    {
        $this->_filesystem = new Varien_Io_File();
    }

    /**
     * Creates modules config xml file.
     *
     * @param array $contentData
     */
    public function createModulesConfigFile(array $contentData) {
        $contentData['type'] = 'xml';
        $contentData['xmlType'] = 'modules';
        $contentObj = Mage::getModel('blackchacal_prometheus/extension_file_content_xml_configwriter', $contentData);
        $contentObj->prepareFileContents();

        $contents = $contentObj->getContents();
        $filename = $contentData['extensionFullName'].'.xml';
        $filepath = Mage::getBaseDir('etc').DS.self::MODULES_DIR.DS.$filename;

        try {
            @$this->_filesystem->write($filepath, $contents);
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, Mage::helper('blackchacal_prometheus')->getLogFilename());
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * Creates extension etc/config.xml file.
     *
     * @param array $contentData
     */
    public function createExtensionEtcConfigFile(array $contentData) {
        $this->_createExtensionEtcFile('config', $contentData);
    }

    /**
     * Creates extension etc/system.xml file.
     *
     * @param array $contentData
     */
    public function createExtensionEtcSystemFile(array $contentData) {
        if (array_key_exists('config_section_name', $contentData) &&
            array_key_exists('config_section_label', $contentData) &&
            $contentData['config_section_name'] != '' &&
            $contentData['config_section_label'] != '') {

            $this->_createExtensionEtcFile('system', $contentData);
        }
    }

    /**
     * Creates extension etc/adminhtml.xml file.
     *
     * @param array $contentData
     */
    public function createExtensionEtcAdminhtmlFile(array $contentData) {
        if (array_key_exists('config_section_name', $contentData) &&
            array_key_exists('config_section_label', $contentData) &&
            $contentData['config_section_name'] != '' &&
            $contentData['config_section_label'] != '') {

            $this->_createExtensionEtcFile('adminhtml', $contentData);
        }
    }

    /**
     * Creates extension Helper/Data.php file.
     *
     * @param array $contentData
     */
    public function createExtensionHelperDataFile(array $contentData) {
        $contentData['className'] = $contentData['extensionFullName'].'_Helper_Data';
        $contentData['extends'] = 'Mage_Core_Helper_Data';
        $contentData['implements'] = '';
        $contentData['contents'] = '';
        $this->_createExtensionHelperFile('Data', $contentData);
    }

    /**
     * Creates the extension etc/ files according to filename.
     *
     * @param $filename
     */
    private function _createExtensionEtcFile($filename, $contentData)
    {
        $contentData['type'] = 'xml';
        $contentData['xmlType'] = $filename;
        $contentObj = Mage::getModel('blackchacal_prometheus/extension_file_content_xml_configwriter', $contentData);
        $contentObj->prepareFileContents();

        $contents = $contentObj->getContents();
        $filename = $filename.'.xml';
        $filepath = $this->_getExtensionPath($contentData['codepool'], $contentData['namespace'], $contentData['name']).DS.self::ETC_FOLDER.DS.$filename;

        try {
            @$this->_filesystem->write($filepath, $contents);
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, Mage::helper('blackchacal_prometheus')->getLogFilename());
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * Creates the extension Helper/ files according to filename.
     *
     * @param $filename
     */
    private function _createExtensionHelperFile($filename, $contentData)
    {
        $contentData['type'] = 'php';
        $contentObj = Mage::getModel('blackchacal_prometheus/extension_file_content_php_classwriter', $contentData);
        $contentObj->prepareFileContents();

        $contents = $contentObj->getContents();
        $filename = $filename.'.php';
        $filepath = $this->_getExtensionPath($contentData['codepool'], $contentData['namespace'], $contentData['name']).DS.self::HELPER_FOLDER.DS.$filename;

        try {
            @$this->_filesystem->write($filepath, $contents);
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, Mage::helper('blackchacal_prometheus')->getLogFilename());
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * Gets extension path.
     *
     * @param $codepool
     * @param $namespace
     * @param $extensionName
     * @return string
     */
    private function _getExtensionPath($codepool, $namespace, $extensionName) {
        $codePath = Mage::getBaseDir('code');
        $extensionPath = $codePath.DS.$codepool.DS.$namespace.DS.$extensionName;
        return $extensionPath;
    }
}