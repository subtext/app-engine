<?php

namespace Subtext\AppEngine\Services;

use PDO;
use PDOException;
use PDOStatement;
use stdClass;
use Subtext\AppEngine\Entities\Entity;
use Throwable;

class Database
{
    /**
     * @var PDO
     */
    private PDO $pdo;

    /**
     * @var array []PDOStatement
     */
    private array $queries = [];

    /**
     * @var array []PDOError
     */
    private array $errors = [];

    /**
     * @var Database
     */
    private static Database $instance;

    /**
     * @param string $dsn
     * @param string $user
     * @param string $pass
     */
    private function __construct(string $dsn, string $user, string $pass)
    {
        $this->pdo = new PDO($dsn, $user, $pass);
    }

    public static function getInstance(string $dsn, string $user, string $pass): Database
    {
        if (empty(self::$instance)) {
            self::$instance = new self($dsn, $user, $pass);
        }

        return self::$instance;
    }

    public function getObject(string $sql, array $data = []): stdClass
    {
        return $this->getResultFromDatabase($sql, $data, PDO::FETCH_OBJ, false);
    }

    public function getArrayOfObjects(string $sql, array $data = []): array
    {
        return $this->getResultFromDatabase($sql, $data, PDO::FETCH_OBJ);
    }

    public function getAssociativeArray(string $sql, array $data = []): array
    {
        return $this->getResultFromDatabase($sql, $data, PDO::FETCH_ASSOC, false);
    }

    public function getArrayOfAssociativeArrays(string $sql, array $data = []): array
    {
        return $this->getResultFromDatabase($sql, $data, PDO::FETCH_ASSOC);
    }

    public function getEntity(string $sql, array $data = [], string $entityClassname): Entity
    {
        try {
            $stmt = $this->getPreparedStatement($sql);
            if (!$this->executeStatement($stmt, $data)) {
                $this->handleError(null);
            } else {
                $class = $stmt->fetchObject($entityClassname);
            }
        } catch (PDOException $e) {
            $this->handleError($e);
        } finally {
            return $class;
        }
    }

    public function getIdForInsert(string $sql, array $data = []): int
    {
        $id = 0;
        if (strpos(trim($sql), 'INSERT') !== 0) {
            throw new \InvalidArgumentException(
                "SQL statement must begin with INSERT"
            );
        }
        try {
            $stmt = $this->getPreparedStatement($sql);
            if ($this->executeStatement($stmt, $data)) {
                $id = intval($this->pdo->lastInsertId());
            }
        } catch (PDOException $e) {
            array_push($this->errors, $e);
        }

        return $id;
    }

    public function getRowsAffectedForUpdate(string $sql, array $data = []): int
    {
        $num = 0;
        if (strpos(trim($sql), 'UPDATE') !== 0) {
            throw new \InvalidArgumentException(
                "SQL statement must begin with UPDATE"
            );
        }
        try {
            $stmt = $this->getPreparedStatement($sql);
            if ($this->executeStatement($stmt, $data)) {
                $num = $stmt->rowCount();
            }
        } catch (PDOException $e) {
            array_push($this->errors, $e);
        }

        return $num;
    }

    public function execute(string $sql, $data = []): bool
    {
        try {
            $stmt = $this->getPreparedStatement($sql);
            $result = $this->executeStatement($stmt, $data);
        } catch (PDOException $e) {
            $result = false;
            array_push($this->errors, $e);
        } finally {
            return $result;
        }
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function getResultFromDatabase(string $sql, array $data, int $style, bool $all = true)
    {
        try {
            $stmt = $this->getPreparedStatement($sql);
            if (!$this->executeStatement($stmt, $data)) {
                $result = null;
            } else {
                if ($all) {
                    $result = $stmt->fetchAll($style);
                } else {
                    $result = $stmt->fetch($style);
                }
            }
        } catch (PDOException $e) {
            array_push($this->errors, $e);
            $result = null;
        } finally {
            return $result;
        }
    }

    private function getPreparedStatement(string $sql): PDOStatement
    {
        $sql = trim($sql);
        $hash = md5($sql);
        if (empty($this->queries[$hash])) {
            if (($stmt = $this->pdo->prepare($sql)) === false) {
                throw new PDOException("The query with hash: $hash could not be prepared");
            }
            $this->queries[$hash] = $stmt;
        }

        return $this->queries[$hash];
    }

    private function executeStatement(PDOStatement $stmt, array $data): bool
    {
        try {
            $success = $stmt->execute($data);
            if (!$success) {
                $info = $stmt->errorInfo();
                if (!empty($info)) {
                    $exception = new PDOException($info[2], $info[1]);
                    $exception->errorInfo = $info;
                    throw $exception;
                }
            }
        } catch (PDOException $e) {
            $success = false;
            array_push($this->errors, $e);
        } finally {
            return $success;
        }
    }

    private function handleError(?Throwable $e): void
    {
        if (is_null($e)) {
            // check for database errors
        } else {
            $e->getMessage();
            $e->getCode();
            $e->getLine();
            $e->getFile();
        }
    }
}
