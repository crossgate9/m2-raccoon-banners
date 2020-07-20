<?php

namespace Raccoon\Banner\Model\ResourceModel\Banner;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection {

    protected function _construct() {
        $this->_init('Raccoon\Banner\Model\Banner', 'Raccoon\Banner\Model\ResourceModel\Banner');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

    public function addStoreFilter($storeIds, $withAdmin = true) {
        if (! $this->getFlag('store_filter')) {
            if ($withAdmin) {
                $storeIds = [0, $storeIds];
            }

            $this->getSelect()->where(
                'store_id IN (?)',
                $storeIds
            );

            $this->setFlag('store_filter', true);
        }
        return $this;
    }

}
?>