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

class BlackChacal_Prometheus_Model_Extension_File_Content_Writer extends BlackChacal_Prometheus_Model_Extension_File_Writer
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
     * ['author'] = 'author name'
     * ['author_email'] = 'author email'
     * ]
     *
     * @var array Content data.
     */
    protected $_contentData = array();

    /**
     * List of placeholders for text replacement.
     *
     * @var array
     */
    protected $_placeholders = array(
        'Namespace'         => 'namespace',
        'Module'            => 'name',
        'Codepool'          => 'codepool',
        'Version'           => 'version',
        'Author'            => 'author',
        'Email'             => 'author_email',
        'Config_Node_Code'  => 'config_node_code',
    );

    /**
     * @var string File opening tag. Ex: <?php, <?xml version="1.0"?>
     */
    protected $openingTag = '';

    public function __construct(array $contentData)
    {
        parent::__construct();

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
        $this->_content = "{$this->_openingTag} {$this->_eol} {$this->getLicenseText()} {$this->_eol}";
    }

    /**
     * Gets the copyright text to include on the file contents.
     *
     * @return string
     */
    protected function getLicenseText()
    {
        return "<!-- {$this->_eol}".$this->replacePlaceholders($this->_contentData['license'])."{$this->_eol} --> {$this->_eol}";
    }

    /**
     * Replaces text placeholders by respective value.
     *
     * @param $str
     * @return mixed
     */
    protected function replacePlaceholders($str)
    {
        $newStr = $str;
        foreach($this->_placeholders as $placeholder => $value) {
            if (array_key_exists($value, $this->_contentData)) {
                $newStr = str_replace("{{".$placeholder."}}", $this->_contentData[$value], $newStr);
            }
        }
        return $newStr;
    }
}