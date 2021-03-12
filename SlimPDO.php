<?php
class SlimPDO
{
    protected static $pdocores;       // Will contain all PDOCore instances

    /**
     * Initiates a new connection to target db host and database using PDOCore
     * Does NOT create a new instance if the connection already exists
     * @param String $host Target host ip or url
     * @param String $db Database name
     * @param String $user Username for db account
     * @param String $pass Password for db account
     * @param String $charset Charset
     * @return Object A very slim PDO wrapper object ready for queries
     */
    public static function initDb(string $db = DATABASE, string $host = DB_HOST, string $user = DB_USER, string $pass = DB_PASS, string $charset = DB_CHARSET)
    {
        if (self::$pdocores === null) {
            self::$pdocores = [];
        }

        foreach (self::$pdocores as $key => $instance) {
            if ($instance->getDb() === $db && $instance->getHost() === $host) {
                return self::$pdocores[$key];
            }
        }

        require_once CORE . 'Classes/SlimPDO/PDOCore.php';
        self::$pdocores[] = new PDOCore($db, $host, $user, $pass, $charset);
        return end(self::$pdocores);
    }
}
