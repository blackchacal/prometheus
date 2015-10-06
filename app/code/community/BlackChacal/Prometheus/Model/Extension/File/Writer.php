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

    /**
     * This array should have the following format:
     * [
     * 'type' = 'php'/'xml'/'sql',
     * 'xmlType' = 'modules'/'general'/'adminhtml'/'system'/'layout'
     * 'extensionName' = 'Namespace_ExtensionName',
     * 'codepool' = 'codepool',
     * 'className' = 'class name',
     * 'extends' = 'extended class',
     * 'implements' = 'implemented interface',
     * ['contents'] =
     * [
     *      ['methods'] = [
     *          [
     *              'name' = 'name',
     *              'contents' = 'contents',
     *              'args' = ['arg1', 'arg2', ...]
     *              'scope' = 'private'/'protected'/'public'
     *          ],
     *          [...],
     *          ...
     *      ],
     *      ['nodes'] = [
     *          [
     *              'nodeName' = 'name',
     *              'nodeValue' = 'value',
     *              'selfClosing' = true/false
     *          ],
     *          [...],
     *          ...
     *      ],
     *      ['contentString'] = 'content'
     * ],
     * ['copyright'] = 'copyright text'
     * ]
     *
     * @param array $contentData
     */
    public function __construct()
    {
        $this->_filesystem = new Varien_Io_File();
    }

    public function createModulesConfigFile(array $contentData) {
        $contentObj = Mage::getModel('blackchacal_prometheus/extension_file_content_xml_configwriter', $contentData);
        $contentObj->prepareFileContents();

        $contents = $contentObj->getContents();
        $filename = $contentData['extensionName'].'.xml';
        $filepath = Mage::getBaseDir('etc').DS.self::MODULES_DIR.DS.$filename;

        try {
            $this->_filesystem->filePutContent($filepath, $contents);
        } catch (Exception $e) {
            Mage::log($e->getMessage(), null, Mage::helper('blackchacal_prometheus')->getLogFilename());
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }
}