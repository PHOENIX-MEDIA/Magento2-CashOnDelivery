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

namespace Phoenix\CashOnDelivery\Block;

use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order;
use Magento\Sales\Helper\Admin;
use Phoenix\CashOnDelivery\Model\Config;

class Info extends \Magento\Payment\Block\Info
{
    /**
     * Path to template file in theme.
     *
     * @var string $_template
    */
    protected $_template = 'info.phtml';

    /**
     * Admin helper
     *
     * @var Admin
     */
    protected $adminHelper;

    /**
     * @var Config $config
     */
    private $config;

    public function __construct(
        Template\Context $context,
        Admin $adminHelper,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->adminHelper = $adminHelper;
        $this->config = $config;
    }

    /**
     * @return float
     */
    public function getFee()
    {
        /** @var Order $order */
        $order = $this->getInfo()->getOrder();

        $baseFee = $order->getBaseCodFee();
        $fee = $order->getCodFee();

        $formattedPrice = $this->displayPrice($baseFee, $fee);
        return $formattedPrice;
    }

    /**
     * @return float
     */
    public function getFeeInclTax()
    {
        /** @var Order $order */
        $order = $this->getInfo()->getOrder();

        $baseFee = $order->getBaseCodFeeInclTax();
        $fee = $order->getCodFeeInclTax();

        $formattedPrice = $this->displayPrice($baseFee, $fee);
        return $formattedPrice;
    }

    public function getTitle()
    {
        return $this->config->getTitle();
    }

    private function displayPrice($basePrice, $price)
    {
        $order = $this->getInfo()->getOrder();

        return $this->adminHelper->displayPrices(
            $order,
            $basePrice,
            $price,
            false,
            ' '
        );
    }
}
