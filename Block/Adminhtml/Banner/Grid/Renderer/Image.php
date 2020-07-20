<?php 

namespace Raccoon\Banner\Block\Adminhtml\Banner\Grid\Renderer;

use \Magento\Framework\App\ObjectManager;

class Image extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer {
    protected $_storeManager;

    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,      
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_storeManager = $storeManager;        
    }

    public function getMediaUrl($_path) {
        return ObjectManager::getInstance()->get('Magento\Store\Model\StoreManagerInterface')
                                           ->getStore()
                                           ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $_path;
    }

    public function render(\Magento\Framework\DataObject $row) {
        return sprintf('<img src="%s" width="150">', $this->getMediaUrl($this->_getValue($row)));
    }
}