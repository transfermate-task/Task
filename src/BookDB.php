<?php

namespace src;

class BookDB
{
    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * BookDB constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->createTable();
    }

    /**
     * @return $this
     */
    public function createTable(): self
    {
        $sqlList = [
            'CREATE TABLE IF NOT EXISTS books (
                id serial PRIMARY KEY,
                name character varying(255) NOT NULL,
                author character varying(255) NOT NULL,
                updated_at timestamp NOT NULL DEFAULT NOW()
             );'
        ];

        foreach ($sqlList as $sql) {
            $this->pdo->exec($sql);
        }

        return $this;
    }

    /**
     * @param string $name
     * @param string $author
     * @return int|string
     * @throws \Exception
     */
    public function updateOrInsert(string $name, string $author)
    {
        if ($obj = $this->findBy(['name' => $name, 'author' => $author], true)) {
            return $this->update($obj->id, $name, $author);
        } else {
            return $this->insert($name, $author);
        }
    }

    /**
     * @param string $name
     * @param string $author
     * @return string
     * @throws \Exception
     */
    public function insert(string $name, string $author): string
    {
        $sql = 'INSERT INTO books(name,author,updated_at) VALUES(:name,:author,NOW())';
        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':author', $author);

        $stmt->execute();

        return $this->pdo->lastInsertId();
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $author
     * @return int
     * @throws \Exception
     */
    public function update(int $id, string $name, string $author): int
    {
        $this->pdo->beginTransaction();

        $sql = 'UPDATE books '
            . 'SET author = :author, '
            . 'name = :name, '
            . 'updated_at = NOW() '
            . 'WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->bindValue(':author', $author);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $this->pdo->commit();

        return $stmt->rowCount();
    }

    /**
     * @param array $array
     * @param bool $first
     * @return array
     */
    public function findBy(array $array, bool $first = false)
    {
        $where = false;
        $binds = [];

        foreach ($array as $column => $value) {
            if (!$where) {
                $where = "{$column} = :{$column}";
            } else {
                $where .= " AND {$column} = :{$column}";
            }
            $binds[] = [':' . $column => $value];
        }

        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE $where");
        foreach ($binds as $bindArray) {
            foreach ($bindArray as $column => $value) {
                $stmt->bindValue($column, $value);
            }
        }

        $stmt->execute();

        if ($first) {
            return $stmt->fetchObject();
        }

        return $this->fetchAssoc($stmt);
    }

    /**
     * @return array
     */
    public function all(): array
    {
        $stmt = $this->pdo->query('SELECT * FROM books ORDER BY id');
        return $this->fetchAssoc($stmt);
    }

    /**
     * @param \PDOStatement $stmt
     * @return array
     */
    private function fetchAssoc(\PDOStatement $stmt): array
    {
        $array = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $array[] = [
                'id' => $row['id'],
                'name' => $row['name'],
                'author' => $row['author'],
                'updated_at' => $row['updated_at'],
            ];
        }
        return $array;
    }
}