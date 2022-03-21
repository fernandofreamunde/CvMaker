<?php

declare(strict_types=1);

namespace App\Common\DataPersister;

class CommonDataPersister
{
    protected ResourceServiceInterface $resourceService;

    public function supports($data, array $context = []): bool
    {
        return false;
    }

    public function persist($data, array $context = [])
    {
        if ($this->getOperation($context)->equals(OperationsEnum::CREATE())) {

            $this->resourceService->create($data);
            return;
        }

        if ($this->getOperation($context)->equals(OperationsEnum::UPDATE())) {

            $this->resourceService->update($data);
            return;
        }
    }

    public function remove($data, array $context = [])
    {
        $this->resourceService->delete($data);
    }

    private function getOperation(array $context): OperationsEnum
    {
        $operation = null;
        if (isset($context['collection_operation_name'])) {
            $operation = strtolower($context['collection_operation_name']);
        }

        if (isset($context['item_operation_name'])) {
            $operation = strtolower($context['item_operation_name']);
        }

        if (isset($context['graphql_operation_name'])) {
            $operation = strtolower($context['graphql_operation_name']);
        }

        if ($operation === 'post' || $operation === 'create') {
            return OperationsEnum::CREATE();
        }

        if ($operation === 'put' || $operation === 'update') {
            return OperationsEnum::UPDATE();
        }

        if ($operation === 'get') {
            return OperationsEnum::READ();
        }

        if ($operation === 'delete') {
            return OperationsEnum::DELETE();
        }

        throw new UnexpectedOperation($operation);
    }
}

