<?php

/**
 * Various Helper functions for the Aschroder.com SMTP Pro module.
 *
 *
 * @author Ashley Schroder (aschroder.com)
 * @copyright  Copyright (c) 2010 Ashley Schroder
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Aschroder_SMTPPro_Helper_Debug extends Mage_Core_Helper_Abstract {

    const LOG_FILE = "smtppro.log";
    

    public function isEnabled($storeId = null)
    {
        return Mage::helper('smtppro/config')->isEnabled($storeId);
    }
    
    public function isLogEnabled($storeId = null)
    {
        return Mage::helper('smtppro/config')->isLogEnabled($storeId);
    }
    
    public function getDevelopmentMode($storeId = null)
    {
        return Mage::helper('smtppro/config')->getDevelopmentMode($storeId);
    }

    public function isDebugLoggingEnabled($storeId = null)
    {
        return Mage::helper('smtppro/config')->isDebugLoggingEnabled($storeId);
    }

    
    /**
     * Write to the email log table with an email's details provided.
     * @param  [type] $to       
     * @param  [type] $template 
     * @param  [type] $subject  
     * @param  [type] $email    
     * @param  [type] $isHtml   
     * @return $this
     */
    public function emailLog($to, $template, $subject, $email, $isHtml)
    {
        if (!$this->isLogEnabled()) {
            return $this;
        }

        $log = Mage::getModel('smtppro/email_log')
            ->setEmailTo($to)
            ->setTemplate($template)
            ->setSubject($subject)
            ->setEmailBody(  $isHtml ? $email : nl2br($email)  )
            ->save();

        return $this;
    }


    /**
     * Log to the file log (smtppro.log)
     * @param  [type] $m 
     * @return [type]    
     */
    public function fileLog($m)
    {
        if ($this->isDebugLoggingEnabled()) {
            Mage::log($m, null, self::LOG_FILE);
        }
        return $this;
    }

    /**
     * @alias fileLog();
     * @param  $m)    $m 
     * @return [type]    
     */
    public function log($m) { return $this->fileLog($m); }
    
}
