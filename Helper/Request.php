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

namespace Phoenix\CashOnDelivery\Helper;

use Magento\Framework\App\Request\Http;

/**
 * HTTP request helper
 *
 * @package Phoenix\CashOnDelivery\Helper
 */
class Request
{
    /**
     * @var Http $request
     */
    private $request;

    /**
     * @var bool|string
     */
    private $customFee;

    public function __construct(Http $request)
    {
        $this->request = $request;
        $this->customFee = false;
    }

    public function includesCustomFee()
    {
        $this->getCustomCashOnDeliveryFee();

        return $this->customFee !== null;
    }

    public function getCustomCashOnDeliveryFee()
    {
        if ($this->customFee === false) {
            $creditmemoParam = $this->request->getParam('creditmemo');
            $this->customFee = $creditmemoParam ? $creditmemoParam['cashondelivery_amount'] : null;
        }

        return $this->customFee;
    }
}
