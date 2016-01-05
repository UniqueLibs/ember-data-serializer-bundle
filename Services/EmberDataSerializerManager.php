<?php

namespace UniqueLibs\EmberDataSerializerBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UniqueLibs\EmberDataSerializerBundle\Exception\InvalidEmberDataSerializerAdapterServiceNameException;
use UniqueLibs\EmberDataSerializerBundle\Exception\InvalidEmberDataSerializerInputException;
use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializableInterface;
use UniqueLibs\EmberDataSerializerBundle\Interfaces\EmberDataSerializerAdapterInterface;

/**
 * Class EmberDataSerializerManager
 *
 * @package Main\MainBundle\Services
 * @author  Marvin Rind <marvin.rind@uniquelibs.com>
 */
class EmberDataSerializerManager implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * Contains parsed data in ember format.
     *
     * @var array
     */
    protected $data;

    /**
     * Contains cached ember data serializer adapters.
     *
     * @var EmberDataSerializerAdapterInterface[]
     */
    protected $adapters;

    public function __construct()
    {
        $this->data = array();
        $this->adapters = array();
    }

    /**
     * @param EmberDataSerializableInterface[] $objects
     * @param null|string                      $forcedKey
     *
     * @return array
     *
     * @throws InvalidEmberDataSerializerInputException
     */
    public function format($objects, $forcedKey = null)
    {
        $this->createDataKeyIfNotExists($forcedKey);

        if (!isset($objects[0])) {
            return $this->data;
        }

        $className = $this->getClass($objects[0]);

        if (!$objects[0] instanceof EmberDataSerializableInterface) {
            throw new InvalidEmberDataSerializerInputException("Objects needs to implement the EmberDataSerializableInterface.");
        }

        foreach ($objects as $object) {
            if ($this->getClass($object) != $className) {
                throw new InvalidEmberDataSerializerInputException("Objects needs to be an array containing the same classes.");
            }
        }

        foreach ($objects as $object) {
            $this->checkAccessAndParseSerializableObject($object);
        }

        return $this->data;
    }

    /**
     * @param EmberDataSerializableInterface $object
     * @param null|string                    $forcedKey
     *
     * @return array
     * @throws \Exception
     */
    public function formatOne(EmberDataSerializableInterface $object, $forcedKey = null)
    {
        $this->createDataKeyIfNotExists($forcedKey);

        $this->parseSerializableObject($object, false);

        return $this->data;
    }

    /**
     * @param string|null $key
     */
    private function createDataKeyIfNotExists($key = null)
    {
        if (!is_null($key)) {
            if (!isset($this->data[$key])) {
                $this->data[$key] = array();
            }
        }
    }

    /**
     * @param EmberDataSerializableInterface $object
     *
     * @throws \Exception
     */
    private function checkAccessAndParseSerializableObject(EmberDataSerializableInterface $object)
    {
        $adapter = $this->getSerializerAdapterOrNullBySerializableObject($object);

        if (is_null($adapter)) {
            return;
        }

        if (isset($this->data[$adapter->getModelNameSingular()])) {
            if ($this->data[$adapter->getModelNameSingular()]['id'] == $object->getId()) {
                return;
            }
        }

        if (isset($this->data[$adapter->getModelNamePlural()])) {
            foreach ($this->data[$adapter->getModelNamePlural()] as $check) {
                if ($check['id'] == $object->getId()) {
                    return;
                }
            }
        } else {
            $this->data[$adapter->getModelNamePlural()] = array();
        }

        $this->parseSerializableObject($object, true);
    }

    /**
     * @param EmberDataSerializableInterface $object
     * @param bool                           $plural
     *
     * @throws InvalidEmberDataSerializerInputException
     */
    private function parseSerializableObject(EmberDataSerializableInterface $object, $plural = false)
    {
        $adapter = $this->getSerializerAdapterOrNullBySerializableObject($object);

        if (is_null($adapter)) {
            return;
        }

        $index = 0;

        if ($plural) {
            $this->data[$adapter->getModelNamePlural()][] = array();
            $index = count($this->data[$adapter->getModelNamePlural()])-1;
        } else {
            $this->data[$adapter->getModelNameSingular()] = array();
        }

        $data = $adapter->getData($object);

        foreach ($data as $key => $array) {

            $value = $array[0];
            $recurse = $array[1];

            if ($this->isArrayCollection($value)) {

                if (count($value) && is_null($value[0])) {
                    throw new InvalidEmberDataSerializerInputException("Given array needs to start at zero.");
                }

                if (count($value) && $value[0] instanceof EmberDataSerializableInterface) {

                    foreach ($value as $var) {
                        if (!$var instanceof EmberDataSerializableInterface) {
                            throw new InvalidEmberDataSerializerInputException("Each array element needs to be an instance of EmberDataSerializableInterface.");
                        }
                    }

                    $allocatedData = array();

                    $valueAdapter = $this->getSerializerAdapterOrNullBySerializableObject($value[0]);

                    if (!is_null($valueAdapter)) {
                        /** @var EmberDataSerializableInterface $v */
                        foreach ($value as $v) {

                            if ($valueAdapter->hasAccess($v)) {
                                $allocatedData[] = $v->getId();
                            }

                        }
                    }

                    if ($plural) {
                        $this->data[$adapter->getModelNamePlural()][$index][$key] = $allocatedData;
                    } else {
                        $this->data[$adapter->getModelNameSingular()][$key] = $allocatedData;
                    }

                    if ($recurse) {
                        $this->format($value);
                    }

                } else {
                    if ($plural) {
                        $this->data[$adapter->getModelNamePlural()][$index][$key] = count($value) === 0 ? array() : $value;
                    } else {
                        $this->data[$adapter->getModelNameSingular()][$key] = count($value) === 0 ? array() : $value;
                    }
                }
            } else if ($value instanceof EmberDataSerializableInterface) {

                $valueAdapter = $this->getSerializerAdapterOrNullBySerializableObject($value);

                if (!is_null($valueAdapter)) {
                    if ($valueAdapter->hasAccess($value)) {

                        if ($plural) {
                            $this->data[$adapter->getModelNamePlural()][$index][$key] = $value->getId();
                        } else {
                            $this->data[$adapter->getModelNameSingular()][$key] = $value->getId();
                        }

                        if ($recurse) {
                            $this->checkAccessAndParseSerializableObject($value);
                        }
                    }
                }

            } else {

                if ($plural) {
                    $this->data[$adapter->getModelNamePlural()][$index][$key] = $value;
                } else {
                    $this->data[$adapter->getModelNameSingular()][$key] = $value;
                }

            }
        }
    }

    /**
     * @param $value
     *
     * @return bool
     */
    private function isArrayCollection($value)
    {
        if (is_array($value) || $value instanceof \ArrayAccess) {
            return true;
        }

        return false;
    }

    /**
     * @param EmberDataSerializableInterface $object
     *
     * @return null|EmberDataSerializerAdapterInterface
     * @throws InvalidEmberDataSerializerAdapterServiceNameException
     */
    private function getSerializerAdapterOrNullBySerializableObject(EmberDataSerializableInterface $object)
    {
        $class = $this->getClass($object);

        if (!isset($this->adapters[$class])) {

            $adapter = $this->container->get($object->getEmberDataSerializerAdapterServiceName());

            if (!$adapter instanceof EmberDataSerializerAdapterInterface) {
                throw new InvalidEmberDataSerializerAdapterServiceNameException('Adapter is not an instance of EmberDataSerializerAdapterInterface.');
            }

            $this->adapters[$class] = $adapter;
        }

        if ($this->adapters[$class]->hasAccess($object)) {
            return $this->adapters[$class];
        }

        return null;
    }

    /**
     * @param EmberDataSerializableInterface $object
     *
     * @return string
     */
    private function getClass(EmberDataSerializableInterface $object)
    {
        $class = get_class($object);

        if (substr($class, 0, 15) == 'Proxies\__CG__\\') {
            $class = substr($class, 15);
        }

        return $class;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
