<?php
namespace Studio404\Apollo\Observer;
require_once './app/code/Studio404/Apollo/Model/spaceinvoices.php';

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;
use \Studio404\Apollo\Helper\InvoiceHelper;
use \Magento\Directory\Model\CountryFactory;

class OrderData implements ObserverInterface
{
    public function __construct(InvoiceHelper $helper, CountryFactory $countryFactory) {
      $this->generalSettings = $helper->getGeneralSettings();
      $this->mailSettings = $helper->getMailSettings();
      $this->_countryFactory = $countryFactory;
      $this->storeName = $helper->getStoreName();
    }
    public function execute(Observer $observer)
    {
      setupSI($this->generalSettings);
      $order = $observer->getEvent()->getOrder();

      $itemsData = $order->getAllItems();

      //get county by id
      $country = $this->_countryFactory->create()->loadByCode($order->getBillingAddress()->getData()['country_id']);
      $order['country'] = $country->getName();

      $paymentInfo = $order->getPayment()->getData();
      $paymentType = $paymentInfo['method'];

      $msg = $this->mailSettings['pdf_msg'];


      if ($order->getStatus() === $this->mailSettings['order_status'] && $this->mailSettings['send_invoice'] === '1') {
        $documentData = generateApolloDocument('invoice', $order, $itemsData);
        sendPdf($order, 'invoice', $this->storeName, $msg);

      } else if ($paymentType === 'banktransfer' && $this->mailSettings['send_estimate'] === '1') {
        $documentData = generateApolloDocument('estimate', $order, $itemsData);
        sendPdf($order, 'estimate', $this->storeName, $msg);
      }
    }
}