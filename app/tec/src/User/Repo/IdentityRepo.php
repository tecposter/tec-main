<?php
namespace Tec\User\Repo;

use Tec\User\Dto\IdentityDto;

class IdentityRepo extends RepoBase
{
    private $table = 'open_identity';

    public function create(IdentityDto $identity): void
    {
        $zcodeByteLen = 16;
        $identity->zcode = uniqBin($zcodeByteLen);
        if (empty($identity->data)) {
            throw new \Exception('data cannot be empty');
        }

        if ((!$identity->created) || (!$identity->expired)) {
            throw new \Exception('require both created and expired');
        }

        $this->cnn->isb()
            ->insert($this->table)
            ->field(
                'zcode',
                'data',
                'created',
                'expired'
            )
            ->value()
                ->addStr($identity->zcode)
                ->addStr($identity->data)
                ->addDateTime($identity->created)
                ->addDateTime($identity->expired)
            ->end()
            ->execute();
        $identity->identityId = $this->cnn->lastInsertId();
    }
}
