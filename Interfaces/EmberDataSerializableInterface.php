<?php

namespace UniqueLibs\EmberDataSerializerBundle\Interfaces;

/**
 * Interface EmberDataSerializableInterface
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Interfaces
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
interface EmberDataSerializableInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getEmberDataSerializerAdapterServiceName();
}