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

class CashOnDeliveryTax extends Template
{
    /**
     * @var \Magento\Sales\Model\Order\Invoice
     */
    protected $source;

    public function __construct(Template\Context $context, array $data = [])
    {
        parent::__construct($context, $data);
    }

    public function initTotals()
    {
        /** @var \Magento\Sales\Block\Order\Invoice\Totals $parent */
        $parent = $this->getParentBlock();
        $this->source = $parent->getSource();

        $cod_tax = new DataObject(
            [
                'code' => 'cashondelivery_tax',
                'base_value' => $this->source->getBaseCodTaxAmount(),
                'value' => $this->source->getCodTaxAmount(),
                'label' => __('Cash on Delivery tax'),
            ]
        );
        $parent->addTotalBefore($cod_tax, 'tax');
    }
}
