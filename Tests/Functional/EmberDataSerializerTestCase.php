<?php

namespace UniqueLibs\EmberDataSerializerBundle\Tests\Functional;

use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;
use UniqueLibs\EmberDataSerializerBundle\Tests\Model\TestUser;
use UniqueLibs\EmberDataSerializerBundle\Tests\Model\TestUserGroup;

/**
 * Class EmberDataSerializerTestCase
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Tests\Functional
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class EmberDataSerializerTestCase extends BaseTestCase
{
    /**
     * @return VirtualDatabaseContainer
     */
    public function getVirtualDatabaseContainerWithFixtures()
    {
        $db = new VirtualDatabaseContainer();

        $userGroupAllUsers = new TestUserGroup(1, "All Users Group");
        $userGroupOnlyFirstUser = new TestUserGroup(2, "Only First User Group");
        $userGroupHidden = new TestUserGroup(3, "Hidden User Group");

        $db->addTestUserGroup($userGroupAllUsers);
        $db->addTestUserGroup($userGroupOnlyFirstUser);
        $db->addTestUserGroup($userGroupHidden);

        $user = new TestUser(1, "First User", "My Secret Password", "First Email");
        $user->addUserGroup($userGroupAllUsers);
        $user->addUserGroup($userGroupOnlyFirstUser);
        $user->addUserGroup($userGroupHidden);

        $db->addTestUser($user);

        $user = new TestUser(2, "Second User", "My Secret Password", "Second Email");
        $user->addUserGroup($userGroupAllUsers);

        $db->addTestUser($user);

        $user = new TestUser(3, "Third Hidden User", "My Secret Password", "Third Hidden Email");
        $user->addUserGroup($userGroupAllUsers);
        $user->addUserGroup($userGroupHidden);

        $db->addTestUser($user);

        $user = new TestUser(4, "User Four", "My Secret Password", "Last Email");
        $user->addUserGroup($userGroupAllUsers);

        $db->addTestUser($user);

        return $db;
    }
}