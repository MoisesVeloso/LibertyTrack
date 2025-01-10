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
        break;
    case 'Verified':
        header("Location: home.php");
        exit();
    case 'Reviewing':
        header("Location: review.php");
        exit();
    default:
        header("Location: index.php");
        exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="src/output.css" rel="stylesheet">
  <title>Account Verification</title>
</head>
<body>

  <div class="flex justify-center h-screen items-center" style="background-image: url(img/Backgroundblur.png);">
    <div class="bg-slate-200 w-1/4 p-5 rounded-md">
      <h1 class="text-center text-black text-xl font-semibold mb-5">Account Verification</h1>
      <p class="text-center text-black mb-3">Welcome, <span class="font-bold uppercase"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!</p>

      <form class="flex flex-col gap-4 px-2" action="controllers/verification_code.php" method="post">
        <div class="text-center text-black mb-3">
          <p>Please enter the 6-digit verification code sent to your email</p>
        </div>

        <div class="flex justify-center gap-2 w-full">
          <input type="text" id="code1" oninput="handleInput(event, 1)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
          <input type="text" id="code2" oninput="handleInput(event, 2)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
          <input type="text" id="code3" oninput="handleInput(event, 3)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
          <input type="text" id="code4" oninput="handleInput(event, 4)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
          <input type="text" id="code5" oninput="handleInput(event, 5)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
          <input type="text" id="code6" oninput="handleInput(event, 6)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
        </div>

        <div class="flex justify-center mt-5">
          <button class="btn btn-success w-2/4 text-white">Verify Account</button>
        </div>
      </form>

      <div class="flex justify-center my-5 flex-col text-center">
        <p class="text-black">Didn't receive the code?</p>
        <a href="#" id="resendCodeButton" onclick="resendVerificationCode()" class="text-blue-500">Resend Code</a>
        <p id="timer" class="text-black"></p>
      </div>
    </div>    
  </div>

  <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    const RESEND_COOLDOWN = 60; 

    const inputs = document.querySelectorAll('input[name="code[]"]');
    
    inputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1);
            
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace') {
                if (this.value === '') {
                    if (index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                        e.preventDefault();
                    }
                } else {
                    this.value = '';
                }
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = e.clipboardData.getData('text');
            const numbers = pastedText.replace(/[^0-9]/g, '');
            
            for (let i = 0; i < numbers.length && i + index < inputs.length; i++) {
                inputs[i + index].value = numbers[i];
                if (i + index < inputs.length - 1) {
                    inputs[i + index + 1].focus();
                }
            }
        });
    });

    window.addEventListener('load', function() {
        inputs[0].focus();
        const resendTimestamp = localStorage.getItem('resendTimestamp');
        if (resendTimestamp) {
            const currentTime = Math.floor(Date.now() / 1000);
            const elapsed = currentTime - resendTimestamp;
            if (elapsed < RESEND_COOLDOWN) {
                document.getElementById('resendCodeButton').style.pointerEvents = 'none';
                document.getElementById('resendCodeButton').style.color = 'gray';
                startTimer(RESEND_COOLDOWN - elapsed, document.querySelector('#timer'));
            }
        }
    });

    function startTimer(duration, display) {
        let timer = duration, minutes, seconds;
        const interval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            seconds = seconds < 10 ? "0" + seconds : seconds;

            display.textContent = "Please wait " + minutes + ":" + seconds + " before resending.";

            if (--timer < 0) {
                clearInterval(interval);
                display.textContent = "";
                document.getElementById('resendCodeButton').style.pointerEvents = 'auto';
                document.getElementById('resendCodeButton').style.color = 'blue';
                localStorage.removeItem('resendTimestamp');
            }
        }, 1000);
    }

    function resendVerificationCode() {
        document.getElementById('resendCodeButton').style.pointerEvents = 'none';
        document.getElementById('resendCodeButton').style.color = 'gray';

        $.ajax({
            url: 'controllers/resend_code.php',
            type: 'POST',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Verification Code Sent',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                    const timestamp = Math.floor(Date.now() / 1000);
                    localStorage.setItem('resendTimestamp', timestamp);
                    startTimer(RESEND_COOLDOWN, document.querySelector('#timer'));
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonText: 'OK'
                    });
                    document.getElementById('resendCodeButton').style.pointerEvents = 'auto';
                    document.getElementById('resendCodeButton').style.color = 'blue';
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while sending the verification code. Please try again later.',
                    confirmButtonText: 'OK'
                });
                document.getElementById('resendCodeButton').style.pointerEvents = 'auto';
                document.getElementById('resendCodeButton').style.color = 'blue';
            }
        });
    }

    $('form').on('submit', function(e) {
        e.preventDefault(); 

        $.ajax({
            url: 'controllers/verification_code.php',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                const res = JSON.parse(response);
                if (res.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = 'review.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message,
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred. Please try again later.',
                });
            }
        });
    });
  </script>

</body>
</html>