Getting Started With UniqueLibs/EmberDataSerializerBundle
==================================

Ember Data needs a specific api result model structure.
This bundle can serialize objects and arrays to this specific structure.

## Prerequisites

This version of the bundle requires Symfony 2.1+.

## Installation

Please follow these steps:

1. Download EmberDataSerializerBundle using composer
2. Enable the Bundle
3. Configure your first serializable object
4. Create your first serializer adapter class
5. Register created adapter in services.yml
6. Use the serializer manager

### Step 1: Download EmberDataSerializerBundle using composer

Add EmberDataSerializerBundle by running the command:

``` bash
$ php composer.phar require UniqueLibs/EmberDataSerializerBundle "~1.0"
```

Composer will install the bundle to your project's `vendor/UniqueLibs` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new UniqueLibs\EmberDataSerializerBundle\UniqueLibsEmberDataSerializerBundle(),
    );
}
```

### Step 3: Configure your first serializable object

The goal of this bundle is to serialize any object to the ember data structure. Every object, which should be serialized, must implement the UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializableInterface.

This interface needs the following public functions:
1. getId() - Should return the object id
2. getEmberDataSerializerAdapterServiceName() - Should return the adapter service name (defined later)

#### Example: Doctrine Example Entity


``` php
<?php
// src/Acme/UserBundle/Entity/User.php

namespace Acme\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializableInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="acme_user")
 */
class User implements EmberDataSerializableInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="username", nullable=false)
     */
    protected $username;
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getEmberDataSerializerAdapterServiceName()
    {
        return 'your.bundle.ember_data_serializer_adapter.user';
    }
}
```

**Note:**

> The service name of the ember data serializer adapter is defined later.


### Step 4: Create your first serializer adapter class

Now you need to create an ember data serializer adapter, which handles the behavior of the created serializable object.

#### Example: Doctrine Example Entity Serializer Adapter

``` php
<?php
// src/Acme/UserBundle/Entity/EmberDataSerializerAdapter/UserAdapter.php

namespace Acme\UserBundle\Entity\EmberDataSerializerAdapter;

use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializableInterface;
use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializerAdapterInterface;
use Acme\UserBundle\Entity\User;

class UserAdapter implements EmberDataSerializerAdapterInterface
{
	const MODEL_NAME_SINGULAR = 'user';
    const MODEL_NAME_PLURAL = 'users';

    /**
     * @param EmberDataSerializableInterface $object
     *
     * @return bool
     */
    public function hasAccess(EmberDataSerializableInterface $object)
    {
        /** @var User $object */
        return true;
    }

    /**
     * @param EmberDataSerializableInterface $object
     *
     * @return array
     */
    public function getData(EmberDataSerializableInterface $object)
    {
        /** @var User $object */
        return array(
            'id' => array($object->getId(), false),
            'username' => array($object->getUsername(), false),
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
```

First you need to define the singular and plural model name, which is defined as an ember data model.

In every adapter you will need the hasAccess() function which is very powerful for securing your application. Every object passes through this function and you can handle each of it with your security functions.

The last function is the getData() function. Here you can manage which information are serialized. If you want you can add some logic here to serialize information which are not in the object. Every array key like 'id' or 'username' must be an array containing two values.
The first value contains the output data. The second value defines if the data should be loaded automatically. So if you want to load ember data async, you need to set the second value to false. If you want to let it load automatically, you have to set is to true.

**Note:**

> If you handle big data through this serializer, you should not load all data automatically. You do not have to care about never ending loops. The Manager only loads not loaded data.

### Step 5: Register created adapter in services.yml

Now you have to register every adapter in your `services.yml`.

``` yaml
# Resources/config/services.yml
services:
    your.bundle.ember_data_serializer_adapter.user:
        class: Acme\UserBundle\Entity\EmberDataSerializerAdapter\UserAdapter
```

**Note:**

> Feel free to add your custom services to the constructor.
> You can optimize the adapter however you want.

### Step 6: Use the serializer manager

Now you are ready to use the ember data serializer.
You can load the manager `unique_libs.ember_data_serializer.manager` in your symfony controller or inject it into your custom service.

Now you have two possibilities:
1. $manager->format($array, $forcedKey) - Serializes many objects
2. $manager->formatOne($object, $forceyKey) - Serializes one object

You need to set the forcedKey, because if you pass an empty array to format(), ember data needs an result with the corresponding key. So if ember data sends a GET request to /users it needs to get a result which has at least the key array('users' => array()). 


#### Example: Use serializer in symfony controller returning many users
``` php
public function defaultAction()
{
	$users = // Get users from repository or whatever

	$emberDataSerializerManager = $this->get('unique_libs.ember_data_serializer.manager');
    
    $serializedArray = $emberDataSerializerManager->format($users, UserAdapter::MODEL_NAME_PLURAL);
    
	return JsonResponse($serializedArray);
}
```

#### Example: Use serializer in symfony controller returning one user
``` php
public function defaultAction()
{
	$users = // Get user from repository or whatever

	$emberDataSerializerManager = $this->get('unique_libs.ember_data_serializer.manager');
    
    $serializedArray = $emberDataSerializerManager->formatOne($user, UserAdapter::MODEL_NAME_SINGULAR);
    
	return JsonResponse($serializedArray);
}
```