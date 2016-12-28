<?php

namespace VersionPress\ChangeInfos;

use Nette\NotSupportedException;
use Nette\Utils\Strings;
use VersionPress\Utils\StringUtils;

class BulkChangeInfo implements ChangeInfo
{

    /** @var TrackedChangeInfo[] */
    protected $changeInfos;
    /** @var int */
    protected $count;

    /**
     * @param TrackedChangeInfo[] $changeInfos
     */
    public function __construct(array $changeInfos)
    {
        $this->changeInfos = $changeInfos;
        $this->count = $this->countUniqueChanges($changeInfos);
    }

    public function getCommitMessage()
    {
        throw new NotSupportedException("Commit message is created in ChangeInfoEnvelope from original objects");
    }

    /**
     * Returns original ChangeInfo objects.
     *
     * @return TrackedChangeInfo[]
     */
    public function getChangeInfos()
    {
        return $this->changeInfos;
    }

    public function getChangeDescription()
    {
        $entityName = $this->getScope();
        $action = $this->getAction();

        if ($this->count === 1) {
            $defaultDescription = $this->changeInfos[0]->getChangeDescription();
        } else {
            $defaultDescription = sprintf(
                "%s %d %s",
                Strings::capitalize(StringUtils::verbToPastTense($action)),
                $this->count,
                StringUtils::pluralize($entityName)
            );
        }

        $tags = array_map(function (TrackedChangeInfo $changeInfo) {
            return $changeInfo->getCustomTags();
        }, $this->changeInfos);

        return apply_filters("vp_bulk_change_description_{$entityName}", $defaultDescription, $action, $this->count, $tags);
    }

    public function getAction()
    {
        return $this->changeInfos[0]->getAction();
    }

    public function getScope()
    {
        return $this->changeInfos[0]->getScope();
    }

    public function getPriority()
    {
        return $this->changeInfos[0]->getPriority();
    }

    /**
     * @param TrackedChangeInfo[] $changeInfos
     * @return int
     */
    private function countUniqueChanges($changeInfos)
    {
        if (!($changeInfos[0] instanceof EntityChangeInfo)) {
            return count($changeInfos);
        }

        /** @var EntityChangeInfo[] $changeInfos */
        $numberOfUniqueChanges = 0;
        $uniqueEntities = [];

        foreach ($changeInfos as $changeInfo) {
            if (!in_array($changeInfo->getId(), $uniqueEntities)) {
                $numberOfUniqueChanges += 1;
                $uniqueEntities[] = $changeInfo->getId();
            }
        }

        return $numberOfUniqueChanges;
    }
}
