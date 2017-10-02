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

namespace Phoenix\CashOnDelivery\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface FeeInterface extends ExtensibleDataInterface
{
    const BASE_FEE = 'base_cod_fee';
    const FEE = 'cod_fee';
    const BASE_TAX_AMOUNT = 'base_cod_tax_amount';
    const TAX_AMOUNT = 'cod_tax_amount';
    const BASE_FEE_INCL_TAX = 'base_cod_fee_incl_tax';
    const FEE_INCL_TAX = 'cod_fee_incl_tax';

    /**
     * @return float
     */
    public function getBaseFee();

    /**
     * @param float $baseFee
     * @return self
     */
    public function setBaseFee($baseFee);


    /**
     * @return float
     */
    public function getFee();

    /**
     * @param float $fee
     * @return self
     */
    public function setFee($fee);

    /**
     * @return float
     */
    public function getBaseTaxAmount();

    /**
     * @param float $baseTaxAmount
     * @return self
     */
    public function setBaseTaxAmount($baseTaxAmount);

    /**
     * @return float
     */
    public function getTaxAmount();

    /**
     * @param float $taxAmount
     * @return self
     */
    public function setTaxAmount($taxAmount);

    /**
     * @return float
     */
    public function getBaseFeeInclTax();

    /**
     * @param float $baseFeeInclTax
     * @return self
     */
    public function setBaseFeeInclTax($baseFeeInclTax);

    /**
     * @return float
     */
    public function getFeeInclTax();

    /**
     * @param float $feeInclTax
     * @return self
     */
    public function setFeeInclTax($feeInclTax);

    /**
     * @return \Phoenix\CashOnDelivery\Api\Data\FeeExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * @param \Phoenix\CashOnDelivery\Api\Data\FeeExtensionInterface $extensionAttributes
     * @return self
     */
    public function setExtensionAttributes(
        \Phoenix\CashOnDelivery\Api\Data\FeeExtensionInterface $extensionAttributes
    );
}
