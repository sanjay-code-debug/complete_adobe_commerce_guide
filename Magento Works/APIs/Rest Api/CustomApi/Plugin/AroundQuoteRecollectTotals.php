<?php

namespace Codilar\CustomApi\Plugin;

use Magento\Framework\Event\Observer;
use Magento\SalesRule\Model\Spi\RuleQuoteRecollectTotalsInterface;
use Magento\SalesRule\Observer\RuleQuoteRecollectTotalsObserver;

class AroundQuoteRecollectTotals
{
    /**
     * @var RuleQuoteRecollectTotalsInterface
     */
    private RuleQuoteRecollectTotalsInterface $recollectTotals;

    /**
     * @param RuleQuoteRecollectTotalsInterface $recollectTotals
     */
    public function __construct(RuleQuoteRecollectTotalsInterface $recollectTotals)
    {
        $this->recollectTotals = $recollectTotals;
    }

    /**
     * Validating and trigger recollect total
     *
     * @param RuleQuoteRecollectTotalsObserver $recollectTotals
     * @param callable $proceed
     * @param Observer $observer
     * @return void
     */
    public function aroundExecute(
        RuleQuoteRecollectTotalsObserver $recollectTotals,
        callable $proceed,
        Observer $observer
    ) {
        $rule = $observer->getRule();
        if (!$rule->isObjectNew() && ( (isset($rule->getStoredData()['rule_is_for']) &&
                $rule->getData("rule_is_for") != $rule->getStoredData()['rule_is_for']
            ) || !$rule->getIsActive() || $rule->isDeleted()
            )
        ) {
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/rule_collecter.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info("Quote collecter called for rule ". $rule->getRuleId());
            $this->recollectTotals->execute((int) $rule->getId());
        }
    }
}
