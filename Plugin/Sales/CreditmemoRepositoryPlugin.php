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
use Magento\Sales\Api\Data\CreditmemoExtensionFactory;
use Magento\Sales\Api\CreditmemoRepositoryInterface;
use Magento\Sales\Model\Order\Creditmemo;
use Phoenix\CashOnDelivery\Api\FeeProviderInterface;

class CreditmemoRepositoryPlugin
{
    /**
     * @var CreditmemoExtensionFactory
     */
    private $_creditmemoExtensionFactory;

    /**
     * @var FeeProviderInterface
     */
    private $_feeProvider;

    /**
     * CreditmemoRepositoryPlugin constructor.
     * @param CreditmemoExtensionFactory $creditmemoExtensionFactory
     * @param FeeProviderInterface $feeProvider
     */
    public function __construct(
        CreditmemoExtensionFactory $creditmemoExtensionFactory,
        FeeProviderInterface $feeProvider
    ) {
        $this->_creditmemoExtensionFactory = $creditmemoExtensionFactory;
        $this->_feeProvider = $feeProvider;
    }

    /**
     * @param CreditmemoRepositoryInterface $subject
     * @param Creditmemo $entity
     * @return Creditmemo
     */
    public function afterGet(
        CreditmemoRepositoryInterface $subject,
        Creditmemo $entity
    ) {
        $this->_addCodFeeToCreditmemo($entity);

        return $entity;
    }

    /**
     * @param CreditmemoRepositoryInterface $subject
     * @param SearchResultsInterface $searchResult
     * @return SearchResultsInterface
     */
    public function afterGetList(
        CreditmemoRepositoryInterface $subject,
        SearchResultsInterface $searchResult
    ) {
        /** @var Creditmemo $creditmemo */
        foreach ($searchResult->getItems() as $creditmemo) {
            $this->_addCodFeeToCreditmemo($creditmemo);
        }

        return $searchResult;
    }

    /**
     * @param Creditmemo $creditmemo
     * @return $this
     */
    private function _addCodFeeToCreditmemo(Creditmemo $creditmemo)
    {
        $extensionAttributes = $creditmemo->getExtensionAttributes();

        if (empty($extensionAttributes)) {
            $extensionAttributes = $this->_creditmemoExtensionFactory->create();
        }

        $codData = $this->_feeProvider->getFeeData($creditmemo);
        $extensionAttributes->setData(FeeProviderInterface::EXTENSION_ATTRIBUTE_KEY, $codData);
        $creditmemo->setExtensionAttributes($extensionAttributes);

        return $this;
    }
}
