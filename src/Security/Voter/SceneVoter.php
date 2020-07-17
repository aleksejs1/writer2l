<?php

namespace App\Security\Voter;

use App\Entity\Scene;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SceneVoter extends Voter
{
    public const SCENE_VIEW = 'SCENE_VIEW';
    public const SCENE_EDIT = 'SCENE_EDIT';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::SCENE_VIEW, self::SCENE_EDIT])
            && $subject instanceof Scene;
    }

    /**
     * @param string $attribute
     * @param Scene $subject
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
            case self::SCENE_VIEW:
            case self::SCENE_EDIT:
                if (
                    $subject->getChapter()
                    && $subject->getChapter()->getProject()
                    && $subject->getChapter()->getProject()->getUser()
                    && $subject->getChapter()->getProject()->getUser()->getId() === $user->getId()
                ) {
                    return true;
                }
                break;
        }

        return false;
    }
}
