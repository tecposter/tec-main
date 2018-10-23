<?php
namespace Tec\User\Repo;

use Tec\User\Dto\IdentityDto;

class IdentityRepo extends RepoBase
{
    private $table = 'open_identity';

    public function create(array $data, \DateInterval $ttl): IdentityDto
    {
        $identity = new IdentityDto([
            'code' => $this->createCode(),
            'data' => $data,
            'created' => new DateTime(),
            'expired' => (new DateTime())->add($ttl)
        ]);

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
        return $identity;
    }

    public function fetchData(string $code): array
    {
        $res = $this->cnn->ssb()
            ->select('data')
            ->from($this->table)->end()
            ->where()
                ->expect('code')->equal()->str(hex2bin($code))
            ->end()
            ->fetchAssoc();

        if (!$res) {
            return [];
        }

        $arr = json_decode($res['data'], true);
        if ($arr === null) {
            throw new \Exception('json decode failed');
        }
        return $arr;
    }

    private function createCode(): string
    {
        return m4sBin() . random_bytes(2);
    }
}
