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
 * @category  Mage
 * @package   Phoenix_CashOnDelivery
 * @copyright Copyright (c) 2017 Phoenix Media GmbH (http://www.phoenix-media.eu)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Phoenix\CashOnDelivery\Api\Data;

interface OrderDataInterface extends FeeInterface
{
    const BASE_FEE_INVOICED = 'base_cod_fee_invoiced';
    const BASE_FEE_REFUNDED = 'base_cod_fee_refunded';
    const BASE_FEE_CANCELED = 'base_cod_fee_canceled';
    const FEE_INVOICED = 'cod_fee_invoiced';
    const FEE_REFUNDED = 'cod_fee_refunded';
    const FEE_CANCELED = 'cod_fee_canceled';
    const BASE_TAX_AMOUNT_INVOICED = 'base_cod_tax_amount_invoiced';
    const BASE_TAX_AMOUNT_REFUNDED = 'base_cod_tax_amount_refunded';
    const BASE_TAX_AMOUNT_CANCELED = 'base_cod_tax_amount_canceled';
    const TAX_AMOUNT_INVOICED = 'cod_tax_amount_invoiced';
    const TAX_AMOUNT_REFUNDED = 'cod_tax_amount_refunded';
    const TAX_AMOUNT_CANCELED = 'cod_tax_amount_canceled';

    /**
     * @return float
     */
    public function getBaseFeeInvoiced();

    /**
     * @param float $baseFee
     * @return self
     */
    public function setBaseFeeInvoiced($baseFee);

    /**
     * @return float
     */
    public function getBaseFeeRefunded();

    /**
     * @param float $baseFee
     * @return self
     */
    public function setBaseFeeRefunded($baseFee);

    /**
     * @return float
     */
    public function getBaseFeeCanceled();

    /**
     * @param float $baseFee
     * @return self
     */
    public function setBaseFeeCanceled($baseFee);

    /**
     * @return float
     */
    public function getFeeInvoiced();

    /**
     * @param float $fee
     * @return self
     */
    public function setFeeInvoiced($fee);

    /**
     * @return float
     */
    public function getFeeRefunded();

    /**
     * @param float $fee
     * @return self
     */
    public function setFeeRefunded($fee);

    /**
     * @return float
     */
    public function getFeeCanceled();

    /**
     * @param float $fee
     * @return self
     */
    public function setFeeCanceled($fee);

    /**
     * @return float
     */
    public function getBaseTaxAmountInvoiced();

    /**
     * @param float $baseTaxAmount
     * @return self
     */
    public function setBaseTaxAmountInvoiced($baseTaxAmount);

    /**
     * @return float
     */
    public function getBaseTaxAmountRefunded();

    /**
     * @param float $baseTaxAmount
     * @return self
     */
    public function setBaseTaxAmountRefunded($baseTaxAmount);

    /**
     * @return float
     */
    public function getBaseTaxAmountCanceled();

    /**
     * @param float $baseTaxAmount
     * @return self
     */
    public function setBaseTaxAmountCanceled($baseTaxAmount);

    /**
     * @return float
     */
    public function getTaxAmountInvoiced();

    /**
     * @param float $taxAmount
     * @return self
     */
    public function setTaxAmountInvoiced($taxAmount);
    /**
     * @return float
     */
    public function getTaxAmountRefunded();

    /**
     * @param float $taxAmount
     * @return self
     */
    public function setTaxAmountRefunded($taxAmount);

    /**
     * @return float
     */
    public function getTaxAmountCanceled();

    /**
     * @param float $taxAmount
     * @return self
     */
    public function setTaxAmountCanceled($taxAmount);
}
