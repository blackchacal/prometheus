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

class BlackChacal_Prometheus_Model_Extension_File_Content_Php_Classwriter extends BlackChacal_Prometheus_Model_Extension_File_Content_Writer
{
    /**
     * @inheritdoc
     */
    protected $_openingTag = '<?php';

    /**
     * @inheritdoc
     */
    public function prepareFileContents()
    {
        if ($this->_contentData['type'] != 'php') {
            Mage::throwException("Wrong content type! It should be php.");
        }

        parent::prepareFileContents();

        $className = $this->_contentData['className'];
        $extends = $this->_contentData['extends'];
        $implements = $this->_contentData['implements'];
        $contents = $this->_contentData['contents'];

        $this->_content .= $this->_createClassWrapper($className, $contents, $extends, $implements);
    }

    private function _createClassWrapper($className, $classContents, $extends = '', $implements = '')
    {
        $extendsBlock = ($extends) ? "extends $extends" : "";
        $implementsBlock = ($implements) ? "implements $implements" : "";
        $contents = $this->_createClassContents($classContents);

        $classWrapper = "class $className $extendsBlock $implementsBlock {$this->_eol}";
        $classWrapper .= "{{$this->_eol} $contents {$this->_eol}}";

        return $classWrapper;
    }

    private function _createClassContents($classContents)
    {
        $contents = "";
        if (is_array($classContents)) {
            foreach($classContents['methods'] as $method) {
                $contents .= $this->_createClassMethod($method['name'], $method['args'], $method['contents'], $method['scope']);
            }
        } else {
            $contents = $classContents;
        }

        return $contents;
    }

    private function _createClassMethod($methodName, array $methodArgs, $methodContents, $scope = 'public')
    {
        $args = implode(', ', $methodArgs);
        $methodWrapper = "{$this->_eol} $scope function $methodName($args) {$this->_eol}";
        $methodWrapper .= "{{$this->_eol} $methodContents {$this->_eol}}{$this->_eol}";

        return $methodWrapper;
    }
}