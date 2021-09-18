<?php

namespace Subtext\AppEngine\Services;

use InvalidArgumentException;
use PDO;
use PDOException;
use PDOStatement;
use RuntimeException;
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
     * @var ?Database
     */
    private static ?Database $instance;

    /**
     * @param PDO $pdo
     */
    private function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function hasPDO(): bool
    {
        return !empty(self::$instance);
    }

    public static function resetInstance(): bool
    {
        $value = false;
        if (self::hasPDO()) {
            self::$instance = null;
            $value = true;
        }

        return $value;
    }

    public static function getInstance(?PDO $pdo = null): Database
    {
        if (empty(self::$instance)) {
            if (empty($pdo)) {
                throw new RuntimeException(
                    "The PDO object must be set on the first instantiation",
                    500,
                );
            }
            self::$instance = new self($pdo);
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
            if ($this->executeStatement($stmt, $data)) {
                $class = $stmt->fetchObject($entityClassname);
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        } finally { // @codeCoverageIgnore
            return $class;
        }
    } // @codeCoverageIgnore

    public function getIdForInsert(string $sql, array $data = []): int
    {
        $id = 0;
        if (strpos(trim($sql), 'INSERT') !== 0) {
            throw new InvalidArgumentException(
                "SQL statement must begin with INSERT"
            );
        }
        try {
            $stmt = $this->getPreparedStatement($sql);
            if ($this->executeStatement($stmt, $data)) {
                $id = intval($this->pdo->lastInsertId());
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        }

        return $id;
    }

    public function getRowsAffectedForUpdate(string $sql, array $data = []): int
    {
        $num = 0;
        if (strpos(trim($sql), 'UPDATE') !== 0) {
            throw new InvalidArgumentException(
                "SQL statement must begin with UPDATE"
            );
        }
        try {
            $stmt = $this->getPreparedStatement($sql);
            if ($this->executeStatement($stmt, $data)) {
                $num = $stmt->rowCount();
            }
        } catch (Throwable $e) {
            $this->handleError($e);
        }

        return $num;
    }

    public function execute(string $sql, $data = []): bool
    {
        try {
            $stmt = $this->getPreparedStatement($sql);
            $result = $this->executeStatement($stmt, $data);
        } catch (Throwable $e) {
            $result = false;
            $this->handleError($e);
        } finally { // @codeCoverageIgnore
            return $result;
        }
    } // @codeCoverageIgnore

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
        } catch (Throwable $e) {
            $result = null;
            $this->handleError($e);
        } finally { // @codeCoverageIgnore
            return $result;
        }
    } // @codeCoverageIgnore

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
            $this->handleError($e);
        } catch (Throwable $e) {
            $this->handleError($e);
        } finally { // @codeCoverageIgnore
            return $success;
        }
    } // @codeCoverageIgnore

    private function handleError(Throwable $e): void
    {
        array_push($this->errors, $e);
    }
}
