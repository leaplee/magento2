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
namespace Magento\Downloader\Model\Config;

/**
 * Class config
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Community extends \Magento\Downloader\Model\Config\AbstractConfig implements
    \Magento\Downloader\Model\Config\ConfigInterface
{
    /**
     * Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->load();
    }

    /**
     * Set data for Settings View
     *
     * @param \Magento\Framework\Connect\Config $config
     * @param \Magento\Downloader\View $view
     * @return void
     */
    public function setInstallView($config, $view)
    {
        $view->set('channel_logo', 'logo');
    }

    /**
     * Set data for Settings View
     * @param \Magento\Framework\Connect\Config $config
     * @param \Magento\Downloader\View $view
     * @return void
     */
    public function setSettingsView($config, $view)
    {
    }

    /**
     * Set session data for Settings
     * @param array $post post data
     * @param mixed $session Session object
     * @return void
     */
    public function setSettingsSession($post, $session)
    {
    }

    /**
     * Get root channel URI
     *
     * @return string Root channel URI
     */
    public function getRootChannelUri()
    {
        if (!$this->get('root_channel_uri')) {
            $this->set('root_channel_uri', 'connect20.magentocommerce.com/community');
        }
        return $this->get('root_channel_uri');
    }

    /**
     * Set config data from POST
     *
     * @param \Magento\Framework\Connect\Config $config Config object
     * @param array $post post data
     * @return void
     */
    public function setPostData($config, &$post)
    {
    }

    /**
     * Set additional command options
     *
     * @param mixed $session Session object
     * @param array $options
     * @return void
     */
    public function setCommandOptions($session, &$options)
    {
    }
}
