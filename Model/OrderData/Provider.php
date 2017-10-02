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

namespace Phoenix\CashOnDelivery\Model\OrderData;

use Magento\Sales\Model\Order;
use Phoenix\CashOnDelivery\Api\Data\OrderDataInterface;
use Phoenix\CashOnDelivery\Api\OrderDataProviderInterface;

class Provider implements OrderDataProviderInterface
{
    /**
     * @var \Phoenix\CashOnDelivery\Model\OrderDataFactory $_orderDataFactory
     */
    private $_orderDataFactory;

    public function __construct(
        \Phoenix\CashOnDelivery\Model\OrderDataFactory $orderDataFactory
    ) {
        $this->_orderDataFactory = $orderDataFactory;
    }

    public function getOrderData(Order $order)
    {
        /** @var OrderDataInterface $orderData */
        $orderData = $this->_orderDataFactory->create();

        $orderData->setBaseFee($order->getData(OrderDataInterface::BASE_FEE));
        $orderData->setFee($order->getData(OrderDataInterface::FEE));
        $orderData->setBaseTaxAmount($order->getData(OrderDataInterface::BASE_TAX_AMOUNT));
        $orderData->setTaxAmount($order->getData(OrderDataInterface::TAX_AMOUNT));
        $orderData->setBaseFeeInclTax($order->getData(OrderDataInterface::BASE_FEE_INCL_TAX));
        $orderData->setFeeInclTax($order->getData(OrderDataInterface::FEE_INCL_TAX));

        $orderData->setBaseFeeInvoiced($order->getData(OrderDataInterface::BASE_FEE_INVOICED));
        $orderData->setFeeInvoiced($order->getData(OrderDataInterface::FEE_INVOICED));
        $orderData->setBaseTaxAmountInvoiced($order->getData(OrderDataInterface::BASE_TAX_AMOUNT_INVOICED));
        $orderData->setTaxAmountInvoiced($order->getData(OrderDataInterface::TAX_AMOUNT_INVOICED));

        $orderData->setBaseFeeRefunded($order->getData(OrderDataInterface::BASE_FEE_REFUNDED));
        $orderData->setFeeRefunded($order->getData(OrderDataInterface::FEE_REFUNDED));
        $orderData->setBaseTaxAmountRefunded($order->getData(OrderDataInterface::BASE_TAX_AMOUNT_REFUNDED));
        $orderData->setTaxAmountRefunded($order->getData(OrderDataInterface::TAX_AMOUNT_REFUNDED));

        $orderData->setBaseFeeCanceled($order->getData(OrderDataInterface::BASE_FEE_CANCELED));
        $orderData->setFeeCanceled($order->getData(OrderDataInterface::FEE_CANCELED));
        $orderData->setBaseTaxAmountCanceled($order->getData(OrderDataInterface::BASE_TAX_AMOUNT_CANCELED));
        $orderData->setTaxAmountCanceled($order->getData(OrderDataInterface::TAX_AMOUNT_CANCELED));

        return $orderData;
    }
}
