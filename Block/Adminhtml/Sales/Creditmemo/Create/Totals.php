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

namespace Phoenix\CashOnDelivery\Block\Adminhtml\Sales\Creditmemo\Create;

use Magento\Framework\View\Element\Template;
use Magento\Framework\DataObject;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Phoenix\CashOnDelivery\Model\Config;

/**
 * Gets totals form for to-be-created credit memos
 *
 * @package Phoenix\CashOnDelivery\Block\Adminhtml\Sales\Creditmemo\Create
 */
class Totals extends Template
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
    protected $_template = 'sales/creditmemo/create/totals.phtml';

    /**
     * Holds the creditmemo object.
     * @var \Magento\Sales\Model\Order\Creditmemo
     */
    private $source;

    /**
     * @var Config $config
     */
    private $config;

    /**
     * @var PriceCurrencyInterface $priceCurrency
     */
    private $priceCurrency;

    /**
     * Totals constructor
     *
     * @param Template\Context $context
     * @param Config $config
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Config $config,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->config = $config;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Initialize creditmemo CoD totals
     *
     * @return Totals
     */
    public function initTotals()
    {
        /** @var \Magento\Sales\Block\Order\Creditmemo\Totals $parent */
        $parent = $this->getParentBlock();
        $this->source = $parent->getSource();
        $total = new DataObject([
            'code' => 'phoenix_cashondelivery_fee',
            'block_name' => $this->getNameInLayout(),
        ]);

        $parent->addTotal($total, 'agjustments');
        return $this;
    }

    /**
     * Getter for the creditmemo object.
     *
     * @return \Magento\Sales\Model\Order\Creditmemo
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Get CoD fee amount for actual invoice.
     *
     * @return float
     */
    public function getCodAmount()
    {
        $codFee = $this->config->codFeeIncludesTax()
            ? $this->getSource()->getBaseCodFeeInclTax()
            : $this->getSource()->getBaseCodFee();

        return $this->formatFee($codFee);
    }

    /**
     * Get label for refund subtotal
     *
     * @return string Refund label
     */
    public function getRefundLabel()
    {
        $base = __('Refund Cash on Delivery fee');

        $taxInclusionLabel = $this->config->codFeeIncludesTax() ? 'Incl.' : 'Excl.';
        $taxLabel = sprintf('(%s Tax)', $taxInclusionLabel);
        $tax = __($taxLabel);

        return sprintf('%s %s', $base, $tax);
    }

    private function formatFee($fee)
    {
        return $this->priceCurrency->convertAndRound($fee);
    }
}
