<?php

namespace UniqueLibs\EmberDataSerializerBundle\Tests\Functional\EmberDataSerializerManager;

use UniqueLibs\EmberDataSerializerBundle\Services\EmberDataSerializerManager;
use UniqueLibs\EmberDataSerializerBundle\Tests\Functional\EmberDataSerializerTestCase;
use UniqueLibs\EmberDataSerializerBundle\Tests\Model\EmberDataSerializerAdapter\TestUserAdapter;
use UniqueLibs\EmberDataSerializerBundle\Tests\Model\TestUser;

class EmptyArrayTest extends EmberDataSerializerTestCase
{
    public function testFormatOneTestUserWithHiddenUser()
    {
        /** @var EmberDataSerializerManager $serializerManager */
        $serializerManager = $this->getContainer()->get('unique_libs.ember_data_serializer.manager');

        $serialized = $serializerManager->formatOne(new TestUser(1, "test", "test", "test"), TestUserAdapter::MODEL_NAME_SINGULAR);
        $userGroups = $serialized['testuser']['userGroups'];
        $this->assertTrue(is_array($userGroups) && count($userGroups) === 0);
    }
}
