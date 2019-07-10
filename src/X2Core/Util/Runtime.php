<?php

namespace X2Core\Util;

/**
 * Class Runtime
 * @package X2Core\Util
 *
 * @desc Class to manager runtime and handler errors and provide tools to runtime control
 */
class Runtime
{
    /**
     * @var mixed
     */
    private static $runTarget;

    /**
     * @var callable
     */
    private static $detectHandle;

    /**
     * @var array[]
     */
    private static $bufferTags = [];

    /**
     * @param callable $handle
     *
     *
     * @desc Set to handle to errors
     * @return void
     */
    public static function handleError($handle){
       set_error_handler($handle);
    }

    /**
     * @param callable $handle
     *
     *
     * @desc Set to handle to critical errors
     * @return void
     */
    public static function handleErrorStrict($handle){
        set_error_handler($handle, E_STRICT);
    }

    /**
     * @param callable $handle
     *
     *
     * @desc Set to handle to exceptions
     * @return void|mixed
     */
    public static function handleExceptions($handle){
        set_exception_handler($handle);
    }

    /**
     * @param null|string|array|callable
     * @param null|callable
     *
     *
     * @desc to prepare mode to detect run tags
     * @return void
     */
    public static function detectRunTags(){
        $arguments = func_get_args();
        if (is_string($arguments[0]) || is_array($arguments[0])){
            self::$runTarget = $arguments[0];
        }elseif (is_callable($arguments[0])){
            self::$runTarget = 1;
            self::$detectHandle = $arguments[1];
            return;
        }else{
            return;
        }

        if(is_callable($arguments[1])){
            self::$detectHandle = $arguments[1];
        }
    }

    /**
     * @param $tag
     * @param $payload
     * @return void
     */
    public static function trigger($tag, $payload){
        if(self::$runTarget === 1 ||
            is_string($tag) && $tag === self::$runTarget ||
            is_array(self::$runTarget) && in_array($tag, self::$runTarget)){
            self::$bufferTags[] = [$tag, $payload];
            if(is_callable(self::$runTarget)){
                (self::$runTarget)($tag, $payload);
            }
        }
    }

    /**
     * @param callable $fn
     * @desc reset buffer array of tags
     * @void
     */
    public static function flush($fn){
        $length = count(self::$bufferTags);
        for($i = 0; $i < $length; $i++){
            $fn(...self::$bufferTags[$i]);
        }
    }

    /**
     * @desc reset buffer array of tags
     * @void
     */
    public static function resetBufferTags(){
        self::$bufferTags = [];
    }

    /**
     * @param $name
     * @param $arguments
     * @return bool
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
       if($name[0] === 'i' && $name[0] === 's'){
           $name = Str::slice($name, 2);
           if(!isset($arguments[0])){
               throw new \Exception("Runtime method magic 'is' need a argument");
           }
           return is_a($name, $arguments[0], TRUE);
       }else{
           throw new \Exception("Runtime method not found");
       }
    }
}