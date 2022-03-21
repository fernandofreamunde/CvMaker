<?php

declare(strict_types=1);

namespace App\Common\Traits;

use App\Common\Entity;

trait RepositoryTrait
{
    /**
     * Calls EntityManagerFlush can be used after using commit() in many different entities
     */
    public function save()
    {
        $this->_em->flush();
    }

    public function commit(Entity $account)
    {
        $this->_em->persist($account);
    }

    public function delete(Entity $account)
    {
        $this->_em->remove($account);
    }

}
