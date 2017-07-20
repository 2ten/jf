<?php

class Alliance_Contact_IndexController extends Mage_Core_Controller_Front_Action
{

    public function indexAction()
    {

        $this->loadLayout();
        $this->renderLayout();
    }

    public function successAction()
    {

        $this->loadLayout();
        $this->renderLayout();
    }

    public function submitAction()
    {
        $post = $this->getRequest()->getPost();
        
        if ($post) {
            $_helper = Mage::helper('contact');

            $translate = Mage::getSingleton('core/translate');
            $translate->setTranslateInline(FALSE);

            try {
                $post['ctlocation'] = $_helper->getContactLabel($post['locations']);
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = FALSE;

                if (!Zend_Validate::is(trim($post['ctfirstname']), 'NotEmpty')) {
                    $error = TRUE;
                }

                if (!Zend_Validate::is(trim($post['ctlastname']), 'NotEmpty')) {
                    $error = TRUE;
                }

                if (!Zend_Validate::is(trim($post['ctemail']), 'NotEmpty')) {
                    $error = TRUE;
                }

                if (!Zend_Validate::is(trim($post['ctmessages']), 'NotEmpty')) {
                    $error = TRUE;
                }

                if (!Zend_Validate::is(trim($post['ctemail']), 'EmailAddress')) {
                    $error = TRUE;
                }

                if ($error) {
                    throw new Exception();
                }

                $mailTemplate = Mage::getModel('core/email_template');
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($post['ctemail'])
                    ->sendTransactional(
                            $_helper->getEmailTemplate($post['locations']),
                            $_helper->getSenderEmailIdentity($post['locations']),
                            $_helper->getEmailRecipient($post['locations']), null, array('data' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(TRUE);

                $this->_redirect('*/*/success');

                return;
            }
            catch (Exception $e) {
                $translate->setTranslateInline(TRUE);

                Mage::getSingleton('customer/session')->addError(
                        Mage::helper('appointment')
                                ->__('Sorry, but we were unable to submit your request. Please try again later.')
                );

                $this->_redirect('*/*/');

                return;
            }
        }
        else {
            $this->_redirect('*/*/');
        }
    }

}
