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
    <title>Release List</title>
</head>
<body class="bg-neutral-200">

    <div class="flex flex-row relative">

        <ul class="flex flex-col w-96 bg-neutral-700 text-white h-dvh sticky top-0">
            <img src="img/police-logo.png" alt="police-logo.png" class="my-10 self-center" style="width: 150px; height: 150px;">
    
            <li class="hover:bg-neutral-500">
          <a href="dashboard.php" class="flex items-center pl-5 py-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
              <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
              <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
            </svg>
            <h1 class="pl-5 text-lg">Dashboard</h1>
          </a>
        </li>

            <li class="hover:bg-neutral-500">
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

        <li class="hover:bg-neutral-500 bg-neutral-500">
            <a href="release_admin.php" class="flex items-center pl-5 py-2 ">
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

                <div role="tablist" class="tabs tabs-bordered">
                    <input type="radio" name="inmate_tabs" role="tab" class="tab text-black font-semibold" aria-label="Released Inmates" checked="checked" />
                    <div role="tabpanel" class="tab-content py-10">
                        <table class="table border-2 border-black">
                            <thead>
                                <tr class="text-black text-md">
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Middle Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Case Number</th>
                                    <th>Case</th>
                                    <th>Status</th>
                                    <th>Date of Release</th>
                                    <th>Approved By</th>
                                    <th>Release Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $limit = 10;  
                                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  
                                $start = ($page - 1) * $limit;
                                
                                $total_results = $conn->query("SELECT COUNT(*) AS count FROM inmates WHERE status = 'released'")->fetch_assoc()['count'];
                                $total_pages = ceil($total_results / $limit);  
                                
                                $result = $conn->query("
                                    SELECT 
                                        i.*, 
                                        TIMESTAMPDIFF(YEAR, i.birthday, CURDATE()) AS age,
                                        u.username AS approved_by,
                                        DATE_FORMAT(pr.processed_at, '%M %d, %Y') AS formatted_date,
                                        DATE_FORMAT(pr.processed_at, '%l:%i %p') AS formatted_time,
                                        DATE_FORMAT(i.date_release, '%M %d, %Y') AS formatted_release_date
                                    FROM inmates i
                                    LEFT JOIN pending_releases pr ON i.id = pr.inmate_id
                                    LEFT JOIN users u ON pr.user_id = u.user_id
                                    WHERE i.status = 'released' 
                                    ORDER BY i.date_admitted DESC 
                                    LIMIT $start, $limit
                                ");

                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th>{$row['id']}</th>"; 
                                        echo "<td>{$row['firstname']}</td>";
                                        echo "<td>{$row['lastname']}</td>";
                                        echo "<td>{$row['middlename']}</td>";
                                        echo "<td>{$row['age']}</td>";
                                        echo "<td>{$row['gender']}</td>";
                                        echo "<td>{$row['case_number']}</td>";
                                        echo "<td>{$row['case_detail']}</td>";
                                        echo "<td>{$row['status']}</td>";
                                        echo "<td>{$row['formatted_release_date']}</td>";
                                        echo "<td>" . ($row['approved_by'] ? htmlspecialchars($row['approved_by']) : 'N/A') . "</td>";
                                        echo "<td>" . ($row['formatted_date'] && $row['formatted_time'] ? 
                                            htmlspecialchars($row['formatted_date'] . ' at ' . $row['formatted_time']) : 'N/A') . "</td>";
                                        echo "<td>
                                                <a href='view_inmate.php?id={$row['id']}' class='btn btn-info btn-sm'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-4'>
                                                        <path d='M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z' />
                                                        <path fill-rule='evenodd' d='M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z' clip-rule='evenodd' />
                                                    </svg>
                                                </a>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='12'>No released inmates found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <input type="radio" name="inmate_tabs" role="tab" class="tab text-black font-semibold" aria-label="Under Review" />
                    <div role="tabpanel" class="tab-content py-10">
                        <table class="table border-2 border-black">
                            <thead>
                                <tr class="text-black text-md">
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Middle Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Case Number</th>
                                    <th>Case</th>
                                    <th>Status</th>
                                    <th>Date Admitted</th>
                                    <th>Date of Release</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $review_result = $conn->query("SELECT *, TIMESTAMPDIFF(YEAR, birthday, CURDATE()) AS age 
                                                            FROM inmates 
                                                            WHERE status = 'reviewing' 
                                                            ORDER BY date_admitted DESC");

                                if ($review_result->num_rows > 0) {
                                    while($row = $review_result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<th>{$row['id']}</th>"; 
                                        echo "<td>{$row['firstname']}</td>";
                                        echo "<td>{$row['lastname']}</td>";
                                        echo "<td>{$row['middlename']}</td>";
                                        echo "<td>{$row['age']}</td>";
                                        echo "<td>{$row['gender']}</td>";
                                        echo "<td>{$row['case_number']}</td>";
                                        echo "<td>{$row['case_detail']}</td>";
                                        echo "<td>{$row['status']}</td>";
                                        echo "<td>{$row['date_admitted']}</td>";
                                        echo "<td>{$row['date_release']}</td>";
                                        echo "<td>
                                                <a href='view_inmate.php?id={$row['id']}' class='btn btn-info btn-sm'>
                                                    <svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='currentColor' class='size-4'>
                                                        <path d='M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z' />
                                                        <path fill-rule='evenodd' d='M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z' clip-rule='evenodd' />
                                                    </svg>
                                                    View
                                                </a>
                                            </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='12'>No inmates under review found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-center">
                    <div class="join my-10">
                        <?php if ($page > 1): ?>
                            <button class="join-item btn btn-square bg-white hover:bg-neutral-400"
                                    onclick="window.location.href='?page=<?= $page - 1 ?>'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061A1.125 1.125 0 0 1 21 8.689v8.122ZM11.25 16.811c0 .864-.933 1.406-1.683.977l-7.108-4.061a1.125 1.125 0 0 1 0-1.954l7.108-4.061a1.125 1.125 0 0 1 1.683.977v8.122Z" />
                                </svg>
                            </button>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <input class="join-item btn btn-square hover:bg-neutral-400  <?= $page == $i ? 'bg-green-400 text-white' : 'bg-white text-black' ?>"
                                   type="radio"
                                   name="options"
                                   aria-label="<?= $i ?>"
                                   value="<?= $i ?>"
                                   <?= $page == $i ? 'checked="checked"' : '' ?>
                                   onchange="window.location.href='?page=<?= $i ?>'" />
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <button class="join-item btn btn-square bg-white hover:bg-neutral-400"
                                    onclick="window.location.href='?page=<?= $page + 1 ?>'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="black" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061A1.125 1.125 0 0 1 3 16.811V8.69ZM12.75 8.689c0-.864.933-1.406 1.683-.977l7.108 4.061a1.125 1.125 0 0 1 0 1.954l-7.108 4.061a1.125 1.125 0 0 1-1.683-.977V8.69Z" />
                                </svg>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
            
           
      
</body>
</html>