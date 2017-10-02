<?php
/**
 * Phoenix Cash on Delivery module for Magento 2
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @category   Mage
 * @package    Phoenix_CashOnDelivery
 * @copyright  Copyright (c) 2017 Phoenix Media GmbH (http://www.phoenix-media.eu)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Phoenix\CashOnDelivery\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Phoenix\CashOnDelivery\Api\Data\FeeInterface;
use Phoenix\CashOnDelivery\Api\Data\OrderDataInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $commonColumns = $this->_getCommonColumns();
        $orderColumns = $this->_getOrderColumns();

        foreach ($this->_getTables() as $table) {
            foreach ($commonColumns as $column => $comment) {
                $this->_addColumn($setup, $table, $column, $comment);
            }
        }

        foreach($orderColumns as $column => $comment) {
            $this->_addColumn($setup, 'sales_order', $column, $comment);
        }

        $setup->endSetup();
    }

    /**
     * @return string[]
     */
    private function _getTables()
    {
        return [
            'quote',
            'quote_address',
            'sales_order',
            'sales_invoice',
            'sales_creditmemo',
        ];
    }

    /**
     * @return string[]
     */
    private function _getCommonColumns()
    {
        return [
            FeeInterface::BASE_FEE => 'Base Cash on Delivery fee',
            FeeInterface::FEE => 'Cash on Delivery fee',
            FeeInterface::BASE_TAX_AMOUNT => 'Base Cash on Delivery tax amount',
            FeeInterface::TAX_AMOUNT => 'Cash on Delivery tax amount',
            FeeInterface::BASE_FEE_INCL_TAX => 'Base Cash on Delivery fee including tax',
            FeeInterface::FEE_INCL_TAX => 'Cash on Delivery fee including tax',
        ];
    }

    /**
     * @return string[]
     */
    private function _getOrderColumns()
    {
        return [
            OrderDataInterface::BASE_FEE_INVOICED => 'Base invoiced Cash on Delivery fee',
            OrderDataInterface::BASE_FEE_REFUNDED => 'Base refunded Cash on Delivery fee',
            OrderDataInterface::BASE_FEE_CANCELED => 'Base canceled Cash on Delivery fee',
            OrderDataInterface::FEE_INVOICED => 'Invoiced Cash on Delivery fee',
            OrderDataInterface::FEE_REFUNDED => 'Refunded Cash on Delivery fee',
            OrderDataInterface::FEE_CANCELED => 'Canceled Cash on Delivery fee',
            OrderDataInterface::BASE_TAX_AMOUNT_INVOICED => 'Invoiced Base Cash on Delivery tax amount',
            OrderDataInterface::BASE_TAX_AMOUNT_REFUNDED => 'Refunded Base Cash on Delivery tax amount',
            OrderDataInterface::BASE_TAX_AMOUNT_CANCELED => 'Canceled Base Cash on Delivery tax amount',
            OrderDataInterface::TAX_AMOUNT_INVOICED => 'Invoiced Cash on Delivery tax amount',
            OrderDataInterface::TAX_AMOUNT_REFUNDED => 'Refunded Cash on Delivery tax amount',
            OrderDataInterface::TAX_AMOUNT_CANCELED => 'Canceled Cash on Delivery tax amount',
        ];
    }

    /**
     * @return array
     */
    private function _getColumnDefinition($comment)
    {
        return [
            'type' => Table::TYPE_DECIMAL,
            'length' => '12,4',
            'unsigned' => false,
            'nullable' => true,
            'comment' => $comment,
        ];
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param string $table
     * @param string $column
     * @return void
     */
    private function _addColumn(SchemaSetupInterface $setup, $table, $column, $comment)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable($table),
            $column,
            $this->_getColumnDefinition($comment)
        );
    }
}
