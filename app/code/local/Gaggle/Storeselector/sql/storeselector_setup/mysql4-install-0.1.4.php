<?php
$installer = $this;
$connection = $installer->getConnection();
$installer->startSetup();
$installer->getConnection()
->addColumn($installer->getTable('core/store'),
		'location',
		array(
				'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
				'nullable' => true,
				'default' => null,
				'comment' => 'location'
		)
);

$installer->endSetup();
