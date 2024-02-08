<?php

namespace IICN\Subscription\Services\Transaction;

class TransactionalData implements \IICN\Subscription\TransactionalData
{

    public function additionalData(): array
    {
        return [
            'version' => request()->hasHeader('version-name') ? request()->header('version-name') : null
        ];
    }
}
