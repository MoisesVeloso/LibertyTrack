<?php
header('Content-Type: application/json');
require 'db_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $suffix = $_POST['suffix'] ?? '';
    $birthday = $_POST['birthday'] ?? '';
    $arresting_officers = $_POST['arresting_officers'] ?? '';
    $ioc = $_POST['ioc'] ?? '';
    $address = $_POST['address'] ?? '';
    $date_time_arrested = $_POST['date_time_arrested'] ?? '';
    $case_number = $_POST['case_number'] ?? '';
    $case_detail = $_POST['case_detail'] ?? '';
    $date_admitted = $_POST['date_admitted'] ?? '';
    $date_release = $_POST['date_release'] ?? '';

    if (empty($id)) {
        echo json_encode([
            'success' => false,
            'message' => 'ID is required.'
        ]);
        exit();
    }

    $checkStmt = $conn->prepare("SELECT * FROM inmates WHERE id = ?");
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $currentData = $result->fetch_assoc();
    $checkStmt->close();

    if ($currentData['firstname'] === $firstname &&
        $currentData['lastname'] === $lastname &&
        $currentData['middlename'] === $middlename &&
        $currentData['suffix'] === $suffix &&
        $currentData['birthday'] === $birthday &&
        $currentData['arresting_officers'] === $arresting_officers &&
        $currentData['ioc'] === $ioc &&
        $currentData['address'] === $address &&
        $currentData['date_time_arrested'] === $date_time_arrested &&
        $currentData['case_number'] === $case_number &&
        $currentData['case_detail'] === $case_detail &&
        $currentData['date_admitted'] === $date_admitted &&
        $currentData['date_release'] === $date_release) {
        
        echo json_encode([
            'success' => true,
            'noChanges' => true,
            'message' => 'No changes were made to the inmate details.'
        ]);
        exit();
    }

    $stmt = $conn->prepare("UPDATE inmates SET 
        firstname=?, 
        lastname=?, 
        middlename=?, 
        suffix=?, 
        birthday=?, 
        arresting_officers=?, 
        ioc=?, 
        address=?, 
        date_time_arrested=?, 
        case_number=?, 
        case_detail=?, 
        date_admitted=?, 
        date_release=? 
        WHERE id=?");

    if (!$stmt) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to prepare statement: ' . $conn->error
        ]);
        exit();
    }

    $stmt->bind_param("sssssssssssssi", 
        $firstname, 
        $lastname, 
        $middlename, 
        $suffix, 
        $birthday, 
        $arresting_officers, 
        $ioc, 
        $address, 
        $date_time_arrested, 
        $case_number, 
        $case_detail, 
        $date_admitted, 
        $date_release, 
        $id
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'noChanges' => false,
            'message' => 'Inmate details updated successfully.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update inmate details: ' . $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>
