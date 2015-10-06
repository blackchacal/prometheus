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

class BlackChacal_Prometheus_Model_Extension_File_Content_Writer
{
    const SOURCE_FOLDER = 'source';

    /**
     * @var string File content.
     */
    protected $_content;

    /**
     * @var string
     */
    protected $_eol;

    /**
     * @var BlackChacal_Prometheus_Helper_Data
     */
    protected $_helper;

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
     * @var array Content data.
     */
    protected $contentData = array();

    /**
     * @var string File opening tag. Ex: <?php, <?xml version="1.0"?>
     */
    protected $openingTag = '';

    public function __construct(array $contentData)
    {
        $this->_contentData = $contentData;
        $this->_helper = Mage::helper('blackchacal_prometheus');
        $this->_eol = $this->_helper->getEol();
    }

    /**
     * Returns file content to be written to actual file.
     *
     * @return string
     */
    public function getContents()
    {
        return $this->_content;
    }

    /**
     * Prepares the file contents according to file type and origin.
     *
     * @return void
     */
    public function prepareFileContents()
    {

    }

    /**
     * Gets the copyright text to include on the file contents.
     *
     * @return string
     */
    protected function getLicenseText()
    {
        return "<!-- {$this->_eol}".$this->_contentData['license']."{$this->_eol} --> {$this->_eol}";
    }
}