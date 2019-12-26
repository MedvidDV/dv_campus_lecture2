<?php
declare(strict_types=1);

namespace Medvids\RecursiveData\Block;

class Block extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Medvids\RecursiveData\Model\DirectoryAndFileIterator
     */
    private $directoryIterator;
    /**
     * @var \Medvids\RecursiveData\Model\ConstantIterator
     */
    private $constantIterator;
    /**
     * @var \Medvids\RecursiveData\Model\DIConstructorInjection
     */
    private $constructorInjection;

    /**
     * Block constructor.
     * @param \Medvids\RecursiveData\Model\DirectoryAndFileIterator $directoryIterator
     * @param \Medvids\RecursiveData\Model\ConstantIterator $constantIterator
     * @param \Medvids\RecursiveData\Model\DIConstructorInjection $constructorInjection
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Medvids\RecursiveData\Model\DirectoryAndFileIterator $directoryIterator,
        \Medvids\RecursiveData\Model\ConstantIterator $constantIterator,
        \Medvids\RecursiveData\Model\DIConstructorInjection $constructorInjection,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->directoryIterator = $directoryIterator;
        $this->constantIterator = $constantIterator;
        $this->constructorInjection = $constructorInjection;
    }

    /**
     * @return array
     */
    public function getFileTree(): array
    {
        return $this->directoryIterator->createDirectoryIterator();
    }

    /**
     * @return array
     */
    public function getConstantTree(): array
    {
        return $this->constantIterator->getConstantArray();
    }

    /**
     * @return array
     */
    public function getPublicMethodsTree(): array
    {
        return $this->constantIterator->getPublicMethods();
    }

    /**
     * @return array
     */
    public function getDIConstructorTree(): array
    {
        return $this->constructorInjection->getConstructParameters();
    }
}
