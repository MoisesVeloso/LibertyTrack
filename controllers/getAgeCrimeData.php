<?php
require 'db_conn.php';

$month = isset($_GET['month']) ? $_GET['month'] : 'all';
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

function calculateAge($birthday) {
    if (!$birthday || $birthday == '0000-00-00') return null;
    $birth = new DateTime($birthday);
    $today = new DateTime();
    $age = $birth->diff($today)->y;
    return $age;
}

function getAgeGroup($age) {
    if ($age < 18) return "Under 18 years old";
    if ($age <= 24) return "18-24 years old";
    if ($age <= 34) return "25-34 years old";
    if ($age <= 54) return "35-54 years old";
    return "55+ years old";
}

$dateCondition = "WHERE status = 'detained'";
$params = [];
$types = "";

if ($year !== 'all') {
    $dateCondition .= " AND YEAR(date_admitted) = ?";
    $params[] = $year;
    $types .= "i";
}

if ($month !== 'all') {
    $dateCondition .= " AND MONTH(date_admitted) = ?";
    $params[] = $month;
    $types .= "i";
}

$query = "SELECT case_detail, COUNT(*) as count 
          FROM inmates 
          $dateCondition
          GROUP BY case_detail 
          ORDER BY count DESC 
          LIMIT 3";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$topCrimes = [];
while ($row = $result->fetch_assoc()) {
    $topCrimes[] = $row['case_detail'];
}

$ageGroups = [
    'Under 18 years old' => 0,
    '18-24 years old' => 0,
    '25-34 years old' => 0,
    '35-54 years old' => 0,
    '55+ years old' => 0
];

$crimeAgeData = [];

foreach ($topCrimes as $crime) {
    $query = "SELECT birthday FROM inmates 
              WHERE case_detail = ? AND status = 'detained'";
    $params = [$crime];
    $types = "s";

    if ($year !== 'all') {
        $query .= " AND YEAR(date_admitted) = ?";
        $params[] = $year;
        $types .= "i";
    }

    if ($month !== 'all') {
        $query .= " AND MONTH(date_admitted) = ?";
        $params[] = $month;
        $types .= "i";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $ageGroups = array_fill_keys(array_keys($ageGroups), 0);

    while ($row = $result->fetch_assoc()) {
        $age = calculateAge($row['birthday']);
        if ($age !== null) {
            $ageGroup = getAgeGroup($age);
            $ageGroups[$ageGroup]++;
        }
    }

    $crimeAgeData[$crime] = $ageGroups;
}

$response = [
    'crimes' => $topCrimes,
    'labels' => array_keys($ageGroups),
    'data' => $crimeAgeData
];

header('Content-Type: application/json');
echo json_encode($response);
?>