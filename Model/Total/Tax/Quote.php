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

namespace Phoenix\CashOnDelivery\Model\Total\Tax;

use Magento\Tax\Api\Data\TaxDetailsInterface;
use Magento\Tax\Model\Sales\Total\Quote\CommonTaxCollector;
use Magento\Quote\Model\Quote as MagentoQuote;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Customer\Api\Data\AddressInterfaceFactory as CustomerAddressFactory;
use Magento\Customer\Api\Data\RegionInterfaceFactory as CustomerAddressRegionFactory;
use Magento\Tax\Api\Data\TaxClassKeyInterface;
use Magento\Tax\Api\Data\TaxDetailsItemInterface;
use Phoenix\CashOnDelivery\Helper\Data;

class Quote extends CommonTaxCollector
{
    const ITEM_TYPE_COD_FEE = 'cod_fee';
    const ITEM_CODE_COD_FEE = 'cod_fee';

    /**
     * @var Data $helper
     */
    protected $helper;

    /**
     * Quote total collector constructor.
     *
     * @param \Magento\Tax\Model\Config $taxConfig
     * @param \Magento\Tax\Api\TaxCalculationInterface $taxCalculationService
     * @param \Magento\Tax\Api\Data\QuoteDetailsInterfaceFactory $quoteDetailsDataObjectFactory
     * @param \Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory $quoteDetailsItemDataObjectFactory
     * @param \Magento\Tax\Api\Data\TaxClassKeyInterfaceFactory $taxClassKeyDataObjectFactory
     * @param CustomerAddressFactory $customerAddressFactory
     * @param CustomerAddressRegionFactory $customerAddressRegionFactory
     * @param Data $config
     */
    public function __construct(
        \Magento\Tax\Model\Config $taxConfig,
        \Magento\Tax\Api\TaxCalculationInterface $taxCalculationService,
        \Magento\Tax\Api\Data\QuoteDetailsInterfaceFactory $quoteDetailsDataObjectFactory,
        \Magento\Tax\Api\Data\QuoteDetailsItemInterfaceFactory $quoteDetailsItemDataObjectFactory,
        \Magento\Tax\Api\Data\TaxClassKeyInterfaceFactory $taxClassKeyDataObjectFactory,
        CustomerAddressFactory $customerAddressFactory,
        CustomerAddressRegionFactory $customerAddressRegionFactory,
        Data $helper
    ) {
        parent::__construct(
            $taxConfig,
            $taxCalculationService,
            $quoteDetailsDataObjectFactory,
            $quoteDetailsItemDataObjectFactory,
            $taxClassKeyDataObjectFactory,
            $customerAddressFactory,
            $customerAddressRegionFactory
        );

        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function collect(
        MagentoQuote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $this->_setTotalTaxCalculationAmounts($total);

        $taxDetails = $this->_getTaxDetails($quote, $total, $shippingAssignment);

        if (!$this->helper->isActiveMethod($quote)) {
            $this->_clearValues($quote, $total, $taxDetails);
            return $this;
        }

        $this->_addCodTax($quote, $total, $taxDetails['baseCodTaxDetails'], $taxDetails['codTaxDetails']);

        $this->_processAssociatedTaxables($quote, $total);

        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        return [
            'code' => 'cashondelivery_incl_tax',
            'title' => __('Cash on Delivery fee (Incl. Tax)'),
            'value' => $quote->getCodFeeInclTax(),
        ];
    }

    /**
     * @param MagentoQuote $quote
     * @param Total $total
     * @param TaxDetailsInterface[] $taxDetails
     */
    protected function _clearValues(MagentoQuote $quote, Total $total, array $taxDetails)
    {
        $this->_unsetTaxableInfo($quote);

        $total->setBaseTotalAmount('cashondelivery', 0);
        $total->setTotalAmount('cashondelivery', 0);

        if (!empty($taxDetails)) {
            /** @var TaxDetailsItemInterface $baseCodTaxDetails */
            $baseCodTaxDetails = $taxDetails['baseCodTaxDetails']->getItems()[self::ITEM_TYPE_COD_FEE];
            /** @var TaxDetailsItemInterface $codTaxDetails */
            $codTaxDetails = $taxDetails['codTaxDetails']->getItems()[self::ITEM_TYPE_COD_FEE];

            $total->addBaseTotalAmount('tax', $baseCodTaxDetails->getRowTax() * -1);
            $total->addTotalAmount('tax', $codTaxDetails->getRowTax() * -1);
        }

        $total->setBaseCodFee(0);
        $total->setCodFee(0);

        $total->setBaseCodTaxAmount(0);
        $total->setCodTaxAmount(0);

        $total->setBaseCodFeeInclTax(0);
        $total->setCodFeeInclTax(0);

        $quote->setBaseCodFee(0);
        $quote->setCodFee(0);

        $quote->setBaseCodTaxAmount(0);
        $quote->setCodTaxAmount(0);

        $quote->setBaseCodFeeInclTax(0);
        $quote->setCodFeeInclTax(0);
    }

    private function _getTaxDetails(MagentoQuote $quote, Total $total, ShippingAssignmentInterface $shippingAssignment)
    {
        $baseCodFeeDataObject = $this->_getCashOnDeliveryDataObject($shippingAssignment, $total, true);
        $codFeeDataObject = $this->_getCashOnDeliveryDataObject($shippingAssignment, $total, false);

        if ($baseCodFeeDataObject == null || $codFeeDataObject == null) {
            return [];
        }

        $storeId = $quote->getStoreId();

        $quoteDetails = $this->prepareQuoteDetails($shippingAssignment, [$codFeeDataObject]);
        $codTaxDetails = $this->taxCalculationService
            ->calculateTax($quoteDetails, $storeId);

        $baseQuoteDetails = $this->prepareQuoteDetails($shippingAssignment, [$baseCodFeeDataObject]);
        $baseCodTaxDetails = $this->taxCalculationService
            ->calculateTax($baseQuoteDetails, $storeId);

        return [
            'baseCodTaxDetails' => $baseCodTaxDetails,
            'codTaxDetails' => $codTaxDetails,
        ];
    }

    private function _addCodTax(MagentoQuote $quote, Total $total, TaxDetailsInterface $baseCodTaxDetails, TaxDetailsInterface $codTaxDetails)
    {
        $this->_processCodTaxInfo(
            $total,
            $quote,
            $baseCodTaxDetails->getItems()[self::ITEM_TYPE_COD_FEE],
            $codTaxDetails->getItems()[self::ITEM_TYPE_COD_FEE]
        );

        return $this;
    }

    private function _processAssociatedTaxables(MagentoQuote $quote, Total $total)
    {
        $address = $quote->getShippingAddress();

        $associatedTaxables = $address->getAssociatedTaxables() ?: [];
        $associatedTaxables[] = $this->_getTaxableInfo($total);

        $address->setAssociatedTaxables($associatedTaxables);
    }

    private function _getTaxableInfo(Total $total)
    {
        return [
            self::KEY_ASSOCIATED_TAXABLE_TYPE => 'cod_fee',
            self::KEY_ASSOCIATED_TAXABLE_CODE => 'cod_fee',
            self::KEY_ASSOCIATED_TAXABLE_BASE_UNIT_PRICE => $total->getBaseCodTaxCalculationAmount(),
            self::KEY_ASSOCIATED_TAXABLE_UNIT_PRICE => $total->getCodTaxCalculationAmount(),
            self::KEY_ASSOCIATED_TAXABLE_QUANTITY => 1,
            self::KEY_ASSOCIATED_TAXABLE_TAX_CLASS_ID => $this->helper->getTaxClassId(),
            self::KEY_ASSOCIATED_TAXABLE_PRICE_INCLUDES_TAX => $this->helper->codFeeIncludesTax(),
            self::KEY_ASSOCIATED_TAXABLE_ASSOCIATION_ITEM_CODE => CommonTaxCollector::ASSOCIATION_ITEM_CODE_FOR_QUOTE,
        ];
    }

    private function _unsetTaxableInfo(MagentoQuote $quote)
    {
        $address = $quote->getShippingAddress();
        $associatedTaxables = $address->getAssociatedTaxables() ?: [];
        foreach ($associatedTaxables as $key => $taxable) {
            if ($taxable[self::KEY_ASSOCIATED_TAXABLE_CODE] === 'cod_fee') {
                unset($associatedTaxables[$key]);
            }
        }
        $address->setAssociatedTaxables($associatedTaxables);
    }

    private function _getCashOnDeliveryDataObject(
        ShippingAssignmentInterface $shippingAssignment,
        Total $total,
        $useBaseCurrency
    ) {
        $store = $shippingAssignment->getShipping()->getAddress()->getQuote()->getStore();

        if ($total->getCodTaxCalculationAmount() !== null) {
            /** @var \Magento\Tax\Api\Data\QuoteDetailsItemInterface $itemDataObject */
            $itemDataObject = $this->quoteDetailsItemDataObjectFactory->create()
                ->setType(self::ITEM_TYPE_COD_FEE)
                ->setCode(self::ITEM_CODE_COD_FEE)
                ->setQuantity(1);
            if ($useBaseCurrency) {
                $itemDataObject->setUnitPrice($total->getBaseCodTaxCalculationAmount());
            } else {
                $itemDataObject->setUnitPrice($total->getCodTaxCalculationAmount());
            }
            $itemDataObject->setTaxClassKey(
                $this->taxClassKeyDataObjectFactory->create()
                    ->setType(TaxClassKeyInterface::TYPE_ID)
                    ->setValue($this->helper->getTaxClassId($store))
            );
            $itemDataObject->setIsTaxIncluded(
                $this->helper->codFeeIncludesTax($store)
            );
            return $itemDataObject;
        }

        return null;
    }

    /**
     * @param Total $total
     * @param MagentoQuote $quote
     * @param TaxDetailsItemInterface $baseCodTaxDetails
     * @param TaxDetailsItemInterface $codTaxDetails
     */
    private function _processCodTaxInfo(
        Total $total,
        MagentoQuote $quote,
        $baseCodTaxDetails,
        $codTaxDetails
    ) {
        $total->setBaseTotalAmount('cashondelivery', $baseCodTaxDetails->getRowTotal());
        $total->setTotalAmount('cashondelivery', $codTaxDetails->getRowTotal());

        $total->addBaseTotalAmount('tax', $baseCodTaxDetails->getRowTax());
        $total->addTotalAmount('tax', $codTaxDetails->getRowTax());

        $total->setBaseCodFee($baseCodTaxDetails->getRowTotal());
        $total->setCodFee($codTaxDetails->getRowTotal());

        $total->setBaseCodTaxAmount($baseCodTaxDetails->getRowTax());
        $total->setCodTaxAmount($codTaxDetails->getRowTax());

        $total->setBaseCodFeeInclTax($baseCodTaxDetails->getRowTotalInclTax());
        $total->setCodFeeInclTax($codTaxDetails->getRowTotalInclTax());

        $quote->setBaseCodFee($baseCodTaxDetails->getRowTotal());
        $quote->setCodFee($codTaxDetails->getRowTotal());

        $quote->setBaseCodTaxAmount($baseCodTaxDetails->getRowTax());
        $quote->setCodTaxAmount($codTaxDetails->getRowTax());

        $quote->setBaseCodFeeInclTax($baseCodTaxDetails->getRowTotalInclTax());
        $quote->setCodFeeInclTax($codTaxDetails->getRowTotalInclTax());
    }

    /**
     * @param Total $total
     * @return Total
     */
    private function _setTotalTaxCalculationAmounts(Total $total)
    {
        $pricesIncludeTax = $this->helper->codFeeIncludesTax();

        if ($pricesIncludeTax) {
            $total->setBaseCodTaxCalculationAmount($total->getBaseCodFeeInclTax());
            $total->setCodTaxCalculationAmount($total->getCodFeeInclTax());
        } else {
            $total->setBaseCodTaxCalculationAmount($total->getBaseCodFee());
            $total->setCodTaxCalculationAmount($total->getCodFee());
        }

        return $total;
    }
}
