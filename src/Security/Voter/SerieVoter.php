<?php

namespace App\Security\Voter;

use App\Entity\Serie;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SerieVoter extends Voter
{
    public const EDIT = 'SERIE_EDIT';
    public const VIEW = 'SERIE_VIEW';
    public const DELETE = 'SERIE_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof Serie;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return (\in_array('ROLE_AUTEUR', $user->getRoles()) && $subject->getStatus() === 'ended' || \in_array('ROLE_ADMIN', $user->getRoles()));
            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                break;
            case self::DELETE:
                return (\in_array('ROLE_ADMIN', $user->getRoles()) && $subject->getStatus() === 'ended');
        }

        return false;
    }
}
