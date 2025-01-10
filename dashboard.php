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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="src/output.css" />
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <title>Dashboard</title>
    <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
  </head>
  <body class="bg-neutral-200">
    <div class="flex flex-row">
    <ul class="flex flex-col w-1/4 bg-neutral-700 text-white h-screen sticky top-0">
        <img src="img/police-logo.png" alt="police-logo.png" class="my-10 self-center"  style="width: 150px; height: 150px;"/>

        <li class="hover:bg-neutral-500 bg-neutral-500">
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

        <div class="flex flex-row gap-5">
          <div class="w-1/2">
            <div class="bg-neutral-300 rounded-lg mb-5 text-black">
              <div class="card-body w-full">
                <h2 class="card-title mb-4">Create Post | Announcement</h2>
                <form action="controllers/create_post.php" method="POST" enctype="multipart/form-data">
                  <input type="text" class="input input-bordered w-full mb-3 bg-neutral-200" placeholder="Title" name="title" required>
                  <textarea class="textarea textarea-bordered w-full mb-3 bg-neutral-200" placeholder="What's on your mind?" name="content" required></textarea>

                  <div class="form-control w-full mb-5">
                    <label class="label">
                      <span class="label-text text-black">Attach Image</span>
                    </label>
                    <input type="file" class="file-input file-input-bordered file-input-accent bg-neutral-200 w-full" name="image" accept="image/*" />
                  </div>

                  <div class="flex justify-end w-full gap-5">
                    <div class="card-actions">
                      <button class="btn btn-success" type="submit">Post</button>
                      <button class="btn btn-info" onclick="document.getElementById('my_modal_1').showModal()">Create Event</button>
                    </div>
                  </div>
                </form>

                
                <dialog id="my_modal_1" class="modal">
                  <div class="modal-box bg-neutral-300">
                    <h3 class="text-lg font-bold">Create Event</h3>
                    <form id="createEventForm">
                      <div class="form-control w-full mb-3">
                        <label class="label">
                          <span class="label-text text-black">Event Title</span>
                        </label>
                        <select name="event_title" class="select select-bordered bg-neutral-200 w-full" required>
                          <option value="" disabled selected>Select Event Type</option>
                          <option value="Video Conferencing">Video Conferencing</option>
                          <option value="Video Produce">Video Produce</option>
                        </select>
                      </div>
                      <div class="form-control w-full mb-3">
                        <label class="label">
                          <span class="label-text text-black">Description</span>
                        </label>
                        <textarea name="event_description" class="textarea textarea-bordered bg-neutral-200 w-full h-48 whitespace-pre-wrap" style="line-height: 1.5;" required></textarea>
                      </div>
                      <div class="form-control w-full mb-3">
                        <label class="label">
                          <span class="label-text text-black">Date</span>
                        </label>
                        <input type="date" name="event_date" class="input input-bordered bg-neutral-200 w-full" required>
                      </div>
                      <div class="form-control w-full mb-3">
                        <label class="label">
                          <span class="label-text text-black">Time</span>
                        </label>
                        <input type="time" name="event_time" class="input input-bordered bg-neutral-200 w-full" required>
                      </div>
                      <div class="modal-action">
                        <button type="button" class="btn btn-success" id="submitEventButton">Create Event</button>
                        <button type="button" class="btn btn-error" onclick="document.getElementById('my_modal_1').close()">Close</button>
                      </div>
                    </form>
                  </div>
                </dialog>
              </div>
            </div>

            <div class="w-full">
              <div class="bg-neutral-300 rounded-lg mb-5 text-black">
                <div class="card-body w-full">
                  <h2 class="card-title mb-4">Events</h2>
                  <div class="overflow-y-auto overflow-x-hidden pr-2" style="max-height: 60vh">
                    <?php
                    require 'controllers/db_conn.php';
                    $upcoming_events_query = "SELECT * FROM events WHERE date >= CURDATE() ORDER BY date ASC, time ASC";
                    $upcoming_events_result = $conn->query($upcoming_events_query);

                    if ($upcoming_events_result->num_rows > 0) {
                        while ($event = $upcoming_events_result->fetch_assoc()) {
                            $dateTime = new DateTime($event['date'] . ' ' . $event['time']);
                            $day = $dateTime->format('d');
                            $month = $dateTime->format('F');
                            $formattedDate = $dateTime->format('g:i a');
                            ?>
                            <div class="card bg-neutral-200 mb-5 text-black">
                                <div class="card-body">
                                    <div class="flex flex-row gap-2 items-center justify-between" id="header">
                                        <div class="flex flex-col gap-2">
                                            <h1 class="font-semibold"><?= htmlspecialchars($event['title']) ?></h1>
                                            <p class="text-sm text-gray-500">Time: <?= $formattedDate ?></p>
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            <div class="border-2 border-black h-20 w-20 p-2 rounded-xl items-center justify-center flex flex-col">
                                                <p class="text-4xl font-bold"><?= $day ?></p>
                                                <p class="text-sm font-bold"><?= $month ?></p>
                                            </div>
                                            
                                            <div class="dropdown dropdown-end">
                                                <div tabindex="0" role="button" class="btn border-none bg-neutral-300 hover:bg-neutral-400 m-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="fill" viewBox="0 0 24 24" stroke-width="3" stroke="black" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                    </svg>
                                                </div>
                                                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-32 p-2 shadow gap-2">
                                                    <button type="button" class="btn btn-sm btn-error" onclick="confirmDeleteEvent(<?= $event['id'] ?>)">Delete</button>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="collapse bg-neutral-300">
                                        <input type="checkbox" />
                                        <div class="collapse-title font-semibold">View Details</div>
                                        <div class="collapse-content">
                                            <p class="text-justify whitespace-pre-wrap"><?= htmlspecialchars($event['description']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p class="text-center font-semibold">No upcoming events available.</p>';
                    }
                    ?>

                    <div class="divider before:border-t-2 before:border-black after:border-t-2 after:border-black">Recent Events</div>

                    <?php
                    $recent_events_query = "SELECT * FROM events WHERE date < CURDATE() ORDER BY date DESC, time DESC LIMIT 5";
                    $recent_events_result = $conn->query($recent_events_query);

                    if ($recent_events_result->num_rows > 0) {
                        while ($event = $recent_events_result->fetch_assoc()) {
                            $dateTime = new DateTime($event['date'] . ' ' . $event['time']);
                            $day = $dateTime->format('d');
                            $month = $dateTime->format('F');
                            $formattedDate = $dateTime->format('g:i a');
                            ?>
                            <div class="card bg-neutral-200 mb-5 text-black opacity-75">
                                <div class="card-body">
                                    <div class="flex flex-row gap-2 items-center justify-between" id="header">
                                        <div class="flex flex-col gap-2">
                                            <h1 class="font-semibold"><?= htmlspecialchars($event['title']) ?></h1>
                                            <p class="text-sm text-gray-500">Time: <?= $formattedDate ?></p>
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            <div class="border-2 border-black h-20 w-20 p-2 rounded-xl items-center justify-center flex flex-col">
                                                <p class="text-4xl font-bold"><?= $day ?></p>
                                                <p class="text-sm font-bold"><?= $month ?></p>
                                            </div>
                                            
                                            <div class="dropdown dropdown-top dropdown-end">
                                                <div tabindex="0" role="button" class="btn border-none bg-neutral-300 hover:bg-neutral-400 m-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="fill" viewBox="0 0 24 24" stroke-width="3" stroke="black" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                    </svg>
                                                </div>
                                                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-32 p-2 shadow gap-2">
                                                    <button type="button" class="btn btn-sm btn-error" onclick="confirmDeleteEvent(<?= $event['id'] ?>)">Delete</button>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="collapse bg-neutral-300">
                                        <input type="checkbox" />
                                        <div class="collapse-title font-semibold">View Details</div>
                                        <div class="collapse-content">
                                            <p class="text-justify whitespace-pre-wrap"><?= htmlspecialchars($event['description']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<p class="text-center font-semibold">No recent events available.</p>';
                    }
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="w-1/2 bg-neutral-300 p-5 rounded-lg">
            <div class="overflow-y-auto overflow-x-hidden" style="max-height: calc(100vh - 5rem);">
              <?php
              require 'controllers/db_conn.php';
              $posts_query = "SELECT p.*, u.first_name, u.last_name 
                              FROM posts p 
                              JOIN users u ON p.user_id = u.user_id 
                              ORDER BY p.created_at DESC";

              $posts_result = $conn->query($posts_query);

              if ($posts_result->num_rows > 0) {
                  while ($post = $posts_result->fetch_assoc()) {
                      echo '<div id="post-' . htmlspecialchars($post['post_id']) . '" class="rounded bg-neutral-200 text-black mb-5 w-full">';
                      echo '<div class="p-5">';
                      echo '<div class="flex flex-row items-center justify-between">';
                      echo '<div>';
                      echo '<h3>' . htmlspecialchars($post['first_name']) . ' ' . htmlspecialchars($post['last_name']) . '</h3>';
                      echo '</div>';
                      echo '<div>';
                      echo '<p class="text-sm text-gray-600">Posted on: ' . date('F j, Y, g:i a', strtotime($post['created_at'])) . '</p>';
                      echo '</div>';
                      echo '<div class="dropdown dropdown-end">';
                      echo '<div tabindex="0" role="button" class="btn border-none bg-neutral-300 hover:bg-neutral-400 m-1">';
                      echo '<svg xmlns="http://www.w3.org/2000/svg" fill="fill" viewBox="0 0 24 24" stroke-width="3" stroke="black" class="size-6">';
                      echo '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />';
                      echo '</svg>';
                      echo '</div>';
                      echo '<ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-32 p-2 shadow gap-2">';
                      echo '<button type="button" class="btn btn-sm btn-error" onclick="confirmDelete(' . htmlspecialchars($post['post_id']) . ')">Delete</button>';
                      echo '</form>';
                      echo '</ul>';
                      echo '</div>';
                      echo '</div>';
                      echo '<h2 class="card-title mb-5">' . htmlspecialchars($post['title']) . '</h2>';
                      echo '<p class="mb-5 text-justify whitespace-pre-wrap">' . htmlspecialchars($post['content']) . '</p>';
                      if (!empty($post['image_path'])) {
                          echo '<img src="' . htmlspecialchars($post['image_path']) . '" alt="Post Image" class="w-full h-96 rounded-md">';
                      }
                      echo '</div>';
                      echo '</div>';
                  }
              } else {
                  echo '<p class="text-center text-black">No posts available.</p>';
              }
              ?>
            </div>
          </div>
        </div>
      </div>

    <script src="src/jquery-3.7.1.min.js"></script>
    <script>

        $(document).ready(function() {
            $('#submitEventButton').on('click', function() {
                document.getElementById('my_modal_1').close();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to create this event?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, create it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'controllers/create_event.php',
                            type: 'POST',
                            data: $('#createEventForm').serialize(),
                            success: function(response) {
                                Swal.fire({
                                    icon: response.status === 'success' ? 'success' : 'error',
                                    title: response.message
                                }).then(() => {
                                    if (response.status === 'success') {
                                        location.reload(); 
                                    } else {
                                        document.getElementById('my_modal_1').showModal(); 
                                    }
                                });
                            },
                            error: function() {
                                Swal.fire('Error!', 'An error occurred while creating the event.', 'error').then(() => {
                                    document.getElementById('my_modal_1').showModal(); 
                                });
                            }
                        });
                    } else {
                        document.getElementById('my_modal_1').showModal();
                    }
                });
            });
        });

    document.getElementById('submitEventButton').addEventListener('click', function() {
        Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to create this event?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, create it!'
        }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('createEventForm').submit();
            }
        });
        });

      document.querySelectorAll('.review-form button').forEach(button => {
        button.addEventListener('click', function() {
          const form = this.closest('form');
          const action = this.getAttribute('data-action');
          form.querySelector('input[name="action"]').value = action;
          const userId = form.querySelector('input[name="user_id"]').value;

          Swal.fire({
            title: `Are you sure you want to ${action} this account?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: `Yes, ${action} it!`,
            cancelButtonText: 'Cancel'
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        });
      });

      function confirmDelete(postId) {
        Swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.isConfirmed) {
            $.ajax({
              url: 'controllers/delete_post.php',
              type: 'POST',
              data: { post_id: postId },
              success: function(response) {
                Swal.fire(
                  'Deleted!',
                  'Your post has been deleted.',
                  'success'
                );
                document.getElementById('post-' + postId).remove();
              },
              error: function(xhr, status, error) {
                Swal.fire(
                  'Error!',
                  'There was an error deleting your post.',
                  'error'
                );
              }
            });
          }
        });
      }

      function confirmDeleteEvent(eventId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'controllers/delete_event.php',
                    type: 'POST',
                    data: { event_id: eventId },
                    success: function(response) {
                        Swal.fire(
                            'Deleted!',
                            'Event has been deleted.',
                            'success'
                        ).then(() => {
                            location.reload(); 
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(
                            'Error!',
                            'There was an error deleting the event.',
                            'error'
                        );
                    }
                });
            }
        });
      }
    </script>

  </body>
</html>
