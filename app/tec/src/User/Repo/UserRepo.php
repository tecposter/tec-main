<?php
namespace Tec\User\Repo;

use Tec\User\Dto\UserDto;
use Tec\User\Dto\RegDto;

use Gap\Valid\ValidPassword;
use Gap\Valid\ValidEmail;
use Gap\Valid\ValidNotEmpty;
use Gap\Valid\ValidWord;

use Gap\Security\PasshashProvider;
use Gap\Dto\DateTime;

class UserRepo extends RepoBase
{
    private $table = 'tec_user';

    public function fetchByEmail(string $inEmail): ?UserDto
    {
        $email = trim($inEmail);
        $ssb = $this->cnn->ssb()
            ->select(
                'userId',
                'email',
                'phone',
                'slug',
                'fullname',
                'passhash'
            )
            ->from($this->table)->end()
            ->where()
                ->expect('email')->equal()->str($email)
            ->end();
        return $ssb->fetch(UserDto::class);
    }

    public function reg(RegDto $reg): void
    {
        $password = trim($reg->password);
        $email = trim($reg->email);
        $phone = trim($reg->phone);
        $fullname = trim($reg->fullname);
        $slug = trim($reg->slug);

        (new ValidPassword())->assert($password);
        (new ValidEmail())->assert($email);
        (new ValidNotEmpty())->assert($phone);
        (new ValidWord())
            ->setMin(5)
            ->setMax(64)
            ->assert($slug);

        $this->assertNotExists('email', $email);
        $this->assertNotExists('phone', $phone);
        $this->assertNotExists('slug', $slug);
        $this->assertNotExists('fullname', $fullname);

        $now = new DateTime();
        $passhash = (new PasshashProvider())->hash($password);

        $this->cnn->isb()
            ->insert($this->table)
            ->field(
                'email',
                'phone',
                'slug',
                'fullname',
                'passhash',
                'created',
                'changed'
            )
            ->value()
                ->addStr($email)
                ->addStr($phone)
                ->addStr($slug)
                ->addStr($fullname)
                ->addStr($passhash)
                ->addDateTime($now)
                ->addDateTime($now)
            ->end()
            ->execute();
    }

    private function assertNotExists(string $field, $val): void
    {
        $existed = $this->cnn->ssb()
            ->select($field)
            ->from($this->table)->end()
            ->where()
                ->expect($field)->equal()->str($val)
            ->end()
            ->fetchAssoc();
        if ($existed) {
            throw new \Exception($field . ': ' . $val . ' already exists in ' . $this->table);
        }
    }
}
