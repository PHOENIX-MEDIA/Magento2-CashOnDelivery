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

namespace Phoenix\CashOnDelivery\Helper;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\Store;
use Phoenix\CashOnDelivery\Model\Config;
use Phoenix\CashOnDelivery\Model\Ui\ConfigProvider;

/**
 * Class Data
 * @package Phoenix\CashOnDelivery\Helper
 */
class Data
{
    /**
     * @var Config $codConfig
     */
    private $codConfig;

    /**
     * @var PriceCurrencyInterface $priceCurrency
     */
    private $priceCurrency;

    /**
     * Data constructor.
     *
     * @param Config $codConfig
     */
    public function __construct(
        Config $codConfig,
        PriceCurrencyInterface $priceCurrency
    ) {
        $this->codConfig = $codConfig;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->codConfig->isActive();
    }

    /**
     * Calculate the inland cost of Cash on Delivery based on a total value
     *
     * @param float $base
     */
    public function getInlandCodFee($base)
    {
        $cost = $this->codConfig->getInlandCost();
        $minCost = $this->getMinInlandCodFee();

        return $this->calculateCodFee($base, $cost, $minCost);
    }

    /**
     * Calculate the foreign country cost of Cash on Delivery based on a total value
     *
     * @param float $base
     */
    public function getForeignCodFee($base)
    {
        $cost = $this->codConfig->getForeignCost();
        $minCost = $this->getMinForeignCodFee();

        return $this->calculateCodFee($base, $cost, $minCost);
    }

    /**
     * Get origin country for shipping
     *
     * @return int
     */
    public function getShippingOriginCountry()
    {
        return $this->codConfig->getShippingOriginCountry();
    }

    /**
     * Check if configured Cash on Delivery fee already includes tax
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function codFeeIncludesTax($store = null)
    {
        return $this->codConfig->codFeeIncludesTax($store);
    }

    /**
     * Get tax class ID for the Cash on Delivery fee
     *
     * @param null|string|bool|int|Store $store
     * @return int
     */
    public function getTaxClassId($store = null)
    {
        return $this->codConfig->getTaxClassId($store);
    }

    /**
     * Checks if quote has set Phoenix Cash on Delivery as its payment method
     *
     * @param Quote $quote
     * @return bool
     */
    public function isActiveMethod(Quote $quote)
    {
        $paymentMethod = $quote->getPayment();
        $method = $paymentMethod->getMethod();

        return $method === ConfigProvider::CODE;
    }

    /**
     * @param float $price
     *
     * @return float
     */
    public function roundPrice($price)
    {
        return $this->priceCurrency->round($price);
    }

    private function calculateCodFee($base, $cost, $minCost)
    {
        $calculationType = $this->codConfig->getCalculationType();
        if ($calculationType === Config::CALCULATION_TYPE_PERCENTAGE) {
            $cost = $base / 100 * $cost;
        }

        $cost = $minCost + 0.0001 < $cost ? $cost : $minCost;

        return $cost;
    }

    private function getMinInlandCodFee()
    {
        $minCost = 0;
        $calculationType = $this->codConfig->getCalculationType();
        if ($calculationType === Config::CALCULATION_TYPE_PERCENTAGE) {
            $minCost = $this->codConfig->getMinInlandCost();
        }

        return $minCost;
    }

    private function getMinForeignCodFee()
    {
        $minCost = 0;
        $calculationType = $this->codConfig->getCalculationType();
        if ($calculationType === Config::CALCULATION_TYPE_PERCENTAGE) {
            $minCost = $this->codConfig->getMinForeignCost();
        }

        return $minCost;
    }
}
