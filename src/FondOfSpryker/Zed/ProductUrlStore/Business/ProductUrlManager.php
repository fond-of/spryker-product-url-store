<?php

namespace FondOfSpryker\Zed\ProductUrlStore\Business;

use FondOfSpryker\Zed\ProductUrlStore\Dependency\Facade\ProductToUrlInterface;
use FondOfSpryker\Zed\ProductUrlStore\Dependency\Facade\StoreToProductStoreUrlBridgeInterface;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManager as SprykerProductUrlMananger;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductUrlManager extends SprykerProductUrlMananger implements ProductUrlManagerInterface
{
    /**
     * @var \FondOfSpryker\Zed\ProductUrlStore\Dependency\Facade\StoreToProductStoreUrlBridgeInterface
     */
    protected $storeFacade;

    /**
     * @var \FondOfSpryker\Zed\ProductUrlStore\Dependency\Facade\ProductToUrlInterface
     */
    protected $urlFacade;

    /**
     * ProductUrlManager constructor.
     *
     * @param \FondOfSpryker\Zed\ProductUrlStore\Dependency\Facade\ProductToUrlInterface $urlFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface $touchFacade
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     * @param \Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface $urlGenerator
     * @param \FondOfSpryker\Zed\ProductUrlStore\Dependency\Facade\StoreToProductStoreUrlBridgeInterface $storeFacade
     */
    public function __construct(
        ProductToUrlInterface $urlFacade,
        ProductToTouchInterface $touchFacade,
        ProductToLocaleInterface $localeFacade,
        ProductQueryContainerInterface $productQueryContainer,
        ProductUrlGeneratorInterface $urlGenerator,
        StoreToProductStoreUrlBridgeInterface $storeFacade
    ) {
        parent::__construct($urlFacade, $touchFacade, $localeFacade, $productQueryContainer, $urlGenerator);
        $this->storeFacade = $storeFacade;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function createProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        $this->productQueryContainer->getConnection()->beginTransaction();

        $productUrl = $this->urlGenerator->generateProductUrl($productAbstractTransfer);

        foreach ($productAbstractTransfer->getStoreRelation()->getIdStores() as $idStore) {
            foreach ($productUrl->getUrls() as $localizedUrlTransfer) {
                $urlTransfer = new UrlTransfer();
                $urlTransfer
                    ->setUrl($localizedUrlTransfer->getUrl())
                    ->setFkLocale($localizedUrlTransfer->getLocale()->getIdLocale())
                    ->setFkStore($idStore)
                    ->setFkResourceProductAbstract($productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract());

                $this->urlFacade->createUrl($urlTransfer);
            }
        }

        $this->productQueryContainer->getConnection()->commit();

        return $productUrl;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function updateProductUrl(ProductAbstractTransfer $productAbstractTransfer)
    {
        $availableStores = $this->storeFacade->getAllStores();

        $this->productQueryContainer->getConnection()->beginTransaction();

        $productUrl = $this->urlGenerator->generateProductUrl($productAbstractTransfer);

        foreach ($productAbstractTransfer->getStoreRelation()->getIdStores() as $idStore) {
            $availableStores = $this->filterStores($availableStores, $idStore);

            foreach ($productUrl->getUrls() as $localizedUrlTransfer) {
                $urlTransfer = $this->createUrlTransfer($productAbstractTransfer, $idStore, $localizedUrlTransfer);

                if ($urlTransfer->getIdUrl()) {
                    $this->urlFacade->updateUrl($urlTransfer);
                } else {
                    $this->urlFacade->createUrl($urlTransfer);
                }
            }
        }

        $this->clearOrphanedUrls($productAbstractTransfer, $availableStores, $productUrl);
        $this->productQueryContainer->getConnection()->commit();

        return $productUrl;
    }

    /**
     * @param int $idProductAbstract
     * @param int $idStore
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function getUrlByIdProductAbstractIdStoreAndIdLocale($idProductAbstract, $idStore, $idLocale)
    {
        $urlEntity = $this->productQueryContainer
            ->queryUrlByIdProductAbstractIdStoreAndIdLocale($idProductAbstract, $idStore, $idLocale)
            ->findOneOrCreate();

        $urlTransfer = (new UrlTransfer())
            ->fromArray($urlEntity->toArray(), true);

        return $urlTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param int $idStore
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $localizedUrlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function createUrlTransfer(
        ProductAbstractTransfer $productAbstractTransfer,
        int $idStore,
        LocalizedUrlTransfer $localizedUrlTransfer
    ): UrlTransfer {
        $urlTransfer = $this->getUrlByIdProductAbstractIdStoreAndIdLocale(
            $productAbstractTransfer->requireIdProductAbstract()->getIdProductAbstract(),
            $idStore,
            $localizedUrlTransfer->getLocale()->getIdLocale()
        );

        $urlTransfer
            ->setUrl($localizedUrlTransfer->getUrl())
            ->setFkLocale($localizedUrlTransfer->getLocale()->getIdLocale())
            ->setFkStore($idStore)
            ->setFkResourceProductAbstract($productAbstractTransfer->getIdProductAbstract());
        return $urlTransfer;
    }

    /**
     * @param array $availableStores
     * @param int $idStore
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    protected function filterStores(array $availableStores, int $idStore): array
    {
        foreach ($availableStores as $index => $availableStore) {
            if ($availableStore->getIdStore() === $idStore) {
                unset($availableStores[$index]);
                break;
            }
        }

        return $availableStores;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer[] $availableStores
     * @param \Generated\Shared\Transfer\ProductUrlTransfer $productUrl
     *
     * @return void
     */
    protected function clearOrphanedUrls(
        ProductAbstractTransfer $productAbstractTransfer,
        array $availableStores,
        ProductUrlTransfer $productUrl
    ): void {
        foreach ($availableStores as $availableStore) {
            foreach ($productUrl->getUrls() as $localizedUrlTransfer) {
                $urlTransfer = $this->createUrlTransfer(
                    $productAbstractTransfer,
                    $availableStore->getIdStore(),
                    $localizedUrlTransfer
                );

                $urlTransfer = $this->urlFacade->findUrl($urlTransfer);

                if ($urlTransfer && $urlTransfer->getIdUrl() && $availableStore->getIdStore() === $urlTransfer->getFkStore()) {
                    $this->urlFacade->deleteUrl($urlTransfer);
                }
            }
        }
    }
}
