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
     * Extension model.
     * @var BlackChacal_Prometheus_Model_Extension
     */
    protected $_model;

    /**
     * @var BlackChacal_Prometheus_Helper_Data
     */
    protected $_helper;

    /**
     * Sets the filesystem wrapper and starts managing file creation paths according
     * to type.
     * $args is an array with the following fields:
     * 'model': BlackChacal_Prometheus_Model_Extension
     * 'extensionName': has the format 'Vendor_ExtensionName'
     * 'codepool': 'community'/'local'
     * @param $args
     */
    public function __construct($args)
    {
        $this->_filesystem = new Varien_Io_File();
        $this->_filesystem->setAllowCreateFolders(true);

        if (is_array($args)) {
            $this->_model = $args['extensionModel'];
            $this->_helper = Mage::helper('blackchacal_prometheus');
            $this->_namespace = $this->_model->getNamespace();
            $this->_extensionName = $this->_model->getName();
            $this->_codepool = $this->_model->getCodepool();
        } else {
            Mage::log('BlackChacal_Prometheus_Model_Extension_Writer constructor has array argument.', null, $this->_helper->getLogFilename());
            Mage::getSingleton('adminhtml/session')->addError('BlackChacal_Prometheus_Model_Extension_Writer constructor has array argument.');
        }
    }

    /**
     * Install the extension by creating its files and folders
     */
    public function install() {
        $installed = $this->_model->getInstalled();
        $rewrite = $this->_model->getRewrite();

        if (!$installed || $rewrite) {
            $this->_createExtensionMainFolder();
            $this->_createExtensionBaseFolders();
            $this->_createModulesConfigFile();
            $this->_createExtensionBaseFiles();
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
            Mage::log($e->getMessage(), null, $this->_helper->getLogFilename());
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * Deletes all extension folders and the app/etc/modules config file.
     */
    public function deleteExtensionFolderFiles()
    {
        $namespacePath = $this->_getNamespacePath();
        $extensionPath = $this->_getExtensionPath();

        try {
            $this->_filesystem->rmdir($extensionPath, true);
            if (is_dir($namespacePath)) {
                $this->_filesystem->cd($namespacePath);

                if (count($this->_filesystem->ls()) == 0) {
                    $this->_filesystem->rmdir($namespacePath, true);
                }
            }

            $modulesConfigFile = $this->_namespace.'_'.$this->_extensionName.'.xml';
            $modulesConfigFilePath = $this->_helper->getModulesConfigDir().DS.$modulesConfigFile;
            if (file_exists($modulesConfigFilePath)) {
                $this->_filesystem->rm($modulesConfigFilePath);
            }
        } catch(Exception $e) {
            Mage::log($e->getMessage(), null, $this->_helper->getLogFilename());
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * Creates the extension namespace folder and extension folder.
     */
    private function _createExtensionMainFolder()
    {
        $namespacePath = $this->_getNamespacePath();
        $extensionPath = $namespacePath.DS.$this->_extensionName;

        $this->createExtensionFolders($namespacePath);
        $this->createExtensionFolders($extensionPath);
    }

    /**
     * Creates extension base folders (etc, Helper, etc.)
     */
    private function _createExtensionBaseFolders()
    {
        $extensionPath = $this->_getExtensionPath();

        $etcPath = $extensionPath.DS.'etc';
        $helperPath = $extensionPath.DS.'Helper';

        $this->createExtensionFolders($etcPath);
        $this->createExtensionFolders($helperPath);
    }

    /**
     * Gets namespace path.
     *
     * @return string
     */
    private function _getNamespacePath()
    {
        $codePath = Mage::getBaseDir('code');
        $codepoolPath = $codePath.DS.$this->_codepool;
        $namespacePath = $codepoolPath.DS.$this->_namespace;

        return $namespacePath;
    }

    /**
     * Gets extension path.
     *
     * @return string
     */
    private function _getExtensionPath()
    {
        $namespacePath = $this->_getNamespacePath();
        $extensionPath = $namespacePath.DS.$this->_extensionName;
        return $extensionPath;
    }

    /**
     * Creates app/etc/modules configuration file.
     */
    private function _createModulesConfigFile() {
        $data = array(
            'namespace'         => $this->_namespace,
            'name'              => $this->_extensionName,
            'extensionFullName' => $this->_namespace.'_'.$this->_extensionName,
            'codepool'          => $this->_codepool,
            'license'           => $this->_model->getLicense(),
            'author'            => $this->_model->getAuthorName(),
            'author_email'      => $this->_model->getAuthorEmail(),
            'config_node_code'  => $this->_model->getConfigNodeCode()
        );

        Mage::getModel('blackchacal_prometheus/extension_file_writer')->createModulesConfigFile($data);
    }

    /**
     * Creates extension base files (config.xml, system.xml, Data.php, etc)
     */
    private function _createExtensionBaseFiles()
    {
        $data = array(
            'namespace'             => $this->_namespace,
            'name'                  => $this->_extensionName,
            'extensionFullName'     => $this->_namespace.'_'.$this->_extensionName,
            'codepool'              => $this->_codepool,
            'version'               => $this->_model->getVersion(),
            'license'               => $this->_model->getLicense(),
            'author'                => $this->_model->getAuthorName(),
            'author_email'          => $this->_model->getAuthorEmail(),
            'config_node_code'      => $this->_model->getConfigNodeCode(),
            'config_tab_type'       => $this->_model->getConfigTabType(),
            'config_tab_name'       => $this->_model->getConfigTabName(),
            'config_tab_label'      => $this->_model->getConfigTabLabel(),
            'config_tab_position'   => $this->_model->getConfigTabPosition(),
            'config_section_name'   => $this->_model->getConfigSectionName(),
            'config_section_label'  => $this->_model->getConfigSectionLabel(),
            'admin_menu_parent'     => $this->_model->getAdminMenuParent(),
            'admin_menu_name'       => $this->_model->getAdminMenuName(),
            'admin_menu_title'      => $this->_model->getAdminMenuTitle(),
            'admin_menu_action'     => $this->_model->getAdminMenuAction(),
            'admin_menu_position'   => $this->_model->getAdminMenuPosition()
        );

        Mage::getModel('blackchacal_prometheus/extension_file_writer')->createExtensionEtcConfigFile($data);
        Mage::getModel('blackchacal_prometheus/extension_file_writer')->createExtensionEtcSystemFile($data);
        Mage::getModel('blackchacal_prometheus/extension_file_writer')->createExtensionEtcAdminhtmlFile($data);
        Mage::getModel('blackchacal_prometheus/extension_file_writer')->createExtensionHelperDataFile($data);
    }
}