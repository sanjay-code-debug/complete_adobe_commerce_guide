<?php

/**
 * Codilar
 *
 * @package Codilar_CustomApi
 * @author Codilar
 * @copyright Copyright (c) 2023 Codilar (https://www.codilar.com/)
 */

namespace Codilar\CustomApi\Logger;

use Magento\Framework\Logger\Handler\Base;
use Monolog\Logger;
use Magento\Framework\Filesystem\DriverInterface;

class LogHandler extends Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/custom-logger.log';

    /**
     * File name
     * @var string
     */
    protected $filePath;

    /**
     * @var DriverInterface
     */
    protected $filesystem;

    /**
     * @param DriverInterface $filesystem
     */
    public function __construct(
        DriverInterface $filesystem
    ) {
        $this->filesystem = $filesystem;
    }

    /**
     * @throws \Exception
     */
    public function setLoggerName($loggerName)
    {
        if (empty($loggerName)) {
            $loggerName = 'custom_log';
        }
        $this->filePath = '/var/log/' . $loggerName . '.log';
        parent::__construct(
            $this->filesystem,
            null,
            $this->filePath
        );
    }
}
