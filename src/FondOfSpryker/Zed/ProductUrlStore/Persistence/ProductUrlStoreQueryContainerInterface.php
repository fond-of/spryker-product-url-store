<?php

namespace FondOfSpryker\Zed\ProductUrlStore\Persistence;

use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface as SprykerProductQueryContainerInterface;

interface ProductUrlStoreQueryContainerInterface extends SprykerProductQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int $idStore
     * @param int $idLocale
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdProductAbstractidStoreAndIdLocale($idProductAbstract, $idStore, $idLocale): SpyUrlQuery;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idStore
     * @param int $idLocale
     * @param string $url
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryUrlByIdStoreAndIdLocaleAndUrl($idStore, $idLocale, $url): SpyUrlQuery;
}
