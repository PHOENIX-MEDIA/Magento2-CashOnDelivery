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

use Phoenix\CashOnDelivery\Api\Data\OrderDataInterface;

class OrderData extends Fee implements OrderDataInterface
{
    /**
     * @var float
     */
    private $_baseFeeInvoiced;
    /**
     * @var float
     */
    private $_baseFeeRefunded;

    /**
     * @var float
     */
    private $_baseFeeCanceled;

    /**
     * @var float
     */
    private $_feeInvoiced;

    /**
     * @var float
     */
    private $_feeRefunded;

    /**
     * @var float
     */
    private $_feeCanceled;

    /**
     * @var float
     */
    private $_baseTaxAmountInvoiced;

    /**
     * @var float
     */
    private $_baseTaxAmountRefunded;

    /**
     * @var float
     */
    private $_baseTaxAmountCanceled;

    /**
     * @var float
     */
    private $_taxAmountInvoiced;

    /**
     * @var float
     */
    private $_taxAmountRefunded;

    /**
     * @var float
     */
    private $_taxAmountCanceled;

    /**
     * {@inheritdoc}
     */
    public function getBaseFeeInvoiced()
    {
        return $this->_baseFeeInvoiced;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseFeeInvoiced($baseFee)
    {
        $this->_baseFeeInvoiced = $baseFee;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseFeeRefunded()
    {
        return $this->_baseFeeRefunded;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseFeeRefunded($baseFee)
    {
        $this->_baseFeeRefunded = $baseFee;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseFeeCanceled()
    {
        return $this->_baseFeeCanceled;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseFeeCanceled($baseFee)
    {
        $this->_baseFeeCanceled = $baseFee;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFeeInvoiced()
    {
        return $this->_feeInvoiced;
    }

    /**
     * {@inheritdoc}
     */
    public function setFeeInvoiced($fee)
    {
        $this->_feeInvoiced = $fee;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFeeRefunded()
    {
        return $this->_feeRefunded;
    }

    /**
     * {@inheritdoc}
     */
    public function setFeeRefunded($fee)
    {
        $this->_feeRefunded = $fee;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFeeCanceled()
    {
        return $this->_feeCanceled;
    }

    /**
     * {@inheritdoc}
     */
    public function setFeeCanceled($fee)
    {
        $this->_feeCanceled = $fee;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTaxAmountInvoiced()
    {
        return $this->_baseTaxAmountInvoiced;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTaxAmountInvoiced($baseTaxAmount)
    {
        $this->_baseTaxAmountInvoiced = $baseTaxAmount;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTaxAmountRefunded()
    {
        return $this->_baseTaxAmountRefunded;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTaxAmountRefunded($baseTaxAmount)
    {
        $this->_baseTaxAmountRefunded = $baseTaxAmount;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseTaxAmountCanceled()
    {
        return $this->_baseTaxAmountCanceled;
    }

    /**
     * {@inheritdoc}
     */
    public function setBaseTaxAmountCanceled($baseTaxAmount)
    {
        $this->_baseTaxAmountCanceled = $baseTaxAmount;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxAmountInvoiced()
    {
        return $this->_taxAmountInvoiced;
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxAmountInvoiced($taxAmount)
    {
        $this->_taxAmountInvoiced = $taxAmount;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxAmountRefunded()
    {
        return $this->_taxAmountRefunded;
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxAmountRefunded($taxAmount)
    {
        $this->_taxAmountRefunded = $taxAmount;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxAmountCanceled()
    {
        return $this->_taxAmountCanceled;
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxAmountCanceled($taxAmount)
    {
        $this->_taxAmountCanceled = $taxAmount;
        return $this;
    }
}
