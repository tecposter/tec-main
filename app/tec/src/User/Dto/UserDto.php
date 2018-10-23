<?php
namespace Tec\User\Dto;

use Gap\Security\PasshashProvider;

class UserDto extends DtoBase
{
    public $userId;
    public $email;
    public $phone;
    public $slug;
    public $fullname;
    public $passhash;

    public function verifyPassword(string $password): bool
    {
        return (new PasshashProvider())->verify($password, $this->passhash);
    }
}
