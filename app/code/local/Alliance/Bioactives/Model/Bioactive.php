<?php

/**
 * Class Alliance_Bioactives_Model_Bioactive
 */
class Alliance_Bioactives_Model_Bioactive extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('alliance_bioactives/bioactive');
    }

    /**
     * Returns the image URL of the currently loaded Bioactive
     *
     * @return string
     */
    public function getImageUrl()
    {
        $media_base = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $image_path = $this->getImage();
        $image_url = $media_base . $image_path;
        return $image_url;
    }
}