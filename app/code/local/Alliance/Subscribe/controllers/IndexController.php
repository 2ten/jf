<?php
include Mage::getModuleDir('controllers', 'Alliance_Subscribe') . '/MCAPI.class.php';

class Alliance_Subscribe_IndexController extends Mage_Core_Controller_Front_Action
{
	const MCAPI_ID = "9b36190f5eae860ab41c6ac37abe7d04-us8";
	 const LIST_ID  = "73255d473e";
    public function indexAction()
    {	 
		$postinfo = Mage::app()->getRequest()->getParam('form_key');
		$formkey = Mage::getSingleton('core/session')->getFormKey();
		

			$email = Mage::app()->getRequest()->getParam('email');
			$firstname = Mage::app()->getRequest()->getParam('firstname');
			$lastname = Mage::app()->getRequest()->getParam('lastname');
			$api = new MCAPI(self::MCAPI_ID);
			$merge_vars = array( 'FNAME' => $firstname,'LNAME' => $lastname );
			$retval = $api->listSubscribe( self::LIST_ID, $email, $merge_vars, $email_type = 'html', $double_optin = false, $send_welcome = true );
			if ($api->errorCode){
				if ($api->errorCode == 214) {
					echo '<span class="error">You have already subscribed.</span>';
				} else {
					echo '<span class="error">' .$api->errorMessage . '</span>';
				}
			} else {
				echo '<span class="success">Thanks for signing up!</span>';
			}
		
		return true;
	}
}