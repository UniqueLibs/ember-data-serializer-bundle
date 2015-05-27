<?php

namespace UniqueLibs\EmberDataSerializerBundle\Tests\Functional\EmberDataSerializerManager;

use UniqueLibs\EmberDataSerializerBundle\Services\EmberDataSerializerManager;
use UniqueLibs\EmberDataSerializerBundle\Tests\Functional\EmberDataSerializerTestCase;
use UniqueLibs\EmberDataSerializerBundle\Tests\Model\EmberDataSerializerAdapter\TestUserAdapter;

/**
 * Class TestUserFixturesTest
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Tests\Functional\EmberDataSerializerManager
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class TestUserFixturesTest extends EmberDataSerializerTestCase
{
    public function testFormatTestUsers()
    {
        $virtualDatabaseContainer = $this->getVirtualDatabaseContainerWithFixtures();

        /** @var EmberDataSerializerManager $serializerManager */
        $serializerManager = $this->getContainer()->get('unique_libs.ember_data_serializer.manager');

        $serialized = $serializerManager->format($virtualDatabaseContainer->getUsers(), TestUserAdapter::MODEL_NAME_PLURAL);

        $this->assertCount(2, $serialized);
        $this->assertArrayHasKey('testusers', $serialized);
        $this->assertArrayHasKey('testusergroups', $serialized);
        $this->assertCount(3, $serialized['testusers']);
        $this->assertCount(2, $serialized['testusergroups']);

        foreach ($serialized['testusers'] as $testUser) {
            $this->assertArrayHasKey('id', $testUser);
            $this->assertArrayHasKey('username', $testUser);
            $this->assertArrayNotHasKey('password', $testUser);
            $this->assertArrayHasKey('email', $testUser);
            $this->assertArrayHasKey('userGroups', $testUser);

            if ($testUser['id'] == 1) {
                $this->assertEquals("First User", $testUser['username']);
                $this->assertEquals("First Email", $testUser['email']);
                $this->assertCount(2, $testUser['userGroups']);
                $this->assertTrue(in_array(1, $testUser['userGroups']));
                $this->assertTrue(in_array(2, $testUser['userGroups']));
            } else if ($testUser['id'] == 2) {
                $this->assertEquals("Second User", $testUser['username']);
                $this->assertEquals("Second Email", $testUser['email']);
                $this->assertCount(1, $testUser['userGroups']);
                $this->assertTrue(in_array(1, $testUser['userGroups']));
            } else if ($testUser['id'] == 4) {
                $this->assertEquals("User Four", $testUser['username']);
                $this->assertEquals("Last Email", $testUser['email']);
                $this->assertCount(1, $testUser['userGroups']);
                $this->assertTrue(in_array(1, $testUser['userGroups']));
            } else {
                $this->assertTrue(false, "Invalid User ID found.");
            }
        }

        foreach ($serialized['testusergroups'] as $testUserGroup) {
            $this->assertArrayHasKey('id', $testUserGroup);
            $this->assertArrayHasKey('groupname', $testUserGroup);
            $this->assertArrayHasKey('users', $testUserGroup);

            if ($testUserGroup['id'] == 1) {
                $this->assertEquals("All Users Group", $testUserGroup['groupname']);
                $this->assertCount(3, $testUserGroup['users']);
                $this->assertTrue(in_array(1, $testUserGroup['users']));
                $this->assertTrue(in_array(2, $testUserGroup['users']));
                $this->assertTrue(in_array(4, $testUserGroup['users']));
            } else if ($testUserGroup['id'] == 2) {
                $this->assertEquals("Only First User Group", $testUserGroup['groupname']);
                $this->assertCount(1, $testUserGroup['users']);
                $this->assertTrue(in_array(1, $testUserGroup['users']));
            } else {
                $this->assertTrue(false, "Invalid Group ID found.");
            }
        }
    }

    public function testFormatOneTestUserWithId1()
    {
        $virtualDatabaseContainer = $this->getVirtualDatabaseContainerWithFixtures();

        /** @var EmberDataSerializerManager $serializerManager */
        $serializerManager = $this->getContainer()->get('unique_libs.ember_data_serializer.manager');

        $serialized = $serializerManager->formatOne($virtualDatabaseContainer->getUserById(1), TestUserAdapter::MODEL_NAME_SINGULAR);

        $this->assertCount(2, $serialized);
        $this->assertArrayHasKey('testuser', $serialized);
        $this->assertArrayHasKey('testusergroups', $serialized);
        $this->assertCount(2, $serialized['testusergroups']);

        $this->assertArrayHasKey('id', $serialized['testuser']);
        $this->assertArrayHasKey('username', $serialized['testuser']);
        $this->assertArrayNotHasKey('password', $serialized['testuser']);
        $this->assertArrayHasKey('email', $serialized['testuser']);
        $this->assertArrayHasKey('userGroups', $serialized['testuser']);

        $this->assertEquals("First User", $serialized['testuser']['username']);
        $this->assertEquals("First Email", $serialized['testuser']['email']);
        $this->assertCount(2, $serialized['testuser']['userGroups']);
        $this->assertTrue(in_array(1, $serialized['testuser']['userGroups']));
        $this->assertTrue(in_array(2, $serialized['testuser']['userGroups']));

        foreach ($serialized['testusergroups'] as $testUserGroup) {
            $this->assertArrayHasKey('id', $testUserGroup);
            $this->assertArrayHasKey('groupname', $testUserGroup);
            $this->assertArrayHasKey('users', $testUserGroup);

            if ($testUserGroup['id'] == 1) {
                $this->assertEquals("All Users Group", $testUserGroup['groupname']);
                $this->assertCount(3, $testUserGroup['users']);
                $this->assertTrue(in_array(1, $testUserGroup['users']));
                $this->assertTrue(in_array(2, $testUserGroup['users']));
                $this->assertTrue(in_array(4, $testUserGroup['users']));
            } else if ($testUserGroup['id'] == 2) {
                $this->assertEquals("Only First User Group", $testUserGroup['groupname']);
                $this->assertCount(1, $testUserGroup['users']);
                $this->assertTrue(in_array(1, $testUserGroup['users']));
            } else {
                $this->assertTrue(false, "Invalid Group ID found.");
            }
        }
    }

    public function testFormatOneTestUserWithHiddenUser()
    {
        $virtualDatabaseContainer = $this->getVirtualDatabaseContainerWithFixtures();

        /** @var EmberDataSerializerManager $serializerManager */
        $serializerManager = $this->getContainer()->get('unique_libs.ember_data_serializer.manager');

        $serialized = $serializerManager->formatOne($virtualDatabaseContainer->getUserById(3), TestUserAdapter::MODEL_NAME_SINGULAR);

        $this->assertCount(1, $serialized);
        $this->assertArrayHasKey('testuser', $serialized);
        $this->assertCount(0, $serialized['testuser']);
    }
}