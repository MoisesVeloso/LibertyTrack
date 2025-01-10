<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="src/output.css" rel="stylesheet">
  <title>Reset Password</title>
</head>
<body>

  <div class="flex justify-center h-screen items-center" style="background-image: url(img/Backgroundblur.png);">
    <div class="bg-slate-200 w-1/4 p-5 rounded-md">
      <h1 class="text-center text-black text-xl font-semibold mb-5">Reset Password</h1>

      <form id="resetPasswordForm" class="flex flex-col gap-4 px-2" action="controllers/sendVerificationCode.php" method="post">


        <label class="input input-bordered input-accent text-black bg-white flex items-center gap-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 16 16"
            fill="currentColor"
            class="h-4 w-4 opacity-70">
            <path
              d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" />
          </svg>
          <input type="email" class="grow" placeholder="Email" name="email" required />
        </label>

        <div class="flex justify-center mt-5">
          <button id="resetButton" class="btn btn-success w-2/4 text-white" type="submit" data-loading-text="Sending...">Send Verification Code</button>
        </div>

      </form>

      <div class="flex justify-center my-5 flex-col text-center">
        <p class="text-black">Remembered your password?</p>
        <a href="login.php" class="text-blue-500">Login</a>
      </div>

    </div>    
  </div>

  <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const resetButton = document.getElementById('resetButton');
        
        resetButton.textContent = resetButton.getAttribute('data-loading-text');
        resetButton.disabled = true;
        
        fetch('controllers/sendVerificationCode.php', {
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
                    window.location.href = 'password_reset.php';
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
                text: 'An error occurred while sending the verification code.',
                confirmButtonText: 'Try Again'
            });
        })
        .finally(() => {
            resetButton.textContent = 'Send Verification Code';
            resetButton.disabled = false;
        });
    });
  </script>

</body>
</html>