<?php
namespace app\redis_lock;

class RedisLock
{

    //锁的超时时间
    const TIMEOUT = 20;

    const SLEEP = 100000;

    protected $_server = null;
    protected $_config;
    protected static $expire;

    public function __construct($config = ['host' => '127.0.0.1', 'port' => '6379'])
    {
        $this->_config = $config;
        $this->get_server();
    }

    private function get_server()
    {
        if ($this->_server !== null) {
            return $this->_server;
        }
        $this->_server = new \Redis();
        $result = $this->_server->connect($this->_config['host'], $this->_config['port']);
        if (!$result) {
            // throw new \Exception("memecacheq connected failed.");
            //Log::record('memecacheq connected failed.');
            return $this->_server = null;
        }
        return $this->_server;
    }

    /**
     * 加锁
     * @param string $key 锁key
     * @param int $timeout 锁超时时间
     * @return boolean
     */
    public function lock($key, $timeout = 0)
    {
        if (!$key) {
            return false;
        }

        $start = time();

        $redis = $this->_server;

        do {
            self::$expire = self::timeout();

            if ($acquired = ($this->_server->setnx("Lock:{$key}", self::$expire))) {
                break;
            }

            if ($acquired = ($this->recover($key))) {
                break;
            }
            if ($timeout === 0) {
                //如果超时时间为0，即为
                break;
            }

            usleep(self::SLEEP);

        } while (!is_numeric($timeout) || time() < $start + $timeout);

        if (!$acquired) {
            //超时
            return false;
        }

        return true;
    }

    /**
     * 释放锁
     * @param string $key 锁key
     * @return boolean
     */
    public function unlock($key)
    {
        if (!$key) {
            return false;
        }

        // Only release the lock if it hasn't expired
        if (self::$expire > time()) {
            $this->_server->del("Lock:{$key}");
        }
    }

    /**
     * 检测是否为加锁状态
     * @param string $key
     * @return boolean
     */
    public function recover($key)
    {

        if (($lockTimeout = $this->_server->get("Lock:{$key}")) > time()) {
            //锁还没有过期
            return false;
        }

        $timeout = self::timeout();
        $currentTimeout = $this->_server->getset("Lock:{$key}", $timeout);

        if ($currentTimeout != $lockTimeout) {
            return false;
        }

        self::$expire = $timeout;
        return true;
    }

    /**
     * 生成锁的过期时间
     * @return int    timeout
     */
    protected static function timeout()
    {
        return (int)(time() + self::TIMEOUT + 1);
    }

    public function test(){
        echo 'testsss';
    }

}