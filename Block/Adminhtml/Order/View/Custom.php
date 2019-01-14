<?php
namespace Studio404\Apollo\Block\Adminhtml\Order\View;
require_once dirname(__FILE__).'../../../../../Model/spaceinvoices.php';

class Custom extends \Magento\Backend\Block\Template
{
  public function __construct(
    \Studio404\Apollo\Helper\InvoiceHelper $invoiceHelper,
    \Magento\Framework\App\Request\Http $request,
    \Magento\Backend\Block\Template\Context $context,
    \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
    \Magento\Directory\Model\CountryFactory $countryFactory,
    \Magento\Framework\Message\ManagerInterface $messageManager,
    array $data = []
    ) {
    $this->invoiceHelper = $invoiceHelper;
    $this->request = $request;
    $this->orderRepository = $orderRepository;
    $this->_countryFactory = $countryFactory;
    $this->_messageManager = $messageManager;

    parent::__construct($context, $data);

    // send data to SI model
    setupSI($this->invoiceHelper->getGeneralSettings());

    $orderId = $this->request->getParam('order_id');

    $this->generateInvoiceUrl = '';
    $this->generateEstimateUrl = '';

    $this->invoiceData = false;
    $this->estimateData = false;

    $order = $this->orderRepository->get($orderId);
    $itemsData = $order->getAllItems();

    //get county by id
    $country = $this->_countryFactory->create()->loadByCode($order->getBillingAddress()->getData()['country_id']);
    $order['country'] = $country->getName();

    $this->generateInvoiceUrl = $this->getUrl('*/*/*', ['order_id' => $orderId, 'generate' => 'invoice']);
    $this->generateEstimateUrl = $this->getUrl('*/*/*', ['order_id' => $orderId, 'generate' => 'estimate']);

    $this->invoiceData = getInvoice($order);
    $this->estimateData = getEstimate($order);

    if ($this->invoiceData) {
      $this->invoiceData['view_pdf'] = $this->getUrl('*/*/*', ['order_id' => $orderId, 'viewpdf' => 'invoice']);
      $this->invoiceData['send_mail'] = $this->getUrl('*/*/*', ['order_id' => $orderId, 'send_mail' => 'invoice']);
    }

    if ($this->estimateData) {
      $this->estimateData['view_pdf'] = $this->getUrl('*/*/*', ['order_id' => $orderId, 'viewpdf' => 'estimate']);
      $this->estimateData['send_mail'] = $this->getUrl('*/*/*', ['order_id' => $orderId, 'send_mail' => 'estimate']);
    }

    if($this->request->getParam('send_mail')) {
      $newURL = $this->getUrl('*/*/*', ['order_id' => $orderId, 'send_mail' => 'sent']);
      $storeName = $this->invoiceHelper->getStoreName();
      $msg = $this->invoiceHelper->getMailSettings()['pdf_msg'];

      if ($this->request->getParam('send_mail') === 'invoice') {
        $pdf = sendPdf($order, 'invoice', $storeName, $msg);
        if (isset($pdf->success) && $pdf->success === true) {
          header('Location: '.$newURL);
          die();
        } else {
          $this->_messageManager->addError(__("There was error sending email. (Error: ".$pdf->error->message.")"));
        }

      } else if ($this->request->getParam('send_mail') === 'estimate') {
        $pdf = sendPdf($order, 'estimate', $storeName, $msg);
        if (isset($pdf->success) && $pdf->success === true) {
          header('Location: '.$newURL);
          die();
        } else {
          $this->_messageManager->addError(__("There was error sending email. (Error: ".$pdf->error->message.")"));
        }
      } else {
        $this->_messageManager->addSuccess(__("PDF successfully sent to costumer."));
      }

    } else if($this->request->getParam('viewpdf')) {
      if ($this->request->getParam('viewpdf') === 'invoice') {
        viewPdf($this->invoiceData['id'], $this->invoiceData['number'], 'invoice');
      } else if ($this->request->getParam('viewpdf') === 'estimate') {
        viewPdf($this->estimateData['id'], $this->estimateData['number'], 'estimate');
      }

    } else if($this->request->getParam('generate')) {
      $documentData = generateApolloDocument($this->request->getParam('generate'), $order, $itemsData);

      if (isset($documentData->error)) {
        $this->_messageManager->addError(__("Error while generating document. (Error: ".$documentData->error->message.")"));
      } else {
        $this->_messageManager->addSuccess(__("Document successfully created."));
        if ($this->request->getParam('generate') === 'invoice') {
          $this->invoiceData = $documentData;
          $this->invoiceData['view_pdf'] = $this->getUrl('*/*/*', ['order_id' => $orderId, 'viewpdf' => 'invoice']);
          $this->invoiceData['send_mail'] = $this->getUrl('*/*/*', ['order_id' => $orderId, 'send_mail' => 'invoice']);
        } else if ($this->request->getParam('generate') === 'estimate') {
          $this->estimateData = $documentData;
          $this->estimateData['view_pdf'] = $this->getUrl('*/*/*', ['order_id' => $orderId, 'viewpdf' => 'estimate']);
          $this->estimateData['send_mail'] = $this->getUrl('*/*/*', ['order_id' => $orderId, 'send_mail' => 'estimate']);
        }
      }


    }
    // var_dump($this->request->getParams());
  }
}