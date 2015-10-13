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

class BlackChacal_Prometheus_Model_Extension_File_Content_Xml_Configwriter extends BlackChacal_Prometheus_Model_Extension_File_Content_Writer
{
    /**
     * @inheritdoc
     */
    protected $_openingTag = '<?xml version="1.0"?>';

    /**
     * @inheritdoc
     */
    public function prepareFileContents()
    {
        if ($this->_contentData['type'] != 'xml') {
            Mage::throwException("Wrong content type! It should be xml.");
        }

        parent::prepareFileContents();

        $contents = $this->selectXmlFileType();
        $this->_content .= $contents;
    }

    private function selectXmlFileType()
    {
        $content = '';
        $type = $this->_contentData['xmlType'];

        switch ($type) {
            case 'modules':
                $content = $this->_createModulesConfigXml();
                break;
            case 'general':
                $content = $this->_createExtensionConfigXml();
                break;
            case 'adminhtml':
                break;
            case 'system':
                break;
            default:
                break;
        }

        return $content;
    }

    /**
     * Creates app/etc/modules extension config file.
     *
     * @return mixed|string
     */
    private function _createModulesConfigXml()
    {
        $path = $this->getSourceFilesPath('modules_config_xml.txt');

        try {
            $xmlText = @$this->_filesystem->read($path);
            $xmlText = $this->replacePlaceholders($xmlText);
        } catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        return $xmlText;
    }

    /**
     * Creates extension etc/config.xml file.
     *
     * @return mixed|string
     */
    private function _createExtensionConfigXml()
    {
        $path = $this->getSourceFilesPath('extension_config_xml.txt');

        try {
            $xmlText = @$this->_filesystem->read($path);
            $xmlText = $this->replacePlaceholders($xmlText);
        } catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        return $xmlText;
    }
}