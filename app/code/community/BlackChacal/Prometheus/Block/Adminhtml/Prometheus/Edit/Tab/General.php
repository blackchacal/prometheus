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

        $form = $this->_prepareInfoFieldset($model, $form);
        $form = $this->_prepareConfigFieldset($model, $form);
        $form = $this->_prepareAdminMenuFieldset($model, $form);

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

    /**
     * Prepares the "Extension Information" fieldset.
     *
     * @param $model BlackChacal_Prometheus_Model_Extension
     * @param $form Varien_Data_Form
     */
    private function _prepareInfoFieldset($model, $form)
    {
        $infoFieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('blackchacal_prometheus')->__('Extension Information'),
            'class'     => 'fieldset-wide',
            'expanded'  => true,
        ));

        if ($model->getId()) {
            $infoFieldset->addField('extension_id', 'hidden', array(
                'name' => 'id',
            ));
        }

        // Fields on "Extension Information" fieldset
        $infoFieldset->addField('namespace', 'text', array(
            'name'      => 'namespace',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Namespace'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Namespace'),
            'required'  => true,
            'value'     => ($model->getNamespace()) ? $model->getNamespace() :
                Mage::helper('blackchacal_prometheus')->escapeStrings(Mage::helper('blackchacal_prometheus')->getConfig('namespace'))
        ));
        $infoFieldset->addField('name', 'text', array(
            'name'      => 'name',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Name'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Name'),
            'required'  => true,
            'value'     => $model->getName()
        ));
        $infoFieldset->addField('codepool', 'select', array(
            'name'      => 'codepool',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Code Pool'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Code Pool'),
            'required'  => true,
            'value'     => ($model->getCodepool()) ? $model->getCodepool() :
                Mage::helper('blackchacal_prometheus')->getConfig('codepool'),
            'values'    => Mage::getModel('blackchacal_prometheus/system_config_source_codepool')->toOptionArray()
        ));
        $infoFieldset->addField('version', 'text', array(
            'name'      => 'version',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Version'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Version'),
            'required'  => true,
            'value'     => ($model->getVersion()) ? $model->getVersion() :
                Mage::helper('blackchacal_prometheus')->getConfig('version')
        ));
        $infoFieldset->addField('license', 'textarea', array(
            'name'      => 'license',
            'label'     => Mage::helper('blackchacal_prometheus')->__('License'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('License'),
            'required'  => false,
            'value'     => ($model->getLicense()) ? $model->getLicense() :
                Mage::helper('blackchacal_prometheus')->getConfig('license')
        ));
        $infoFieldset->addField('author_name', 'text', array(
            'name'      => 'author_name',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Author'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Author'),
            'required'  => false,
            'value'     => ''
        ));
        $infoFieldset->addField('author_email', 'text', array(
            'name'      => 'author_email',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Author Email'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Author Email'),
            'required'  => false,
            'value'     => ''
        ));
        $infoFieldset->addField('action', 'select', array(
            'name'      => 'action',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Action'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Action'),
            'required'  => false,
            'value'     => ($model->getAction()) ? $model->getAction() :
                Mage::helper('blackchacal_prometheus')->getConfig('action'),
            'values'    => Mage::getModel('blackchacal_prometheus/system_config_source_action')->toOptionArray()
        ));
        $infoFieldset->addField('rewrite', 'select', array(
            'name'      => 'rewrite',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Rewrite Files'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Rewrite Files'),
            'required'  => false,
            'values'    => Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray()
        ));
        $infoFieldset->addField('installed', 'text', array(
            'name'      => 'installed',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Installed'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Installed'),
            'required'  => false,
            'readonly'  => true,
            'value'     => $model->getInstalled()
        ));
        $infoFieldset->addField('config_node_code', 'text', array(
            'name'      => 'config_node_code',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Config Node Code'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Config Node Code'),
            'required'  => true
        ));

        // Add fields dependencies
        $this->setChild('form_after', $this->getLayout()
            ->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap('action', 'action')
            ->addFieldMap('rewrite', 'rewrite')
            ->addFieldDependence('rewrite', 'action', 'install')
        );

        return $form;
    }

    /**
     * Prepares the "Extension Configuration" fieldset.
     *
     * @param $model BlackChacal_Prometheus_Model_Extension
     * @param $form Varien_Data_Form
     */
    private function _prepareConfigFieldset($model, $form)
    {
        $configFieldset = $form->addFieldset('config_fieldset', array(
            'legend'    => Mage::helper('blackchacal_prometheus')->__('Extension Configuration'),
            'class'     => 'fieldset-wide',
            'expanded'  => false,
        ));

        // Fields on "Extension Configuration" fieldset
        $configFieldset->addField('config_tab_type', 'select', array(
            'name'      => 'config_tab_type',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Configuration Tab Type'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Configuration Tab Type'),
            'value'     => ($model->getConfigTabType()) ? $model->getConfigTabType() :
                Mage::helper('blackchacal_prometheus')->getConfig('config_tab_type'),
            'values'    => Mage::getModel('blackchacal_prometheus/system_config_source_tabtypes')->toOptionArray()
        ));
        $configFieldset->addField('config_tab_name', 'text', array(
            'name'      => 'config_tab_name',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Configuration Tab Name'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Configuration Tab Name'),
            'value'     => ($model->getConfigTabName()) ? $model->getConfigTabName() :
                $this->_getConfigTabName(Mage::helper('blackchacal_prometheus')->getConfig('config_tab_type'))
        ))->setAfterElementHtml("<script type='text/javascript'>
        //<![CDATA[
            (function() {
                var onloadName = '".$model->getConfigTabName()."',
                    namespaceName = '".$this->_getConfigTabName('namespace')."',
                    namespaceLabel = '".$this->_getConfigTabLabel('namespace')."',
                    systemName = '".$this->_getConfigTabName('system')."',
                    systemLabel = '".$this->_getConfigTabLabel('system')."';

                setDefaultConfigTabName(onloadName, namespaceName, namespaceLabel, systemName, systemLabel);
            })();
        //]]>
        </script>");
        $configFieldset->addField('config_system_tab_name', 'select', array(
            'name'      => 'config_tab_name',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Configuration Tab Name'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Configuration Tab Name'),
            'value'     => ($model->getConfigTabName()) ? $model->getConfigTabName() :
                $this->_getConfigTabName(Mage::helper('blackchacal_prometheus')->getConfig('config_tab_type')),
            'values'    => Mage::getModel('blackchacal_prometheus/system_config_source_systemtabs')->toOptionArray()
        ))->setAfterElementHtml("<script type='text/javascript'>
        //<![CDATA[
            (function() {
                setDefaultConfigSystemTabLabel();
            })();
        //]]>
        </script>");
        $configFieldset->addField('config_tab_label', 'text', array(
            'name'      => 'config_tab_label',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Configuration Tab Label'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Configuration Tab Label'),
            'value'     => ($model->getConfigTabLabel()) ? $model->getConfigTabLabel() :
                $this->_getConfigTabLabel(Mage::helper('blackchacal_prometheus')->getConfig('config_tab_type'))
        ));
        $configFieldset->addField('config_tab_position', 'text', array(
            'name'      => 'config_tab_position',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Configuration Tab Position'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Configuration Tab Position'),
            'value'     => ($model->getConfigTabPosition()) ? $model->getConfigTabPosition() :
                $this->_getConfigTabPositions(Mage::helper('blackchacal_prometheus')->getConfig('config_tab_type'))
        ));
        $configFieldset->addField('config_section_name', 'text', array(
            'name'      => 'config_section_name',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Configuration Section Name'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Configuration Section Name')
        ));
        $configFieldset->addField('config_section_label', 'text', array(
            'name'      => 'config_section_label',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Configuration Section Label'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Configuration Section Label')
        ));

        // Add fields dependencies
        $this->setChild('form_after', $this->getLayout()
            ->createBlock('adminhtml/widget_form_element_dependence')
            ->addFieldMap('config_tab_type', 'config_tab_type')
            ->addFieldMap('config_system_tab_name', 'config_system_tab_name')
            ->addFieldMap('config_tab_name', 'config_tab_name')
            ->addFieldMap('config_tab_label', 'config_tab_label')
            ->addFieldMap('config_tab_position', 'config_tab_position')
            ->addFieldDependence('config_system_tab_name', 'config_tab_type', 'system')
            ->addFieldDependence('config_tab_name', 'config_tab_type', array('namespace', 'custom'))
            ->addFieldDependence('config_tab_label', 'config_tab_type', array('namespace', 'custom'))
            ->addFieldDependence('config_tab_position', 'config_tab_type', array('namespace', 'custom'))
        );

        return $form;
    }

    /**
     * Prepares the "Extension Admin Menu" fieldset.
     *
     * @param $model BlackChacal_Prometheus_Model_Extension
     * @param $form Varien_Data_Form
     */
    private function _prepareAdminMenuFieldset($model, $form)
    {
        $menuFieldset = $form->addFieldset('menu_fieldset', array(
            'legend'    => Mage::helper('blackchacal_prometheus')->__('Extension Admin Menu'),
            'class'     => 'fieldset-wide',
            'expanded'  => false,
        ));

        // Fields on "Extension Admin Menu" fieldset
        $menuFieldset->addField('admin_menu_parent', 'select', array(
            'name'      => 'admin_menu_parent',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Admin Menu Parent'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Admin Menu Parent'),
            'value'     => $model->getAdminMenuParent(),
            'values'    => Mage::getModel('blackchacal_prometheus/system_config_source_systemmenu')->toOptionArray()
        ));
        $menuFieldset->addField('admin_menu_name', 'text', array(
            'name'      => 'admin_menu_name',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Admin Menu Name'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Admin Menu Name')
        ));
        $menuFieldset->addField('admin_menu_title', 'text', array(
            'name'      => 'admin_menu_title',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Admin Menu Title'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Admin Menu Title')
        ));
        $menuFieldset->addField('admin_menu_action', 'text', array(
            'name'      => 'admin_menu_action',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Admin Menu Action'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Admin Menu Action')
        ));
        $menuFieldset->addField('admin_menu_position', 'text', array(
            'name'      => 'admin_menu_position',
            'label'     => Mage::helper('blackchacal_prometheus')->__('Admin Menu Position'),
            'title'     => Mage::helper('blackchacal_prometheus')->__('Admin Menu Position')
        ));

        return $form;
    }

    /**
     * Retrives the Configuration Tab Label according to config settings.
     *
     * @param $type string
     * @return string
     */
    private function _getConfigTabLabel($type)
    {
        switch ($type) {
            case 'namespace':
                $name = Mage::helper('blackchacal_prometheus')->getConfig('namespace');
                $name = Mage::helper('blackchacal_prometheus')->escapeStrings($name);
                return $name;
                break;
            case 'system':
                $tabs = Mage::getModel('blackchacal_prometheus/system_config_source_systemtabs')->toOptionArray();
                foreach($tabs as $tab) {
                    if ($tab['value'] == Mage::helper('blackchacal_prometheus')->getConfig('config_tab_system')) {
                        return $tab['label'];
                    }
                }
                break;
            case 'custom':
                $name = Mage::helper('blackchacal_prometheus')->getConfig('config_tab_custom');
                $name = Mage::helper('blackchacal_prometheus')->escapeStrings($name);
                return $name;
                break;
        }
    }

    /**
     * Retrives the Configuration Tab Name according to config settings.
     *
     * @param $type string
     * @return string
     */
    private function _getConfigTabName($type)
    {
        switch ($type) {
            case 'namespace':
                $name = strtolower(Mage::helper('blackchacal_prometheus')->getConfig('namespace'));
                $name = Mage::helper('blackchacal_prometheus')->escapeStrings($name);
                return preg_replace('/\s+/', '_', $name);
                break;
            case 'system':
                $tabs = Mage::getModel('blackchacal_prometheus/system_config_source_systemtabs')->toOptionArray();
                foreach($tabs as $tab) {
                    if ($tab['value'] == Mage::helper('blackchacal_prometheus')->getConfig('config_tab_system')) {
                        return $tab['value'];
                    }
                }
                break;
            case 'custom':
                $name = strtolower(Mage::helper('blackchacal_prometheus')->getConfig('config_tab_custom'));
                $name = Mage::helper('blackchacal_prometheus')->escapeStrings($name);
                return preg_replace('/\s+/', '_', $name);
                break;
        }
    }

    /**
     * Retrives the Configuration Tab Position according to config settings.
     *
     * @param $type string
     * @return string
     */
    private function _getConfigTabPositions($type)
    {
        switch ($type) {
            case 'namespace':
                return Mage::helper('blackchacal_prometheus')->getConfig('config_tab_namespace_position');
                break;
            case 'custom':
                return Mage::helper('blackchacal_prometheus')->getConfig('config_tab_custom_position');
                break;
        }
    }
}
