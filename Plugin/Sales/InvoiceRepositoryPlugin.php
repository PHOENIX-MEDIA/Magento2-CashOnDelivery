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

namespace Phoenix\CashOnDelivery\Plugin\Sales;

use Magento\Framework\Api\SearchResultsInterface;
use Magento\Sales\Api\Data\InvoiceExtensionFactory;
use Magento\Sales\Api\InvoiceRepositoryInterface;
use Magento\Sales\Model\Order\Invoice;
use Phoenix\CashOnDelivery\Api\FeeProviderInterface;

class InvoiceRepositoryPlugin
{
    /**
     * @var InvoiceExtensionFactory
     */
    private $_invoiceExtensionFactory;

    /**
     * @var FeeProviderInterface
     */
    private $_feeProvider;

    /**
     * InvoiceRepositoryPlugin constructor.
     * @param InvoiceExtensionFactory $invoiceExtensionFactory
     * @param FeeProviderInterface $feeProvider
     */
    public function __construct(
        InvoiceExtensionFactory $invoiceExtensionFactory,
        FeeProviderInterface $feeProvider
    ) {
        $this->_invoiceExtensionFactory = $invoiceExtensionFactory;
        $this->_feeProvider = $feeProvider;
    }

    /**
     * @param InvoiceRepositoryInterface $subject
     * @param Invoice $entity
     * @return Invoice
     */
    public function afterGet(
        InvoiceRepositoryInterface $subject,
        Invoice $entity
    ) {
        $this->_addCodFeeToInvoice($entity);

        return $entity;
    }

    /**
     * @param InvoiceRepositoryInterface $subject
     * @param SearchResultsInterface $searchResult
     * @return SearchResultsInterface
     */
    public function afterGetList(
        InvoiceRepositoryInterface $subject,
        SearchResultsInterface $searchResult
    ) {
        /** @var Invoice $invoice */
        foreach ($searchResult->getItems() as $invoice) {
            $this->_addCodFeeToInvoice($invoice);
        }

        return $searchResult;
    }

    /**
     * @param Invoice $invoice
     * @return $this
     */
    private function _addCodFeeToInvoice(Invoice $invoice)
    {
        $extensionAttributes = $invoice->getExtensionAttributes();

        if (empty($extensionAttributes)) {
            $extensionAttributes = $this->_invoiceExtensionFactory->create();
        }

        $codData = $this->_feeProvider->getFeeData($invoice);
        $extensionAttributes->setData(FeeProviderInterface::EXTENSION_ATTRIBUTE_KEY, $codData);
        $invoice->setExtensionAttributes($extensionAttributes);

        return $this;
    }
}
