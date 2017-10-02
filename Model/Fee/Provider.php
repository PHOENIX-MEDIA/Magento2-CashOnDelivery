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

namespace Phoenix\CashOnDelivery\Model\Fee;

use Magento\Framework\Model\AbstractModel;
use Phoenix\CashOnDelivery\Api\Data\FeeInterface;
use Phoenix\CashOnDelivery\Api\FeeProviderInterface;

class Provider implements FeeProviderInterface
{
    /**
     * @var \Phoenix\CashOnDelivery\Model\FeeFactory $_feeFactory
     */
    private $_feeFactory;

    public function __construct(
        \Phoenix\CashOnDelivery\Model\FeeFactory $feeFactory
    ) {
        $this->_feeFactory = $feeFactory;
    }

    public function getFeeData(AbstractModel $model)
    {
        /** @var FeeInterface $fee */
        $fee = $this->_feeFactory->create();

        $fee->setBaseFee($model->getData(FeeInterface::BASE_FEE));
        $fee->setFee($model->getData(FeeInterface::FEE));
        $fee->setBaseTaxAmount($model->getData(FeeInterface::BASE_TAX_AMOUNT));
        $fee->setTaxAmount($model->getData(FeeInterface::TAX_AMOUNT));
        $fee->setBaseFeeInclTax($model->getData(FeeInterface::BASE_FEE_INCL_TAX));
        $fee->setFeeInclTax($model->getData(FeeInterface::FEE_INCL_TAX));

        return $fee;
    }
}
