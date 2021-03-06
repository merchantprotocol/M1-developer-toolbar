<?php

/**
 * Class MP_Debug_Model_Core_Email_Template rewrites core/email_template class in order
 * to capture e-mail variables.
 *
 * @method string getSenderName()
 * @method string getSenderEmail()
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 */
trait MP_Debug_Model_Core_Email_Template_Trait
{

    /**
     * Calls real send() method
     *
     * @param array|string $email
     * @param null         $name
     * @param array        $variables
     * @return bool
     */
    public function parentSend($email, $name = null, array $variables = array())
    {
        return parent::send($email, $name, $variables);
    }


    /**
     * Overwrites parent method to capture e-mail details
     *
     * @param array|string $email
     * @param null         $name
     * @param array        $variables
     * @return bool
     */
    public function send($email, $name = null, array $variables = array())
    {
        // store a reference to mail object that get populate by parent send()
        $zendMail = $this->getMail();

        $result = $this->parentSend($email, $name, $variables);

        try {
            $this->addEmailToProfile($email, $name, $variables, $result, $zendMail);
        } catch (Exception $e) {
            Mage::logException($e);
        }

        return $result;
    }


    /**
     * Adds e-mail information on request profiler
     *
     * @param           $email
     * @param           $name
     * @param           $variables
     * @param           $result
     * @param Zend_Mail $mail
     */
    public function addEmailToProfile($email, $name, $variables, $result, Zend_Mail $mail)
    {
        $emailCapture = Mage::getModel('mp_debug/email');

        $subject = $this->decodeSubject($mail->getSubject());
        $body = $this->getContent($mail);

        $emailCapture->setFromName($this->getSenderName());
        $emailCapture->setFromEmail($this->getSenderEmail());

        $emailCapture->setToEmail($email);
        $emailCapture->setToName($name);
        $emailCapture->setSubject($subject);
        $emailCapture->setIsPlain($this->isPlain());
        $emailCapture->setBody($body);
        $emailCapture->setIsAccepted($result);
        $emailCapture->setVariables($variables);

        Mage::getSingleton('mp_debug/observer')->getRequestInfo()->addEmail($emailCapture);
    }


    /**
     * Returns raw content attached to specified mail object
     *
     * @param Zend_Mail $mail
     * @return string
     */
    public function getContent(Zend_Mail $mail)
    {
        $hasQueue = $this->hasQueue();

        if ($hasQueue && $queue = $this->getQueue()) {
            return $queue->getMessageBody();
        }

        /** @var Zend_Mime_Part $mimePart */
        $mimePart = $this->isPlain() ? $mail->getBodyText() : $mail->getBodyHtml();

        return $this->getPartDecodedContent($mimePart);
    }


    /**
     * Returns raw content of e-mail message. Abstract Zend_Mime_Part interface changes between 1.11.0 and 1.12.0
     *
     * @param Zend_Mime_Part $mimePart
     * @return String
     */
    public function getPartDecodedContent(Zend_Mime_Part $mimePart)
    {

        // getRawContent is not available in Zend 1.11 (Magento CE 1.7)
        if (method_exists($mimePart, 'getRawContent')) {
            return $mimePart->getRawContent();
        }

        $encoding = $mimePart->encoding;
        $mimePart->encoding = 'none';
        $content = $mimePart->getContent();
        $mimePart->encoding = $encoding;

        return $content;
    }


    /**
     * Returns raw subject
     *
     * @param $subject
     * @return string
     */
    public function decodeSubject($subject)
    {
        if ($this->hasQueue() && $queue = $this->getQueue()) {
            return $queue->getMessageParameters('subject');
        }

        return base64_decode(substr($subject, strlen('=?utf-8?B?'), -1 * strlen('?=')));
    }

}
