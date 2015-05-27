<?php

namespace UniqueLibs\EmberDataSerializerBundle\Tests\Model;

use Doctrine\Common\Collections\ArrayCollection;
use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializableInterface;

/**
 * Class TestUser
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Tests\Model
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class TestUser implements EmberDataSerializableInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    /** @var string */
    protected $email;

    /** @var ArrayCollection|TestUserGroup[] */
    protected $userGroups;

    /**
     * @param int    $id
     * @param string $username
     * @param string $password
     * @param string $email
     */
    public function __construct($id, $username, $password, $email)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;

        $this->userGroups = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return ArrayCollection|TestUserGroup[]
     */
    public function getUserGroups()
    {
        return $this->userGroups;
    }

    /**
     * @param TestUserGroup $userGroup
     */
    public function addUserGroup(TestUserGroup $userGroup)
    {
        if (!$this->userGroups->contains($userGroup)) {
            $this->userGroups->add($userGroup);

            if (!$userGroup->getUsers()->contains($this)) {
                $userGroup->getUsers()->add($this);
            }
        }
    }

    /**
     * @return string
     */
    public function getEmberDataSerializerAdapterServiceName()
    {
        return 'unique_libs.test.ember_data_serializer_adapter.test_user';
    }
}