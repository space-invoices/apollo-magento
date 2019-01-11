<?php

namespace Studio404\Apollo\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
  /**
   * Upgrades DB schema for a module
   *
   * @param SchemaSetupInterface $setup
   * @param ModuleContextInterface $context
   * @return void
   */
  public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
  {
    $setup->startSetup();

    $quote = 'quote';
    $orderTable = 'sales_order';

    // $setup->getConnection()
    //   ->addColumn(
    //     $setup->getTable($quote),
    //     'estimate_sent',
    //     [
    //       'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
    //       'default' => 0,
    //       'comment' =>'Estimate Sent'
    //     ]
    //   );

    // $setup->getConnection()
    // ->addColumn(
    //   $setup->getTable($quote),
    //   'invoice_sent',
    //   [
    //     'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
    //     'default' => 0,
    //     'comment' =>'Invoice Sent'
    //   ]
    // );

    // $setup->getConnection()
    // ->addColumn(
    //   $setup->getTable($quote),
    //   'invoice_number',
    //   [
    //     'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
    //     'length' => 255,
    //     'comment' =>'Invoice Number'
    //   ]
    // );

    // $setup->getConnection()
    // ->addColumn(
    //   $setup->getTable($quote),
    //   'si_invoice_id',
    //   [
    //     'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
    //     'length' => 255,
    //     'comment' =>'SI Invoice Id'
    //   ]
    // );
    //Order table
    $setup->getConnection()
      ->addColumn(
        $setup->getTable($orderTable),
        'apollo_estimate_sent',
        [
          'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
          'default' => 0,
          'comment' =>'Apollo Estimate Sent'
        ]
      );
    $setup->endSetup();

    $setup->getConnection()
      ->addColumn(
        $setup->getTable($orderTable),
        'apollo_invoice_sent',
        [
          'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
          'default' => 0,
          'comment' =>'Invoice Sent'
        ]
      );
    $setup->endSetup();

    $setup->getConnection()
    ->addColumn(
      $setup->getTable($orderTable),
      'apollo_invoice_number',
      [
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        'length' => 255,
        'comment' =>'Apollo Invoice Number'
      ]
    );

    $setup->getConnection()
    ->addColumn(
      $setup->getTable($orderTable),
      'apollo_invoice_id',
      [
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        'length' => 255,
        'comment' =>'Apollo Invoice Id'
      ]
    );

    $setup->getConnection()
    ->addColumn(
      $setup->getTable($orderTable),
      'apollo_estimate_number',
      [
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        'length' => 255,
        'comment' =>'Apollo Estimate Number'
      ]
    );

    $setup->getConnection()
    ->addColumn(
      $setup->getTable($orderTable),
      'apollo_estimate_id',
      [
        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        'length' => 255,
        'comment' =>'Apollo Estimate Id'
      ]
    );
  }
}