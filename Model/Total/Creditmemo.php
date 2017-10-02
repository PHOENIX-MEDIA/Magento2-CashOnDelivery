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

namespace Phoenix\CashOnDelivery\Model\Total;

use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Magento\Sales\Model\Order\Creditmemo as MagentoCreditmemo;
use Magento\Framework\Exception\LocalizedException;
use Phoenix\CashOnDelivery\Helper\Data;
use Phoenix\CashOnDelivery\Helper\Request;
use Phoenix\CashOnDelivery\Model\Config;

class Creditmemo extends AbstractTotal
{
    /**
     * @var Request $requestHelper
     */
    private $requestHelper;

    /**
     * @var Data $helper
     */
    private $helper;

    /**
     * @var Config $config
     */
    private $config;

    public function __construct(
        Request $requestHelper,
        Data $helper,
        Config $config,
        array $data = []
    ) {
        parent::__construct($data);

        $this->requestHelper = $requestHelper;
        $this->helper = $helper;
        $this->config = $config;
    }

    /**
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @return self
     */
    public function collect(MagentoCreditmemo $creditmemo)
    {
        $this->setCustomRefund($creditmemo);

        $isPartialShippingRefunded = $this->isPartialShippingRefunded($creditmemo);
        if ($isPartialShippingRefunded) {
            $totalBaseCodFee = $creditmemo->getBaseCodFeeInclTax();
            $totalCodFee = $creditmemo->getCodFeeInclTax();
        } else {
            $totalBaseCodFee = $creditmemo->getBaseCodFee();
            $totalCodFee = $creditmemo->getCodFee();
        }
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $totalBaseCodFee);
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $totalCodFee);

        return $this;
    }

    private function setCustomRefund(MagentoCreditmemo $creditmemo)
    {
        $allowedRefund = $this->getAllowedRefund($creditmemo);
        $baseCustomFee = $this->requestHelper->includesCustomFee()
            ? $this->requestHelper->getCustomCashOnDeliveryFee()
            : $allowedRefund;

        $rate = $creditmemo->getBaseToOrderRate();
        $customFee = $this->helper->roundPrice($baseCustomFee * $rate);

        $taxRate = $creditmemo->getBaseCodFeeInclTax() / $creditmemo->getBaseCodFee();

        if ($this->config->codFeeIncludesTax()) {
            $baseCodFeeInclTax = $baseCustomFee;
            $codFeeInclTax = $customFee;

            $baseCodFee = $this->helper->roundPrice($baseCodFeeInclTax / $taxRate);
            $codFee = $this->helper->roundPrice($codFeeInclTax / $taxRate);
        } else {
            $baseCodFee = $baseCustomFee;
            $codFee = $customFee;

            $baseCodFeeInclTax = $this->helper->roundPrice($baseCodFee * $taxRate);
            $codFeeInclTax = $this->helper->roundPrice($codFee * $taxRate);
        }

        $baseTax = $baseCodFeeInclTax - $baseCodFee;
        $tax = $codFeeInclTax - $codFee;

        $this->checkRefundAmount($creditmemo, $baseCodFee);

        $creditmemo->setBaseCodFee($baseCodFee);
        $creditmemo->setCodFee($codFee);
        $creditmemo->setBaseCodFeeInclTax($baseCodFeeInclTax);
        $creditmemo->setCodFeeInclTax($codFeeInclTax);
        $creditmemo->setBaseCodTaxAmount($baseTax);
        $creditmemo->setCodTaxAmount($tax);

        $creditmemo->setBaseTaxAmount($creditmemo->getBaseTaxAmount() + $baseTax);
        $creditmemo->setTaxAmount($creditmemo->getTaxAmount() + $tax);
    }

    private function isPartialShippingRefunded(MagentoCreditmemo $creditmemo)
    {
        $part = $creditmemo->getShippingAmount() / $creditmemo->getOrder()->getShippingAmount();

        return $part < 1 && $creditmemo->getOrder()->getShippingTaxAmount() > 0;
    }

    private function getAllowedRefund(MagentoCreditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        return (float)$order->getBaseCodFeeInvoiced() - $order->getBaseCodFeeRefunded();
    }

    private function checkRefundAmount(MagentoCreditmemo $creditmemo, $refund)
    {
        $allowedRefund = $this->getAllowedRefund($creditmemo);

        if ($allowedRefund + 0.0001 < $refund) {
            $message = __(
                'Maximum Cash on Delivery amount allowed to refund is: %1',
                $creditmemo->getOrder()->getBaseCurrency()->format($allowedRefund, [], false)
            );

            throw new LocalizedException($message);
        }

        return true;
    }
}
