<?php

namespace App\Security\Voter;

use App\Entity\Chapter;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ChapterVoter extends Voter
{
    public const CHAPTER_VIEW = 'CHAPTER_VIEW';
    public const CHAPTER_EDIT = 'CHAPTER_EDIT';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::CHAPTER_VIEW, self::CHAPTER_EDIT])
            && $subject instanceof Chapter;
    }

    /**
     * @param string $attribute
     * @param Chapter $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof User) {
            return false;
        }

        switch ($attribute) {
            case self::CHAPTER_VIEW:
            case self::CHAPTER_EDIT:
                if (
                    $subject->getProject()
                    && $subject->getProject()->getUser()
                    && $subject->getProject()->getUser()->getId() === $user->getId()
                ) {
                    return true;
                }
                break;
        }

        return false;
    }
}
