<?php
/**
 * HandlerFactory
 *
 * @copyright Copyright © 2020 Mageseller. All rights reserved.
 * @author    satish29g@hotmail.com
 */

namespace Mageseller\DickerdataImport\Logger\Handler;

use InvalidArgumentException;
use Magento\Framework\ObjectManagerInterface;
use Mageseller\DickerdataImport\Logger\Handler\HandlerAbstract as ObjectType;

class HandlerFactory
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    protected $objectManager = null;

    /**
     * Instance name to create
     *
     * @var string
     */
    protected $instanceTypeNames = [
        'error' => \Mageseller\DickerdataImport\Logger\Handler\Error::class,
        'info' => \Mageseller\DickerdataImport\Logger\Handler\Info::class,
        'debug' => \Mageseller\DickerdataImport\Logger\Handler\Debug::class,
    ];

    /**
     * Factory constructor
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create corresponding class instance
     *
     * @param $type
     * @param array $data
     * @return ObjectType
     */
    public function create($type, array $data = [])
    {
        if (empty($this->instanceTypeNames[$type])) {
            throw new InvalidArgumentException('"' . $type . ': isn\'t allowed');
        }

        $resultInstance = $this->objectManager->create($this->instanceTypeNames[$type], $data);
        if (!$resultInstance instanceof ObjectType) {
            throw new InvalidArgumentException(get_class($resultInstance) .
                ' isn\'t instance of \Mageseller\DickerdataImport\Logger\Handler\HandlerAbstract');
        }

        return $resultInstance;
    }
}