<?php

class Alliance_JulienReviews_WriteController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function previewAction()
    {
        $postData = $this->getRequest()->getPost();
        if ($postData) {
            $this->loadLayout();
            $this->renderLayout();
        }
    }
}
