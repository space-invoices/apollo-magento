<?php
require_once __DIR__.'/spaceinvoices/vendor/autoload.php';

function setupSI($configData) {
  global $si_organization, $pdfs_path;
  $pdfs_path = getcwd()."/app/code/Studio404/Apollo/pdfs";

  if ($configData['sandbox_mode'] === '1') {
    if (!$configData['apollo_token_sandbox'] || !$configData['apollo_organization_sandbox']) {
      return "Set Apollo sandbox data!";
    }
    Spaceinvoices\Spaceinvoices::setMode('sandbox');
    Spaceinvoices\Spaceinvoices::setAccessToken($configData['apollo_token_sandbox']);
    $si_organization = $configData['apollo_organization_sandbox'];
  } else {
    if (!$configData['apollo_token'] || !$configData['apollo_organization']) {
      return "Set Apollo production data!";
    }
    Spaceinvoices\Spaceinvoices::setMode('production');
    Spaceinvoices\Spaceinvoices::setAccessToken($configData['apollo_token']);
    $si_organization = $configData['apollo_organization'];
  }
}

function generateApolloDocument($type, $orderData, $items) {
  global $si_organization;

  $billingData = $orderData->getBillingAddress()->getData();
  $orderInfo = $orderData->getData();
  $paymentInfo = $orderData->getPayment()->getData();

  $document_exsists = exists($orderData, $type);
  if ($document_exsists) {
    return $document_exsists;
  }

  if (!$orderData || !$items) {
    return false;
  }

  $SI_products_data = array();
  $SI_total = 0;
  $order_total = 0;

  foreach ($items as $item_data) {
    $product = $item_data->getData();

    $SI_product = array(
      'name'     => $product['name'],
      'unit'     => 'item',
      'quantity' => $product['qty_ordered'],
      'price'    => $product['price'],
      '_documentItemTaxes' => array(array('rate' => $product['tax_percent']))
      );

      if ($product['discount_percent'] !== '0.0000') {
        $SI_product['discount'] = $product['discount_percent'];
      } else if ($product['discount_amount'] !== '0.0000') {
        $SI_product['discountIsAmount'] = true;
        $SI_product['discount'] = $product['discount_amount'];
      }

      $SI_products_data[] = $SI_product;
    }

  if ($orderInfo['shipping_method'] != '') {
    $SI_shipping = array(
      'name'     => $orderInfo['shipping_description'],
      'unit'     => 'shipping',
      'quantity' => 1,
      'price'    => $orderInfo['shipping_amount'],
    );
    if ($orderInfo['shipping_amount'] !== $orderInfo['shipping_incl_tax']) {
      $taxPrice = intval($orderInfo['shipping_incl_tax']);
      $shipPrice = intval($orderInfo['shipping_amount']);
      if ($taxPrice > $shipPrice) {
        $shippingTaxRate = ($taxPrice / $shipPrice) -1;

        $SI_shipping['_documentItemTaxes'] = array(array('rate' => $shippingTaxRate));
      }
    }
    if ($orderInfo['shipping_discount_amount'] !== '0.0000') {
      $SI_shipping['discountIsAmount'] = true;
      $SI_shipping['discount'] = $orderInfo['shipping_discount_amount'];
      // $SI_shipping['discount'] = '25.5';
    }

    $SI_products_data[] = $SI_shipping;
  }

  $order_data = array(
    "type" => $type,
    "currencyId" => $orderData->getOrderCurrencyCode(),
    "_documentClient" => array(
      'name' 		=> $billingData['company'] ? $billingData['company'] : $billingData['firstname'] . ' ' .$billingData['lastname'],
      'contact' => $billingData['company'] ? $billingData['firstname'] . ' ' .$billingData['lastname'] : '',
      'address' => $billingData['street'],
      'city'    => $billingData['city'] . ', ' . $billingData['region'],
      'zip'			=> $billingData['postcode'],
      'country' => $orderData['country'],
      'email' 	=> $billingData['email'],
      'phone' 	=> $billingData['telephone']
    ),
    "_documentItems" => $SI_products_data
  );
  $create = Spaceinvoices\Documents::create($si_organization, $order_data);

  if (isset($create->error)) {
    return $create;
  }

  $document_id = $create->id;
  $document_number = $create->number;


  $document_data = array(
    "type" => $type,
    "id" => $document_id,
    "number" => $document_number,
    "sent" => false,
    "apollo_url" => "https://getapollo.io/app/$si_organization/documents/view/".$document_id
  );

  if ($type === 'invoice') {
    $orderData->setData('apollo_invoice_id', $document_id)->save();
    $orderData->setData('apollo_invoice_number', $document_number)->save();
    $orderData->setData('apollo_invoice_sent', false)->save();


    Spaceinvoices\Payments::create($document_id, array( // mark invoice as paid
      "type" => "other",
      "date" => date("Y-m-d"),
      "amount" => round(floatval($orderData['grand_total']),2),
      "note" => $paymentInfo['additional_information']['method_title']
    ));

  } else if ($type === 'estimate') {
    $orderData->setData('apollo_estimate_id', $document_id)->save();
    $orderData->setData('apollo_estimate_number', $document_number)->save();
    $orderData->setData('apollo_estimate_sent', false)->save();
  }

  return $document_data;
}

function exists($order, $type) { // check if invoice or estimate exists
  if ($type === 'invoice') {
    return getInvoice($order);
  } else if ($type === 'estimate') {
    return getEstimate($order);
  }
  return false;
}

function getInvoice($order) {
  global $si_organization;
  if (!empty($order->getData('apollo_invoice_id'))) {
    return array(
      "type" => "invoice",
      "id" => $order->getData('apollo_invoice_id'),
      "number" => $order->getData('apollo_invoice_number'),
      "sent" => $order->getData('apollo_invoice_sent'),
      "apollo_url" => "https://getapollo.io/app/$si_organization/documents/view/".$order->getData('apollo_invoice_id')
    );
  }
  return false;
}

function getEstimate($order) {
  global $si_organization;
  if (!empty($order->getData('apollo_estimate_id'))) {
    return array(
      "type" => "estimate",
      "id" => $order->getData('apollo_estimate_id'),
      "number" => $order->getData('apollo_estimate_number'),
      "sent" => $order->getData('apollo_estimate_sent'),
      "apollo_url" => "https://getapollo.io/app/$si_organization/documents/view/".$order->getData('apollo_estimate_id')
    );
  }
  return false;
}

function getPdf($id, $number, $type) { // download PDF file and return path
  global $pdfs_path;

  $pdf_path = $pdfs_path."/".$type." - ".$number.".pdf";
  if (file_exists($pdf_path)) {
    return $pdf_path;
  }

  $pdf = Spaceinvoices\Documents::getPdf($id);

  if(isset($pdf->error)) {
    // return $pdf;
    return "Error creating PDF";
  }

  $pdf_path = $pdfs_path."/".$type." - ".$number.".pdf";

  if(!file_exists(dirname($pdf_path)))
    mkdir(dirname($pdf_path), 0777, true);

    $fp = fopen($pdf_path,"wb");
    fwrite($fp,$pdf);
    fclose($fp);

    return $pdf_path;
}

function viewPdf($id, $number, $type) { // show pdf
  global $pdfs_path;

  $pdf_path = $pdfs_path."/".$type." - ".$number.".pdf";
  if (!file_exists($pdf_path)) {
    $pdf_path = getPdf($id, $number, $type);
  }
  header( 'Content-type: application/pdf' );
  header( 'Content-Disposition: inline; filename="' . basename( $pdf_path ) . '"' );
  header( 'Content-Transfer-Encoding: binary' );
  header( 'Content-Length: ' . filesize( $pdf_path ) );
  header( 'Accept-Ranges: bytes' );

  readfile( $pdf_path );
}

function sendPdf($order, $type, $store = '', $msg = '') {
  $billingData = $order->getBillingAddress()->getData();
  $email = $billingData['email'];
  if ($type === 'estimate') {
    $id = $order->getData('apollo_estimate_id');
    $subject = "$store order ".$order->getId()." - estimate";
    $order->setData('apollo_estimate_sent', true)->save();

  } else {
    $id = $order->getData('apollo_invoice_id');
    $subject = "$store order ".$order->getId()." - invoice";
    $order->setData('apollo_invoice_sent', true)->save();
  }

  return Spaceinvoices\Documents::send($id, array(
    "recipients" => $email,
    "subject" => $subject,
    "message" => $msg,
  ));
}