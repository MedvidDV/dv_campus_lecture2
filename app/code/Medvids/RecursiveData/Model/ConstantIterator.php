<?php
declare(strict_types=1);

namespace Medvids\RecursiveData\Model;

use Magento\Framework\View\Element\Template;

class ConstantIterator extends \Magento\Framework\View\Element\Template
{
    private const PI = 3.14;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * ConstantIterator constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->logger = $logger;
    }

    /**
     * @return array
     */
    public function getConstantArray(): array
    {
        $constantList = [];

        try {
            $reflection = new \ReflectionClass($this);
            $constantList = $reflection->getConstants();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $constantList;
    }

    public function getPublicMethods()
    {
        $methodsList = [];

        try {
            $reflection = new \ReflectionClass($this);
            $methodsList = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        } catch (\Exception $e) {
            $e->getMessage();
        }

        return $methodsList;
    }
}
