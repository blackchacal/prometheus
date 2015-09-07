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

class BlackChacal_Prometheus_Block_Adminhtml_Prometheus_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return Mage::helper('blackchacal_prometheus')->__('General Settings');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return Mage::helper('blackchacal_prometheus')->__('General Settings');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Setup the form fields for this tab.
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('blackchacal_prometheus');

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('blackchacal_prometheus')->__('Extension Information'),
            'class'     => 'fieldset-wide',
        ));

        if ($model->getId()) {
            $fieldset->addField('extension_id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('namespace', 'text', array(
            'name'      => 'namespace',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Namespace'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Namespace'),
            'required'  => true,
            'value'     => ($model->getNamespace()) ? $model->getNamespace() :
                            Mage::helper('blackchacal_prometheus')->getConfig('namespace')
        ));

        $fieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Name'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Name'),
            'required'  => true,
            'value'     => $model->getName()
        ));

        $fieldset->addField('codepool', 'select', array(
            'name'      => 'codepool',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Code Pool'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Code Pool'),
            'required'  => true,
            'value'     => $model->getCodepool(),
            'values'    => Mage::getModel('blackchacal_prometheus/system_config_source_codepool')->toOptionArray()
        ));

        $fieldset->addField('version', 'text', array(
            'name'      => 'version',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Version'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Version'),
            'required'  => true,
            'value'     => ($model->getVersion()) ? $model->getVersion() :
                            Mage::helper('blackchacal_prometheus')->getConfig('version')
        ));

        $fieldset->addField('license', 'textarea', array(
            'name'      => 'license',
            'label'     => Mage::helper('blackchacal_prometheus')->__('License'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('License'),
            'required'  => false,
            'value'     => ($model->getLicense()) ? $model->getLicense() :
                            Mage::helper('blackchacal_prometheus')->getConfig('license')
        ));

        $fieldset->addField('action', 'select', array(
            'name'      => 'action',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Action'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Action'),
            'required'  => false,
            'values'     => Mage::getModel('blackchacal_prometheus/system_config_source_action')->toOptionArray()
        ));

        $this->setForm($form);
        /**
         * If is "edit" page use model values. If is "new" page use default
         * configuration data.
         */
        if ($this->getRequest()->getParam('id')) {
            $form->setValues($model->getData());
        }

        return parent::_prepareForm();
    }
}
