<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css">
    <title>Registration</title>
</head>
<body class="bg-cover bg-center h-screen" style="background-image: url(img/Backgroundblur.png);">

  <div class="flex justify-center pt-10">
    <div class="bg-white w-2/5 p-5 rounded-xl h-full">
      <form id="registerForm" method="post">

        <h3 class="text-lg font-bold text-center text-black mb-5">Register</h3>

        <div class="flex gap-2">

          <label class="form-control w-full">
            <div class="label">
                <span class="label-text text-black font-semibold py-2 after:content-['*'] after:text-red-500">First Name</span>
            </div>
            <input type="text" name="firstname" id="firstname" class="input input-bordered input-info w-full bg-neutral-200 text-black" required />
            </label>  
        
            <label class="form-control w-full">
            <div class="label">
                <span class="label-text text-black font-semibold py-2 after:content-['*'] after:text-red-500">Last Name</span>
            </div>
            <input type="text" name="lastname" id="lastname" class="input input-bordered input-info w-full bg-neutral-200 text-black" required />
            </label>  

        </div>
    
        <label class="form-control w-full">
        <div class="label">
            <span class="label-text text-black font-semibold py-2">Middle Name</span>
        </div>
        <input type="text" name="middlename" id="middlename" class="input input-bordered input-info w-full bg-neutral-200 text-black" />
        </label>  

        <label class="form-control w-full">
          <div class="label">
              <span class="label-text text-black font-semibold py-2 after:content-['*'] after:text-red-500">Role</span>
          </div>
            <select class="w-full bg-neutral-200 font-semibold input input-bordered input-info text-black" name="role" required>
              <option disabled selected></option>
              <option value="Admin">Admin</option>
              <option value="User">User</option>
          </select>
          </label>  
        
        <label class="form-control w-full">
          <div class="label">
              <span class="label-text text-black font-semibold py-2 after:content-['*'] after:text-red-500">Email</span>
          </div>
          <input type="email" name="email" id="email" class="input input-bordered input-info w-full bg-neutral-200 text-black" required />
          </label>  
    
        <label class="form-control w-full">
        <div class="label">
            <span class="label-text text-black font-semibold py-2 after:content-['*'] after:text-red-500">Username</span>
        </div>
        <input type="text" name="username" id="username" class="input input-bordered input-info w-full bg-neutral-200 text-black" required />
        </label>  
        
        <label class="form-control w-full">
          <div class="label">
              <span class="label-text text-black font-semibold py-2 after:content-['*'] after:text-red-500">Password</span>
          </div>
          <input type="password" name="password" id="password" class="input input-bordered input-info w-full bg-neutral-200 text-black" required />
          </label> 
          
        <div class="flex justify-center mt-5">
            <button id="registerButton" class="btn btn-success" type="submit" data-loading-text="Registering...">Register</button>
        </div>

        <div class="flex justify-center my-5 flex-col text-center">
          <p class="text-black">Already have an account?</p>
          <a href="index.php" class="text-blue-500">Login</a>
        </div>
    
      </form>
    </div>
  </div>

  <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const registerButton = document.getElementById('registerButton');
        
        registerButton.textContent = registerButton.getAttribute('data-loading-text');
        registerButton.disabled = true;
        
        fetch('controllers/addUser.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: data.alert,
                title: data.alert.charAt(0).toUpperCase() + data.alert.slice(1),
                text: data.message,
                showConfirmButton: true,
            }).then((result) => {
                if (result.isConfirmed && data.alert === 'success') {
                    localStorage.setItem('userEmail', formData.get('email'));
                    window.location.href = 'index.php';
                }
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Something went wrong!',
                showConfirmButton: false,
                timer: 1500
            });
        })
        .finally(() => {
            registerButton.textContent = 'Register';
            registerButton.disabled = false;
        });
    });
  </script>

</body>
</html>