<?php

class Alliance_JulienReviews_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getConfigEnableCustomerNotification()
    {
        return Mage::getStoreConfig('alliance_julienreviews/customer_notification_settings/enable_customer_notification');
    }

    public function getConfigSenderEmailIdentity()
    {
        return Mage::getStoreConfig('alliance_julienreviews/customer_notification_settings/sender_email_identity');
    }

    public function getConfigEmailTemplate()
    {
        return Mage::getStoreConfig('alliance_julienreviews/customer_notification_settings/email_template');
    }

    public function updateTopContributors()
    {
        $contributors = Mage::getResourceModel('alliance_julienreviews/contributor_collection');
        $contributors->setOrder('reviews_count', 'DESC')
            ->setCurPage(1)
            ->setPageSize(100);
        $i = 1;
        foreach ($contributors as $contributor) {
            $topcontributor = Mage::getModel('alliance_julienreviews/topcontributor');
            $topcontributor->loadByRank($i);
            if (!$topcontributor->getId()) $topcontributor->setRank($i);
            $topcontributor->setCustomerId($contributor->getCustomerId());
            $topcontributor->save();
            $i++;
        }
    }

    public function getStarAverage($product_id)
    {
        $child_product_ids = Mage::getModel('catalog/product_type_configurable')->getChildrenIds($product_id);
        $accepted_product_ids = array_keys($child_product_ids[0]);
        $accepted_product_ids[] = $product_id;
        $collection = Mage::getResourceModel('alliance_julienreviews/review_collection');
        $collection->addFieldToFilter('product_id', array(
            'in' => $accepted_product_ids,
        ));
        $collection->addFieldToFilter('status', array(
            'eq' => 'Approved',
        ));
        $collection->setOrder('date', 'DESC');

        $number_reviews = 0;
        $total_stars = 0;
        $customers = array();
        foreach ($collection as $review) {
            if (!in_array($review->getCustomerId(), $customers)) {
                $customers[] = $review->getCustomerId();
                $number_reviews++;
                $total_stars += $review->getStarRating();
            }
        }
        if ($number_reviews) {
            return round($total_stars / $number_reviews, 1, PHP_ROUND_HALF_UP);
        }
        else {
            return 0;
        }
    }
}
