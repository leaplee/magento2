<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2014 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Paypal\Helper\Shortcut;

class Validator implements ValidatorInterface
{
    /**
     * @var \Magento\Paypal\Model\ConfigFactory
     */
    private $_paypalConfigFactory;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    private $_checkoutSession;

    /**
     * @var \Magento\Framework\Registry
     */
    private $_registry;

    /**
     * @var \Magento\Catalog\Model\ProductTypes\ConfigInterface
     */
    private $_productTypeConfig;

    /**
     * @var \Magento\Payment\Helper\Data
     */
    private $_paymentData;

    /**
     * @param \Magento\Paypal\Model\ConfigFactory $paypalConfigFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig
     * @param \Magento\Payment\Helper\Data $paymentData
     */
    public function __construct(
        \Magento\Paypal\Model\ConfigFactory $paypalConfigFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Payment\Helper\Data $paymentData
    ) {
        $this->_paypalConfigFactory = $paypalConfigFactory;
        $this->_registry = $registry;
        $this->_productTypeConfig = $productTypeConfig;
        $this->_paymentData = $paymentData;
    }

    /**
     * Validates shortcut
     *
     * @param string $code
     * @param bool $isInCatalog
     * @return bool
     */
    public function validate($code, $isInCatalog)
    {
        return $this->isContextAvailable($code, $isInCatalog)
            && $this->isPriceOrSetAvailable($isInCatalog)
            && $this->isMethodAvailable($code);
    }

    /**
     * Checks visibility of context (cart or product page)
     *
     * @param string $paymentCode Payment method code
     * @param bool $isInCatalog
     * @return bool
     */
    public function isContextAvailable($paymentCode, $isInCatalog)
    {
        /** @var \Magento\Paypal\Model\Config $config */
        $config = $this->_paypalConfigFactory->create();
        $config->setMethod($paymentCode);

        // check visibility on cart or product page
        $context = $isInCatalog ? 'visible_on_product' : 'visible_on_cart';
        if (!$config->getConfigValue($context)) {
            return false;
        }
        return true;
    }

    /**
     * Check is product available depending on final price or type set(configurable)
     *
     * @param bool $isInCatalog
     * @return bool
     */
    public function isPriceOrSetAvailable($isInCatalog)
    {
        if ($isInCatalog) {
            // Show PayPal shortcut on a product view page only if product has nonzero price
            /** @var $currentProduct \Magento\Catalog\Model\Product */
            $currentProduct = $this->_registry->registry('current_product');
            if (!is_null($currentProduct)) {
                $productPrice = (double)$currentProduct->getFinalPrice();
                if (empty($productPrice) && !$this->_productTypeConfig->isProductSet($currentProduct->getTypeId())) {
                    return  false;
                }
            }
        }
        return true;
    }

    /**
     * Сhecks payment method and quote availability
     *
     * @param string $paymentCode
     * @return bool
     */
    public function isMethodAvailable($paymentCode)
    {
        // check payment method availability
        /** @var \Magento\Payment\Model\Method\AbstractMethod $methodInstance */
        $methodInstance = $this->_paymentData->getMethodInstance($paymentCode);
        if (!$methodInstance || !$methodInstance->isAvailable()) {
            return false;
        }
        return true;
    }
}
