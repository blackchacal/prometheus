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
        'Namespace'             => 'namespace',
        'Module'                => 'name',
        'Codepool'              => 'codepool',
        'Version'               => 'version',
        'Author'                => 'author',
        'Email'                 => 'author_email',
        'Config_Node_Code'      => 'config_node_code',
        'Config_Tab_Name'       => 'config_tab_name',
        'Config_Tab_Label'      => 'config_tab_label',
        'Config_Tab_Position'   => 'config_tab_position',
        'Config_Section_Name'   => 'config_section_name',
        'Config_Section_Label'  => 'config_section_label',
        'Admin_Menu_Parent'     => 'admin_menu_parent',
        'Admin_Menu_Name'       => 'admin_menu_name',
        'Admin_Menu_Title'      => 'admin_menu_title',
        'Admin_Menu_Action'     => 'admin_menu_action',
        'Admin_Menu_Position'   => 'admin_menu_position'
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
        return $this->wrapStringIntoCommentPhpBlock($this->replacePlaceholders($this->_contentData['license']), $this->_contentData['type']);
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
        $newStr = $this->_processPlaceholderConditionals($newStr, $this->_contentData);
        foreach($this->_placeholders as $placeholder => $value) {
            if (array_key_exists($value, $this->_contentData)) {
                $newStr = str_replace("{{".$placeholder."}}", $this->_contentData[$value], $newStr);
            }
        }
        return $newStr;
    }

    /**
     * Processes the string conditionals and removes or not blocks of text.
     *
     * @param $str
     */
    private function _processPlaceholderConditionals($str, $data)
    {
        $newStr = $str;
        $conditions = array();
        preg_match_all("/\{\{\@if \((\w+\s.{2,3}\s.+)\) \}\}/", $newStr, $conditionals);

        if (count($conditionals[0]) > 0) {
            if (count($conditionals[1]) > 0) {
                // Extract all the conditions from if statement
                foreach ($conditionals[1] as $condition) {
                    preg_match_all("/(&&|\|\|)/", $condition, $operators);
                    $condition = preg_split("/(&&|\|\|)/", $condition);
                    if (count($condition) <= 1) {
                        $conditions[] = explode(' ', $condition[0]);
                    } else {
                        $subconditions = array();
                        $i = 0;
                        foreach ($condition as $subcondition) {
                            $subcondition = explode(' ', trim($subcondition));
                            if ($i < (count($condition) - 1)) {
                                $subcondition['operator'] = $operators[0][$i];
                            }
                            $subconditions[] = $subcondition;
                            $i++;
                        }
                        $conditions[] = $subconditions;
                    }
                }

                // Remove if statements and/or if blocks from string
                foreach ($conditions as $condition) {
                    $conditionStr = $this->_buildConditionString($condition);

                    if ($this->_processMultipleComparisons($condition, $data)) {
                        $pattern = "/\s*\{\{\@if \(".$conditionStr."\) \}\}/";
                        $newStr = preg_replace($pattern, '', $newStr);
                    } else {
                        $pattern = "/\s*\{\{\@if \(".$conditionStr."\) \}\}((.|\n)*?)\{\{@endif\}\}/";
                        $newStr = preg_replace($pattern, '', $newStr);
                    }
                }
                $newStr = preg_replace("/\{\{@endif\}\}\s*/", '', $newStr);
            }
        }

        return $newStr;
    }

    /**
     * Process multiple conditional statements "concatenated" by '&&' or '||'.
     *
     * @param $conditions
     * @param $data
     * @return bool
     */
    private function _processMultipleComparisons($conditions, $data)
    {
        if (is_array($conditions[0])) {
            for ($i = 0, $size = (count($conditions) - 1); $i < $size; $i++) {
                if (!isset($result)) {
                    $result = $this->_concatComparisons($conditions[$i]['operator'], $conditions[$i], $conditions[$i+1], $data);
                } else {
                    $result = $this->_concatComparisons($conditions[$i]['operator'], $result, $conditions[$i+1], $data);
                }
            }
        } else {
            return $this->_processComparisons($data[$conditions[0]], $conditions[1], $conditions[2]);
        }

        return $result;
    }

    /**
     * "Concatenate" boolean expressions.
     *
     * @param $operation
     * @param $comparison1
     * @param $comparison2
     * @return bool
     */
    private function _concatComparisons($operation, $comparison1, $comparison2, $data)
    {
        if (is_bool($comparison1)) {
            $leftComp = $comparison1;
        } elseif (is_array($comparison1)) {
            $leftComp = $this->_processComparisons($data[$comparison1[0]], $comparison1[1], $comparison1[2]);
        }

        if (is_bool($comparison2)) {
            $rightComp = $comparison2;
        } elseif (is_array($comparison2)) {
            $rightComp = $this->_processComparisons($data[$comparison2[0]], $comparison2[1], $comparison2[2]);
        }

        switch ($operation) {
            case '||':
                return $leftComp || $rightComp;
                break;
            default:
                return $leftComp && $rightComp;
                break;
        }
    }

    /**
     * Gives the result of comparisons according to operator.
     *
     * @param $var
     * @param $operator
     * @param $value
     */
    private function _processComparisons($var, $operator, $value)
    {
        switch ($operator) {
            case '==':
                return $var == trim(stripslashes($value), "'");
                break;
            case '===':
                return $var === trim(stripslashes($value), "'");
                break;
            case '!=':
            case '<>':
                return $var != trim(stripslashes($value), "'");
                break;
            case '!==':
                return $var !== trim(stripslashes($value), "'");
                break;
            case '<':
                return $var < trim(stripslashes($value), "'");
                break;
            case '>':
                return $var > trim(stripslashes($value), "'");
                break;
            case '<=':
                return $var <= trim(stripslashes($value), "'");
                break;
            case '>=':
                return $var >= trim(stripslashes($value), "'");
                break;
        }
    }

    /**
     * Create condition string to be used on logical conditions regex.
     *
     * @param $condition
     * @return string
     */
    private function _buildConditionString($condition)
    {
        $conditionStr = '';
        if (is_array($condition[0])) {
            foreach ($condition as $subcondition) {
                $conditionStr .= ' '.implode(' ', $subcondition);
            }
        } else {
            $conditionStr = implode(' ', $condition);
        }
        $conditionStr = trim($conditionStr);

        return $conditionStr;
    }

    /**
     * Gets the source file path given the filename.
     *
     * @param $filename
     * @return string
     */
    protected function getSourceFilesPath($filename)
    {
        return $this->_helper->getSourceFilesDir().DS.$filename;
    }

    /**
     * Wrap a string into a comment block.
     * @param $str
     * @param $type
     * @return string
     */
    protected function wrapStringIntoCommentPhpBlock($str, $type)
    {
        if ($type == 'xml') {
            $newStr = "<!-- {$this->_eol}".$str."{$this->_eol} --> {$this->_eol}";
        } else {
            $newStr = "/**{$this->_eol}";
            $lines = explode($this->_eol, $str);
            foreach ($lines as $line) {
                $newStr .= "  * ".$line.$this->_eol;
            }
            $newStr .= "  */ {$this->_eol}";
        }

        return $newStr;
    }
}