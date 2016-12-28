<?php
namespace VersionPress\ChangeInfos;

use VersionPress\Git\CommitMessage;

/**
 * Represents commits that were not created by VersionPress and we don't know
 * much about them. This ChangeInfo type is a fallback type when no better
 * match is found by ChangeInfoFactory.
 *
 * @see TrackedChangeInfo
 */
class UntrackedChangeInfo implements ChangeInfo
{

    /** @var CommitMessage */
    private $commitMessage;

    public function __construct(CommitMessage $commitMessage)
    {
        $this->commitMessage = $commitMessage;
    }

    public function getCommitMessage()
    {
        return $this->commitMessage;
    }

    public function getChangeDescription()
    {
        return $this->commitMessage->getUnprefixedSubject();
    }

    public function getPriority()
    {
        return 0;
    }
}
