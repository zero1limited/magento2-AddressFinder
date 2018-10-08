<?php

namespace Zero1\AddressFinder\Helper;

class Config
{
    const CONFIG_PATH_ENABLED = 'zero1_address_finder/general/enable';
    const CONFIG_PATH_ACCOUNT_CODE = 'zero1_address_finder/general/account_code';
    const CONFIG_PATH_PUBLIC_KEY = 'zero1_address_finder/general/public_key';

    protected $config;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    ){
        $this->config = $scopeConfigInterface;
    }

    /**
     * @param string $scope
     * @param null $scopeCode
     * @return bool
     */
    public function isEnabled($scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return (bool)$this->config->getValue(self::CONFIG_PATH_ENABLED, $scope, $scopeCode);
    }

    /**
     * @param string $scope
     * @param null $scopeCode
     * @return string
     */
    public function getAccountCode($scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->config->getValue(self::CONFIG_PATH_ACCOUNT_CODE, $scope, $scopeCode);
    }

    /**
     * @param string $scope
     * @param null $scopeCode
     * @return string
     */
    public function getPublicKey($scope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->config->getValue(self::CONFIG_PATH_PUBLIC_KEY, $scope, $scopeCode);
    }
}