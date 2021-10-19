<?php
/**
 * Date:    23.01.18
 *
 * @author: dolphin54rus <dolphin54rus@gmail.com>
 */

namespace App\Enum;

use Traversable;

class Enum implements \IteratorAggregate
{
    private static $constCacheArray = NULL;

    private static function getConstants() {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }
        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new \ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
            
            if (isset(self::$constCacheArray[$calledClass]['DEFAULT'])) {
                unset(self::$constCacheArray[$calledClass]['DEFAULT']);
            }
        }
        return self::$constCacheArray[$calledClass];
    }

    /**
     * Retrieve an external iterator
     *
     * @link  http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        return new \ArrayIterator( self::getConstants() );
    }
}