<?php

namespace App\Models;

use Exception;
use App\Services\DBConnector;
use App\Services\Validator;
use FaaPz\PDO\Clause\Conditional;

if (!class_exists(Person::class)) {
    throw new Exception("Person class is not exist.");
}

class PersonsList
{
    private array $idsArr = [];

    private $conn;

    public function __construct($params) {
        $this->conn = DBConnector::getInstance()->getConnection();

        if (!Validator::supportedSigns($params["sign"])) {
            exit("Invalid sign.");
        }

        $select = $this->conn->select()
            ->from("persons")
            ->where(new Conditional($params["field"], $params["sign"], $params["value"]));

        $result = $select->execute();

        $persons = $result->fetchAll();

        foreach ($persons as $person) {
            $this->idsArr[] = $person["id"];
        }
    }

    public function getPersonsArr() {
        $personsArr = [];

        foreach ($this->idsArr as $id) {
            $personsArr[] = new Person($id);
        }

        return $personsArr;
    }

    public function deletePersons() {
        $personsArr = $this->getPersonsArr();

        foreach ($personsArr as $person) {
            $person->delete();
        }
    }

}