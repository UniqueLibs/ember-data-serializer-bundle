<?php

namespace UniqueLibs\EmberDataSerializerBundle\Tests\Functional\EmberDataSerializerManager;

use UniqueLibs\EmberDataSerializerBundle\Services\EmberDataSerializerManager;
use UniqueLibs\EmberDataSerializerBundle\Tests\Functional\EmberDataSerializerTestCase;
use UniqueLibs\EmberDataSerializerBundle\Tests\Model\EmberDataSerializerAdapter\TestUserGroupAdapter;

/**
 * Class TestUserGroupFixturesTest
 *
 * @package UniqueLibs\EmberDataSerializerBundle\Tests\Functional\EmberDataSerializerManager
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class TestUserGroupFixturesTest extends EmberDataSerializerTestCase
{
    public function testFormatTestUserGroups()
    {
        $virtualDatabaseContainer = $this->getVirtualDatabaseContainerWithFixtures();

        /** @var EmberDataSerializerManager $serializerManager */
        $serializerManager = $this->getContainer()->get('unique_libs.ember_data_serializer.manager');

        $serialized = $serializerManager->format($virtualDatabaseContainer->getUserGroups(), TestUserGroupAdapter::MODEL_NAME_PLURAL);

        $this->assertCount(1, $serialized);
        $this->assertArrayHasKey('testusergroups', $serialized);
        $this->assertCount(2, $serialized['testusergroups']);

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

    public function testFormatOneTestUserGroupWithId1()
    {
        $virtualDatabaseContainer = $this->getVirtualDatabaseContainerWithFixtures();

        /** @var EmberDataSerializerManager $serializerManager */
        $serializerManager = $this->getContainer()->get('unique_libs.ember_data_serializer.manager');

        $serialized = $serializerManager->formatOne($virtualDatabaseContainer->getUserGroupById(1), TestUserGroupAdapter::MODEL_NAME_SINGULAR);

        $this->assertCount(1, $serialized);
        $this->assertArrayHasKey('testusergroup', $serialized);

        $this->assertArrayHasKey('id', $serialized['testusergroup']);
        $this->assertArrayHasKey('groupname', $serialized['testusergroup']);
        $this->assertArrayHasKey('users', $serialized['testusergroup']);

        $this->assertEquals("All Users Group", $serialized['testusergroup']['groupname']);
        $this->assertCount(3, $serialized['testusergroup']['users']);
        $this->assertTrue(in_array(1, $serialized['testusergroup']['users']));
        $this->assertTrue(in_array(2, $serialized['testusergroup']['users']));
        $this->assertTrue(in_array(4, $serialized['testusergroup']['users']));
    }

    public function testFormatOneTestUserGroupWithHiddenGroup()
    {
        $virtualDatabaseContainer = $this->getVirtualDatabaseContainerWithFixtures();

        /** @var EmberDataSerializerManager $serializerManager */
        $serializerManager = $this->getContainer()->get('unique_libs.ember_data_serializer.manager');

        $serialized = $serializerManager->formatOne($virtualDatabaseContainer->getUserGroupById(3), TestUserGroupAdapter::MODEL_NAME_SINGULAR);

        $this->assertCount(1, $serialized);
        $this->assertArrayHasKey('testusergroup', $serialized);
        $this->assertCount(0, $serialized['testusergroup']);
    }
}
