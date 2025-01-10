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

if ($userRole !== 'User') {
    header("Location: dashboard.php"); 
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
    <title>Visitors</title>
</head>
<body class="bg-neutral-200">

    <div class="flex flex-row">

        <ul class="flex flex-col w-96 bg-neutral-700 text-white h-dvh sticky top-0">
            <img src="img/police-logo.png" alt="police-logo.png" class="my-10 self-center" style="width: 150px; height: 150px;">
    
            <li class="hover:bg-neutral-500">
                <a href="home.php" class="flex items-center pl-5 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                        <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                        <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                    </svg>
                    <h1 class="pl-5 text-lg">Home</h1>
                </a>
            </li>

            <li class="dropdown">
                <div tabindex="0" role="button" class="hover:bg-neutral-500">
                    <div class="flex items-center pl-5 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                            <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 0 1 8.25-8.25.75.75 0 0 1 .75.75v6.75H18a.75.75 0 0 1 .75.75 8.25 8.25 0 0 1-16.5 0Z" clip-rule="evenodd" />
                            <path fill-rule="evenodd" d="M12.75 3a.75.75 0 0 1 .75-.75 8.25 8.25 0 0 1 8.25 8.25.75.75 0 0 1-.75.75h-7.5a.75.75 0 0 1-.75-.75V3Z" clip-rule="evenodd" />
                        </svg>
                        <p class="pl-5 text-lg">Analytics</p>
                    </div>
                </div>
                <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="top_crime.php">Top Crime</a></li>
                    <li><a href="monthly_crime.php">Monthly Crime</a></li>
                    <li><a href="crime_type.php">Crime Type</a></li>
                </ul>
            </li>
            
            <li class="hover:bg-neutral-500">
                <a href="inmates.php" class="flex items-center pl-5 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                        <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                        <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                    </svg>
                    <h1 class="pl-5 text-lg">Inmates List</h1>
                </a>
            </li>
        
            <li class="hover:bg-neutral-500 bg-neutral-500">
                <a href="visitors.php" class="flex items-center pl-5 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                        <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                    </svg>
                    <h1 class="pl-5 text-lg">Visitors List</h1>
               </a>
            </li>

            <li class="hover:bg-neutral-500">
                <a href="release.php" class="flex items-center pl-5 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                        <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                    </svg>
                    <h1 class="pl-5 text-lg">Release List</h1>
               </a>
            </li>

            <li class="hover:bg-neutral-500">
                <a href="transfered.php" class="flex items-center pl-5 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                        <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                    </svg>
                    <h1 class="pl-5 text-lg">Commit to Other Facility</h1>
               </a>
            </li>
            
            <li class="hover:bg-neutral-500">
                <a href="reports.php" class="flex items-center pl-5 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                        <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                        <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                    </svg>
                    <h1 class="pl-5 text-lg">Reports</h1>
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
                    <form method="dialog">
                        <button class="btn btn-info">Close</button>
                    </form>
            </div>
            </div>
        </div>

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
   
            <!--Main Content-->
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

                <div class="flex items-center justify-between pb-10">
                    <p class="font-semibold text-xl">Visitors</p>
    
                    <button class="btn btn-success" onclick="addVisitor.showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M5.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM2.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM18.75 7.5a.75.75 0 0 0-1.5 0v2.25H15a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H21a.75.75 0 0 0 0-1.5h-2.25V7.5Z" />
                        </svg>
                        <h1 class="pl-5">Add Visitors</h1>
                    </button>
                    <dialog id="addVisitor" class="modal text-black">
                        <div class="rounded-xl w-2/6 bg-white p-10 flex flex-col justify-center">
                            <h3 class="text-lg font-bold text-center pb-5">Add Visitor</h3>
                            
                            <form id="visitorForm" method="post">
                                <div class="flex flex-col gap-4">
                                    <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                        Visitor Name:
                                        <input type="text" class="grow text-blue-900" name="visitor_name" required>
                                    </label>

                                    <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                        Name of Inmate:
                                        <input type="text" id="inmateSearch" class="grow text-blue-900" name="inmate_name" oninput="searchInmates()" required>
                                        <div id="inmateDropdown" class="dropdown-content bg-white hidden absolute p-2 border-solid border-black border-2 max-h-40 overflow-auto right-1/4 rounded-lg"></div>
                                    </label>

                                    <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                        Relationship:
                                        <select class="grow bg-neutral-200 text-blue-900" name="relationship" required>
                                            <option value="">Select Relationship</option>
                                            <option value="Mother">Mother</option>
                                            <option value="Father">Father</option>
                                            <option value="Brother">Brother</option>
                                            <option value="Sister">Sister</option>
                                            <option value="Uncle">Uncle</option>
                                            <option value="Aunt">Aunt</option>
                                            <option value="Nephew">Nephew</option>
                                            <option value="Niece">Niece</option>
                                            <option value="Cousin">Cousin</option>
                                            <option value="Grandparent">Grandparent</option>
                                            <option value="Grandchild">Grandchild</option>
                                            <option value="Friend">Friend</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </label>

                                    <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200">
                                        Purpose:
                                        <input type="text" class="grow text-blue-900" name="purpose" required>
                                    </label>
                                    <input type="hidden" name="image_path" id="visitorImage">
                                    <div id="capturedImage" class="w-full flex justify-center items-center"></div>
                                    <button type="button" class="btn btn-info" onclick="openCamera()">Capture Image</button>
                                </div>
                                
                                <input type="hidden" name="date" id="visitorDate">
                                <input type="hidden" name="time" id="visitorTime">
                                <input type="hidden" id="inmateId" name="inmate_id">

                                <div class="modal-action">
                                    <button class="btn btn-error text-white" type="button" onclick="addVisitor.close()">Cancel</button>
                                    <button type="submit" class="btn btn-success">Add Visitor</button>
                                </div>
                            </form>
                        </div>
                    </dialog>
    
                </div>
                
                <table class="table">
                    <thead>
                        <tr class="text-black">
                            <th></th>
                            <th>Visitor's Name</th>
                            <th>Name of Inmate</th>
                            <th>Relationship</th>
                            <th>Purpose</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'controllers/db_conn.php'; 

                        $result = $conn->query("SELECT * FROM visitors ORDER BY visit_date DESC, visit_time DESC");
                        
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr id='visitorRow{$row['id']}' class='hover:bg-gray-300 text-black'>";
                                echo "<td><img src='{$row['image_path']}' alt='Visitor Image' class='w-16 h-16 object-cover rounded-lg cursor-pointer' onclick='openModal(\"{$row['image_path']}\")'></td>";
                                echo "<td>{$row['visitor_name']}</td>";
                                echo "<td>{$row['inmate_name']}</td>";
                                echo "<td>{$row['relationship']}</td>";
                                echo "<td>{$row['purpose']}</td>";
                                echo "<td>{$row['visit_date']}</td>";
                                echo "<td>{$row['visit_time']}</td>"; 
                                echo "<td><button class='btn btn-error btn-sm' onclick='deleteVisitor({$row['id']}, \"{$row['image_path']}\")'><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-6'>
                                        <path fill-rule='evenodd' d='M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z' clip-rule='evenodd' />
                                        </svg>
                                        Delete
                                    </button></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>No visitors found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
    

            </div>
            
        </div>

    </div>

    <script src="src/jquery-3.7.1.min.js"></script>
    <script>
        function searchInmates() {
            const query = $('#inmateSearch').val();
            const dropdown = $('#inmateDropdown');
            const inmateIdInput = $('#inmateId');

            // Clear inmate ID when search input changes
            inmateIdInput.val('');

            if (query.length > 0) {
                $.ajax({
                    url: 'controllers/fetch_inmates.php',
                    type: 'GET',
                    data: { query: query },
                    dataType: 'json',
                    success: function(data) {
                        dropdown.empty(); 
                        dropdown.removeClass('hidden'); 

                        if (data.length > 0) {
                            data.forEach(inmate => {
                                const option = $('<div></div>')
                                    .text(inmate.firstname + ' ' + inmate.lastname)
                                    .addClass('dropdown-item')
                                    .click(function() {
                                        $('#inmateSearch').val(inmate.firstname + ' ' + inmate.lastname);
                                        inmateIdInput.val(inmate.id);
                                        dropdown.addClass('hidden'); 
                                    });
                                dropdown.append(option);
                            });
                        } else {
                            dropdown.append('<div>No results found</div>');
                        }
                    },
                    error: function() {
                        console.error('Error fetching inmate data');
                    }
                });
            } else {
                dropdown.addClass('hidden'); 
            }
        }
    </script>

    <script src="src/jquery-3.7.1.min.js"></script>
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const params = new URLSearchParams(window.location.search);
            if (params.has('alert')) {
                const alertType = params.get('alert');
                const message = params.get('message');
                if (alertType === "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: message,
                        showConfirmButton: true,
                    });
                } else if (alertType === "error") {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message,

                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            }
            const date = new Date();
            const formattedDate = date.toISOString().split('T')[0];
            const options = { hour: 'numeric', minute: 'numeric', hour12: true };
            const formattedTime = date.toLocaleString('en-US', options);
            document.getElementById('visitorDate').value = formattedDate;
            document.getElementById('visitorTime').value = formattedTime;
        });

        function deleteVisitor(visitorId, imagePath) {
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
                    $.ajax({
                        url: 'controllers/deleteVisitor.php',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({ 
                            visitor_id: visitorId,
                            image_path: imagePath 
                        }),
                        success: function(data) {
                            data = JSON.parse(data);
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'The visitor has been deleted.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $(`#visitorRow${visitorId}`).remove();
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the visitor.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

    </script>

    <script>
    $(document).ready(function() {
        $('#visitorForm').submit(function(e) {
            e.preventDefault();
            const inmateId = $('#inmateId').val();
            const modal = document.getElementById('addVisitor');

            if (!inmateId) {
                modal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Inmate',
                    text: 'Please select an inmate from the dropdown list',
                }).then(() => {
                    modal.showModal();
                });
                return;
            }

            modal.close();
            
            $.ajax({
                type: 'POST',
                url: 'controllers/addVisitor.php',
                data: $(this).serialize(),
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    modal.showModal();
                                }
                            });
                        }
                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Invalid server response',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                modal.showModal();
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong! ' + error,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            modal.showModal();
                        }
                    });
                }
            });
        });
    });
    </script>

    <script>
        function openCamera() {
            const timestamp = new Date().getTime();
            window.open('visitor_cam.php?t=' + timestamp, 'Capture Image', 'width=800,height=600');
        }
        function updateImagePreview(imagePath) {
            const previewImage = document.getElementById('previewImage');
            const imageInput = document.getElementById('image');
            if (previewImage && imageInput) {
                previewImage.src = imagePath + '?t=' + new Date().getTime();
                imageInput.value = imagePath;
            }
        }
        window.addEventListener('message', function(event) {
            if (event.data.type === 'imageCaptured') {
                const imagePath = event.data.imagePath;
                updateImagePreview(imagePath);
            }
        });
    </script>

    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 items-center flex justify-center hidden">
        <div class="relative">
            <img id="modalImage" src="" alt="Visitor Image" class="max-w-full max-h-full rounded-lg">
            <button onclick="closeModal()" class="absolute top-0 right-0 m-2 text-white text-2xl">&times;</button>
        </div>
    </div>

    <script>
    function openModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
    }

    function closeModal() {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
    }
    </script>

    <script>
        $('#inmateSearch').on('input', function(e) {
            const inmateIdInput = $('#inmateId');
            if (inmateIdInput.val()) {
                inmateIdInput.val(''); 
            }
        });
    </script>

</body>
</html>
