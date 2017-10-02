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

namespace Phoenix\CashOnDelivery\Block;

use Magento\Framework\View\Element\Template;
use Phoenix\CashOnDelivery\Model\Config;

class Form extends \Magento\Payment\Block\Form
{
    /**
     * Path to template file in theme.
     *
     * @var string $_template
     */
    protected $_template = 'form.phtml';

    /**
     * @var Config $config
     */
    private $config;

    public function __construct(Template\Context $context, Config $config, array $data = [])
    {
        parent::__construct($context, $data);

        $this->config = $config;
    }

    public function getCustomText()
    {
        return $this->config->getCustomText();
    }
}
