<?php

namespace Generated\Shared\Transfer;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class UrlTransfer extends AbstractTransfer
{
    public const FK_STORE = 'fkStore';

    public const FK_RESOURCE_PRODUCT_ABSTRACT = 'fkResourceProductAbstract';

    public const URL = 'url';

    public const FK_LOCALE = 'fkLocale';

    public const ID_URL = 'idUrl';

    /**
     * @var int|null
     */
    protected $fkStore;

    /**
     * @var int|null
     */
    protected $fkResourceProductAbstract;

    /**
     * @var string|null
     */
    protected $url;

    /**
     * @var int|null
     */
    protected $fkLocale;

    /**
     * @var int|null
     */
    protected $idUrl;

    /**
     * @module Url
     *
     * @param int|null $fkStore
     *
     * @return $this
     */
    public function setFkStore($fkStore)
    {
        $this->fkStore = $fkStore;
        $this->modifiedProperties[self::FK_STORE] = true;

        return $this;
    }

    /**
     * @module Url
     *
     * @return int|null
     */
    public function getFkStore()
    {
        return $this->fkStore;
    }

    /**
     * @module Url
     *
     * @param int|null $fkResourceProductAbstract
     *
     * @return $this
     */
    public function setFkResourceProductAbstract($fkResourceProductAbstract)
    {
        $this->fkResourceProductAbstract = $fkResourceProductAbstract;
        $this->modifiedProperties[self::FK_RESOURCE_PRODUCT_ABSTRACT] = true;

        return $this;
    }

    /**
     * @module Url
     *
     * @return int|null
     */
    public function getFkResourceProductAbstract()
    {
        return $this->fkResourceProductAbstract;
    }

    /**
     * @module Url
     *
     * @param string|null $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        $this->modifiedProperties[self::URL] = true;

        return $this;
    }

    /**
     * @module Url
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @module Url
     *
     * @param int|null $fkLocale
     *
     * @return $this
     */
    public function setFkLocale($fkLocale)
    {
        $this->fkLocale = $fkLocale;
        $this->modifiedProperties[self::FK_LOCALE] = true;

        return $this;
    }

    /**
     * @module Url
     *
     * @return int|null
     */
    public function getFkLocale()
    {
        return $this->fkLocale;
    }

    /**
     * @module Url
     *
     * @param int|null $idUrl
     *
     * @return $this
     */
    public function setIdUrl($idUrl)
    {
        $this->idUrl = $idUrl;
        $this->modifiedProperties[self::ID_URL] = true;

        return $this;
    }

    /**
     * @module Url
     *
     * @return int|null
     */
    public function getIdUrl()
    {
        return $this->idUrl;
    }
}
