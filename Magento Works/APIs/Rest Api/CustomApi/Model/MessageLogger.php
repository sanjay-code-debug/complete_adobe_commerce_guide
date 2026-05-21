<?php

namespace Codilar\CustomApi\Model;

/**
 * Print message class
 */
class MessageLogger
{
    /**
     * print log
     *
     * @param string $apiEndPoint
     * @param string $area
     * @param string $time
     * @param string $message
     * @return true
     * @throws \Zend_Log_Exception
     */
    public function printMessage(string $apiEndPoint, string $area, string $time, string $message)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/mobile_app_logger.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info("Api end Point is --> " . $apiEndPoint);
        $logger->info("Area is " . $area);
        $logger->info("Log time ". $time);
        $logger->info("<---------- Log data is -------------->");
        $logger->info($message);
        return true;
    }
}
