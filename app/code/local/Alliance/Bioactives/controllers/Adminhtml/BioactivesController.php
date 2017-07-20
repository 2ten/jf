<?php

/**
 * Class Alliance_Bioactives_Adminhtml_BioactivesController
 */
class Alliance_Bioactives_Adminhtml_BioactivesController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Handles route 'adminhtml/bioactives/index'
     *
     * Displays key bioactives grid, with options to edit key bioactives or add new key bioactives
     */
    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }

    /**
     * Initializes layout
     *
     * @return $this
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('cms/alliance_bioactives')
            ->_title($this->__('CMS'))->_title($this->__('Key Bioactives'))->_title($this->__('Manage Key Bioactives'))
            ->_addBreadcrumb($this->__('CMS'), $this->__('CMS'))
            ->_addBreadcrumb($this->__('Key Bioactives'), $this->__('Key Bioactives'));

        return $this;
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/alliance_bioactives');
    }

    /**
     * Handles route 'adminhtml/bioactives/message'
     */
    public function messageAction()
    {
        $data = Mage::getModel('alliance_bioactives/bioactive')->load($this->getRequest()->getParam('id'));
        echo $data->getContent();
    }

    /**
     * Handles route 'adminhtml/bioactives/save'
     *
     * Used for saving key bioactives
     */
    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            if (isset($_FILES['image']['name']) and (file_exists($_FILES['image']['tmp_name']))) {
                try {
                    $file_name_info = pathinfo($_FILES['image']['name']);
                    $file_extension = $file_name_info['extension'];
                    $filename_hash = md5(str_shuffle($_FILES['image']['name'].rand(1,1000).time()));
                    $final_filename = $filename_hash . '.' . $file_extension;

                    $uploader = new Varien_File_Uploader('image');
                    $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
                    $uploader->setAllowRenameFiles(false);
                    $uploader->setFilesDispersion(false);

                    $path = Mage::getBaseDir('media') . DS . 'alliance' . DS . 'bioactives';

                    $uploader->save($path, $final_filename);

                    $postData['image'] = 'alliance' . DS .'bioactives' . DS . $final_filename;
                }
                catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')
                        ->addError($this->__("An error occurred while saving the image for this Key Bioactive. Please make sure the file you
                        selected was an image of file type jpg, jpeg, gif, or png, and wasn't too large in filesize."));
                }
            }
            else {
                if(isset($postData['image']['delete']) && $postData['image']['delete'] == 1)
                    $postData['image'] = '';
                else
                    unset($postData['image']);
            }

            $model = Mage::getSingleton('alliance_bioactives/bioactive');
            $model->setData($postData);

            try {
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('The bioactive has been saved.'));
                $this->_redirect('*/*/');

                return;
            }
            catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')
                    ->addError($this->__('An error occurred while saving this bioactive.'));
            }

            Mage::getSingleton('adminhtml/session')->setBioactiveData($postData);
            $this->_redirectReferer();
        }
    }

    /**
     * Handles route 'adminhtml/bioactives/edit'
     *
     * Used for editing key bioactives
     */
    public function editAction()
    {
        $this->_initAction();

        $id  = $this->getRequest()->getParam('id');
        $model = Mage::getModel('alliance_bioactives/bioactive');

        if ($id) {
            $model->load($id);

            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError($this->__('This bioactive no longer exists.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getTitle() : $this->__('New Bioactive'));

        $data = Mage::getSingleton('adminhtml/session')->setBioactiveData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('alliance_bioactives', $model);

        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Edit Bioactive') : $this->__('New Bioactive'),
                $id ? $this->__('Edit Bioactive') : $this->__('New Bioactive'))
            ->_addContent($this->getLayout()->createBlock('alliance_bioactives/adminhtml_bioactive_edit')
                ->setData('action', $this->getUrl('*/*/save')))
            ->renderLayout();
    }

    /**
     * Handles route 'adminhtml/bioactives/new'
     *
     * Used for creating new key bioactives
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Handles route 'adminhtml/bioactives/delete'
     *
     * Used for deleting key bioactives
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $model = Mage::getModel('alliance_bioactives/bioactive');
            $model->load($id);
            try {
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('alliance_bioactives')->__('The bioactive has been deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('alliance_bioactives')->__('An error occurred while deleting this bioactive.'));
        $this->_redirect('*/*/');
    }
}