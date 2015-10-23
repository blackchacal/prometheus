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

class BlackChacal_Prometheus_Model_Extension extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('blackchacal_prometheus/extension');
    }

    /**
     * Install the extension by creating all the necessary files and folders.
     * It behaves differently according to extension configuration.
     */
    public function install()
    {
        try {
            Mage::getModel('blackchacal_prometheus/extension_writer', array(
                'extensionModel' => $this,
            ))->install();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Uninstall the extension by deleting all necessary files and folders.
     * It behaves differently according to extension configuration.
     */
    public function uninstall()
    {
        try {
            Mage::getModel('blackchacal_prometheus/extension_writer', array(
                'extensionModel' => $this,
            ))->deleteExtensionFolderFiles();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
