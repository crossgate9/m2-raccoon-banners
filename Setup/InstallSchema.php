<?php
namespace Raccoon\Banner\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface {
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0) {
          $installer->run("CREATE TABLE `raccoon_banner` (
                                        `id` int(11) NOT NULL,
                                        `is_active` tinyint(1) NOT NULL,
                                        `type` int(11) NOT NULL DEFAULT '0' COMMENT '0 for image, 1 for video',
                                        `store_id` int(11) NOT NULL,
                                        `desktop_media` varchar(255) NOT NULL,
                                        `mobile_media` varchar(255) NOT NULL,
                                        `headline` varchar(255) NOT NULL,
                                        `linktext` varchar(255) NOT NULL,
                                        `link` varchar(255) NOT NULL,
                                        `position` int(11) NOT NULL COMMENT '0 for main banner',
                                        `from_date` datetime NOT NULL,
                                        `to_date` datetime NOT NULL
                                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8"
          );

          $installer->run('ALTER TABLE `raccoon_banner`
                                  ADD PRIMARY KEY (`id`),
                                  ADD KEY `raccoon_banner_from_date` (`from_date`),
                                  ADD KEY `raccoon_banner_to_date` (`to_date`),
                                  ADD KEY `raccoon_banner_store_id` (`store_id`),
                                  ADD KEY `raccoon_banner_position` (`position`) USING BTREE,
                                  ADD KEY `raccoon_banner_is_active` (`is_active`);');

          $installer->run('ALTER TABLE `raccoon_banner` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT');
        }

        $installer->endSetup();
    }
}