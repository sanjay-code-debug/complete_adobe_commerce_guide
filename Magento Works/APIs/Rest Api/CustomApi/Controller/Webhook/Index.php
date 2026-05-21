<?php

namespace Codilar\CustomApi\Controller\Webhook;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Psr\Log\LoggerInterface;

class Index implements HttpPostActionInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResponseInterface
     */
    protected $response;

    public function __construct(
        LoggerInterface $logger,
        RequestInterface $request,
        ResponseInterface $response
    ) {
        $this->logger = $logger;
        $this->request = $request;
        $this->response = $response;
    }

    public function execute()
    {
        $post = $this->request->getPostValue();

        // Logging the received data for traceability
        $this->logger->info('Received webhook data: ' . json_encode($post));

        try {
            // Processing webhook data
            // ... [your processing logic]

            // Respond to webhook sender
            $this->response->setBody('Webhook processed.');
        } catch (\Exception $e) {
            $this->logger->error('Error processing webhook: ' . $e->getMessage());
            $this->response->setBody('Error processing webhook.');
        }

        return $this->response;
    }
}
