<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require 'controllers/db_conn.php';
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $userStatus = $user['status'];
    $userRole = $user['role'];
} else {
    echo "No user found.";
    exit;
}
$stmt->close();

if ($userRole !== 'Admin') {
    header("Location: home.php");
    exit();
}

switch ($userStatus) {
    case 'Pending':
        header("Location: verify_account.php");
        exit();
    case 'Reviewing':
        header("Location: review.php");
        exit();
    case 'Verified':
        break; 
    default:
        header("Location: index.php");
        exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="src/output.css">
    <title>Inmates</title>
</head>
<body class="bg-neutral-200">

<div class="flex flex-row">
    <ul class="flex flex-col w-1/4 bg-neutral-700 text-white h-screen sticky top-0">
        <img src="img/police-logo.png" alt="police-logo.png" class="my-10 self-center"  style="width: 150px; height: 150px;"/>

        <li class="hover:bg-neutral-500 ">
          <a href="dashboard.php" class="flex items-center pl-5 py-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
              <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
              <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
            </svg>
            <h1 class="pl-5 text-lg">Dashboard</h1>
          </a>
        </li>

            <li class="hover:bg-neutral-500 bg-neutral-500">
                <a href="inmate_admin.php" class="flex items-center pl-5 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                        <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                        <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                    </svg>
                    <h1 class="pl-5 text-lg">Inmates List</h1>
                </a>
            </li>

        <li class="hover:bg-neutral-500">
          <a href="review_accounts.php" class="flex items-center pl-5 py-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
            <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
            </svg>
            <h1 class="pl-5 text-lg">Review Accounts</h1>
          </a>
        </li>

        <li class="hover:bg-neutral-500">
            <a href="transfered_admin.php" class="flex items-center pl-5 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                    <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                </svg>
                <h1 class="pl-5 text-lg">Commit to Other Facility</h1>
           </a>
        </li>

        <li class="hover:bg-neutral-500">
          <a href="pendingRelease.php" class="flex items-center pl-5 py-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
            <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
            <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
          </svg>
            <h1 class="pl-5 text-lg">Review Release Inmates</h1>
          </a>
        </li>

        <li class="hover:bg-neutral-500">
            <a href="release_admin.php" class="flex items-center pl-5 py-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
            </svg>
            <h1 class="pl-5 text-lg">Release List</h1>
            </a>
        </li>

      </ul>
      
        
        <dialog id="profile" class="modal text-black">
        <div class="modal-box w-2/6 max-w-5xl h-auto bg-neutral-200">
            <h3 class="text-lg font-bold">Profile</h3>
    
            <div class="overflow-y-auto max-h-full p-5">
                <div class="form-control w-full">
                    <div class="label">
                        <span class="label-text text-black font-semibold">First Name</span>
                    </div>
                    <input type="text" value="<?php echo htmlspecialchars($user['first_name']); ?>" class="input input-bordered bg-neutral-300 w-full" readonly id="firstName">
                </div>
        
                <div class="form-control w-full">
                    <div class="label">
                        <span class="label-text text-black font-semibold">Last Name</span>
                    </div>
                    <input type="text" value="<?php echo htmlspecialchars($user['last_name']); ?>" class="input input-bordered bg-neutral-300 w-full" readonly id="lastName">
                </div>
        
                <div class="form-control w-full">
                    <div class="label">
                        <span class="label-text text-black font-semibold">Middle Name</span>
                    </div>
                    <input type="text" value="<?php echo htmlspecialchars($user['middle_name']); ?>" class="input input-bordered bg-neutral-300 w-full" readonly id="middleName">
                </div>
        
                <div class="form-control w-full">
                    <div class="label">
                        <span class="label-text text-black font-semibold">Email</span>
                    </div>
                    <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="input input-bordered bg-neutral-300 w-full" readonly id="email">
                </div>
        
                <div class="form-control w-full">
                    <div class="label">
                        <span class="label-text text-black font-semibold">Username</span>
                    </div>
                    <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" class="input input-bordered bg-neutral-300 w-full" readonly id="username">
                </div>
        
                <button class="btn btn-accent mt-4" id="editButton">Edit</button>
                <button class="btn btn-info mt-4" id="saveChanges" style="display: none;">Save Changes</button>
                
                <div class="mt-5">
                    <p class="text-lg font-semibold">Change Password</p>

                    <div class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-black font-semibold">Current Password</span>
                        </div>
                        <input type="password" 
                            id="currentPassword" 
                            placeholder="Enter your current password" 
                            class="input input-bordered bg-neutral-300 w-full" 
                            required>
                    </div>

                    <div class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-black font-semibold">New Password</span>
                        </div>
                        <input type="password" 
                            id="newPassword" 
                            placeholder="Enter your new password" 
                            class="input input-bordered bg-neutral-300 w-full" 
                            required>
                        <div class="form-control">
                            <label class="cursor-pointer label">
                                <input type="checkbox" id="showPassword" class="checkbox checkbox-success" />
                                <span class="label-text text-black">Show Password</span>
                            </label>
                        </div>
                        <div class="mt-2 text-sm password-rules">
                            <p id="rule1" class="text-black">At least 8 characters long</p>
                            <p id="rule2" class="text-black">Include at least one uppercase letter (A-Z)</p>
                            <p id="rule3" class="text-black">Include at least one lowercase letter (a-z)</p>
                            <p id="rule4" class="text-black">Include at least one numeric digit (0-9)</p>
                            <p id="rule5" class="text-black">Include at least one special character (e.g., @, #, $, %)</p>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success mt-4" id="changePasswordBtn">Change Password</button>
                </div>

                <button class="btn btn-error mt-4" id="logoutButton">Logout</button>

                <div class="modal-action">
                    <button class="btn btn-info" onclick="closeProfileDialog()">Close</button>
                </div>

                <script>
                function closeProfileDialog() {
                    document.getElementById('profile').close();
                }
                </script>
            </div>
        </div>

        <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script>
        document.getElementById('editButton').addEventListener('click', function() {
            const profileInputs = [
                'firstName', 'lastName', 'middleName', 'email', 'username'
            ];
            
            profileInputs.forEach(function(id) {
                document.getElementById(id).removeAttribute('readonly');
            });
            
            document.getElementById('saveChanges').style.display = 'inline-block';
            this.style.display = 'none';
        });

        document.getElementById('saveChanges').addEventListener('click', function() {
            const userData = {
                firstName: document.getElementById('firstName').value,
                lastName: document.getElementById('lastName').value,
                middleName: document.getElementById('middleName').value,
                email: document.getElementById('email').value,
                username: document.getElementById('username').value
            };

            const profileDialog = document.getElementById('profile');
            profileDialog.close();

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save the changes?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('controllers/updateProfile.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(userData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire('Success!', 'Profile updated successfully', 'success').then(() => {
                                const profileInputs = [
                                    'firstName', 'lastName', 'middleName', 'email', 'username'
                                ];
                                
                                profileInputs.forEach(id => {
                                    document.getElementById(id).setAttribute('readonly', true);
                                });
                                
                                document.getElementById('saveChanges').style.display = 'none';
                                document.getElementById('editButton').style.display = 'inline-block';
                                profileDialog.showModal(); 
                            });
                        } else {
                            Swal.fire('Error!', data.message, 'error').then(() => {
                                profileDialog.showModal(); 
                            });
                        }
                    });
                } else {
                    profileDialog.showModal();
                }
            });
        });

        document.getElementById('logoutButton').addEventListener('click', function() {
            const profileDialog = document.getElementById('profile');
            profileDialog.close();

            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'controllers/logout.php';
                } else {
                    profileDialog.showModal();
                }
            });

            document.querySelector('.swal2-container').classList.add('z-50');
        });

        const newPasswordInput = document.getElementById('newPassword');
        const changePasswordBtn = document.getElementById('changePasswordBtn');

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

        newPasswordInput.addEventListener('input', function(e) {
            validatePassword(e.target.value);
        });

        document.getElementById('changePasswordBtn').addEventListener('click', function() {
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const profileDialog = document.getElementById('profile');

            if (!currentPassword || !newPassword) {
                profileDialog.close(); 
                Swal.fire('Error!', 'Both current and new passwords are required', 'error').then(() => {
                    profileDialog.showModal(); 
                });
                return;
            }

            if (!validatePassword(newPassword)) {
                profileDialog.close(); 
                Swal.fire('Error!', 'Password does not meet all requirements', 'error').then(() => {
                    profileDialog.showModal(); 
                });
                return;
            }

            profileDialog.close();
            fetch('controllers/changePassword.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    currentPassword: currentPassword,
                    newPassword: newPassword
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Success!', 'Password changed successfully', 'success').then(() => {
                        document.getElementById('currentPassword').value = '';
                        document.getElementById('newPassword').value = '';
                        document.querySelectorAll('.password-rules p').forEach(rule => {
                            rule.classList.remove('text-green-500', 'text-red-500');
                            rule.classList.add('text-black');
                        });
                        profileDialog.showModal(); 
                    });
                } else {
                    Swal.fire('Error!', data.message, 'error').then(() => {
                        profileDialog.showModal(); 
                    });
                }
            })
            .catch(error => {
                Swal.fire('Error!', 'An error occurred while changing the password', 'error').then(() => {
                    profileDialog.showModal(); 
                });
            });
        });

        document.getElementById('showPassword').addEventListener('change', function() {
            const passwordInput = document.getElementById('newPassword');
            passwordInput.type = this.checked ? 'text' : 'password';
        });
    </script>
    </dialog>
       
            <!---Main Content-->
        <div class="m-10 w-full"> 
            <div class="flex justify-between bg-neutral-700  p-5 rounded-xl mb-5">
                <div class="flex items-center gap-4 w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" class="size-8">
                    <path fill-rule="evenodd" d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" clip-rule="evenodd" />
                </svg>
                    <h2 class="text-xl font-semibold text-white">
                        <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?> 
                    </h2>
                </div>
                <div class="flex items-center gap-4">
                    <button class="btn btn-outline btn-neutral text-white" onclick="profile.showModal()">
                        View Profile
                    </button>
                </div>
            </div>  

            <div class="text-black">

                <div class="flex items-center justify-between">
                    <p class="font-semibold text-xl">Inmates</p>

                    <button class="btn btn-success" onclick="addInmate.showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M5.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM2.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 7.5a.75.75 0 0 0-1.5 0v2.25H15a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H21a.75.75 0 0 0 0-1.5h-2.25V7.5Z" />
                        </svg>
                        <h1 class="pl-5">Add Inmates</h1>
                    </button>
                    <dialog id="addInmate" class="modal text-black">
                        
                        <div class="rounded-xl w-2/6 bg-white p-10 flex flex-col justify-center max-h-[90vh]">
                            <h3 class="text-lg font-bold text-center pb-5">Add Inmate</h3>
    
                            <div class="overflow-y-auto max-h-full p-5">
                                <form action="controllers/addInmate_admin.php" method="post">
                                    <div class="flex flex-col gap-4 ">
    
                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                            First Name:
                                            <input type="text" class="grow text-blue-900" name="firstname" required>
                                        </label>
    
                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                            Last Name:
                                            <input type="text" class="grow text-blue-900" name="lastname" required>
                                        </label>
    
                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                            Middle Name:
                                            <input type="text" class="grow text-blue-900" name="middlename">
                                        </label>

                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                            Suffix:
                                            <select class="w-full bg-neutral-200 font-semibold text-blue-900" name="suffix">
                                                <option value=""  selected>None</option>
                                                <option value="Jr">Jr</option>
                                                <option value="Sr">Sr</option>
                                                <option value="II">II</option>
                                                <option value="III">III</option>
                                            </select>
                                        </label>
    
                                        <div class="relative">
                                        <button onclick="toggleDropdown()" class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200 w-full">
                                            Case: <span id="selectedCases" class="text-sm"></span>
                                        </button>
                                        
                                        <div id="caseSelect" class="absolute hidden w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-96 overflow-y-auto z-50">
                                            <div class="p-2">
                                                <strong class="block py-1">Crimes Against Fraud</strong>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Identity Theft"> Identity Theft</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Phishing and Online Scams"> Phishing and Online Scams</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Credit Card Fraud"> Credit Card Fraud</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Bank Fraud"> Bank Fraud</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Investment Scams"> Investment Scams</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Insurance Fraud"> Insurance Fraud</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Corruption and Bribery"> Corruption and Bribery</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Embezzlement"> Embezzlement</label>
                                            </div>
                                            
                                            <div class="p-2 border-t">
                                                <strong class="block py-1">Drug-Related Crimes</strong>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Drug Trafficking"> Drug Trafficking</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Drug Possession"> Drug Possession</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Drug Use"> Drug Use</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Manufacturing of Drugs"> Manufacturing of Drugs</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Drug Smuggling"> Drug Smuggling</label>
                                            </div>
                                            
                                            <div class="p-2 border-t">
                                                <strong class="block py-1">Crimes Against Property</strong>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Theft"> Theft</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Burglary"> Burglary (Akyat-Bahay)</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Robbery"> Robbery</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Car Theft"> Car Theft</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Vandalism"> Vandalism</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Arson"> Arson</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Extortion"> Extortion</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Shoplifting"> Shoplifting</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Trespassing"> Trespassing</label>
                                            </div>
                                            
                                            <div class="p-2 border-t">
                                                <strong class="block py-1">Crimes Against Persons</strong>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Murder"> Murder</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Homicide"> Homicide</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Assault and Battery"> Assault and Battery</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Rape"> Rape</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Sexual Harassment"> Sexual Harassment</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Domestic Abuse"> Domestic Abuse</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Child Abuse"> Child Abuse</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Kidnapping"> Kidnapping</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Stalking"> Stalking</label>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Human Trafficking"> Human Trafficking</label>
                                            </div>
                                            
                                            <div class="p-2 border-t">
                                                <strong class="block py-1">Other</strong>
                                                <label class="block px-2 py-1 hover:bg-gray-100"><input type="checkbox" name="case_select" value="Other"> Other</label>
                                            </div>
                                        </div>
                                    </div>

                                    <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200" id="otherCaseContainer" style="display: none;">
                                        Specify Case:
                                        <input type="text" class="grow text-blue-900 bg-neutral-200" name="case_detail" id="otherCaseInput">
                                    </label>
    
                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                            Case Number:
                                            <input type="text" class="grow text-blue-900" name="case_number" required>
                                        </label>
    
                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                            Gender:
                                            <select class="w-full bg-neutral-200 font-semibold" name="gender" required>
                                                <option disabled selected></option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </label>

                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200 cursor-pointer" for="date-input">
                                            Birthday:
                                            <input type="date" class="grow text-blue-900 cursor-pointer" name="birthday" required>
                                        </label>

                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                            Arresting Officers:
                                            <input type="text" class="grow text-blue-900" name="arresting_officers" required>
                                        </label>

                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                            Investigating Officer in Charge:
                                            <input type="text" class="grow text-blue-900" name="ioc" required>
                                        </label>

                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                            Address:
                                            <input type="text" class="grow text-blue-900 bg-neutral-200" name="address" required></input>
                                        </label>

                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                            Date and Time Arrested:
                                            <input type="datetime-local" class="grow text-blue-900" name="date_time_arrested" required>
                                        </label>
    
                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200 cursor-pointer" for="date-input">
                                            Date Admitted:
                                            <input type="date" class="grow text-blue-900 cursor-pointer" name="date_admitted" required>
                                        </label>
    
                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200 cursor-pointer" for="date-input">
                                            Date of Release:
                                            <input type="date" class="grow text-blue-900 cursor-pointer" name="date_release" required>
                                        </label>

                                        <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200 cursor-pointer" for="date-input">
                                            Emergency Contact:
                                            <input type="text" class="grow text-blue-900 bg-neutral-200" name="emergency_contact" required>
                                        </label>
    
                                        <input type="hidden" name="image_path" id="imagePath">
                                        <div class="h-auto" id="capturedImage"></div>
                                        
                                        <button type="button" class="btn btn-info" onclick="openCamera()">Capture Image</button>
    
                                        <script>
                                            function openCamera() {
                                                window.open('camera.php', 'Camera', 'width=1000,height=700');
                                            }
                                        </script>

                                    </div>
    
                                    <div class="modal-action">
                                        <button class="btn btn-error text-white" type="button" onclick="addInmate.close()">Cancel</button>
                                        <button type="submit" class="btn btn-success">Add Inmate</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        </dialog>

                        <script>
                            document.querySelectorAll('input[name="case_select"]').forEach(checkbox => {
                                checkbox.addEventListener('change', updateSelectedCases);
                            });

                            function updateSelectedCases() {
                                const selected = Array.from(document.querySelectorAll('input[name="case_select"]:checked'))
                                    .map(cb => cb.value);
                                document.getElementById('selectedCases').textContent = selected.join(', ');
                            }

                            document.querySelectorAll('input[name="case_select"]').forEach(checkbox => {
                                if (checkbox.value === 'Other') {
                                    checkbox.addEventListener('change', function() {
                                        const otherCaseContainer = document.getElementById('otherCaseContainer');
                                        otherCaseContainer.style.display = this.checked ? 'flex' : 'none';
                                    });
                                }
                            });

                            document.querySelector('form[action="controllers/addInmate_admin.php"]').addEventListener('submit', function(e) {
                                e.preventDefault();
                                
                                const selectedCases = Array.from(document.querySelectorAll('input[name="case_select"]:checked'))
                                    .map(cb => cb.value);
                                
                                if (selectedCases.length === 0) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Please select at least one case',
                                        icon: 'error'
                                    });
                                    return false;
                                }
                                
                                if (selectedCases.includes('Other')) {
                                    const otherCase = document.getElementById('otherCaseInput').value.trim();
                                    if (!otherCase) {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Please specify the other case',
                                            icon: 'error'
                                        });
                                        return false;
                                    }
                                    selectedCases[selectedCases.indexOf('Other')] = otherCase;
                                }
                                
                                const caseDetailInput = document.createElement('input');
                                caseDetailInput.type = 'hidden';
                                caseDetailInput.name = 'case_detail';
                                caseDetailInput.value = selectedCases.join(', ');
                                this.appendChild(caseDetailInput);
                            
                                this.submit();
                            });
                        </script>

                        <script>
                            function toggleDropdown() {
                                const dropdown = document.getElementById('caseSelect');
                                dropdown.classList.toggle('hidden');
                            }
                            
                            document.addEventListener('click', function(event) {
                                const dropdown = document.getElementById('caseSelect');
                                const button = event.target.closest('button');
                                if (!button && !event.target.closest('#caseSelect')) {
                                    dropdown.classList.add('hidden');
                                }
                            });

                            document.querySelectorAll('input[name="case_select"]').forEach(checkbox => {
                                checkbox.addEventListener('change', updateSelectedCases);
                            });

                            function updateSelectedCases() {
                                const selected = Array.from(document.querySelectorAll('input[name="case_select"]:checked'))
                                    .map(cb => cb.value);
                                document.getElementById('selectedCases').textContent = selected.join(', ');
                            }

                            function toggleOtherCaseInput() {
                                const caseSelect = document.getElementById('caseSelect');
                                const otherCaseContainer = document.getElementById('otherCaseContainer');
                                const otherCaseInput = document.getElementById('otherCaseInput');
                                
                                if (caseSelect.value === 'Other') {
                                    otherCaseContainer.style.display = 'flex';
                                    otherCaseInput.required = true;
                                } else {
                                    otherCaseContainer.style.display = 'none';
                                    otherCaseInput.required = false;
                                    otherCaseInput.value = caseSelect.value;
                                }
                            }

                            function validateCases() {
                                const selectedCases = document.querySelectorAll('input[name="case_select"]:checked');
                                if (selectedCases.length === 0) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Please select at least one case',
                                        icon: 'error'
                                    });
                                    return false;
                                }
                             
                                if (Array.from(selectedCases).some(cb => cb.value === 'Other')) {
                                    const otherCase = document.getElementById('otherCaseInput').value.trim();
                                    if (!otherCase) {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: 'Please specify the other case',
                                            icon: 'error'
                                        });
                                        return false;
                                    }
                                }
                                return true;
                            }

                            document.querySelector('form').addEventListener('submit', function(e) {
                                e.preventDefault();
                                
                                if (!validateCases()) {
                                    return;
                                }
                                
                                const selectedCases = Array.from(document.querySelectorAll('input[name="case_select"]:checked'))
                                    .map(cb => cb.value);
                                
                                if (selectedCases.includes('Other')) {
                                    const otherCase = document.getElementById('otherCaseInput').value;
                                    if (otherCase) {
                                        selectedCases[selectedCases.indexOf('Other')] = otherCase;
                                    }
                                }
                                
                                document.querySelector('input[name="case_detail"]').value = selectedCases.join('; ');
                              
                                this.submit();
                            });

                            function capitalizeFirstLetter(input) {
                            const words = input.value.split(' ');
                            for (let i = 0; i < words.length; i++) {
                                words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1).toLowerCase();
                            }
                            input.value = words.join(' ');
                        }

                        document.addEventListener('DOMContentLoaded', function() {
                            const nameFields = ['firstname', 'lastname', 'middlename'];
                            nameFields.forEach(field => {
                                const inputElement = document.querySelector(`input[name="${field}"]`);
                                inputElement.addEventListener('input', function() {
                                    capitalizeFirstLetter(this);
                                });
                            });
                        });
                        </script>

                </div>

                <!--Search Function-->
                <label class="input input-bordered flex items-center bg-white gap-2 mb-5 w-1/2">
                <input type="text" id="searchInput" class="grow" placeholder="Search Name, Case and Case Number" oninput="searchInmates()" />
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 16 16"
                        fill="currentColor"
                        class="h-4 w-4 opacity-70">
                        <path
                        fill-rule="evenodd"
                        d="M9.965 11.026a5 5 0 1 1 1.06-1.06l2.755 2.754a.75.75 0 1 1-1.06 1.06l-2.755-2.754ZM10.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0Z"
                        clip-rule="evenodd" />
                    </svg>
                </label>
                

                <table class="table border-2 border-black">
                  <thead>
                    <tr class="text-black text-md">
                      <th>ID</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Middle Name</th>
                      <th>Suffix</th>
                      <th>Age</th>
                      <th>Gender</th>
                      <th>Case Number</th>
                      <th>Case</th>
                      <th>Date Admitted</th>
                      <th>Date of Release</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                        <?php
                        require 'controllers/db_conn.php'; 

                        $limit = 10;  
                        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  
                        $start = ($page - 1) * $limit;

                        $total_results = $conn->query("SELECT COUNT(*) AS count FROM inmates")->fetch_assoc()['count'];
                        $total_pages = ceil($total_results / $limit);  

                        $result = $conn->query("
                            SELECT 
                                *,
                                TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age 
                            FROM inmates 
                            WHERE status = 'detained' 
                            ORDER BY date_admitted DESC 
                            LIMIT $start, $limit
                        ");


                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-gray-300' data-id='" . $row['id'] . "'>";
                                echo "<th>{$row['id']}</th>"; 
                                echo "<td>{$row['lastname']}</td>";
                                echo "<td>{$row['firstname']}</td>";
                                echo "<td>{$row['middlename']}</td>";
                                echo "<td>{$row['suffix']}</td>";
                                echo "<td>" . ($row['birthday'] ? $row['age'] : 'N/A') . "</td>";
                                echo "<td>{$row['gender']}</td>";
                                echo "<td>{$row['case_number']}</td>";
                                echo "<td>{$row['case_detail']}</td>";
                                echo "<td>{$row['date_admitted']}</td>";
                                echo "<td>{$row['date_release']}</td>";
                                echo "<td>
                                        <div class='dropdown dropdown-bottom dropdown-end'>
                                            <div tabindex='0' role='button' class='btn btn-accent btn-sm m-1 text-black'>Modify</div>
                                            <ul tabindex='0' class='dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow flex gap-2'>
                                                <li>
                                                   <button class='btn btn-sm btn-info' onclick='viewInmatePage({$row['id']})'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                                            <path d='M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z' />
                                                            <path fill-rule='evenodd' d='M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z' clip-rule='evenodd' />
                                                        </svg>
                                                        <p>View</p>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class='btn btn-sm btn-success' onclick='editInmate({$row['id']})'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                                            <path d='M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z' />
                                                            <path d='M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z' />
                                                        </svg>
                                                        <p>Edit</p>
                                                    </button>
                                                </li>
                                                 <li>
                                                    <button class='btn btn-sm btn-warning' onclick='confirmReview({$row['id']})'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.5' stroke='currentColor' class='size-6'>
                                                            <path stroke-linecap='round' stroke-linejoin='round' d='M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9' />
                                                        </svg>
                                                        <p>Release</p>
                                                    </button>
                                                </li>
                                                 <li>
                                                    <button class='btn btn-sm btn-accent' onclick='transferInmate({$row['id']})'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                                        <path fill-rule='evenodd' d='M12 5.25c1.213 0 2.415.046 3.605.135a3.256 3.256 0 0 1 3.01 3.01c.044.583.077 1.17.1 1.759L17.03 8.47a.75.75 0 1 0-1.06 1.06l3 3a.75.75 0 0 0 1.06 0l3-3a.75.75 0 0 0-1.06-1.06l-1.752 1.751c-.023-.65-.06-1.296-.108-1.939a4.756 4.756 0 0 0-4.392-4.392 49.422 49.422 0 0 0-7.436 0A4.756 4.756 0 0 0 3.89 8.282c-.017.224-.033.447-.046.672a.75.75 0 1 0 1.497.092c.013-.217.028-.434.044-.651a3.256 3.256 0 0 1 3.01-3.01c1.19-.09 2.392-.135 3.605-.135Zm-6.97 6.22a.75.75 0 0 0-1.06 0l-3 3a.75.75 0 1 0 1.06 1.06l1.752-1.751c.023.65.06 1.296.108 1.939a4.756 4.756 0 0 0 4.392 4.392 49.413 49.413 0 0 0 7.436 0 4.756 4.756 0 0 0 4.392-4.392c.017-.223.032-.447.046-.672a.75.75 0 0 0-1.497-.092c-.013.217-.028.434-.044.651a3.256 3.256 0 0 1-3.01 3.01 47.953 47.953 0 0 1-7.21 0 3.256 3.256 0 0 1-3.01-3.01 47.759 47.759 0 0 1-.1-1.759L6.97 15.53a.75.75 0 0 0 1.06-1.06l-3-3Z' clip-rule='evenodd' />
                                                    </svg>
                                                        <p>Transfer</p>
                                                    </button>
                                                </li>
                                                <li>
                                                    <button class='btn btn-sm btn-error' onclick='confirmDelete({$row['id']})'>
                                                        <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                                            <path fill-rule='evenodd' d='M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z' clip-rule='evenodd' />
                                                        </svg>
                                                        <p>Delete</p>
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No inmates found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <div class="flex justify-center">
                    <div class="join my-10">
                        <?php if ($page > 1): ?>
                            <button
                                class="join-item btn btn-square bg-white hover:bg-neutral-400"
                                onclick="window.location.href='?page=<?= $page - 1 ?>'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061A1.125 1.125 0 0 1 21 8.689v8.122ZM11.25 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061a1.125 1.125 0 0 1 1.683.977v8.122Z" />
                                </svg>
                            </button>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <input
                                class="join-item btn btn-square hover:bg-neutral-400  <?= $page == $i ? 'bg-green-400 text-white' : 'bg-white text-black' ?>"
                                type="radio"
                                name="options"
                                aria-label="<?= $i ?>"
                                value="<?= $i ?>"
                                <?= $page == $i ? 'checked="checked"' : '' ?>
                                onchange="window.location.href='?page=<?= $i ?>'" />
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <button
                                class="join-item btn btn-square bg-white hover:bg-neutral-400"
                                onclick="window.location.href='?page=<?= $page + 1 ?>'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!--Edit Inmates-->

                <dialog id="editInmate" class="modal">
                    <div class="modal-box bg-white">
                        <h3 class="text-lg font-bold text-center text-black">Edit Inmate Details</h3>

                        <form id="editInmateForm" onsubmit="handleFormSubmit(event);">
                            <input type="hidden" id="editInmateId" name="id" />

                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-black">First Name</span>
                                </div>
                                <input type="text" id="editFirstName" name="firstname" class="input input-bordered input-info w-full bg-neutral-200" required />
                            </label>  

                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-black">Last Name</span>
                                </div>
                                <input type="text" id="editLastName" name="lastname" class="input input-bordered input-info w-full bg-neutral-200" required />
                            </label>  

                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-black">Middle Name</span>
                                </div>
                                <input type="text" id="editMiddleName" name="middlename" class="input input-bordered input-info w-full bg-neutral-200" />
                            </label>  

                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-black">Suffix</span>
                                </div>
                                <select class="w-full input input-bordered input-info bg-neutral-200 font-semibold text-black" name="suffix">
                                    <option value=""  selected>None</option>
                                    <option value="Jr">Jr</option>
                                    <option value="Sr">Sr</option>
                                    <option value="II">II</option>
                                    <option value="III">III</option>
                                </select>
                            </label>

                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-black">Birthday</span>
                                </div>
                                <input type="date" id="editBirthday" name="birthday" class="input input-bordered input-info w-full bg-neutral-200" required />
                            </label>  
                            
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-black">Arresting Officers</span>
                                </div>
                                <input type="text" id="editArrestingOfficers" name="arresting_officers" class="input input-bordered input-info w-full bg-neutral-200" required />
                            </label>  

                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-black">IOC</span>
                                </div>
                                <input type="text" id="editIOC" name="ioc" class="input input-bordered input-info w-full bg-neutral-200" required />
                            </label>  

                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-black">Address</span>
                                </div>
                                <input type="text" id="editAddress" name="address" class="input input-bordered input-info w-full bg-neutral-200" required />
                            </label>  

                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-black">Case Number</span>
                                </div>
                                <input type="text" id="editCaseNumber" name="case_number" class="input input-bordered input-info w-full bg-neutral-200" required />
                            </label>  

                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text text-black">Case</span>
                                </div>
                                <input type="text" id="editCase" name="case_detail" class="input input-bordered input-info w-full bg-neutral-200" required />
                            </label>  

                            <div class="modal-action">
                                <button type="button" class="btn btn-error" onclick="document.getElementById('editInmate').close()">Cancel</button>
                                <button type="submit" class="btn btn-success">Verify and Save Changes</button>
                            </div>
                        </form>
                    </div>
                </dialog>

                <script>
                window.addEventListener('message', function(event) {
                    if (event.data === 'updateSuccess') {
                        document.getElementById('editInmate').close(); 
                        Swal.fire({
                            title: 'Success!',
                            text: 'Inmate details have been updated successfully.',
                            icon: 'success'
                        }).then(() => {
                            location.reload(); 
                        });
                    }
                });

                function editInmate(id) {
                    const xhr = new XMLHttpRequest();
                    xhr.open("GET", `controllers/getInmate.php?id=${id}`, true); 
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            const inmate = JSON.parse(xhr.responseText);
                            document.getElementById('editFirstName').value = inmate.firstname;
                            document.getElementById('editLastName').value = inmate.lastname;
                            document.getElementById('editMiddleName').value = inmate.middlename;
                            document.getElementById('editBirthday').value = inmate.birthday;
                            document.getElementById('editArrestingOfficers').value = inmate.arresting_officers;
                            document.getElementById('editIOC').value = inmate.ioc;
                            document.getElementById('editAddress').value = inmate.address;
                            document.getElementById('editCaseNumber').value = inmate.case_number;
                            document.getElementById('editCase').value = inmate.case_detail;
                            document.getElementById('editInmateId').value = inmate.id; 
                            document.getElementById('editInmate').showModal();
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "Failed to retrieve inmate data.",
                                icon: "error"
                            });
                        }
                    };
                    xhr.send();
                }

                function handleFormSubmit(event) {
                    event.preventDefault();
                    const inmateId = document.getElementById('editInmateId').value;
                    const formData = new FormData(document.getElementById('editInmateForm'));
                    const searchParams = new URLSearchParams();

                    for (const pair of formData) {
                        searchParams.append(pair[0], pair[1]);
                    }

                    sessionStorage.setItem('editFormData', JSON.stringify(Object.fromEntries(formData)));
                    window.open('facial_verification.php?id=' + inmateId, 'FacialVerification', 'width=1000,height=700');
                }
                </script>

                <script>
                    function viewInmatePage(id) {
                        window.location.href = `view_inmate_admin.php?id=${id}`;
                    }
                </script>

                <!--Delete Inmate-->
                <script>
                    function confirmDelete(id) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const xhr = new XMLHttpRequest();
                                xhr.open("GET", `controllers/deleteInmate.php?id=${id}`, true);
                                xhr.onload = function() {
                                    if (xhr.status === 200) {
                                        Swal.fire({
                                            title: "Deleted!",
                                            text: "The inmate has been deleted.",
                                            icon: "success"
                                        });
                                        document.querySelector(`tr[data-id='${id}']`).remove();
                                    } else {
                                        Swal.fire({
                                            title: "Error!",
                                            text: "Failed to delete the inmate.",
                                            icon: "error"
                                        });
                                    }
                                };
                                xhr.send();
                            }
                        });
                    }

                    function confirmReview(id) {
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "Inmate will be in Review.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, review it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`controllers/releaseInmate.php?id=${id}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status === 'success') {
                                            Swal.fire({
                                                title: "Updated!",
                                                text: "The inmate's Release is in Review.",
                                                icon: "success"
                                            }).then(() => {
                                                location.reload(); 
                                            });
                                        } else {
                                            Swal.fire({
                                                title: "Error!",
                                                text: data.message,
                                                icon: "error"
                                            });
                                        }
                                    });
                            }
                        });
                    }
                </script>
                

                <!--Search -->
                <script>
                    function searchInmates() {
                        const input = document.getElementById('searchInput').value;
                        const xhr = new XMLHttpRequest();
                        xhr.open("GET", "controllers/search_inmates.php?query=" + encodeURIComponent(input), true);
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                document.querySelector('tbody').innerHTML = xhr.responseText; 
                            }
                        };
                        xhr.send();
                    }
                </script>

                <dialog id="transferModal" class="modal text-black">
                    <div class="modal-box w-2/6 max-w-5xl bg-neutral-200">
                        <h3 class="text-lg font-bold">Transfer Inmate</h3>
                        <div class="form-control w-full mt-4">
                            <label class="label">
                                <span class="label-text text-black font-semibold">Transfer Location</span>
                            </label>
                            <input type="text" id="transferLocation" class="input input-bordered bg-neutral-300 w-full" required>
                            <input type="hidden" id="transferInmateId">
                        </div>
                        <div class="modal-action">
                            <button class="btn btn-success" onclick="confirmTransfer()">Confirm</button>
                            <form method="dialog">
                                <button class="btn btn-error">Cancel</button>
                            </form>
                        </div>
                    </div>
                </dialog>

                <script>
                function transferInmate(inmateId) {
                    document.getElementById('transferInmateId').value = inmateId;
                    document.getElementById('transferLocation').value = '';
                    transferModal.showModal();
                }

                function confirmTransfer() {
                    const inmateId = document.getElementById('transferInmateId').value;
                    const location = document.getElementById('transferLocation').value;

                    if (!location.trim()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Please enter a transfer location'
                        });
                        return;
                    }

                    transferModal.close();

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Commit to other facility?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, transfer!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('controllers/transferInmate.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify({
                                    inmateId: inmateId,
                                    location: location
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Swal.fire(
                                        'Transferred!',
                                        'Inmate has been transferred successfully.',
                                        'success'
                                    ).then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        data.message || 'Something went wrong.',
                                        'error'
                                    );
                                }
                            })
                            .catch(error => {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong.',
                                    'error'
                                );
                            });
                        } else {
                            transferModal.showModal();
                        }
                    });
                }
                </script>

                </div>
            </div>
        </div>

    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php 
            if (isset($_SESSION['alert_type']) && isset($_SESSION['alert_message'])) {
                echo "Swal.fire({
                    icon: '" . $_SESSION['alert_type'] . "',
                    title: '" . ucfirst($_SESSION['alert_type']) . "',
                    text: '" . $_SESSION['alert_message'] . "',
                    showConfirmButton: false,
                    timer: 1500
                });";
   
                unset($_SESSION['alert_type']);
                unset($_SESSION['alert_message']);
            }
            ?>
        });
    </script>
</body>
</html>