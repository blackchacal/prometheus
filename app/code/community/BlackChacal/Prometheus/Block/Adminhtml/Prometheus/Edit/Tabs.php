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

class BlackChacal_Prometheus_Block_Adminhtml_Prometheus_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        /**
         * The setDestElementId() argument string should match the id of the form
         * defined on the _prepareForm() method from the
         * BlackChacal_Prometheus_Block_Adminhtml_Prometheus_Edit_Form class.
         */
        parent::__construct();
        $this->setId('blackchacal_prometheus_extension_info_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('blackchacal_prometheus')->__('Extension Information'));
    }
}
