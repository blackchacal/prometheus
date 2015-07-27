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

class BlackChacal_Prometheus_Block_Adminhtml_Prometheus extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        // The _blockGroup matches the first half of how we call the block,
        // and _controller matches the second half
        // ie. blackchacal_prometheus/adminhtml_prometheus
        $this->_blockGroup = 'blackchacal_prometheus';
        $this->_controller = 'adminhtml_prometheus';
        $this->_headerText = Mage::helper('blackchacal_prometheus')->__('Prometheus - Create Extensions');

        parent::__construct();

        $this->updateButton('add', 'label', Mage::helper('blackchacal_prometheus')->__('Create New Extension'));
    }
}
