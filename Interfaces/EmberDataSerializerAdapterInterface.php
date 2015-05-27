<?php

namespace UniqueLibs\EmberDataSerializerBundle\Interfaces;

/**
 * Interface EmberDataSerializerAdapterInterface
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Interfaces
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
interface EmberDataSerializerAdapterInterface
{
    /**
     * @param EmberDataSerializableInterface $object
     *
     * @return bool
     */
    public function hasAccess(EmberDataSerializableInterface $object);

    /**
     * @param EmberDataSerializableInterface $object
     *
     * @return array
     */
    public function getData(EmberDataSerializableInterface $object);

    /**
     * @return string
     */
    public function getModelNameSingular();

    /**
     * @return string
     */
    public function getModelNamePlural();
}
