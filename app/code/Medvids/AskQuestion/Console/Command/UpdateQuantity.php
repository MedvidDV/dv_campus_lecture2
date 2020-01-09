<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Console\Command;

use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\Area;

class UpdateQuantity extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $productFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\App\State
     */
    private $state;

    /**
     * @var \Magento\CatalogInventory\Model\Stock\ItemFactory
     */
    private $itemFactory;

    /**
     * UpdateQuantity constructor.
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\CatalogInventory\Model\Stock\ItemFactory $itemFactory
     * @param LoggerInterface $logger
     * @param \Magento\Framework\App\State $state
     * @param string|null $name
     */
    public function __construct(
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\CatalogInventory\Model\Stock\ItemFactory $itemFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\State $state,
        string $name = null
    ) {
        parent::__construct($name);
        $this->name = $name;
        $this->productFactory = $productFactory;
        $this->logger = $logger;
        $this->state = $state;
        $this->itemFactory = $itemFactory;
    }

    protected function configure(): void
    {
        $this->setName('ask-question:update-quantity')
            ->setDescription('Update product quantity by provided product ID and new quantity')
            ->setDefinition([
                new InputArgument(
                    'productId',
                    InputArgument::REQUIRED,
                    'Product Id that has to be updated'
                ),
                new InputArgument(
                    'quantity',
                    InputArgument::REQUIRED,
                    'Updated product quantity'
                )
            ]);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->state->emulateAreaCode(
                Area::AREA_ADMINHTML,
                function (int $productId, float $quantity) use ($output) {
                    if ($quantity < 0) {
                        throw new LocalizedException(__('Quantity cannot be less then 0'));
                    }
                    /** @var \Magento\CatalogInventory\Model\Stock\Item $stockItem */
                    $stockItem = $this->itemFactory->create();
                    $stockItem->load($productId, 'product_id');
                    $stockItem->setQty($quantity);
                    $stockItem->save();
//                    $product = $this->productFactory->create();
//                    $product->load($productId);
//                    $name = $product->getName();
//                    $product->setQty($quantity);
//                    $product->save();
                    $output->writeln("Product id#$productId updated successfully");
                },
                [
                    $input->getArgument('productId'),
                    $input->getArgument('quantity')
                ]
            );
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $output->writeln('Error: ' . $e->getMessage());
        }
    }
}
