<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;


class UserVoter extends Voter
{

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['ROLE_PROFIL_UPDATE', 'ROLE_USER_DELETE'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {dump($subject);
    dump($token->getUser());
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'ROLE_PROFIL_UPDATE':
                return $this->canUpdate($subject, $token);
            case 'ROLE_USER_DELETE':
                return $this->canDelete($subject, $token);
        }

        return false;
    }


    public function canUpdate($subject, $token): bool
    {
        return $this->canDelete($subject, $token);
    }


    public function canDelete($subject, $token): bool
    {
        if($this->decisionManager->decide($token, ['ROLE_ADMIN']))
        {
            return true;
        }

        return $subject === $token->getUser();
    }
}
