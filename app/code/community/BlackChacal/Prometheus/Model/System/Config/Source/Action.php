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

class BlackChacal_Prometheus_Model_System_Config_Source_Action
{
    /**
     * Returns the configuration options for the
     * "prometheus/default_extension/action" - "Action" select input.
     *
     * @access public
     * @return array Configuration Options
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('blackchacal_prometheus');

        $options = array();
        $options[] = array(
            'value' => 'save',
            'label' => $helper->__('Save extension.')
        );
//        $options[] = array(
//            'value' => 'package',
//            'label' => $helper->__('Save & Package extension.')
//        );
        $options[] = array(
            'value' => 'install',
            'label' => $helper->__('Save & Install extension.')
        );
        $options[] = array(
            'value' => 'uninstall',
            'label' => $helper->__('Save & Uninstall extension.')
        );

        return $options;
    }
}
