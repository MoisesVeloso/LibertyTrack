<?php
session_start(); 

if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] === 'Admin') {
        header("Location: home.php"); 
        exit();
    } elseif ($_SESSION['role'] === 'User') {
        header("Location: home.php"); 
        exit();
    }
}

$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : '';
if ($error_message) {
    unset($_SESSION['error']);
}
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="src/output.css" rel="stylesheet">
  <title>Login</title>
</head>
<body>

  <div class="flex justify-center h-screen items-center" style="background-image: url(img/Backgroundblur.png);">
    <div class="bg-slate-200 w-1/4 px-5 py-10 rounded-md">
      <h1 class="text-center text-black text-xl font-semibold mb-5">Login</h1>

      <form class="flex flex-col gap-4 px-2" action="controllers/login.php" method="post">
        <label class="input input-bordered input-accent text-black bg-white flex items-center gap-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 16 16"
            fill="currentColor"
            class="h-4 w-4 opacity-70">
            <path
              d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" />
          </svg>
          <input type="text" class="grow" placeholder="Username" name="username" required />
        </label>

        <label class="input input-bordered input-accent text-black bg-white flex items-center gap-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 16 16"
            fill="currentColor"
            class="h-4 w-4 opacity-70">
            <path
              fill-rule="evenodd"
              d="M14 6a4 4 0 0 1-4.899 3.899l-1.955 1.955a.5.5 0 0 1-.353.146H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2.293a.5.5 0 0 1 .146-.353l3.955-3.955A4 4 0 1 1 14 6Zm-4-2a.75.75 0 0 0 0 1.5.5.5 0 0 1 .5.5.75.75 0 0 0 1.5 0 2 2 0 0 0-2-2Z"
              clip-rule="evenodd" />
          </svg>
          <input type="password" class="grow bg-white" placeholder="password" name="password" required />
        </label>

        <div class="flex justify-center mt-5">
          <button class="btn btn-success w-2/4 text-white">Login</button>
        </div>

      </form>

      <div class="flex justify-center my-5 flex-col text-center">
        <p class="text-black">Forgot your Password?</p>
        <a href="reset_password.php" class="text-blue-500">Reset Password</a>
      </div>

      <div class="divider text-black">OR</div>

      <div class="flex justify-center my-5 flex-col text-center">
        <p class="text-black">Don't have an account?</p>
        <a href="register.php" class="text-blue-500">Register</a>
      </div>

    </div>    
  </div>

  <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script>
    const errorMessage = "<?php echo addslashes($error_message); ?>";
    if (errorMessage) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: errorMessage,
        });
    }
  </script>

</body>
</html>