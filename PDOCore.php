<?php
class PDOCore
{
    protected $pdo; // PDO

    // Used for matching if instance already exists
    private $host = '';
    private $db = '';

    /**
     * Create a new PDO instance on construction
     * @param String $db Database name
     * @param String $host Target host ip or url
     * @param String $user Username for db account
     * @param String $pass Password for db account
     * @param String $charset Charset
     * @return Object A very slim PDO wrapper object ready for queries
     */
    public function __construct(string $db, string $host, string $user, string $pass, string $charset)
    {
        $this->host = $host;
        $this->db = $db;
        $opt  = array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => FALSE
        );
        $dsn = 'mysql:host=' . $host . ';dbname=' . $db . ';charset=' . $charset;
        $this->pdo = new PDO($dsn, $user, $pass, $opt);
    }

    // a proxy to native PDO methods
    public function __call($method, $args)
    {
        return call_user_func_array(array($this->pdo, $method), $args);
    }

    // a helper function to run prepared statements smoothly
    public function run($sql, $args = [])
    {
        if (!$args) {
            return $this->query($sql);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getDb()
    {
        return $this->db;
    }
}
