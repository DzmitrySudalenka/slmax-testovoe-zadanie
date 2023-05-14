<?php

use App\Models\Person;
use App\Models\PersonsList;
use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

$person_1 = new Person('Иван', 'Иванов', '2000-01-01', 1, 'Минск');
$person_2 = new Person('Анна', 'Петрова', '2001-02-02', 0, 'Минск');

echo $person_1;
echo $person_2;

$person_1->delete();

$person_1 = new Person($person_1->getId());
$person_2 = new Person($person_2->getId());

echo $person_1;
echo $person_2;

echo Person::getAge($person_2->getBirthDate()) . '<br><br>';
echo Person::getGenderStr($person_2->getGender()) . '<br><br>';

$person_3 = $person_2->format('2000-01-03');
echo '<pre>';print_r($person_3);echo '</pre>';
$person_4 = $person_2->format(1);
echo '<pre>';print_r($person_4);echo '</pre>';
$person_5 = $person_2->format('2000-01-03', 0);
echo '<pre>';print_r($person_5);echo '</pre>';

$personsList = new PersonsList(['field' => 'id', 'sign' => '>', 'value' => 1]);

echo '<pre>';print_r($personsList->getPersonsArr());echo '</pre>';

$personsList->deletePersons();

$personsList_2 = new PersonsList(['field' => 'id', 'sign' => '>', 'value' => 1]);

echo '<pre>';print_r($personsList_2->getPersonsArr());echo '</pre>';
