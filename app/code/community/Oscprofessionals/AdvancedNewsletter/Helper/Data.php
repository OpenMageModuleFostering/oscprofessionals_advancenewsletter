<?php
/**
 * @category    Oscprofessional
 * @package     Oscprofessional_AdvancedNewsletter
 * @author      Oscprofessionals Team <standard@oscprofessionals.com>
 */
class Oscprofessionals_AdvancedNewsletter_Helper_Data extends Mage_Core_Helper_Abstract 
{
    /*
     * Initializing Constants
     */

    const XML_PATH_ENABLE = 'advancednewsletter/subscription/enabled';
    const XML_PATH_IS_POPUP_DISABLE = 'advancednewsletter/subscription/disable_popup_after_closing';
    const XML_PATH_COOKIES_TIMEOUT = 'advancednewsletter/subscription/cookies_timeout';
    const XML_PATH_POPUP_FORM_TITLE = 'advancednewsletter/subscription/subsciption_form_title';
    const XML_PATH_POPUP_DESCRIPTION = 'advancednewsletter/subscription/popup_discription';

    /**
     * Check Module is Enable
     *
     * @return bool
     */
    public function isNewsletterPopUpEnable() 
    {
        return (bool) Mage::getStoreConfig(self::XML_PATH_ENABLE);
    }


    /**
     * Get Cookie TIme Out 
     *
     * @return Time in days
     */
    public function getCookiesTimeOut() 
    {
        $cookiesTimeout = Mage::getStoreConfig(self::XML_PATH_COOKIES_TIMEOUT);
        return $cookiesTimeout;
    }

    /**
     * Convert Day in seconds
     * params int days
     * @return seconds
     */
    public function getCookieTimeInSeconds($days) 
    {
        $cookiesValueInSecond = (84600 * $days);
        return $cookiesValueInSecond;
    }

    /**
     * Newsletter PopUp Title
     *
     * @return Text
     */
    public function getPopupFormTitle() 
    {
        return Mage::getStoreConfig(self::XML_PATH_POPUP_FORM_TITLE);
    }

    /**
     * Newsletter PopUp Description 
     *
     * @return Text
     */
    public function getPopupDescription() 
    {
        return Mage::getStoreConfig(self::XML_PATH_POPUP_DESCRIPTION);
    }

}
