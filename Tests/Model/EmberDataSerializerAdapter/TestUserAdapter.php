<?php

namespace UniqueLibs\EmberDataSerializerBundle\Tests\Model\EmberDataSerializerAdapter;

use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializableInterface;
use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializerAdapterInterface;
use UniqueLibs\EmberDataSerializerBundle\Tests\Model\TestUser;

/**
 * Class TestUserAdapter
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Tests\Model\EmberDataSerializerAdapter
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class TestUserAdapter implements EmberDataSerializerAdapterInterface
{
    const MODEL_NAME_SINGULAR = 'testuser';
    const MODEL_NAME_PLURAL = 'testusers';

    /**
     * @param EmberDataSerializableInterface $object
     *
     * @return bool
     */
    public function hasAccess(EmberDataSerializableInterface $object)
    {
        /** @var TestUser $object */
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
        /** @var TestUser $object */
        return array(
            'id' => array($object->getId(), false),
            'username' => array($object->getUsername(), false),
            'email' => array($object->getEmail(), false),
            'userGroups' => array($object->getUserGroups(), true),
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