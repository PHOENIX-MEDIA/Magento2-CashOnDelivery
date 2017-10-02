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

use Magento\Store\Model\Store;

/**
 * Configuration model class
 *
 * @package Phoenix\CashOnDelivery\Model
 */
class Config
{
    const XML_PATH_PHOENIX_CASHONDELIVERY_TOTALS_SORT = 'sales/totals_sort/phoenix_cashondelivery';

    const XML_PATH_PHOENIX_CASHONDELIVERY_ACTIVE = 'payment/phoenix_cashondelivery/active';

    const XML_PATH_PHOENIX_CASHONDELIVERY_DISPLAY_ZERO_FEE = 'payment/phoenix_cashondelivery/display_zero_fee';

    const XML_PATH_PHOENIX_CASHONDELIVERY_TITLE = 'payment/phoenix_cashondelivery/title';

    const XML_PATH_PHOENIX_CASHONDELIVERY_NEW_ORDER_STATUS = 'payment/phoenix_cashondelivery/order_status';

    const XML_PATH_PHOENIX_CASHONDELIVERY_APPLICABLE_COUNTRIES = 'payment/phoenix_cashondelivery/allowspecific';

    const XML_PATH_PHOENIX_CASHONDELIVERY_SPECIFIC_COUNTRIES = 'payment/phoenix_cashondelivery/specificcountry';

    const XML_PATH_PHOENIX_CASHONDELIVERY_MIN_ORDER_TOTAL = 'payment/phoenix_cashondelivery/min_order_total';

    const XML_PATH_PHOENIX_CASHONDELIVERY_MAX_ORDER_TOTAL = 'payment/phoenix_cashondelivery/max_order_total';

    const XML_PATH_PHOENIX_CASHONDELIVERY_COST_TYPE = 'payment/phoenix_cashondelivery/cost_type';

    const XML_PATH_PHOENIX_CASHONDELIVERY_INLAND_COST = 'payment/phoenix_cashondelivery/inlandcosts';

    const XML_PATH_PHOENIX_CASHONDELIVERY_MIN_INLAND_COST = 'payment/phoenix_cashondelivery/minimum_inlandcosts';

    const XML_PATH_PHOENIX_CASHONDELIVERY_FOREIGN_COST = 'payment/phoenix_cashondelivery/foreigncountrycosts';

    const XML_PATH_PHOENIX_CASHONDELIVERY_MIN_FOREIGN_COST = 'payment/phoenix_cashondelivery/minimum_foreigncountrycosts';

    const XML_PATH_PHOENIX_CASHONDELIVERY_CUSTOM_TEXT = 'payment/phoenix_cashondelivery/customtext';

    const XML_PATH_PHOENIX_CASHONDELIVERY_DISALLOW_SPECIFIC_SHIPPING = 'payment/phoenix_cashondelivery/disallowspecificshippingmethods';

    const XML_PATH_PHOENIX_CASHONDELIVERY_DISALLOW_SHIPPING_METHODS = 'payment/phoenix_cashondelivery/disallowedshippingmethods';

    const XML_PATH_PHOENIX_CASHONDELIVERY_TAX_CLASS = 'tax/classes/phoenix_cashondelivery_tax_class';

    const XML_PATH_PHOENIX_CASHONDELIVERY_FEE_INCLUDES_TAX = 'tax/calculation/phoenix_cashondelivery_includes_tax';

    const XML_PATH_PHOENIX_CASHONDELIVERY_FEE_DISPLAY = 'tax/display/phoenix_cashondelivery_fee';

    const XML_PATH_SHIPPING_ORIGIN_COUNTRY = 'shipping/origin/country_id';

    const CALCULATION_TYPE_FIXED = 0;

    const CALCULATION_TYPE_PERCENTAGE = 1;

    /**
     * Core store config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Config constructor
     *
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Check if payment method is active
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function isActive($store = null)
    {
        return $this->_getConfigFlag(self::XML_PATH_PHOENIX_CASHONDELIVERY_ACTIVE, $store);
    }

    /**
     * Check if zero fee should be displayed
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function displayZeroFee($store = null)
    {
        return $this->_getConfigFlag(self::XML_PATH_PHOENIX_CASHONDELIVERY_DISPLAY_ZERO_FEE, $store);
    }

    /**
     * Check if only specific shipping methods are allowed
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function allowSpecificCountries($store = null)
    {
        return $this->_getConfigFlag(self::XML_PATH_PHOENIX_CASHONDELIVERY_APPLICABLE_COUNTRIES, $store);
    }

    /**
     * Check if Cash on Delivery fee includes tax
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function codFeeIncludesTax($store = null)
    {
        return $this->_getConfigFlag(self::XML_PATH_PHOENIX_CASHONDELIVERY_FEE_INCLUDES_TAX, $store);
    }

    /**
     * Checks if some shipping methods should be disallowed
     *
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    public function disallowSpecificShippingMethods($store = null)
    {
        return $this->_getConfigFlag(self::XML_PATH_PHOENIX_CASHONDELIVERY_DISALLOW_SPECIFIC_SHIPPING, $store);
    }

    /**
     * Get sort order for totals
     *
     * @param null|string|bool|int|Store $store
     * @return int
     */
    public function getTotalsSortOrder($store = null)
    {
        return (int)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_TOTALS_SORT, $store);
    }

    /**
     * Get the title of the payment method
     *
     * @param null|string|bool|int|Store $store
     * @return string
     */
    public function getTitle($store = null)
    {
        return (string)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_TITLE, $store);
    }

    /**
     * Get the status a order should be set to after choosing Cash on Delivery as payment method
     *
     * @param null|string|bool|int|Store $store
     * @return string
     */
    public function getNewOrderStatus($store = null)
    {
        return (string)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_NEW_ORDER_STATUS, $store);
    }

    /**
     * Get allowed shipping destinations
     *
     * @param null|string|bool|int|Store $store
     * @return string
     */
    public function getSpecificCountries($store = null)
    {
        return $this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_SPECIFIC_COUNTRIES, $store);
    }

    /**
     * Get minimum order total for Cash on Delivery to be available
     *
     * @param null|string|bool|int|Store $store
     * @return float
     */
    public function getMinOrderTotal($store = null)
    {
        return (float)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_MIN_ORDER_TOTAL, $store);
    }

    /**
     * Get maximum order total for Cash on Delivery to be available
     *
     * @param null|string|bool|int|Store $store
     * @param null|string|bool|int|Store $store
     * @return float
     */
    public function getMaxOrderTotal($store = null)
    {
        return (float)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_MAX_ORDER_TOTAL, $store);
    }

    /**
     * Get the calculation type for the Cash on Delivery fee (fixed or percantage of subtotal)
     *
     * @param null|string|bool|int|Store $store
     * @return int
     */
    public function getCalculationType($store = null)
    {
        return (int)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_COST_TYPE, $store);
    }

    /**
     * Get configured cost value for inland shipments
     *
     * @param null|string|bool|int|Store $store
     * @return float
     */
    public function getInlandCost($store = null)
    {
        return (float)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_INLAND_COST, $store);
    }

    /**
     * Get minimum cost for inland shipments
     *
     * @param null|string|bool|int|Store $store
     * @return float
     */
    public function getMinInlandCost($store = null)
    {
        return (float)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_MIN_INLAND_COST, $store);
    }

    /**
     * Get configured cost value for shipments to foreign countries
     *
     * @param null|string|bool|int|Store $store
     * @return float
     */
    public function getForeignCost($store = null)
    {
        return (float)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_FOREIGN_COST, $store);
    }

    /**
     * Get minimum cost for shipments to foreign countries
     *
     * @param null|string|bool|int|Store $store
     * @return float
     */
    public function getMinForeignCost($store = null)
    {
        return (float)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_MIN_FOREIGN_COST, $store);
    }

    /**
     * Get custom text shown during checkout
     *
     * @param null|string|bool|int|Store $store
     * @return string
     */
    public function getCustomText($store = null)
    {
        return (string)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_CUSTOM_TEXT, $store);
    }

    /**
     * Get disallowed shipping methods
     *
     * @param null|string|bool|int|Store $store
     * @return array
     */
    public function getDisallowedShippingMethods($store = null)
    {
        return explode(',', $this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_DISALLOW_SHIPPING_METHODS, $store));
    }
    /**
     * Get the tax class for calculation of taxes
     *
     * @param null|string|bool|int|Store $store
     * @return int
     */
    public function getTaxClassId($store = null)
    {
        return (int)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_TAX_CLASS, $store);
    }

    /**
     * Get Cash on Delivery display type
     *  1 - Excluding tax
     *  2 - Including tax
     *  3 - Both
     *
     * @param null|string|bool|int|Store $store
     * @return int
     */
    public function getDisplayType($store = null)
    {
        return (int)$this->_getConfigValue(self::XML_PATH_PHOENIX_CASHONDELIVERY_FEE_DISPLAY, $store);
    }

    /**
     * Get shipping origin country
     *
     * @param null|string|bool|int|Store $store
     * @return int
     */
    public function getShippingOriginCountry($store = null)
    {
        return (string)$this->_getConfigValue(self::XML_PATH_SHIPPING_ORIGIN_COUNTRY, $store);
    }

    /**
     * @param string $path
     * @param null|string|bool|int|Store $store
     * @return mixed
     */
    private function _getConfigValue($path, $store)
    {
        return $this->_scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param string $path
     * @param null|string|bool|int|Store $store
     * @return bool
     */
    private function _getConfigFlag($path, $store)
    {
        return $this->_scopeConfig->isSetFlag(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}
