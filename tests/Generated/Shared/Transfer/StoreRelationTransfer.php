<?php

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class StoreRelationTransfer extends AbstractTransfer
{
    public const ID_STORES = 'idStores';

    public const STORES = 'stores';

    /**
     * @var int[]
     */
    protected $idStores = [];

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    protected $stores;

    /**
     * @module CmsBlock|Discount|ProductManagement|Product|Store
     *
     * @param int[]|null $idStores
     *
     * @return $this
     */
    public function setIdStores(?array $idStores = null)
    {
        if ($idStores === null) {
            $idStores = [];
        }

        $this->idStores = $idStores;
        $this->modifiedProperties[self::ID_STORES] = true;

        return $this;
    }

    /**
     * @module CmsBlock|Discount|ProductManagement|Product|Store
     *
     * @return int[]
     */
    public function getIdStores()
    {
        return $this->idStores;
    }

    /**
     * @module CmsBlock|Discount|ProductManagement|Product
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[] $stores
     *
     * @return $this
     */
    public function setStores(ArrayObject $stores)
    {
        $this->stores = $stores;
        $this->modifiedProperties[self::STORES] = true;

        return $this;
    }

    /**
     * @module CmsBlock|Discount|ProductManagement|Product
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStores()
    {
        return $this->stores;
    }

    /**
     * @module CmsBlock|Discount|ProductManagement|Product
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $stores
     *
     * @return $this
     */
    public function addStores(StoreTransfer $stores)
    {
        $this->stores[] = $stores;
        $this->modifiedProperties[self::STORES] = true;

        return $this;
    }
}
