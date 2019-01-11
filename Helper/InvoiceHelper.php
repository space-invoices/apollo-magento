<?php

namespace Studio404\Apollo\Helper;


use Magento\Framework\App\Helper\AbstractHelper;

class InvoiceHelper extends AbstractHelper
{
    // return Apollo token settings
    function getGeneralSettings()
    {
        return $this->scopeConfig->getValue(
            'apollo_settings/general',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    //return Apollo mail settings
    function getMailSettings()
    {
        return $this->scopeConfig->getValue(
            'apollo_settings/apollo_mail_settings',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getStoreName()
    {
        return $this->scopeConfig->getValue(
            'general/store_information/name',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}