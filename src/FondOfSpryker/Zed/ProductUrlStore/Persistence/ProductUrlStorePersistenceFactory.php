<?php

namespace FondOfSpryker\Zed\ProductUrlStore\Persistence;

use FondOfSpryker\Zed\ProductUrlStore\ProductUrlStoreDependencyProvider;
use Spryker\Zed\Product\Dependency\QueryContainer\ProductToUrlInterface;
use Spryker\Zed\Product\Persistence\ProductPersistenceFactory;

/**
 * @method \FondOfSpryker\Zed\ProductUrlStore\ProductUrlStoreConfig getConfig()
 * @method \FondOfSpryker\Zed\ProductUrlStore\Persistence\ProductUrlStoreQueryContainer getQueryContainer()
 */
class ProductUrlStorePersistenceFactory extends ProductPersistenceFactory
{
    /**
     * @return \Spryker\Zed\Product\Dependency\QueryContainer\ProductToUrlInterface
     */
    public function getUrlQueryContainer(): ProductToUrlInterface
    {
        return $this->getProvidedDependency(ProductUrlStoreDependencyProvider::QUERY_CONTAINER_URL);
    }
}
