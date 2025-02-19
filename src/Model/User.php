<?php

namespace App\Model;


class User
{
    private ?int $id;

    private ?string $fullname;

    private ?string $email;

    private ?string $password;

    private ?array $role;


    public function __construct(?int $id = null, ?string $fullname = null, ?string $email = null, ?string $password = null, ?array $role = null)
    {
        $this->id = $id;
        $this->fullname = $fullname;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }
      

// Getters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRole(): ?array
    {
        return $this->role;
    }

// Setters

    public function setId(?int $id): User
    {
        $this->id = $id;
        return $this;
    }

    public function setFullname(?string $fullname): User
    {
        $this->fullname = $fullname;
        return $this;
    }

    public function setEmail(?string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(?string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function setRole(?array $role): User
    {
        $this->role = $role;
        return $this;
    }

// Methods

    public function findOneById(int $id): static|false
    {
        $pdo = new \PDO('mysql:host=localhost;dbname=my-little-mvc', 'root', '');
        $sql = "SELECT * FROM user WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $user = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($user) {

            $role = json_decode($user['role'], true);

            return new static(
                $user['id'],
                $user['fullname'],
                $user['email'],
                $user['password'],
                $role,
            );
        }
        return false;
    }

    public function findAll(): array
    {
        $pdo = new \PDO('mysql:host=localhost;dbname=my-little-mvc', 'root', '');
        $sql = "SELECT * FROM user";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $users = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $results = [];
        foreach ($users as $user) {
            $results[] = new static(
                $user['id'],
                $user['fullname'],
                $user['email'],
                $user['password'],
                $user['role']
            );
        }

        return $results;
    }

    public function create(): static
    {
        $jsonRole = json_encode($this->role);

        $pdo = new \PDO('mysql:host=localhost;dbname=my-little-mvc', 'root', '');
        $sql = "INSERT INTO user (fullname, email, password, role) VALUES (:fullname, :email, :password, :role)";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':fullname', $this->fullname);
        $statement->bindValue(':email', $this->email);
        $statement->bindValue(':password', $this->password);
        $statement->bindValue(':role', $jsonRole);
        $statement->execute();
        $this->id = (int)$pdo->lastInsertId();
        return $this;
    }

    public function update(): static
    {
        $jsonRole = json_encode($this->role);

        $pdo = new \PDO('mysql:host=localhost;dbname=my-little-mvc', 'root', '');
        $sql = "UPDATE user SET fullname = :fullname, email = :email, password = :password, role = :role WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':fullname', $this->fullname);
        $statement->bindValue(':email', $this->email);
        $statement->bindValue(':password', $this->password);
        $statement->bindValue(':role', $jsonRole);
        $statement->bindValue(':id', $this->id);
        $statement->execute();
        return $this;
    }

    public function findOneByEmail(string $email): static|false
    {

        $pdo = new \PDO('mysql:host=localhost;dbname=my-little-mvc', 'root', '');
        $sql = "SELECT * FROM user WHERE email = :email";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $user = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($user) {

            $role = json_decode($user['role'], true);

            return new static(
                $user['id'],
                $user['fullname'],
                $user['email'],
                $user['password'],
                $role,
            );
        }
        return false;
    }
}