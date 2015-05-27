<?php

namespace UniqueLibs\EmberDataSerializerBundle\Tests\Model;

use Doctrine\Common\Collections\ArrayCollection;
use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializableInterface;

/**
 * Class TestUserGroup
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Tests\Model
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class TestUserGroup implements EmberDataSerializableInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $groupname;

    /** @var ArrayCollection|TestUser[] */
    protected $users;

    /**
     * @param int    $id
     * @param string $groupname
     */
    public function __construct($id, $groupname)
    {
        $this->id = $id;
        $this->groupname = $groupname;

        $this->users = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getGroupname()
    {
        return $this->groupname;
    }

    /**
     * @return ArrayCollection|TestUser[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param ArrayCollection|TestUser $user
     */
    public function addUser($user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);

            if (!$user->getUserGroups()->contains($this)) {
                $user->getUserGroups()->add($this);
            }
        }
    }

    /**
     * @return string
     */
    public function getEmberDataSerializerAdapterServiceName()
    {
        return 'unique_libs.test.ember_data_serializer_adapter.test_user_group';
    }
}