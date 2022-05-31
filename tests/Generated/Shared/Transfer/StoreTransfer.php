<?php

namespace Generated\Shared\Transfer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class StoreTransfer extends AbstractTransfer
{
    public const NAME = 'name';

    public const ID_STORE = 'idStore';

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var int|null
     */
    protected $idStore;

    /**
     * @module AvailabilityCartConnector|CmsBlockGui|Discount|PriceCartConnector|ProductManagement|Store
     *
     * @param string|null $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->modifiedProperties[self::NAME] = true;

        return $this;
    }

    /**
     * @module AvailabilityCartConnector|CmsBlockGui|Discount|PriceCartConnector|ProductManagement|Store
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @module CmsBlock|Discount|ProductBundle|Store
     *
     * @param int|null $idStore
     *
     * @return $this
     */
    public function setIdStore($idStore)
    {
        $this->idStore = $idStore;
        $this->modifiedProperties[self::ID_STORE] = true;

        return $this;
    }

    /**
     * @module CmsBlock|Discount|ProductBundle|Store
     *
     * @return int|null
     */
    public function getIdStore()
    {
        return $this->idStore;
    }
}
