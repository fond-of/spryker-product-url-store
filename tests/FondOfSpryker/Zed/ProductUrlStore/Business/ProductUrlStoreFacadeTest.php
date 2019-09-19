<?php

namespace FondOfSpryker\Zed\ProductUrlStore\Business;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductUrlTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use org\bovigo\vfs\vfsStream;
use Propel\Runtime\Connection\ConnectionInterface;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductUrlStoreFacadeTest extends Unit
{
    /**
     * @var \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     */
    protected $productAbstractTransfer;

    /**
     * @var Spryker\Zed\Product\Dependency\Facade\ProductToLocaleInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productLocalFacadeMock;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToTouchInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productTouchFacadeMock;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productUrlFacadeMock;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Url\ProductUrlGeneratorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productUrlGeneratorMock;

    /**
     * @var \FondOfSpryker\Zed\ProductUrlStore\Business\ProductUrlStoreBusinessFactory|\PHPUnit\Framework\MockObject\MockObject $productUrlStoreBusinessFactoryMock
     */
    protected $productUrlStoreBusinessFactoryMock;

    /**
     * @var \FondOfSpryker\Zed\ProductUrlStore\Business\ProductUrlStoreFacade $productUrlStoreFacade
     */
    protected $productUrlStoreFacade;

    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainerMock
     */
    protected $productQueryContainerMock;

    /**
     * @var \Propel\Runtime\Connection\ConnectionInterface\PHPUnit\Framework\MockObject\MockObject $propelRuntimeConnectionMock
     */
    protected $propelRuntimeConnectionMock;

    /**
     * @var \Orm\Zed\Url\Persistence\Base\SpyUrlQuery
     */
    protected $spyPersistenceUrlQueryMock;

    /**
     * @var Orm\Zed\Url\Persistence\Base\SpyUrl
     */
    protected $spyPersistenceUrlMock;

    /**
     * @return void
     */
    public function _before()
    {
        $this->vfsStreamDirectory = vfsStream::setup('root', null, [
            'config' => [
                'Shared' => [
                    'stores.php' => file_get_contents(codecept_data_dir('stores.php')),
                    'config_default.php' => file_get_contents(codecept_data_dir('config_default.php')),
                ],
            ],
        ]);

        $this->productLocalFacadeMock = $this->getMockBuilder(ProductToLocaleInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productTouchFacadeMock = $this->getMockBuilder(ProductToTouchInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productUrlFacadeMock = $this->getMockBuilder(ProductToUrlInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productUrlGeneratorMock = $this->getMockBuilder(ProductUrlGeneratorInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['generateProductUrl'])
            ->getMock();

        $this->productUrlStoreBusinessFactoryMock = $this->getMockBuilder(ProductUrlStoreBusinessFactory::class)
            ->setMethods(['createProductUrlManager'])
            ->getMock();

        $this->propelRuntimeConnectionMock = $this->getMockBuilder(ConnectionInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['beginTransaction'])
            ->getMockForAbstractClass();

        $this->productQueryContainerMock = $this->getMockBuilder(ProductQueryContainerInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getConnection', 'queryUrlByIdProductAbstractIdStoreAndIdLocale'])
            ->getMockForAbstractClass();

        $this->spyPersistenceUrlQueryMock = $this->getMockBuilder('\Orm\Zed\Url\Persistence\Base\SpyUrlQuery')
            ->disableOriginalConstructor()
            ->setMethods(['findOneOrCreate'])
            ->getMock();

        $this->spyPersistenceUrlMock = $this->getMockBuilder('\Orm\Zed\Url\Persistence\Base\SpyUrl')
            ->disableOriginalConstructor()
            ->setMethods(['toArray'])
            ->getMock();

        $attributes = [
            "url_key" => "url-test-default",
        ];

        $localizedAttributes = $this->generateLocalizedAttributes();

        $stores = $this->generateStores();

        $storeRelation = new StoreRelationTransfer();
        $storeRelation->setStores(new ArrayObject($stores));
        $storeRelation->setIdStores([1]);

        $this->productAbstractTransfer = new ProductAbstractTransfer();
        $this->productAbstractTransfer
            ->setIdProductAbstract(1)
            ->setSku('product-test')
            ->setAttributes($attributes)
            ->setLocalizedAttributes(new ArrayObject($localizedAttributes))
            ->setStoreRelation($storeRelation);

        $this->productUrlTransfer = $this->generateProductUrls($this->productAbstractTransfer);

        $this->productUrlStoreFacade = new ProductUrlStoreFacade();
        $this->productUrlStoreFacade->setFactory($this->productUrlStoreBusinessFactoryMock);
    }

    /**
     * @return void
     */
    public function testCreateProductAbstractUrl()
    {
        $this->productQueryContainerMock->expects($this->atLeastOnce())
            ->method('getConnection')
            ->willReturn($this->propelRuntimeConnectionMock);

        $this->propelRuntimeConnectionMock->expects($this->atLeastOnce())
            ->method('beginTransaction')
            ->willReturn(true);

        $this->productUrlGeneratorMock->expects($this->atLeastOnce())
            ->method('generateProductUrl')
            ->willReturn($this->productUrlTransfer);

        $productUrlManager = new ProductUrlManager(
            $this->productUrlFacadeMock,
            $this->productTouchFacadeMock,
            $this->productLocalFacadeMock,
            $this->productQueryContainerMock,
            $this->productUrlGeneratorMock
        );

        $this->productUrlStoreBusinessFactoryMock->expects($this->atLeastOnce())
            ->method('createProductUrlManager')
            ->willReturn($productUrlManager);

        $productUrl = $this->productUrlStoreFacade->createProductAbstractUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
    }

    /**
     * @return void
     */
    public function testUpdateProductAbstractUrl()
    {
        $this->productQueryContainerMock->expects($this->atLeastOnce())
            ->method('getConnection')
            ->willReturn($this->propelRuntimeConnectionMock);

        $this->productQueryContainerMock->expects($this->atLeastOnce())
            ->method('queryUrlByIdProductAbstractIdStoreAndIdLocale')
            ->willReturn($this->spyPersistenceUrlQueryMock);

        $this->spyPersistenceUrlQueryMock->expects($this->atLeastOnce())
            ->method('findOneOrCreate')
            ->willReturn($this->spyPersistenceUrlMock);

        $this->spyPersistenceUrlMock->expects($this->atLeastOnce())
            ->method('toArray')
            ->willReturn(
                [
                    'id_url' => 1,
                ]
            );

        $this->propelRuntimeConnectionMock->expects($this->atLeastOnce())
            ->method('beginTransaction')
            ->willReturn(true);

        $this->productUrlGeneratorMock->expects($this->atLeastOnce())
            ->method('generateProductUrl')
            ->willReturn($this->productUrlTransfer);

        $productUrlManager = new ProductUrlManager(
            $this->productUrlFacadeMock,
            $this->productTouchFacadeMock,
            $this->productLocalFacadeMock,
            $this->productQueryContainerMock,
            $this->productUrlGeneratorMock
        );

        $this->productUrlStoreBusinessFactoryMock->expects($this->atLeastOnce())
            ->method('createProductUrlManager')
            ->willReturn($productUrlManager);

        $productUrl = $this->productUrlStoreFacade->updateProductAbstractUrl($this->productAbstractTransfer);

        $this->assertInstanceOf(ProductUrlTransfer::class, $productUrl);
    }

    /**
     * @return array
     */
    public function generateLocalizedAttributes()
    {
        $results = [];
        $data = $this->getSampleLocalizedProductAttributeValues();

        foreach ($data as $localeCode => $localizedData) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setLocaleName($localeCode);

            $localizedAttributeTransfer = new LocalizedAttributesTransfer();
            $localizedAttributeTransfer->setAttributes($localizedData);
            $localizedAttributeTransfer->setLocale($localeTransfer);
            $localizedAttributeTransfer->setName('product-' . rand(1, 1000));

            $results[] = $localizedAttributeTransfer;
        }

        return $results;
    }

    /**
     * @return array
     */
    public function getSampleLocalizedProductAttributeValues()
    {
        $result = [
            '_' => [
                "url_key" => "product-url-default",
            ],
            'de_DE' => [
                "url_key" => "product-url-de",
            ],
            'en_US' => [
                "url_key" => "product-url-en",
            ],
        ];

        ksort($result);

        return $result;
    }

    /**
     * @return array
     */
    public function generateStores()
    {
        $results = [];
        $data = $this->getSampleStoresValues();

        foreach ($data as $idStore => $storeData) {
            $storeTransfer = new StoreTransfer();
            $storeTransfer->setIdStore($storeData['id_store']);
            $storeTransfer->setName($storeData['name']);

            $results[] = $storeTransfer;
        }

        return $results;
    }

    /**
     * @return array
     */
    public function getSampleStoresValues()
    {
        $result = [
            1 => [
                "id_store" => 1,
                "name" => "Default Store",
            ],
        ];

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductUrlTransfer
     */
    public function generateProductUrls(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productUrlTransfer = new ProductUrlTransfer();
        $productUrlTransfer->setAbstractSku($productAbstractTransfer->getSku());
        $locales = [
            'de_DE' => [
                'id' => 1,
                'url' => 'product-url-de',
            ],
            'en_US' => [
                'id' => 2,
                'url' => 'product-url-en',
            ],
        ];

        foreach ($locales as $code => $locale) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->setIdLocale($locale['id']);
            $localeTransfer->setLocaleName($code);

            $localizedUrl = new LocalizedUrlTransfer();
            $localizedUrl->setLocale($localeTransfer);
            $localizedUrl->setUrl($locale['url']);

            $productUrlTransfer->addUrl($localizedUrl);
        }

        return $productUrlTransfer;
    }
}