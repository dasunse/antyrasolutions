<?php

/**
 * Created by PhpStorm.
 * User: kanishka
 * Date: 7/2/17
 * Time: 1:30 AM
 */
class Maduranga_Wall_Adminhtml_VisualizerController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        // Let's call our initAction method which will set some basic params for each action
        $this->_initAction()
            ->renderLayout();
    }

    public function newAction()
    {
        // We just forward the new action to a blank edit form
        $this->_forward('edit');
    }

    public function editAction()
    {
        $this->_initAction();

        // Get id if available
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getSingleton('maduranga_wall/visualizer');

        if ($id) {
            // Load record
            $model->load($id);

            // Check if record is loaded
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This wallpaper image no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getName() : $this->__('New Wallpaper Image'));

        $data = Mage::getSingleton('adminhtml/session')->getVisualizerData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('maduranga_wall', $model);

        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Edit Wallpaper Image') : $this->__('New Wallpaper Image'), $id ? $this->__('Edit Visualizer') : $this->__('New Visualizer'))
            ->_addContent($this->getLayout()->createBlock('maduranga_wall/adminhtml_visualizer_edit')->setData('action', $this->getUrl('*/*/save')))
            ->renderLayout();
    }

    public function deleteAction()
    {

        $id = $this->getRequest()->getParam('id');

        try {
            Mage::getSingleton('maduranga_wall/visualizer')->setId($id)->delete();
            Mage::getSingleton('core/session')->addSuccess($this->__('Successfully deleted '));
            $this->_redirect('*/*/');

            return;
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($this->__('Error occurred: %s', $e->getMessage()));
            $this->_redirectReferer();
            return;
        }

    }


    public function saveAction()
    {
        if ($this->getRequest()->getPost()) {

            $postData = $this->getRequest()->getPost();
            $wallModel = Mage::getSingleton('maduranga_wall/visualizer');

            try {

                $filename = NULL;

                if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
                    try {

                        $uploader = new Varien_File_Uploader('image');

                        $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                        $uploader->setAllowRenameFiles(false);
                        $uploader->setFilesDispersion(false);

                        // Set media as the upload dir
                        $media_path = Mage::getBaseDir('media') . '/visualizer/' . time() . '/';

                        $filename = $media_path . $_FILES['image']['name'];

                        while (file_exists($filename)) {
                            $pieces = array();

                            $res = preg_match('/^(.+)_(\d+)$/', $filename, $pieces);

                            if (!$res) {
                                $filename .= '_1';
                            } else {
                                $filename .= '_' . strval(intval($pieces[2]) + 1);
                            }
                        }

                        // Upload the image
                        $uploader->save($media_path, $postData['image']);

                        //$image_path_without_media = str_replace(Mage::getBaseDir('media'), '',$filename);

                        $wallModel->setId($this->getRequest()->getParam('id'))
                            ->setImage($filename)
                            ->setName($postData['name'])
                            ->setStatus($postData['status'])
                            ->save();

                        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The wallpaper image has been saved.'));
                        $this->_redirect('*/*/');

                        return;
                    } catch (Exception $e) {
                        Mage::log($e);
                        Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                        //$this->redirectError(502);
                    }
                } else {
                    if (isset($postData['image']['delete']) && $postData['image']['delete'] == 1) {
                        $filename = NULL;
                    }
                }
            } catch (Mage_Core_Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($this->__('An error occurred while saving this wallpaper image data.'));
            }

            Mage::getSingleton('adminhtml/session')->setVisualizerData($postData);
            $this->_redirectReferer();
        }
        $this->_redirect('*/*/');
    }

    public function messageAction()
    {
        $data = Mage::getModel('maduranga_wall/visualizer')->load($this->getRequest()->getParam('id'));
        echo $data->getContent();
    }

    /**
     * Initialize action
     *
     * Here, we set the breadcrumbs and the active menu
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction()
    {
        $this->loadLayout()
            // Make the active menu match the menu config nodes (without 'children' inbetween)
            ->_setActiveMenu('sales/maduranga_wall_visualizer')
            ->_title($this->__('Sales'))->_title($this->__('Visualizer'))
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Visualizer'), $this->__('Visualizer'));

        return $this;
    }

    /**
     * Check currently called action by permissions for current user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/maduranga_wall_visualizer');
    }
}