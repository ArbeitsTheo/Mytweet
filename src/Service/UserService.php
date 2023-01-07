<?php 

declare(strict_types=1);

namespace App\Service;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

class UserService
{
    private UserRepository $userRepository;
    private Security $security; 

    public function __construct(
        UserRepository $userRepository,
        Security $security
    ) 
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }
    
    public function getUserConnected(): ?User
    {
        return $this->security->getUser();
    }
    
}
