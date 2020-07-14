<?php

namespace App\Security\Voter;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProjectVoter extends Voter
{
    public const PROJECT_EDIT = 'PROJECT_EDIT';

    protected function supports($attribute, $subject)
    {
        return $attribute === self::PROJECT_EDIT
            && $subject instanceof Project;
    }

    /**
     * @param string $attribute
     * @param Project $subject
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
            case self::PROJECT_EDIT:
                if ($subject->getUser()->getId() === $user->getId()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
