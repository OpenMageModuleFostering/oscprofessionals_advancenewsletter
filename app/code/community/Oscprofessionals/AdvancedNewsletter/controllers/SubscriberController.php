<?php
/**
 * @category    Oscprofessionals
 * @package     Oscprofessionals_AdvancedNewsletter
 * @author      Oscprofessionals Team <standard@oscprofessionals.com>
 */
require_once(Mage::getModuleDir('controllers', 'Mage_Newsletter') . DS . 'SubscriberController.php');
class Oscprofessionals_AdvancedNewsletter_SubscriberController extends Mage_Newsletter_SubscriberController 
{

    /**
     * New subscription action
     */
    public function newAction() 
    {

        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session = Mage::getSingleton('core/session');
            $customerSession = Mage::getSingleton('customer/session');
            $email = (string) $this->getRequest()->getPost('email');

            /* As per custom layout change it should display message directly on popup */
            $subscribeErr = false;
            $ajaxSubscribeFlag = $this->getRequest()->getPost('ajaxAction');

            $cookiesTimeout = Mage::getStoreConfig('advancednewsletter/subscription/cookies_timeout');
            $session->setCookiesTimeout($cookiesTimeout);

            try {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    Mage::throwException($this->__('Please enter a valid email address.'));
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 && !$customerSession->isLoggedIn()) {
                    Mage::throwException($this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                    $session->setMyMessage('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl());
                    $subscribeErr = true;
                }

                $ownerId = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($email)
                        ->getId();

                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    Mage::throwException($this->__('This email address is already assigned to another user.'));
                    $session->setMyMessage('This email address is already assigned to another user.');
                    $subscribeErr = true;
                }

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                    $session->addSuccess($this->__('Confirmation request has been sent.'));
                } else {
                    $session->addSuccess($this->__('Thank you for your subscription'));
                }
            } catch (Mage_Core_Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription: %s', $e->getMessage()));
                $subscribeErr = true;
            } catch (Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription.'));
                $subscribeErr = true;
            }
        }

        if ($ajaxSubscribeFlag == 1) {

            /* As per ajax subscribe requirement */
            $subscribeAjaxMessage = array();
            $subscribeSuccess = ($subscribeErr !== true) ? 'true' : 'false';
            $subscribeMessage = array();

            $successMessage = $this->__('Thank you for subscribing to our Newsletter.');

            $messages = Mage::getSingleton('core/session')->getMessages(true);
            foreach ($messages->getItems() as $message) {
                $subscribeMessage[] = $message->getText();
            }

            $subscribeAjaxMessage['success'] = $subscribeSuccess;
            $subscribeAjaxMessage['msg'] = implode('<br>', $subscribeMessage);

            $jsonEncode = json_encode($subscribeAjaxMessage);
            echo $jsonEncode;
            exit;
        }
        $this->_redirectReferer();
    }

}
