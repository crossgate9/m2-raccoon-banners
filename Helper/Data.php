<?php

namespace Raccoon\Banner\Helper;

use \Magento\Framework\App\ObjectManager;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper {
    public function getMediaUrl($_path) {
        return ObjectManager::getInstance()->get('Magento\Store\Model\StoreManagerInterface')
                                           ->getStore()
                                           ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $_path;
    }

    public function getBanners() {
        $_object_manager = \Magento\Framework\App\ObjectManager::getInstance();
        $_store_manager = $_object_manager->get('Magento\Store\Model\StoreManagerInterface');
        $_store_id = $_store_manager->getStore()->getId();

        $_now = date('Y-m-d H:i:s');
        
        $_banners_collection = $_object_manager->create('Raccoon\Banner\Model\ResourceModel\Banner\Collection');
        $_banners = $_banners_collection->addFieldToFilter('is_active', 1)
                                        ->addFieldToFilter('store_id', $_store_id)
                                        ->addFieldToFilter('from_date', ['lteq' => $_now])
                                        ->addFieldToFilter('to_date', ['gteq' => $_now])
                                        ->setOrder('position', 'asc')
                                        ->load();

        return $_banners;
    }
}