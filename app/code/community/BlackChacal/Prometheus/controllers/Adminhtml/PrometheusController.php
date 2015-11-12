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

class BlackChacal_Prometheus_Adminhtml_PrometheusController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Controller index action. Sets basic params and renders layout.
     *
     * @return void
     */
    public function indexAction()
    {
        // Let's call our initAction method which will set some basic params for each action
        $this->_initAction();
        $this->renderLayout();
    }

    /**
     * Controller action for extension creation. It redirects to extension edit
     * action.
     *
     * @return void
     */
    public function newAction()
    {
        // We just forward the new action to a blank edit form
        $this->_forward('edit');
    }

    /**
     * Controller action for extension editing/creation.
     *
     * @return void
     */
    public function editAction()
    {
        $this->_initAction();

        // Get id if available
        $id  = $this->getRequest()->getParam('id');
        $extensionModel = Mage::getModel('blackchacal_prometheus/extension');

        if ($id) {
            // Load record
            $extensionModel->load($id);

            // Check if record is loaded
            if (!$extensionModel->getExtensionId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This extension no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($extensionModel->getExtensionId() ? $extensionModel->getName() : $this->__('New Extension'));

        $data = Mage::getSingleton('adminhtml/session')->getExtensionData(true);
        if (!empty($data)) {
            $extensionModel->setData($data);
        }

        Mage::register('blackchacal_prometheus', $extensionModel);

        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Edit Extension') : $this->__('New Extension'), $id ? $this->__('Edit Extension') : $this->__('New Extension'))
            ->_addContent($this->getLayout()->createBlock('blackchacal_prometheus/adminhtml_prometheus_edit')->setData('action', $this->getUrl('*/*/save')))
            ->renderLayout();
    }

    /**
     * Controller action for extension saving. It connects to database through
     * model to save data.
     *
     * @return void
     */
    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            $extensionModel = Mage::getSingleton('blackchacal_prometheus/extension');
            $extensionModel->setData($postData);
            if (isset($postData['id'])) {
                $extensionModel->setExtensionId($postData['id']);
            }

            try {
                $extensionModel->save();

                switch ($postData['action']) {
                    case 'package':
                        break;
                    case 'install':
                        $installed = $extensionModel->install();
                        if ($installed) {
                            $extensionModel->setInstalled(true);
                            $extensionModel->save();
                        }
                        break;
                    case 'uninstall':
                        $uninstalled = $extensionModel->uninstall();
                        if ($uninstalled) {
                            $extensionModel->setInstalled(false);
                            $extensionModel->save();
                        }
                        break;
                    default:
                        break;
                }

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The extension was saved.'));
                $this->_redirect('*/*/');

                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('An error occurred while saving this extension.'));
            }

            Mage::getSingleton('adminhtml/session')->setExtensionData($postData);
            $this->_redirectReferer();
        }
    }

    /**
     * Controller action for individual deletion of extensions. It's the action
     * associated with the "Delete" button on the extension edit page.
     *
     * @return void
     */
    public function deleteAction()
    {
        // Get id if available
        $id  = $this->getRequest()->getParam('id');

        if ($id) {
            try {
                // Delete record
                $extensionModel = Mage::getModel('blackchacal_prometheus/extension');
                $extensionModel->load($id)->delete();
                $extensionModel->uninstall();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The extension was deleted.'));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Controller action for mass deletion of extensions.
     *
     * @return void
     */
    public function massDeleteAction()
    {
        $extensionIds = $this->getRequest()->getParam('extensions');

        if (!is_array($extensionIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select extension(s).'));
        } else {
            try {
                $extensionModel = Mage::getModel('blackchacal_prometheus/extension');

                foreach ($extensionIds as $extensionId) {
                    $extensionModel->load($extensionId)->delete();
                    $extensionModel->uninstall();
                }

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Total of %d record(s) were deleted.', count($extensionIds)));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }

        $this->_redirect('*/*/index');
    }

    /**
     * Initialize action
     *
     * Here, we set the breadcrumbs and the active menu.
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction()
    {
        $this->loadLayout()
            // Make the active menu match the menu config nodes (without 'children' inbetween)
            ->_setActiveMenu('system/blackchacal_prometheus')
            ->_title($this->__('System'))->_title($this->__('Prometheus'))
            ->_addBreadcrumb($this->__('System'), $this->__('System'))
            ->_addBreadcrumb($this->__('Prometheus - Create Extensions'), $this->__('Prometheus - Create Extensions'));

        return $this;
    }

    /**
     * Check currently called action by permissions for current user. If this method
     * is not defined, on Magento 1.9.1+, a user with restricted permissions can't
     * access the associated grid page.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/blackchacal_prometheus');
    }
}
