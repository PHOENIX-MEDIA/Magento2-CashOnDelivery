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

namespace Phoenix\CashOnDelivery\Plugin\Sales;

use Magento\Framework\Api\SearchResultsInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Phoenix\CashOnDelivery\Api\OrderDataProviderInterface;

class OrderRepositoryPlugin
{
    /**
     * @var OrderExtensionFactory
     */
    private $_orderExtensionFactory;

    /**
     * @var OrderDataProviderInterface
     */
    private $_orderDataProvider;

    /**
     * OrderRepositoryPlugin constructor.
     * @param OrderExtensionFactory $orderExtensionFactory
     * @param OrderDataProviderInterface $orderDataProvider
     */
    public function __construct(
        OrderExtensionFactory $orderExtensionFactory,
        OrderDataProviderInterface $orderDataProvider
    ) {
        $this->_orderExtensionFactory = $orderExtensionFactory;
        $this->_orderDataProvider = $orderDataProvider;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param Order $entity
     * @return Order
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        Order $entity
    ) {
        $this->_addCodFeeToOrder($entity);

        return $entity;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param SearchResultsInterface $searchResult
     * @return SearchResultsInterface
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        SearchResultsInterface $searchResult
    ) {
        /** @var Order $order */
        foreach ($searchResult->getItems() as $order) {
            $this->_addCodFeeToOrder($order);
        }

        return $searchResult;
    }

    /**
     * @param Order $order
     * @return $this
     */
    private function _addCodFeeToOrder(Order $order)
    {
        $extensionAttributes = $order->getExtensionAttributes();

        if (empty($extensionAttributes)) {
            $extensionAttributes = $this->_orderExtensionFactory->create();
        }

        $codData = $this->_orderDataProvider->getOrderData($order);
        $extensionAttributes->setData(OrderDataProviderInterface::EXTENSION_ATTRIBUTE_KEY, $codData);
        $order->setExtensionAttributes($extensionAttributes);

        return $this;
    }
}
