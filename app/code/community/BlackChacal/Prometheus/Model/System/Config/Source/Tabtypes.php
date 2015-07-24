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

class BlackChacal_Prometheus_Model_System_Config_Source_Tabtypes
{
    /**
     * Returns the configuration options for the
     * "prometheus/default_extension/config_tab" - "Configuration Tab Name"
     * select input.
     *
     * @access public
     * @return array Configuration Options
     */
    public function toOptionArray()
    {
        $helper = Mage::helper('blackchacal_prometheus');

        $options = array();
        $options[] = array(
            'value' => 'namespace',
            'label' => $helper->__('Namespace')
        );
        $options[] = array(
            'value' => 'system',
            'label' => $helper->__('System Tabs')
        );
        $options[] = array(
            'value' => 'custom',
            'label' => $helper->__('Custom')
        );

        return $options;
    }
}
