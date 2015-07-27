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

class BlackChacal_Prometheus_Block_Adminhtml_Prometheus_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'blackchacal_prometheus';
        $this->_controller = 'adminhtml_prometheus';

        parent::__construct();

        // Define edit page button labels
        $this->_updateButton('save', 'label', $this->__('Save Extension'));
        $this->_updateButton('delete', 'label', $this->__('Delete Extension'));
    }

    /**
     * Defines the Header Text for the edit page on the case of record creation or
     * editing.
     *
     * @return string Header text label
     */
    public function getHeaderText()
    {
        if (Mage::registry('blackchacal_prometheus')->getId()) {
            return $this->__('Edit Extension');
        } else {
            return $this->__('New Extension');
        }
    }
}
