<?php
declare(strict_types=1);

namespace Medvids\RecursiveData\Model;

class DIConstructorInjection
{
    /**
     * @var string
     */
    public $stringParam;

    /**
     * @var DirectoryAndFileIterator
     */
    private $instanceParam;

    /**
     * @var bool
     */
    private $boolParam;

    /**
     * @var int
     */
    private $intParam;

    /**
     * @var int
     */
    private $constantParam;

    /**
     * @var array
     */
    private $arrayParam;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * DIConstructorInjection constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param string $stringParam
     * @param DirectoryAndFileIterator $instanceParam
     * @param bool $boolParam
     * @param int $intParam
     * @param int $constantParam
     * @param array $arrayParam
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        string $stringParam,
        \Medvids\RecursiveData\Model\DirectoryAndFileIterator $instanceParam,
        bool $boolParam,
        int $intParam,
        int $constantParam,
        array $arrayParam
    ) {
        $this->stringParam = $stringParam;
        $this->instanceParam = $instanceParam;
        $this->boolParam = $boolParam;
        $this->intParam = $intParam;
        $this->constantParam = $constantParam;
        $this->arrayParam = $arrayParam;
        $this->logger = $logger;
    }



    /**
     * @return array
     */
    public function getConstructParameters(): array
    {
        $parametersArray = [];
        try {
            $reflection = new \ReflectionClass($this);
            $parameters = $reflection->getConstructor()->getParameters();
            foreach ($parameters as $parameter) {
                if ($parameter->getClass()) {
                    $parametersArray[] = [
                        'name' => $parameter->getName(),
                        'type' => $parameter->getType(),
                        'value' => $parameter->getType()
                    ];
                } elseif ($parameter->isArray()) {
                    $reflectionProp = $reflection->getProperty($parameter->getName());
                    $reflectionProp->setAccessible(true);
                    $parametersArray[] = [
                        'name' => $parameter->getName(),
                        'type' => $parameter->getType(),
                        'value' => print_r($reflectionProp->getValue($this), true)
                    ];
                } else {
                    $reflectionProp = $reflection->getProperty($parameter->getName());
                    $reflectionProp->setAccessible(true);
                    $parametersArray[] = [
                        'name' => $parameter->getName(),
                        'type' => $parameter->getType(),
                        'value' => $reflectionProp->getValue($this)
                    ];
                }
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $parametersArray;
    }
}
