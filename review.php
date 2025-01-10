<?php
session_start();
require 'controllers/db_conn.php';

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT status FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($userStatus);
$stmt->fetch();
$stmt->close();

switch ($userStatus) {
    case 'Pending':
        header("Location: verify_account.php");
        exit();
    case 'Verified':
        header("Location: home.php");
        exit();
    case 'Reviewing':
        break;
    default:
        header("Location: index.php");
        exit();
}

$review_message = 'Your account is currently under review. Please check back later.';
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="src/output.css" rel="stylesheet">
  <title>Account Review</title>
</head>
<body>

  <div class="flex justify-center h-screen items-center" style="background-image: url(img/Backgroundblur.png);">
    <div class="bg-neutral-300 w-1/4 p-5 rounded-md border-2 border-neutral-200 border-solid">
      <h1 class="text-center text-black text-xl font-semibold mb-5">Account Review</h1>

      <img src="svgs/review.svg" alt="review" class="w-1/2 h-1/2 mx-auto mb-5">

      <div class="flex flex-col gap-4 px-2 text-center">
        <p class="text-black"><?php echo $review_message; ?></p>
      </div>

      <div class="flex justify-center my-5">
        <button onclick="window.location.href='controllers/logout.php'" class="btn btn-success w-1/4 text-white">Logout</button>
      </div>
    </div>    
  </div>

</body>
</html>