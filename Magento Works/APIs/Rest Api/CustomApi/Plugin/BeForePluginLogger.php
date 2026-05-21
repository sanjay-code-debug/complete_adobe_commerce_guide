<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Plugin;

use Codilar\CustomApi\Logger\LogHandler;
use Monolog\Logger as SubjectInterface;

class BeForePluginLogger
{
    /**
     * @var LogHandler
     */
    private LogHandler $logHandler;

    /**
     * @param LogHandler $logHandler
     */
    public function __construct(
        LogHandler $logHandler
    ) {
        $this->logHandler = $logHandler;
    }

    /**
     * Adds a log record at the INFO level and performs additional custom functionality.
     *
     * @param SubjectInterface $subject
     * @param string $message The log message
     * @param array $context
     * @return array
     * @throws \Exception
     */
    public function beforeInfo(
        SubjectInterface $subject,
        string $message,
        array $context = []
    ) {
        // check if the message contains #
        if (strpos($message, 'Codilar') !== false) {
            $parts = explode("Codilar", $message);
            $fileName =  $parts[0];
            $message = $parts[1];
            $this->logHandler->setLoggerName($fileName);
            return [$message, $context]; // Return an array containing the message and context
        }
        // return the default
        return [$message , $context];
    }
}
