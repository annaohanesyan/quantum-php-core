<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 2.6.0
 */

namespace Quantum\Libraries\Database\Idiorm;

use Quantum\Libraries\Database\Idiorm\Statements\Criteria;
use Quantum\Libraries\Database\Idiorm\Statements\Reducer;
use Quantum\Libraries\Database\Idiorm\Statements\Result;
use Quantum\Libraries\Database\Idiorm\Statements\Model;
use Quantum\Libraries\Database\Idiorm\Statements\Query;
use Quantum\Libraries\Database\Idiorm\Statements\Join;
use Quantum\Libraries\Database\DbalInterface;
use Quantum\Exceptions\DatabaseException;
use PDO;
use ORM;


/**
 * Class IdiormDbal
 * @package Quantum\Libraries\Database
 */
class IdiormDbal implements DbalInterface, RelationalInterface
{

    use Model;
    use Result;
    use Criteria;
    use Reducer;
    use Join;
    use Query;

    /**
     * Type array
     */
    const TYPE_ARRAY = 1;

    /**
     * Type object
     */
    const TYPE_OBJECT = 2;

    /**
     * Default charset
     */
    const DEFAULT_CHARSET = 'utf8';

    /**
     * The database table associated with model
     * @var string
     */
    private $table;

    /**
     * Id column of table
     * @var string
     */
    private $idColumn;

    /**
     * Foreign keys
     * @var array
     */
    private $foreignKeys = [];

    /**
     * Idiorm Patch object
     * @var \Quantum\Libraries\Database\Idiorm\IdiormPatch
     */
    private $ormPatch = null;

    /**
     * ORM Model
     * @var object
     */
    private $ormModel;

    /**
     * Active connection
     * @var array|null
     */
    private static $connection = null;

    /**
     * Operators map
     * @var string[]
     */
    private $operators = [
        '=' => 'where_equal',
        '!=' => 'where_not_equal',
        '>' => 'where_gt',
        '>=' => 'where_gte',
        '<' => 'where_lt',
        '<=' => 'where_lte',
        'IN' => 'where_in',
        'NOT IN' => 'where_not_in',
        'LIKE' => 'where_like',
        'NOT LIKE' => 'where_not_like',
        'NULL' => 'where_null',
        'NOT NULL' => 'where_not_null',
        '#=#' => null,
    ];

    /**
     * ORM Class
     * @var string
     */
    private static $ormClass = ORM::class;

    /**
     * Class constructor
     * @param string $table
     * @param string $idColumn
     * @param array $foreignKeys
     */
    public function __construct(string $table, string $idColumn = 'id', array $foreignKeys = [])
    {
        $this->table = $table;
        $this->idColumn = $idColumn;
        $this->foreignKeys = $foreignKeys;
    }

    /**
     * @inheritDoc
     */
    public static function connect(array $config)
    {
        $configuration = [
            'connection_string' => self::buildConnectionString($config),
            'logging' => config()->get('debug', false),
            'error_mode' => PDO::ERRMODE_EXCEPTION,
        ];

        if ($config['driver'] == 'mysql' || $config['driver'] == 'pgsql') {
            $configuration = array_merge($configuration, [
                'username' => $config['username'] ?? null,
                'password' => $config['password'] ?? null,
                'driver_options' => [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . ($config['charset'] ?? self::DEFAULT_CHARSET)
                ]
            ]);

        }

        (self::$ormClass)::configure($configuration);

        self::$connection = (self::$ormClass)::get_config();
    }

    /**
     * @inheritDoc
     */
    public static function getConnection(): ?array
    {
        return self::$connection;
    }

    /**
     * @inheritDoc
     */
    public static function disconnect()
    {
        self::$connection = null;
        (self::$ormClass)::reset_db();
    }

    /**
     * @inheritDoc
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Gets the ORM model
     * @return \ORM
     * @throws \Quantum\Exceptions\DatabaseException
     */
    public function getOrmModel(): ORM
    {
        if (!$this->ormModel) {
            if (!self::getConnection()) {
                throw DatabaseException::missingConfig();
            }

            $this->ormModel = (self::$ormClass)::for_table($this->table)->use_id_column($this->idColumn);
        }

        return $this->ormModel;
    }

    /**
     * @param \ORM $ormModel
     */
    protected function updateOrmModel(ORM $ormModel)
    {
        $this->ormModel = $ormModel;
    }

    /**
     * Builds connection string
     * @param array $connectionDetails
     * @return string
     */
    protected static function buildConnectionString(array $connectionDetails): string
    {
        $connectionString = $connectionDetails['driver'] . ':';

        switch ($connectionDetails['driver']) {
            case 'sqlite':
                $connectionString .= $connectionDetails['database'];
                break;
            case 'mysql':
            case 'pgsql':
                $connectionString .= 'host=' . $connectionDetails['host'] . ';';

                if (isset($connectionDetails['port'])) {
                    $connectionString .= 'post=' . $connectionDetails['port'] . ';';
                }

                $connectionString .= 'dbname=' . $connectionDetails['dbname'] . ';';

                if (isset($connectionDetails['charset'])) {
                    $connectionString .= 'charset=' . $connectionDetails['charset'] . ';';
                }
                break;
        }

        return $connectionString;
    }

}
