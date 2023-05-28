<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '../../vendor/autoload.php';
use Twilio\Rest\Client;

$account_sid = 'AC5481d2621c93d091dc4c5525f2dda7c9';
$auth_token = 'xxxxxxxxxxxxxxx';
$twilio_number = "+16629673932";

session_start();

$host = 'localhost';
$db   = 'yore';
$user = 'heidi';
$pass = 'Mermaid7!!';
$port = "3306";
$charset = 'utf8mb4';

$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
try {
    $pdo = new \PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

echo '<?xml version="1.0" encoding="UTF-8"?><Response>';

$Digits = false;

date_default_timezone_set('America/New_York');

file_put_contents('request.json', json_encode($_REQUEST, JSON_PRETTY_PRINT));

$QuestionMarks = '';
$InsertFields = '';
$InsertValues = [];

foreach ($_REQUEST as $fld => $val) {
    $QuestionMarks .= '?, ';
    $InsertFields .= "`$fld`,";
    $InsertValues[] = $val;
}

try {

    $sql = "INSERT INTO twilio_log ($InsertFields `status`) VALUES ($QuestionMarks '0')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($InsertValues);

} catch (\Exception $e) {
    exit("<Sms>" . $e->getMessage() .  "</Sms></Response>");
}

if (isset($_REQUEST['Body'])) {
    exit("<Sms>Yore The Best!</Sms></Response>");
}

echo "<Say>Hello and Goodbye!</Say>";

exit('</Response>');

