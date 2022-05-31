<?php

namespace FondOfSpryker\Zed\ProductUrlStore\Dependency\Facade;

interface ProductUrlStoreToStoreFacadeInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\StoreTransfer>
     */
    public function getAllStores(): array;
}
