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

namespace Phoenix\CashOnDelivery\Model;

use Phoenix\CashOnDelivery\Api\Data\FeeInterface;

class Fee implements FeeInterface
{
    /**
     * @var float
     */
    private $_baseFee;

    /**
     * @var float
     */
    private $_fee;

    /**
     * @var float
     */
    private $_baseTaxAmount;

    /**
     * @var float
     */
    private $_taxAmount;

    /**
     * @var float
     */
    private $_baseFeeInclTax;

    /**
     * @var float
     */
    private $_feeInclTax;

    /**
     * @var array
     */
    private $_extensionAttributes;

    /**
     * {@inheritdoc}
     */
    public function getBaseFee()
    {
        return $this->_baseFee;
    }

    /**
     * @param $baseFee float
     * @return $this
     */
    public function setBaseFee($baseFee)
    {
        $this->_baseFee = $baseFee;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFee()
    {
        return $this->_fee;
    }

    /**
     * {@inheritdoc}
     */
    public function setFee($fee)
    {
        $this->_fee = $fee;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTaxAmount()
    {
        return $this->_baseTaxAmount;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTaxAmount($baseTaxAmount)
    {
        $this->_baseTaxAmount = $baseTaxAmount;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxAmount()
    {
        return $this->_taxAmount;
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxAmount($taxAmount)
    {
        $this->_taxAmount = $taxAmount;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseFeeInclTax()
    {
        return $this->_baseFeeInclTax;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseFeeInclTax($baseFeeInclTax)
    {
        $this->_baseFeeInclTax = $baseFeeInclTax;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFeeInclTax()
    {
        return $this->_feeInclTax;
    }

    /**
     * {@inheritdoc}
     */
    public function setFeeInclTax($feeInclTax)
    {
        $this->_feeInclTax = $feeInclTax;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensionAttributes()
    {
        return $this->_extensionAttributes;
    }

    /**
     * {@inheritdoc}
     */
    public function setExtensionAttributes(
        \Phoenix\CashOnDelivery\Api\Data\FeeExtensionInterface $extensionAttributes
    ) {
        $this->_extensionAttributes = $extensionAttributes;
        return $this;
    }
}
