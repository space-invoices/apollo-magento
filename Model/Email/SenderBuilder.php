<?php
namespace Studio404\Apollo\Model\Email;

use Magento\Sales\Model\Order\Email\Container\IdentityInterface;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\Order\Invoice;

class SenderBuilder extends \Magento\Sales\Model\Order\Email\SenderBuilder
{
    private $dateTime;

    public function __construct(
        Template $templateContainer,
        IdentityInterface $identityContainer,
        TransportBuilder $transportBuilder
    ) {
        parent::__construct($templateContainer, $identityContainer, $transportBuilder);
    }

    /**
     * Add attachment to the main mail
     */
    public function send()
    {
        $vars = $this->templateContainer->getTemplateVars();
        $this->addDocument($vars);

        parent::send();
    }

    /**
     * Add attachment to the css/bcc mail
     */
    public function sendCopyTo()
    {
        $vars = $this->templateContainer->getTemplateVars();
        $this->addDocument($vars);
        parent::sendCopyTo();
    }

    /**
     *
     * Check if we need to send the invoice email
     *
     * @param $vars
     * @return $this
     */
    private function addDocument($vars)
    {
        $body = file_get_contents("S:\wamp\www\magento\app\code\Studio404\Apollo\pdfs\invoice201900001.pdf");

        $this->transportBuilder->addAttachment(
            $body,
            \Zend_Mime::TYPE_OCTETSTREAM,
            \Zend_Mime::DISPOSITION_ATTACHMENT,
            \Zend_Mime::ENCODING_BASE64,
            "testname.pdf"
        );

        return $this;
    }
}
