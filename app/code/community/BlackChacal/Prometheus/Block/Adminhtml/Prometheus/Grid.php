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

class BlackChacal_Prometheus_Block_Adminhtml_Prometheus_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();

        // Set some defaults for our grid
        $this->setId('blackchacal_prometheus_extensions_grid');
        $this->setUseAjax(true);
        $this->setDefaultSort('extension_id');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    /**
     * Define the collection model class to be used on this grid.
     *
     * @return string Collection Model Class
     */
    protected function _getCollectionClass()
    {
        // This is the model we are using for the grid
        return 'blackchacal_prometheus/extension_collection';
    }

    /**
     * Prepares the collection to be used on the grid.
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        // Get and set our collection for the grid
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Define the columns to be used on the list page. Each column is added using
     * the $this->addColumn() method.
     *
     * @see Mage_Adminhtml_Block_Widget_Grid
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        // Add the columns that should appear in the grid
        $this->addColumn('namespace', array(
            'header'=> $this->__('Namespace'),
            'index' => 'namespace'
        ));
        $this->addColumn('name', array(
            'header'=> $this->__('Name'),
            'index' => 'name'
        ));
        $this->addColumn('codepool', array(
            'header'=> $this->__('Code Pool'),
            'index' => 'codepool'
        ));
        $this->addColumn('version', array(
            'header'=> $this->__('Version'),
            'index' => 'version'
        ));
        $this->addColumn('author_name', array(
            'header'=> $this->__('Author'),
            'index' => 'author_name'
        ));
        $this->addColumn('author_email', array(
            'header'=> $this->__('Author Email'),
            'index' => 'author_email'
        ));

        return parent::_prepareColumns();
    }

    /**
     * Prepare the grid to use mass actions.
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareMassaction()
    {
        /**
         * - setMassactionIdField() should be the table primary key field.
         * - setFormFieldName() defines the name of the url param which holds the
         * mass action ids.
         * - setUseSelectAll() allows the use of the Select/Unselect All links
         * on the grid.
         */
        $this->setMassactionIdField('extension_id');
        $this->getMassactionBlock()->setFormFieldName('extensions');
        $this->getMassactionBlock()->setUseSelectAll(true);

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('blackchacal_prometheus')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
            'confirm' => Mage::helper('blackchacal_prometheus')->__('Are you sure you want to delete the extension(s)?')
        ));

        return parent::_prepareMassaction();
    }

    /**
     * Defines the grid table row url.
     *
     * @see Mage_Adminhtml_Block_Widget_Grid
     * @param  Mage_Catalog_Model_Product|Varien_Object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        // This is where our row data will link to
        return $this->getUrl('*/*/edit', array('id' => $row->getExtensionId()));
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
