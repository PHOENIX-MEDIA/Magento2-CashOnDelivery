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

namespace Phoenix\CashOnDelivery\Observer\Quote\Model\QuoteManagement;

use Magento\Framework\DataObject\Copy;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class AddToOrder implements ObserverInterface
{
    /**
     * @var Copy
     */
    protected $_copyService;

    public function __construct(
        Copy $copyService
    ) {
        $this->_copyService = $copyService;
    }

    public function execute(Observer $observer)
    {
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getQuote();

        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getOrder();

        $this->_copyService->copyFieldsetToTarget(
            'sales_convert_quote',
            'to_order',
            $quote,
            $order
        );

        return $this;
    }
}
