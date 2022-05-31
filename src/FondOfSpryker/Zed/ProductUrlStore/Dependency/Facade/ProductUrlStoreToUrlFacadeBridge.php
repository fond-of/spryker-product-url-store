<?php

namespace FondOfSpryker\Zed\ProductUrlStore\Dependency\Facade;

use FondOfSpryker\Zed\Product\Dependency\Facade\ProductToUrlBridge as FondOfSprykerProductToUrlBridge;
use Generated\Shared\Transfer\UrlTransfer;

class ProductUrlStoreToUrlFacadeBridge extends FondOfSprykerProductToUrlBridge implements ProductUrlStoreToUrlFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function findUrl(UrlTransfer $urlTransfer): ?UrlTransfer
    {
        return $this->urlFacade->findUrl($urlTransfer);
    }
}
