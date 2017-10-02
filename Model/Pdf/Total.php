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

namespace Phoenix\CashOnDelivery\Model\Pdf;

use Magento\Tax\Model\Config as TaxConfig;
use Phoenix\CashOnDelivery\Model\Config;

class Total extends \Magento\Sales\Model\Order\Pdf\Total\DefaultTotal
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(
        \Magento\Tax\Helper\Data $taxHelper,
        \Magento\Tax\Model\Calculation $taxCalculation,
        \Magento\Tax\Model\ResourceModel\Sales\Order\Tax\CollectionFactory $ordersFactory,
        Config $config,
        array $data = []
    ) {
        parent::__construct($taxHelper, $taxCalculation, $ordersFactory, $data);

        $this->config = $config;
    }

    public function getTotalsForDisplay()
    {
        $displayType = $this->config->getDisplayType();
        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
        $amount = $this->getOrder()->getCodFee();
        $amount = $this->getOrder()->formatPriceTxt($amount);
        $amountInclTax = $this->getOrder()->getCodFeeInclTax();
        $amountInclTax = $this->getOrder()->formatPriceTxt($amountInclTax);

        $totals = [];

        if ($displayType === TaxConfig::DISPLAY_TYPE_EXCLUDING_TAX || $displayType === TaxConfig::DISPLAY_TYPE_BOTH) {
            $totals[] =
                [
                    'amount' => $amount,
                    'label' => __('Cash on Delivery fee (Excl.Tax)'),
                    'font_size' => $fontSize,
                ];
        }

        if ($displayType === TaxConfig::DISPLAY_TYPE_INCLUDING_TAX || $displayType === TaxConfig::DISPLAY_TYPE_BOTH) {
            $totals[] =
                [
                    'amount' => $amountInclTax,
                    'label' => __('Cash on Delivery fee (Incl.Tax)'),
                    'font_size' => $fontSize,
                ];
        }

        return $totals;
    }
}
