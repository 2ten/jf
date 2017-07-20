<?php

class Alliance_Appointment_IndexController extends Mage_Core_Controller_Front_Action
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
        if ( $post )
        {
            $translate = Mage::getSingleton( 'core/translate' );
            $translate->setTranslateInline( FALSE );

            try
            {

                $error = FALSE;

                if ( ! Zend_Validate::is( trim( $post['appt-salon'] ) , 'NotEmpty' ))
                {
                    $error = TRUE;
                }

                if ( ! Zend_Validate::is( trim( $post['appt-fullname'] ) , 'NotEmpty' ))
                {
                    $error = TRUE;
                }

                if ( ! Zend_Validate::is( trim( $post['appt-email'] ) , 'NotEmpty' ))
                {
                    $error = TRUE;
                }

                if ( ! Zend_Validate::is( trim( $post['appt-phone'] ) , 'NotEmpty' ))
                {
                    $error = TRUE;
                }

                if ( ! Zend_Validate::is( trim( $post['appt-date'] ) , 'NotEmpty' ))
                {
                    $error = TRUE;
                }

                if ( ! Zend_Validate::is( trim( $post['appt-time'] ) , 'NotEmpty' ))
                {
                    $error = TRUE;
                }

                if ( $error )
                {
                    throw new Exception();
                }

                $post['appt-services-aesthetics'] = $post['appt-services']['aesthetics'];
                $post['appt-services-haircolor'] = $post['appt-services']['haircolor'];
                $post['appt-services-makeup'] = $post['appt-services']['makeup'];
                $post['appt-services-nails'] = $post['appt-services']['nails'];
                $post['appt-services-eyelashes'] = $post['appt-services']['eyelashes'];
                $post['appt-services-haircut'] = $post['appt-services']['haircut'];
                $post['appt-services-other'] = $post['appt-services']['other'];

                $postObject = new Varien_Object();
                $postObject->setData( $post );

                $_helper = Mage::helper( 'appointment' );

                $mailTemplate = Mage::getModel( 'core/email_template' );
                $mailTemplate->setDesignConfig( array( 'area' => 'frontend' ))
                    ->setReplyTo( $post['appt-email'] )
                    ->sendTransactional(
                        $_helper->getEmailTemplate(),
                        $_helper->getEmailSender(),
                        $_helper->getEmailRecipient($post['appt-salon']),
                        null,
                        array( 'data' => $postObject )
                    );

                if ( ! $mailTemplate->getSentSuccess() )
                {
                    throw new Exception();
                }

                $translate->setTranslateInline( TRUE );

                $this->_redirect( '*/*/success' );

                return;
            }
            catch ( Exception $e )
            {
                $translate->setTranslateInline( TRUE );

                Mage::getSingleton( 'customer/session' )->addError(
                    Mage::helper( 'appointment' )
                        ->__( 'Sorry, but we were unable to submit your request. Please try again later.' )
                );

                $this->_redirect( '*/*/' );

                return;
            }

        }
        else
        {
            $this->_redirect( '*/*/' );
        }
    }

}