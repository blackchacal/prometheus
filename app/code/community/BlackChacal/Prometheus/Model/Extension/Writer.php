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

class BlackChacal_Prometheus_Model_Extension_Writer
{
    const MODULES_DIR = 'modules';

    /**
     * Extension base folders
     */
    const ETC_FOLDER = 'etc';
    const MODEL_FOLDER = 'Model';
    const HELPER_FOLDER = 'Helper';
    const BLOCK_FOLDER = 'Block';
    const CONTROLLERS_FOLDER = 'controllers';
    const CONTROLLER_FOLDER = 'Controller';
    const SQL_FOLDER = 'sql';

    /**
     * Filesystem wrapper class.
     * @var Varien_Io_File
     */
    protected $_filesystem;

    /**
     * Extension namespace.
     * @var string
     */
    protected $_namespace;

    /**
     * Extension name.
     * @var string
     */
    protected $_extensionName;

    /**
     * Extension code pool.
     * @var string
     */
    protected $_codepool;

    /**
     * Sets the filesystem wrapper and starts managing file creation paths according
     * to type.
     * $args is an array with the following fields:
     * 'extensionName': has the format 'Vendor_ExtensionName'
     * 'codepool': 'community'/'local'
     * @param $args
     */
    public function __construct($args)
    {
        $this->_filesystem = new Varien_Io_File();
        $this->_filesystem->setAllowCreateFolders(true);

        if (is_array($args)) {
            list($namespace, $name) = explode("_", $args['extensionName']);
            $this->_namespace = $namespace;
            $this->_extensionName = $name;
            $this->_codepool = $args['codepool'];

            $this->_createExtensionMainFolder();
        }
    }

    /**
     * Create extension folders.
     * @param $folderName
     * @throws Exception
     */
    protected function createExtensionFolders($folderName)
    {
        try {
            $this->_filesystem->checkAndCreateFolder($folderName);
        } catch(Exception $e) {
            Mage::log($e->getMessage(), null, Mage::helper('blackchacal_prometheus')->getLogFilename());
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * Deletes all extension folders and the app/etc/modules config file.
     */
    public function deleteExtensionFolderFiles()
    {
        $namespacePath = Mage::getBaseDir('code').DS.$this->_codepool.DS.$this->_namespace;
        $extensionPath = $namespacePath.DS.$this->_extensionName;

        try {
            $this->_filesystem->rmdir($extensionPath, true);
            $this->_filesystem->cd($namespacePath);

            if (count($this->_filesystem->ls()) == 0) {
                $this->_filesystem->rmdir($namespacePath, true);
            }
        } catch(Exception $e) {
            Mage::log($e->getMessage(), null, Mage::helper('blackchacal_prometheus')->getLogFilename());
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * Creates the extension namespace folder and extension folder.
     */
    private function _createExtensionMainFolder()
    {
        $codePath = Mage::getBaseDir('code');
        $codepoolPath = $codePath.DS.$this->_codepool;
        $namespacePath = $codepoolPath.DS.$this->_namespace;
        $extensionPath = $namespacePath.DS.$this->_extensionName;

        $this->createExtensionFolders($namespacePath);
        $this->createExtensionFolders($extensionPath);
    }

}