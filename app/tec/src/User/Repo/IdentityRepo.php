<?php
namespace Tec\User\Repo;

use Tec\User\Dto\IdentityDto;

class IdentityRepo extends RepoBase
{
    private $table = 'open_identity';

    public function create(IdentityDto $identity): void
    {
        $codeByteLen = 16;
        $identity->code = uniqBin($codeByteLen);
        if (empty($identity->data)) {
            throw new \Exception('data cannot be empty');
        }

        if ((!$identity->created) || (!$identity->expired)) {
            throw new \Exception('require both created and expired');
        }

        $this->cnn->isb()
            ->insert($this->table)
            ->field(
                'code',
                'data',
                'created',
                'expired'
            )
            ->value()
                ->addStr($identity->code)
                ->addStr($identity->data)
                ->addDateTime($identity->created)
                ->addDateTime($identity->expired)
            ->end()
            ->execute();
        $identity->identityId = $this->cnn->lastInsertId();
    }

    public function fetchDataByCode(string $code): string
    {
        $res = $this->cnn->ssb()
            ->select('data')
            ->from($this->table)->end()
            ->where()
                ->expect('code')->equal()->str(hex2bin($code))
            ->end()
            ->fetchAssoc();

        if ($res) {
            return $res['data'];
        }

        return '';
    }
}
