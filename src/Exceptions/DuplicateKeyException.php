<?php

namespace NaiveContainer\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class DuplicateKeyException extends Exception implements ContainerExceptionInterface
{
    
}
