<?php

namespace App\Common\DataPersister;

interface ResourceServiceInterface
{
    public function create($entity);
    public function update($entity);
    public function delete($entity);
}
