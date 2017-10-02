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

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Magento\Quote\Model\Quote as MagentoQuote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use Phoenix\CashOnDelivery\Helper\Data;

class Quote extends AbstractTotal
{
    /** @var Data $helper */
    protected $helper;

    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    public function collect(
        MagentoQuote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        if (!$this->helper->isActiveMethod($quote)) {
            $this->_clearValues($total);
            return $this;
        }

        $this->_addCodFee($quote, $total);

        return $this;
    }

    private function _addCodFee(MagentoQuote $quote, Total $total)
    {
        $baseCodFee = $this->_getBaseCodFee($quote);
        $codFee = $this->_getCodFee($quote, $baseCodFee);

        $total->setBaseTotalAmount('cashondelivery', $baseCodFee);
        $total->setTotalAmount('cashondelivery', $codFee);

        $this->_setTotalCodFees($total, $baseCodFee, $codFee);

    }

    protected function _clearValues(Total $total)
    {
        $total->setTotalAmount('cashondelivery', 0);
        $total->setBaseTotalAmount('cashondelivery', 0);
        $total->setBaseCodFee(0);
        $total->setCodFee(0);
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, Total $total)
    {
        return [
            'code' => 'cashondelivery',
            'title' => __('Cash on Delivery fee (Excl. Tax)'),
            'value' => $quote->getCodFee(),
            'value_incl_tax' => $quote->getCodFeeInclTax(),
            'value_excl_tax' => $quote->getCodFee(),
        ];
    }

    public function getLabel()
    {
        return __('Cash on Delivery fee');
    }

    private function _getBaseCodFee(MagentoQuote $quote)
    {
        $shippingOrigin = $this->helper->getShippingOriginCountry();
        $shippingCountry = $quote->getShippingAddress()->getCountry();
        $total = $quote->getBaseSubtotal();

        $baseCodFee = $shippingCountry === $shippingOrigin
            ? $this->helper->getInlandCodFee($total)
            : $this->helper->getForeignCodFee($total);

        return $baseCodFee;
    }

    private function _getCodFee(MagentoQuote $quote, $baseFee = null)
    {
        if ($baseFee === null) {
            $baseFee = $this->_getBaseCodFee($quote);
        }

        $rate = $quote->getBaseToQuoteRate();

        $codFee = $this->helper->roundPrice($baseFee * $rate);

        return $codFee;
    }

    private function _setTotalCodFees(Total $total, $basePrice, $price)
    {
        $pricesIncludeTax = $this->helper->codFeeIncludesTax();

        if ($pricesIncludeTax) {
            $total->setBaseCodFeeInclTax($basePrice);
            $total->setCodFeeInclTax($price);
        } else {
            $total->setBaseCodFee($basePrice);
            $total->setCodFee($price);
        }
    }
}
