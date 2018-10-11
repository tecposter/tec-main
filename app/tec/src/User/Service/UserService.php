<?php
namespace Tec\User\Service;

use Tec\User\Dto\UserDto;
use Tec\User\Dto\RegDto;
use Tec\User\Repo\UserRepo;

class UserService extends ServiceBase
{
    public function fetchByEmail(string $email): ?UserDto
    {
        return (new UserRepo($this->getDmg()))->fetchByEmail($email);
    }

    public function reg(RegDto $regDto): void
    {
        (new UserRepo($this->getDmg()))->reg($regDto);
    }
}
