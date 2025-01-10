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
    <link rel="stylesheet" href="src/output.css">
    <script src="src/jquery-3.7.1.min.js"></script>
    <script src="src/jspdf.umd.min.js"></script>
    <script src="src/jspdf.plugin.autotable.min.js"></script>
    <title>Reports</title>
</head>
<body class="bg-neutral-200 h-full">

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
        
            <li class="hover:bg-neutral-500">
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

            <li class="hover:bg-neutral-500 bg-neutral-500">
                <a href="reports.html" class="flex items-center pl-5 py-2">
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

         <!--Main Content-->
         <div class="m-10 w-full flex-grow overflow-auto">

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


            <div role="tablist" class="tabs tabs-bordered">
                <!-- Inmate Report Tab -->
                <input type="radio" name="report" role="tab" class="tab text-black font-semibold text-xl" aria-label="Inmate Report" checked />
                <div role="tabpanel" class="tab-content py-10">
                    <p class="font-semibold text-black mb-5">Inmates Report</p>
                    <div class="flex justify-between gap-2 bg-neutral-300 p-5 rounded-xl">
                        <form id="inmateFilterForm">
                            <div class="flex justify-center gap-2">
                                <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200 cursor-pointer text-black">
                                    Start Date
                                    <input type="date" class="grow text-blue-900 cursor-pointer" name="start_date">
                                </label>
                                <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200 cursor-pointer text-black">
                                    End Date
                                    <input type="date" class="grow text-blue-900 cursor-pointer" name="end_date">
                                </label>
                                <input type="text" name="search_query" placeholder="Search by case number" class="input input-bordered bg-neutral-200 text-black">
                                <button class="btn btn-success" type="submit">Filter</button>
                            </div>
                        </form>
                        <div class="flex gap-4">
                            <button class="btn btn-accent" id="saveInmatePdf">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                            <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                            </svg>
                                Save as PDF
                            </button>
                            <button class="btn btn-info" id="printInmateReport">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                <path fill-rule="evenodd" d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 0 0 3 3h.27l-.155 1.705A1.875 1.875 0 0 0 7.232 22.5h9.536a1.875 1.875 0 0 0 1.867-2.045l-.155-1.705h.27a3 3 0 0 0 3-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0 0 18 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM16.5 6.205v-2.83A.375.375 0 0 0 16.125 3h-8.25a.375.375 0 0 0-.375.375v2.83a49.353 49.353 0 0 1 9 0Zm-.217 8.265c.178.018.317.16.333.337l.526 5.784a.375.375 0 0 1-.374.409H7.232a.375.375 0 0 1-.374-.409l.526-5.784a.373.373 0 0 1 .333-.337 41.741 41.741 0 0 1 8.566 0Zm.967-3.97a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H18a.75.75 0 0 1-.75-.75V10.5ZM15 9.75a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V10.5a.75.75 0 0 0-.75-.75H15Z" clip-rule="evenodd" />
                            </svg>
                                Print
                            </button>
                        </div>
                    </div>
                    <div id="inmateReport" class="report-container overflow-auto mt-5">
                        <table class="table w-full">
                            <thead class="text-black">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Case Number</th>
                                    <th>Date Admitted</th>
                                </tr>
                            </thead>
                            <tbody class="text-black" id="inmateData">
                                <!-- Inmate data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Visitor Report Tab -->
                <input type="radio" name="report" role="tab" class="tab text-black font-semibold text-xl" aria-label="Visitor Report" />
                <div role="tabpanel" class="tab-content py-10">
                    <p class="font-semibold text-black mb-5">Visitors Report</p>
                    <div class="flex justify-between gap-2 bg-neutral-300 p-5 rounded-xl">
                        <form id="visitorFilterForm">
                            <div class="flex justify-center gap-2">
                                <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200 cursor-pointer text-black">
                                    Start Date
                                    <input type="date" class="grow text-blue-900 cursor-pointer" name="start_date">
                                </label>
                                <label class="input input-bordered input-info flex items-center gap-2 font-semibold bg-neutral-200 cursor-pointer text-black">
                                    End Date
                                    <input type="date" class="grow text-blue-900 cursor-pointer" name="end_date">
                                </label>
                                <input type="text" name="search_query" placeholder="Search by name" class="input input-bordered bg-neutral-200 text-black">
                                <button class="btn btn-success" type="submit">Filter</button>
                            </div>
                        </form>
                        <div class="flex gap-4">
                            <button class="btn btn-accent" id="saveVisitorPdf">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                                    <path d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" />
                                    <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                </svg>
                                Save as PDF
                            </button>
                            <button class="btn btn-info" id="printVisitorReport">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 0 0 3 3h.27l-.155 1.705A1.875 1.875 0 0 0 7.232 22.5h9.536a1.875 1.875 0 0 0 1.867-2.045l-.155-1.705h.27a3 3 0 0 0 3-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0 0 18 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM16.5 6.205v-2.83A.375.375 0 0 0 16.125 3h-8.25a.375.375 0 0 0-.375.375v2.83a49.353 49.353 0 0 1 9 0Zm-.217 8.265c.178.018.317.16.333.337l.526 5.784a.375.375 0 0 1-.374.409H7.232a.375.375 0 0 1-.374-.409l.526-5.784a.373.373 0 0 1 .333-.337 41.741 41.741 0 0 1 8.566 0Zm.967-3.97a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H18a.75.75 0 0 1-.75-.75V10.5ZM15 9.75a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V10.5a.75.75 0 0 0-.75-.75H15Z" clip-rule="evenodd" />
                            </svg>
                                Print
                            </button>
                        </div>
                    </div>
                    <div id="visitorReport" class="report-container overflow-auto mt-5">
                        <table class="table w-full">
                            <thead>
                                <tr class="text-black">
                                    <th>ID</th>
                                    <th>Visitor Name</th>
                                    <th>Inmate Name</th>
                                    <th>Relationship</th>
                                    <th>Purpose</th>
                                    <th>Visit Date</th>
                                    <th>Visit Time</th>
                                </tr>
                            </thead>
                            <tbody class="text-black" id="visitorData">
                                <!-- Visitor data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <iframe id="printFrame" class="hidden"></iframe>

        <script src="src/jquery-3.7.1.min.js"></script>
        <script>
        $(document).ready(function() {
                var imagesToPreload = ['img/pnp_logo.png', 'img/police-logo.png'];
                imagesToPreload.forEach(function(imgUrl) {
                var img = new Image();
                img.src = imgUrl;
            });
            $('#inmateFilterForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'controllers/filter_inmates.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#inmateData').html(response);
                    },
                    error: function() {
                        alert('Error fetching inmate data');
                    }
                });
            });
            $('#visitorFilterForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'controllers/filter_visitors.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#visitorData').html(response);
                    },
                    error: function() {
                        alert('Error fetching visitor data');
                    }
                });
            });

            $('#saveInmatePdf').on('click', function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                doc.setFontSize(12);
                doc.text("NATIONAL POLICE COMMISSION", 105, 15, { align: "center" });
                doc.text("PHILIPPINE NATIONAL POLICE CAPITAL REGION POLICE OFFICE", 105, 20, { align: "center" });
                doc.text("MANILA POLICE DISTRICT", 105, 25, { align: "center" });
                doc.text("MORIONES POLICE STATION (PS-2)", 105, 30, { align: "center" });
                doc.text("J. Nolasco corner Morga Sts. Tondo, Manila", 105, 35, { align: "center" });
                doc.text("Generated on " + new Date().toLocaleDateString(), 105, 40, { align: "center" });

                doc.addImage('img/pnp_logo.png', 'PNG', 10, 10, 20, 20);
                doc.addImage('img/police-logo.png', 'PNG', 180, 10, 20, 20);
                
                doc.setFontSize(16);
                doc.text("Inmate Report", 105, 50, { align: "center" });
                
                doc.autoTable({
                    html: '#inmateReport table',
                    startY: 60,
                    theme: 'grid',
                    styles: {
                        fontSize: 8,
                        cellPadding: 2,
                        overflow: 'linebreak',
                        cellWidth: 'wrap'
                    },
                    columnStyles: {
                        0: {cellWidth: 20},
                        1: {cellWidth: 40},
                        2: {cellWidth: 30},
                        3: {cellWidth: 30}
                    }
                });

                doc.save("inmate_report.pdf");
            });

            // Print Inmate Report
            $('#printInmateReport').on('click', function() {
                var printContents = document.getElementById('inmateReport').innerHTML;
                var printFrame = document.getElementById('printFrame');
                var doc = printFrame.contentWindow.document;
                doc.open();
                doc.write('<html><head><title>Inmate Report</title>');
                doc.write('<link rel="stylesheet" href="src/output.css" type="text/css">'); 
                doc.write('</head><body>');
                doc.write('<div style="text-align: center; margin-bottom: 10px; position: relative;">');
                doc.write('<p style="margin: 5px 0;"><b>NATIONAL POLICE COMMISSION</b></p>');
                doc.write('<p style="margin: 5px 0; position: relative;">');
                doc.write('<img src="img/pnp_logo.png" style="width: 60px; position: absolute; left: 0; top: -10;">');
                doc.write('<b>PHILIPPINE NATIONAL POLICE CAPITAL REGION POLICE OFFICE</b>');
                doc.write('<img src="img/police-logo.png" style="width: 75px; position: absolute; right: 0; top: -10;">');
                doc.write('</p>');
                doc.write('<p style="margin: 5px 0;"><b>MANILA POLICE DISTRICT</b></p>');
                doc.write('<p style="margin: 5px 0;"><b>MORIONES POLICE STATION (PS-2)</b></p>');
                doc.write('<p>J. Nolasco corner Morga St. Tondo, Manila</p>');
                doc.write('<p style="margin: 5px 0;">Generated on ' + new Date().toLocaleDateString() + '</p>');
                doc.write('</div>');
                doc.write('<h1 style="text-align: center; margin-bottom: 20px;">Inmate Report</h1>');
                doc.write(printContents);
                doc.write('</body></html>');
                doc.close();
                

                setTimeout(function() {
                    printFrame.contentWindow.focus();
                    printFrame.contentWindow.print();
                }, 500); 
            });

            $('#saveVisitorPdf').on('click', function() {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                doc.setFontSize(12);
                doc.text("NATIONAL POLICE COMMISSION", 105, 15, { align: "center" });
                doc.text("PHILIPPINE NATIONAL POLICE CAPITAL REGION POLICE OFFICE", 105, 20, { align: "center" });
                doc.text("MANILA POLICE DISTRICT", 105, 25, { align: "center" });
                doc.text("MORIONES POLICE STATION (PS-2)", 105, 30, { align: "center" });
                doc.text("J. Nolasco corner Morga Sts. Tondo, Manila", 105, 35, { align: "center" });
                doc.text("Generated on " + new Date().toLocaleDateString(), 105, 40, { align: "center" });

                doc.addImage('img/pnp_logo.png', 'PNG', 10, 10, 20, 20);
                doc.addImage('img/police-logo.png', 'PNG', 180, 10, 20, 20);
                
                doc.setFontSize(16);
                doc.text("Visitor Report", 105, 50, { align: "center" });
                
                doc.autoTable({
                    html: '#visitorReport table',
                    startY: 60,
                    theme: 'grid',
                    styles: {
                        fontSize: 8,
                        cellPadding: 2,
                        overflow: 'linebreak',
                        cellWidth: 'wrap'
                    }
                });

                doc.save("visitor_report.pdf");
            });

            // Print Visitor Report
            $('#printVisitorReport').on('click', function() {
                var printContents = document.getElementById('visitorReport').innerHTML;
                var printFrame = document.getElementById('printFrame');
                var doc = printFrame.contentWindow.document;
                doc.open();
                doc.write('<html><head><title>Visitor Report</title>');
                doc.write('<link rel="stylesheet" href="src/output.css" type="text/css">'); 
                doc.write('</head><body>');
                doc.write('<div style="text-align: center; margin-bottom: 10px; position: relative;">');
                doc.write('<p style="margin: 5px 0;"><b>NATIONAL POLICE COMMISSION</b></p>');
                doc.write('<p style="margin: 5px 0; position: relative;">');
                doc.write('<img src="img/pnp_logo.png" style="width: 60px; position: absolute; left: 0; top: -10;">');
                doc.write('<b>PHILIPPINE NATIONAL POLICE CAPITAL REGION POLICE OFFICE</b>');
                doc.write('<img src="img/police-logo.png" style="width: 75px; position: absolute; right: 0; top: -10;">');
                doc.write('</p>');
                doc.write('<p style="margin: 5px 0;"><b>MANILA POLICE DISTRICT</b></p>');
                doc.write('<p style="margin: 5px 0;"><b>MORIONES POLICE STATION (PS-2)</b></p>');
                doc.write('<p>J. Nolasco corner Morga St. Tondo, Manila</p>');
                doc.write('<p style="margin: 5px 0;">Generated on ' + new Date().toLocaleDateString() + '</p>');
                doc.write('</div>');
                doc.write('<h1 style="text-align: center; margin-bottom: 20px;">Visitor Report</h1>');
                doc.write(printContents);
                doc.write('</body></html>');
                doc.close();
                
                setTimeout(function() {
                    printFrame.contentWindow.focus();
                    printFrame.contentWindow.print();
                }, 500);
            });
        });
        </script>
    </div>

</body>
</html>
