<?php

namespace FondOfSpryker\Zed\ProductUrlStore\Dependency\Facade;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface as SprykerProductToUrlInterface;

interface ProductUrlStoreToUrlFacadeInterface extends SprykerProductToUrlInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrl(UrlTransfer $urlTransfer): ?UrlTransfer;
}
