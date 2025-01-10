<?php
session_start();
require 'db_conn.php'; 

function isCaseNumberExists($conn, $case_number) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM inmates WHERE case_number = ?");
    $stmt->bind_param("s", $case_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['count'] > 0;
}

function createDirectoryAndSaveImage($lastname, $firstname, $middlename, $suffix, $imageData) {
    $folderName = $lastname . '_' . $firstname;
    if (!empty($middlename)) {
        $folderName .= '_' . $middlename;
    }
    if (!empty($suffix)) {
        $folderName .= '_' . $suffix;
    }
    $targetDir = "../image_inmates/" . $folderName;
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    $fileCount = 0;
    $files = glob($targetDir . "/*"); 
    if ($files) {
        $fileCount = count($files); 
    }
    $imageName = $folderName . "_" . ($fileCount + 1) . ".png";
    $imagePath = $targetDir . "/" . $imageName;

    $imageData = str_replace('data:image/png;base64,', '', $imageData);
    $imageData = str_replace(' ', '+', $imageData);
    $imageData = base64_decode($imageData);
    file_put_contents($imagePath, $imageData);
    
    return [$imagePath, $targetDir]; 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING);
    $lastname = filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING);
    $middlename = filter_input(INPUT_POST, 'middlename', FILTER_SANITIZE_STRING);
    $suffix = filter_input(INPUT_POST, 'suffix', FILTER_SANITIZE_STRING);
    $case_number = filter_input(INPUT_POST, 'case_number', FILTER_SANITIZE_STRING); 
    $case_detail = filter_input(INPUT_POST, 'case_detail', FILTER_SANITIZE_STRING);
    $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
    $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING);
    $date_admitted = filter_input(INPUT_POST, 'date_admitted', FILTER_SANITIZE_STRING);
    $date_release = filter_input(INPUT_POST, 'date_release', FILTER_SANITIZE_STRING);
    $imageData = filter_input(INPUT_POST, 'image_path', FILTER_SANITIZE_STRING);
    $emergency_contact = filter_input(INPUT_POST, 'emergency_contact', FILTER_SANITIZE_STRING);
    $arresting_officers = filter_input(INPUT_POST, 'arresting_officers', FILTER_SANITIZE_STRING);
    $ioc = filter_input(INPUT_POST, 'ioc', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $date_time_arrested = filter_input(INPUT_POST, 'date_time_arrested', FILTER_SANITIZE_STRING);
    error_log("Received image data: " . var_export($imageData, true));

    if (!$firstname || !$lastname || !$case_number || !$case_detail || !$gender || !$birthday || !$date_admitted || !$date_release || !$imageData || !$arresting_officers || !$ioc || !$address || !$date_time_arrested) {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Invalid input data!';
        header("Location: ../inmate_admin.php");
        exit();
    }

    if (isCaseNumberExists($conn, $case_number)) {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Case Number already exists!';
        header("Location: ../inmate_admin.php");
        exit();
    }

    $admittedDate = DateTime::createFromFormat('Y-m-d', $date_admitted);
    $releaseDate = DateTime::createFromFormat('Y-m-d', $date_release);

    if ($admittedDate === false || $releaseDate === false || $admittedDate >= $releaseDate) {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Date of release must be after date admitted!';
        header("Location: ../inmate_admin.php");
        exit();
    }

    $case_detail = filter_input(INPUT_POST, 'case_detail', FILTER_SANITIZE_STRING);
    if (empty($case_detail)) {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Please select at least one case!';
        header("Location: ../inmate_admin.php");
        exit();
    }
    
    $cases = array_filter(explode('; ', $case_detail));
    if (empty($cases)) {
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Invalid case selection!';
        header("Location: ../inmate_admin.php");
        exit();
    }
    
    $case_detail = implode('; ', $cases);

    list($imagePath, $imageDataPath) = createDirectoryAndSaveImage($lastname, $firstname, $middlename, $suffix, $imageData);

    $stmt = $conn->prepare("INSERT INTO inmates (
        firstname, lastname, middlename, suffix, 
        case_number, case_detail, gender, birthday, 
        date_admitted, date_release, image_path, image_data_path, 
        emergency_contact, arresting_officers, ioc, address, 
        date_time_arrested
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error);
        $_SESSION['alert_type'] = 'error';
        $_SESSION['alert_message'] = 'Database error occurred!';
        header("Location: ../inmate_admin.php");
        exit();
    }

    $stmt->bind_param("sssssssssssssssss", 
        $firstname, $lastname, $middlename, $suffix,
        $case_number, $case_detail, $gender, $birthday,
        $date_admitted, $date_release, $imagePath, $imageDataPath,
        $emergency_contact, $arresting_officers, $ioc, $address,
        $date_time_arrested
    );

    $stmt->execute();
    $stmt->close();
    $conn->close(); 

    $_SESSION['alert_type'] = 'success';
    $_SESSION['alert_message'] = 'Inmate added successfully!';
    header("Location: ../inmate_admin.php");
    exit();
} else {
    $_SESSION['alert_type'] = 'error';
    $_SESSION['alert_message'] = 'Invalid request!';
        header("Location: ../inmate_admin.php");
    exit();
}
?>
