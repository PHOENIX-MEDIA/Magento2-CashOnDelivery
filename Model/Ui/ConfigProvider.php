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

namespace Phoenix\CashOnDelivery\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Framework\Escaper;
use Phoenix\CashOnDelivery\Model\Config;

class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'phoenix_cashondelivery';

    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var Escaper $config
     */
    protected $escaper;

    public function __construct(
        Config $config,
        Escaper $escaper
    ) {
        $this->config = $config;
        $this->escaper = $escaper;
    }

    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'customText' => nl2br($this->escaper->escapeHtml($this->config->getCustomText())),
                    'codFeeIncludesTax' => $this->config->codFeeIncludesTax(),
                    'displayType' => $this->config->getDisplayType(),
                    'displayZeroFee' => $this->config->displayZeroFee(),
                    'disallowificShippingMethod' => $this->config->disallowSpecificShippingMethods(),
                    'disallowedShippingMethods' => $this->config->getDisallowedShippingMethods(),
                ]
            ]
        ];
    }
}
