<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: reset_password.php");
    exit();
}
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="src/output.css" rel="stylesheet">
    <title>Password Reset</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="flex justify-center h-screen items-center" style="background-image: url(img/Backgroundblur.png);">
        <div class="bg-slate-200 w-1/4 p-5 rounded-md">
            <h1 class="text-center text-black text-xl font-semibold mb-5">Reset Password</h1>

            <form id="resetPasswordForm" class="flex flex-col gap-4 px-2">
                <label class="input input-bordered input-accent text-black bg-white flex items-center gap-2">
                    <input type="text" class="grow" placeholder="Verification Code" name="verification_code" required />
                </label>
                <label class="input input-bordered input-accent text-black bg-white flex items-center gap-2">
                    <input type="password" class="grow" placeholder="New Password" name="new_password" id="newPassword" required />
                </label>

                <div class="form-control">
                    <label class="cursor-pointer label">
                        <input type="checkbox" id="showPassword" class="checkbox checkbox-success" />
                        <span class="label-text text-black">Show Password</span>
                    </label>
                </div>

                <!-- Password rules -->
                <div class="mt-2 text-sm password-rules">
                    <p id="rule1" class="text-black">At least 8 characters long</p>
                    <p id="rule2" class="text-black">Include at least one uppercase letter (A-Z)</p>
                    <p id="rule3" class="text-black">Include at least one lowercase letter (a-z)</p>
                    <p id="rule4" class="text-black">Include at least one numeric digit (0-9)</p>
                    <p id="rule5" class="text-black">Include at least one special character (e.g., @, #, $, %)</p>
                </div>

                <div class="flex justify-center mt-5">
                    <button id="resetButton" class="btn btn-success w-2/4 text-white" type="submit" data-loading-text="Processing...">Reset Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function validatePassword(password) {
            const rules = {
                rule1: password.length >= 8,
                rule2: /[A-Z]/.test(password),
                rule3: /[a-z]/.test(password),
                rule4: /[0-9]/.test(password),
                rule5: /[!@#$%^&*(),.?":{}|<>]/.test(password)
            };

            let allRulesMet = true;

            Object.keys(rules).forEach(rule => {
                const element = document.getElementById(rule);
                if (rules[rule]) {
                    element.classList.remove('text-red-500', 'text-black');
                    element.classList.add('text-green-500');
                } else {
                    element.classList.remove('text-green-500', 'text-black');
                    element.classList.add('text-red-500');
                    allRulesMet = false;
                }
            });

            return allRulesMet;
        }

        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('newPassword');
            passwordInput.type = this.checked ? 'text' : 'password';
        });

        document.getElementById('newPassword').addEventListener('input', function(e) {
            validatePassword(e.target.value);
        });

        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const resetButton = document.getElementById('resetButton');
            const newPassword = document.getElementById('newPassword').value;

            if (!validatePassword(newPassword)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Password',
                    text: 'Please ensure your password meets all requirements',
                    confirmButtonText: 'Try Again'
                });
                return;
            }

            resetButton.textContent = resetButton.getAttribute('data-loading-text');
            resetButton.disabled = true;
            
            fetch('controllers/resetHandler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.alert === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                } else {
                    Swal.fire({
                        icon: data.alert === 'warning' ? 'warning' : 'error',
                        title: 'Oops...',
                        text: data.message,
                        confirmButtonText: 'Try Again'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while resetting the password.',
                    confirmButtonText: 'Try Again'
                });
            })
            .finally(() => {
                resetButton.textContent = 'Reset Password';
                resetButton.disabled = false;
            });
        });
    </script>
</body>
</html> 