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
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Creditmemo;
use Phoenix\CashOnDelivery\Helper\Data;

class CashOnDeliveryTax extends Template
{
    /**
     * @var Order|Invoice|Creditmemo\
     */
    protected $source;

    /**
     * @var Data $helper
     */
    protected $helper;

    public function __construct(
        Template\Context $context,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->helper = $helper;
    }

    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->source = $parent->getSource();

        if (!$this->helper->isActiveMethod($this->source)) {
            return $this;
        }

        $cod_tax = new DataObject(
            [
                'code' => 'cashondelivery_tax',
                'base_value' => $this->source->getBaseCodTaxAmount(),
                'value' => $this->source->getCodTaxAmount(),
                'label' => __('Cash on Delivery tax'),
            ]
        );
        $parent->addTotalBefore($cod_tax, 'tax');

        return $this;
    }
}
