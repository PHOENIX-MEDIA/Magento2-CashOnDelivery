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

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/totals'
], function(Component, priceUtilities, quote, totals) {
    'use strict';

    var displayTypes = {
        'EXCLUDING_TAX': 1,
        'INCLUDING_TAX': 2,
        'BOTH': 3
    };

    return Component.extend({
        defaults: {
            isFullTaxSummaryDisplayed: window.checkoutConfig.isFullTaxSummaryDisplayed || false,
            template: 'Phoenix/CashOnDelivery/checkout/summary/cashondelivery'
        },
        totals: quote.getTotals(),
        isTaxDisplayedInGrandTotal: window.checkoutConfig.isTaxDisplayedInGrandTotal || false,
        isDisplayed: function() {
            return this.isFullMode();
        },
        isSelected: function() {
            var selected = false;
            if (quote.paymentMethod()) {
                var paymentMethod = quote.paymentMethod().method;
                selected = paymentMethod === 'phoenix_cashondelivery';
            }
            return selected;
        },
        getBaseValue: function() {
            var price = 0;
            if (this.totals()) {
                price = this.totals().base_fee;
            }
            return priceUtilities.formatPrice(price, quote.getBasePriceFormat());
        },
        getValue: function() {
            var price = 0;
            if (this.totals()) {
                price = totals.getSegment('cashondelivery').value;
            }
            return this.getFormattedPrice(price);
        },
        getValueInclTax: function() {
            var price = 0;
            if (this.totals()) {
                price = totals.getSegment('cashondelivery_incl_tax').value;
            }
            return this.getFormattedPrice(price);
        },
        displayIncluding: function () {
            var displayType = window.checkoutConfig.payment.phoenix_cashondelivery.displayType;
            return displayType === displayTypes.INCLUDING_TAX || displayType === displayTypes.BOTH;
        },
        displayExcluding: function () {
            var displayType = window.checkoutConfig.payment.phoenix_cashondelivery.displayType;
            return displayType === displayTypes.EXCLUDING_TAX || displayType === displayTypes.BOTH;
        },
        dispayZeroFee: window.checkoutConfig.payment.phoenix_cashondelivery.displayZeroFee
    })
});