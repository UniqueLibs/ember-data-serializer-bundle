<?php

namespace UniqueLibs\EmberDataSerializerBundle\Tests\Model\EmberDataSerializerAdapter;

use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializableInterface;
use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializerAdapterInterface;
use UniqueLibs\EmberDataSerializerBundle\Tests\Model\TestUserGroup;

/**
 * Class TestUserGroupAdapter
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Tests\Model\EmberDataSerializerAdapter
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class TestUserGroupAdapter implements EmberDataSerializerAdapterInterface
{
    const MODEL_NAME_SINGULAR = 'testusergroup';
    const MODEL_NAME_PLURAL = 'testusergroups';

    /**
     * @param EmberDataSerializableInterface $object
     *
     * @return bool
     */
    public function hasAccess(EmberDataSerializableInterface $object)
    {
        /** @var TestUserGroup $object */
        if ($object->getId() == 3) {
            return false;
        }

        return true;
    }

    /**
     * @param EmberDataSerializableInterface $object
     *
     * @return array
     */
    public function getData(EmberDataSerializableInterface $object)
    {
        /** @var TestUserGroup $object */
        return array(
            'id' => array($object->getId(), false),
            'groupname' => array($object->getGroupname(), false),
            'users' => array($object->getUsers(), false),
        );
    }

    /**
     * @return string
     */
    public function getModelNameSingular()
    {
        return self::MODEL_NAME_SINGULAR;
    }

    /**
     * @return string
     */
    public function getModelNamePlural()
    {
        return self::MODEL_NAME_PLURAL;
    }
}