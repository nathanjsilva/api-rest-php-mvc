<?php

namespace App\Models;

use App\Model;
use Utils\ExceptionsHandler;
use Utils\Security;

class User extends Model
{
    private int $id;
    private string $name;
    private string $secret;
    private string $pass;
    private string $userToken;
    private string $userTokenExp;
    private string $createdAt;
    private string $updatedAt;

    public function __construct($attrs = [])
    {
        if ($attrs) {
            $this->setId((int) @$attrs['id']);
            $this->setName((string) @$attrs['name']);
            $this->setCpf((string) @$attrs['cpf']);
            $this->setTelefone((string) @$attrs['telefone']);
            $this->setName((string) @$attrs['name']);
            $this->setSecret((string) @$attrs['secret']);
            $this->setPass((string) @$attrs['pass']);
            $this->setUserToken((string) @$attrs['userToken']);
            $this->setUserTokenExp((string) @$attrs['userTokenExp']);
            $this->setCreatedAt((string) @$attrs['createdAt']);
            $this->setUpdatedAt((string) @$attrs['updatedAt']);
        }
    }

    # Getters

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
    public function getCpf(): string
    {
        return $this->cpf;
    }
    public function getTelefone(): string
    {
        return $this->telefone;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getPass(): string
    {
        return $this->pass;
    }

    public function getUserToken(): string
    {
        return $this->userToken;
    }

    public function getUserTokenExp(): string
    {
        return $this->userTokenExp;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    # Setters

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function setCpf(string $cpf)
    {
        $this->cpf = $cpf;
    }
    public function setTelefone(string $telefone)
    {
        $this->telefone = $telefone;
    }

    public function setSecret(string $secret)
    {
        $this->secret = $secret;
    }

    public function setPass(string $pass)
    {
        $this->pass = $pass;
    }

    public function setUserToken(string $userToken)
    {
        $this->userToken = $userToken;
    }

    public function setUserTokenExp(string $userTokenExp)
    {
        $this->userTokenExp = $userTokenExp;
    }

    public function setCreatedAt(string $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(string $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function insert(): int
    {
        try {
            return (int) $this->runQuery(
                "INSERT INTO users SET
                        name       = :name,
                        cpf        = :cpf,
                        telefone   = :telefone,
                        secret     = :secret,
                        pass       = :pass,
                        created_at = NOW()",
                [
                    ':name'     => $this->name,
                    ':secret'   => $this->secret,
                    ':cpf'      => $this->cpf,
                    ':telefone' => $this->telefone,
                    ':pass'     => Security::encryptPass($this->pass),
                ]
            )->getLastId();
        } catch (\PDOException $e) {
            (new ExceptionsHandler($e, 'E011-001', 500))->_print();
        }
    }

    public function login(): int
    {
        try {
            return (int) $this->runQuery("SELECT id FROM users WHERE secret = :secret AND pass = :pass", [
                ':secret' => $this->secret,
                ':pass'   => $this->pass,
            ])->getColumn();
        } catch (\PDOException $e) {
            (new ExceptionsHandler($e, 'E011-004', 500))->_print();
        }
    }

    public function getDadosUser(): array
    {
        try {
            return (array) $this->runQuery("SELECT id, name, cpf, telefone FROM users WHERE secret = :secret AND pass = :pass", [
                ':secret' => $this->secret,
                ':pass'   => $this->pass,
            ])->getArray();
        } catch (\PDOException $e) {
            (new ExceptionsHandler($e, 'E011-004', 500))->_print();
        }
    }

    public function updateUser($data)
    {
        try {
            return (int) $this->runQuery("UPDATE users SET name = :name, cpf =:cpf, telefone = :telefone WHERE secret = :secret AND pass = :pass", [
                ':secret'     => $data['secret'],
                ':pass'       => $data['pass'],
                ':name'       => $data['name'],
                ':cpf'        => $data['cpf'],
                ':telefone'   => $data['telefone'],
            ])->getRows();
        } catch (\PDOException $e) {
            (new ExceptionsHandler($e, 'E011-004', 500))->_print();
        }
    }

    public function deleteUser($id)
    {
        try {
            return (int) $this->runQuery("DELETE FROM users WHERE id = :id", [
                ':id'         => $id
            ])->getRows();
        } catch (\PDOException $e) {
            (new ExceptionsHandler($e, 'E011-004', 500))->_print();
        }
    }
}
