<?php
declare(strict_types=1);

namespace Medvids\DemoController\Controller\Index;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Forward as ForwardResult;

class Forward extends \Magento\Framework\App\Action\Action
{

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /** @var ForwardResult $response */
        $response = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        $response->setController('index')
            ->setParams([
                'first_name' => 'Sergii',
                'last_name' => 'Medvid',
                'repository' => 'https://github.com/MedvidDV/dvcampus_magento_demo/'
            ])
            ->forward('data');

        return $response;
    }
}
