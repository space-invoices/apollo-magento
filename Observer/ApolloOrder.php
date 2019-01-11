<?php
class ApolloOrder implements \Magento\Framework\Event\ObserverInterface
{

    protected $logger;
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
      $this->logger = $logger;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
      $this->logger->debug("we was here");
     $order = $observer->getEvent()->getOrder();
    //  echo $orderId = $order->getId();
     $comment = $this->getRequest()->getParam('comment');
     print_r("Catched event succssfully !"); exit;
    }
}