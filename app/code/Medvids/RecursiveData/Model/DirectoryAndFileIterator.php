<?php
declare(strict_types=1);

namespace Medvids\RecursiveData\Model;

class DirectoryAndFileIterator
{
    /**
     * @var \Psr\Log\LoggerInterface;
     */
    private $logger;
    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * DirectoryIterator constructor.
     * @param \Psr\Log\LoggerInterface; $logger
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Filesystem\DirectoryList $directoryList
    ) {
        $this->logger = $logger;
        $this->directoryList = $directoryList;
    }

    /**
     * @return array
     */
    public function createDirectoryIterator(): array
    {
        $fileTree = [];
        try {
            $path = $this->directoryList->getPath('app') . '/code';
            $directory = new \RecursiveDirectoryIterator(
                $path,
                \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_SELF
            );
            $iterator = new \RecursiveIteratorIterator(
                $directory,
                \RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($iterator as $item) {
                if ($item->isDot()) {
                    continue;
                }

                $strippedPath = str_replace($path, '', $item->getPathname());
                $levelCount = count(explode('/', $strippedPath)) - 2;

                $fileTree[] = [
                    'name' => $item->getFilename(),
                    'path' => $item->getPathname(),
                    'isDir' => $item->isDir(),
                    'level' => $levelCount,
                    'createdAt' => date('Y/m/d H:i', $item->getCTime())
                ];
            }
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return $fileTree;
    }
}
