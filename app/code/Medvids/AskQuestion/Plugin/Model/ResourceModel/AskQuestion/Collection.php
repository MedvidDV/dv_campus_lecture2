<?php
declare(strict_types=1);

namespace Medvids\AskQuestion\Plugin\Model\ResourceModel\AskQuestion;

use Medvids\AskQuestion\Model\ResourceModel\AskQuestion\Collection as QuestionCollection;

class Collection
{
    /**
     * @param QuestionCollection $subject
     */
    public function beforeLoad(QuestionCollection $subject): void
    {
        $subject->addStoreFilter()
            ->addSkuFilter();
    }
}
