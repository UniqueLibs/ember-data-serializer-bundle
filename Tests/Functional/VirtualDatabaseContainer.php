<?php

namespace UniqueLibs\EmberDataSerializerBundle\Tests\Functional;

use UniqueLibs\EmberDataSerializerBundle\Tests\Model\TestUser;
use UniqueLibs\EmberDataSerializerBundle\Tests\Model\TestUserGroup;

/**
 * Class VirtualDatabaseContainer
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Tests\Functional
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class VirtualDatabaseContainer
{
    /** @var TestUser[] */
    protected $users;

    /** @var TestUserGroup[] */
    protected $userGroups;

    public function __construct()
    {
        $this->users = array();
        $this->userGroups = array();
    }

    public function addTestUser(TestUser $user)
    {
        $this->users[] = $user;
    }

    public function addTestUserGroup(TestUserGroup $userGroup)
    {
        $this->userGroups[] = $userGroup;
    }

    /**
     * @return TestUser[]
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param int $id
     *
     * @return TestUser|null
     */
    public function getUserById($id)
    {
        foreach ($this->users as $testUser) {
            if ($testUser->getId() == $id) {
                return $testUser;
            }
        }

        return null;
    }

    /**
     * @return TestUserGroup[]
     */
    public function getUserGroups()
    {
        return $this->userGroups;
    }

    /**
     * @param int $id
     *
     * @return TestUserGroup|null
     */
    public function getUserGroupById($id)
    {
        foreach ($this->userGroups as $testUserGroup) {
            if ($testUserGroup->getId() == $id) {
                return $testUserGroup;
            }
        }

        return null;
    }
}