<?php

namespace App\Models;

use DateTime;
use Exception;
use stdClass;
use App\Enums\Gender;
use App\Services\DBConnector;
use App\Services\Validator;
use FaaPz\PDO\Clause\Conditional;

class Person
{
    private int    $id;
    private string $firstName;
    private string $lastName;
    private string $birthDate;
    private int    $gender;
    private string $city;

    private static $conn;

    public function __construct(...$args) {
        $this->conn = DBConnector::getInstance()->getConnection();
        
        $argsCount = count($args);

        if ($argsCount == 1) {
            $this->id = $args[0];
            $this->load();
        } else {
            $firstName = $args[0];
            $lastName  = $args[1];
            $birthDate = $args[2];
            $gender    = $args[3];
            $city      = $args[4];

            try {
                if (self::isValid($firstName, $lastName, $birthDate, $gender, $city)) {
                    $this->firstName = $firstName;
                    $this->lastName  = $lastName;
                    $this->birthDate = $birthDate;
                    $this->gender    = $gender;
                    $this->city      = $city;

                    $this->save();
                } else {
                    throw new Exception("Invalid arguments.");
                }
            } catch (Exception $e) {
                self::showErrorMsg($e);
            }
        }

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function isExist()
    {
        return $this->id > 0 ? true : false;
    }

    private static function isValid(string $firstName, string $lastName, string $birthDate, int $gender, string $city)
    {
        if (!Validator::isString($firstName)) return false;
        if (!Validator::isString($lastName))  return false;
        if (!Validator::isDate($birthDate))   return false;
        if (!Validator::isGender($gender))    return false;
        if (!Validator::isString($city))      return false;

        return true;
    }

    private function save()
    {
        $insert = $this->conn->insert([
            "first_name",
            "last_name",
            "birth_date",
            "gender",
            "city"
        ])
        ->into("persons")
        ->values(
            $this->firstName,
            $this->lastName,
            $this->birthDate,
            $this->gender,
            $this->city
        );

        if ($insert->execute()) {
            $this->id = $this->conn->lastInsertId();
        }
    }

    private function load()
    {
        $select = $this->conn->select()
            ->from("persons")
            ->where(new Conditional("id", "=", $this->id));

        $result = $select->execute();

        $data = $result->fetch();

        try {
            if ($data) {
                if (self::isValid($data["first_name"], $data["last_name"], $data["birth_date"], $data["gender"], $data["city"])) {
                    $this->firstName = $data["first_name"];
                    $this->lastName  = $data["last_name"];
                    $this->birthDate = $data["birth_date"];
                    $this->gender    = $data["gender"];
                    $this->city      = $data["city"];
                } else {
                    throw new Exception("Invalid data.");
                }
            } else {
                throw new Exception("Invalid id.");
            }
        } catch (Exception $e) {
            self::showErrorMsg($e);
        }
    }

    public function delete()
    {
        $delete = $this->conn->delete()
            ->from("persons")
            ->where(new Conditional("id", "=", $this->id));

        $delete->execute();

        $this->id = -1;
    }

    public static function getAge(string $birthDateStr)
    {
        $now = new DateTime();
        $birth = new DateTime($birthDateStr);
        $diff = $now->diff($birth);
        return $diff->y;
    }

    public static function getGenderStr(int $num)
    {
        return Gender::genderByNum($num);
    }

    public function format(...$args)
    {
        $formattedPerson = new stdClass();

        $formattedPerson->id        = $this->id;
        $formattedPerson->firstName = $this->firstName;
        $formattedPerson->lastName  = $this->lastName;
        $formattedPerson->birthDate = $this->birthDate;
        $formattedPerson->gender    = $this->gender;
        $formattedPerson->city      = $this->city;

        if (isset($args[1])) {
            $formattedPerson->birthDate = $args[0];
            $formattedPerson->gender = $args[1];
        } else {
            if (is_string($args[0])) {
                $formattedPerson->birthDate = $args[0];
            }
    
            if (is_int($args[0])) {
                $formattedPerson->gender = $args[0];
            }
        }
        
        return $formattedPerson;
    }

    private static function showErrorMsg(Exception $e) {
        echo $e->getMessage() . "<br><br>";
    }

    public function __toString() {
        if ($this->isExist()) {
            return "".
            "Id: {$this->id}<br>" .
            "First Name: {$this->firstName}<br>" .
            "Last Name: {$this->lastName}<br>" .
            "Birth Date: {$this->birthDate}<br>" .
            "Gender: " . Gender::genderByNum($this->gender) . "<br>".
            "City: {$this->city}<br><br>";
        }
        
        return "";
    }
}
