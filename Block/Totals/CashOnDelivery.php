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

namespace Phoenix\CashOnDelivery\Block\Totals;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Tax\Model\Config as TaxConfig;
use Phoenix\CashOnDelivery\Helper\Data;
use Phoenix\CashOnDelivery\Model\Config;

class CashOnDelivery extends Template
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Data $helper
     */
    protected $helper;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $source;

    public function __construct(
        Template\Context $context,
        Config $config,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->helper = $helper;
    }

    public function displayFullSummary()
    {
        return true;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    public function initTotals()
    {
        /** @var \Magento\Sales\Block\Order\Totals $parent */
        $parent = $this->getParentBlock();
        $this->source = $parent->getSource();

        if (!$this->helper->isActiveMethod($this->source)) {
            return $this;
        }

        $displayType = $this->config->getDisplayType();

        if ($displayType === TaxConfig::DISPLAY_TYPE_EXCLUDING_TAX || $displayType === TaxConfig::DISPLAY_TYPE_BOTH) {
            $cod_fee_excl_tax = new DataObject(
                [
                    'code' => 'cashondelivery',
                    'base_value' => $this->source->getBaseCodFee(),
                    'value' => $this->source->getCodFee(),
                    'label' => __('Cash on Delivery fee (Excl.Tax)'),
                ]
            );
            $parent->addTotalBefore($cod_fee_excl_tax, 'grand_total');
        }

        if ($displayType === TaxConfig::DISPLAY_TYPE_INCLUDING_TAX || $displayType === TaxConfig::DISPLAY_TYPE_BOTH) {
            $cod_fee_incl_tax = new DataObject(
                [
                    'code' => $displayType === TaxConfig::DISPLAY_TYPE_BOTH ? 'cashondelivery_incl' : 'cashondelivery',
                    'base_value' => $this->source->getBaseCodFeeInclTax(),
                    'value' => $this->source->getCodFeeInclTax(),
                    'label' => __('Cash on Delivery fee (Incl.Tax)'),
                ]
            );

            $codTotal = $parent->getTotal('cashondelivery');

            if ($codTotal) {
                $parent->addTotal($cod_fee_incl_tax, 'cashondelivery');
            }
            else {
                $parent->addTotalBefore($cod_fee_incl_tax, 'grand_total');
            }
        }

        return $this;
    }
}
