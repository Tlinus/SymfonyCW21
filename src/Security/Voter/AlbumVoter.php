<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;


class AlbumVoter extends Voter
{

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['ROLE_ALBUM_UPDATE', 'ROLE_ALBUM_DELETE'])
            && $subject instanceof \App\Entity\Album;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case 'ROLE_ALBUM_UPDATE':
                return $this->canUpdate($subject, $token);
            case 'ROLE_ALBUM_DELETE':
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

        return $subject->getUser() === $token->getUser();
    }
}
