UniqueLibs/EmberDataSerializerBundle
=============

The EmberDataSerializerBundle by UniqueLibs adds support for serializing data for ember data rest apis.
It provides a powerful manager which can convert objects and arrays to ember data conform arrays.

Features include:

- Convert any object through adapter to ember data array structure
- Customizable security function to restrict access
- Supports ember data async loading
- Immune to never ending loops
- Unit tested
- Symfony 3.0 Support
- PHP 7.0 Support

**Note:** This bundle does *not* provide an rest api system.
You should pass the serialized output through the [JMSSerializerBundle](http://jmsyst.com/bundles/JMSSerializerBundle) or [FOSRestBundle](http://symfony.com/doc/current/bundles/FOSRestBundle/index.html), which uses the JMSSerializerBundle.

[![Build Status](https://api.travis-ci.org/UniqueLibs/ember-data-serializer-bundle.png?branch=master)](https://travis-ci.org/UniqueLibs/ember-data-serializer-bundle)

Documentation
-------------

The bulk of the documentation is stored in the `Resources/doc/index.md`
file in this bundle:

[Read the Documentation for master](https://github.com/UniqueLibs/ember-data-serializer-bundle/blob/master/Resources/doc/index.md)

Installation
------------

All the installation instructions are located in the documentation.

License
-------

This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

About
-----

EmberDataSerializerBundle is a [UniqueLibs](https://github.com/UniqueLibs) initiative.
See also the list of [contributors](https://github.com/UniqueLibs/ember-data-serializer-bundle/contributors).

Reporting an issue or a feature request
---------------------------------------

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/UniqueLibs/ember-data-serializer-bundle/issues).

When reporting a bug, it may be a good idea to reproduce it in a basic project
built using the [Symfony Standard Edition](https://github.com/symfony/symfony-standard)
to allow developers of the bundle to reproduce the issue by simply cloning it
and following some steps.