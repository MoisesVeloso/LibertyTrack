<?php
session_start();
require 'controllers/db_conn.php';

if (!isset($_GET['id'])) {
    header("Location: inmates.php");
    exit();
}

$id = intval($_GET['id']);

$statusQuery = $conn->prepare("SELECT status FROM inmates WHERE id = ?");
$statusQuery->bind_param("i", $id);
$statusQuery->execute();
$statusResult = $statusQuery->get_result();
$inmateStatus = $statusResult->fetch_assoc()['status'];

$logQuery = $conn->prepare("SELECT activity_type, description, duration, location, created_at FROM inmate_logs WHERE inmate_id = ?");
$logQuery->bind_param("i", $id);
$logQuery->execute();
$logResult = $logQuery->get_result();
$logs = $logResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="node_modules/sweetalert2/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="src/output.css">
    <title>View Inmate</title>
</head>
<body class="bg-neutral-200">

    <div class="flex flex-row relative">
        <!-- Side Navigation -->
        <ul class="flex flex-col w-96 bg-neutral-700 text-white h-dvh sticky top-0">
            <img src="img/police-logo.png" alt="police-logo.png" class="my-10 self-center" style="width: 150px; height: 150px;">
            <li class="hover:bg-neutral-500">
                <a href="inmates.php" class="flex items-center pl-5 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M9.53 2.47a.75.75 0 0 1 0 1.06L4.81 8.25H15a6.75 6.75 0 0 1 0 13.5h-3a.75.75 0 0 1 0-1.5h3a5.25 5.25 0 1 0 0-10.5H4.81l4.72 4.72a.75.75 0 1 1-1.06 1.06l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                    </svg>
                    <h1 class="pl-5 text-lg">Back</h1>
                </a>
            </li>
        </ul>

        <!-- Main Content -->
        <div class="m-10 w-full flex flex-row gap-2 h-dvh">
            <div class="w-2/4 p-5 rounded-lg bg-white overflow-y-auto">
                <div class="flex justify-between mb-5">
                    <h3 class="text-lg font-bold text-center text-black">View Inmate Details</h3>
                    <button class="btn btn-info" onclick="printInmateDetails()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 0 0 3 3h.27l-.155 1.705A1.875 1.875 0 0 0 7.232 22.5h9.536a1.875 1.875 0 0 0 1.867-2.045l-.155-1.705h.27a3 3 0 0 0 3-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0 0 18 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM16.5 6.205v-2.83A.375.375 0 0 0 16.125 3h-8.25a.375.375 0 0 0-.375.375v2.83a49.353 49.353 0 0 1 9 0Z" clip-rule="evenodd"/>
                        </svg>
                        Print
                    </button>
                </div>

                <div class="overflow-y-auto max-h-[calc(100vh-200px)]">
                    <label class="form-control w-full">
                        <img id="viewPicture" class="bg-neutral-200 self-center rounded-md" alt="Inmate Profile Picture" style="width: 250px; height: 250px;" />
                    </label> 

                    <div class="flex flex-row gap-2">
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text text-black">First Name</span>
                            </div>
                            <input type="text" id="viewFirstName" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  

                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text text-black">Last Name</span>
                            </div>
                            <input type="text" id="viewLastName" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  
                    </div>

                    <div class="flex flex-row gap-2">
                        <label class="form-control w-full">
                            <div class="label">
                            <span class="label-text text-black">Middle Name</span>
                        </div>
                        <input type="text" id="viewMiddleName" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  

                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text text-black">Suffix</span>
                            </div>
                            <input type="text" id="viewSuffix" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  
                    </div>

                    <div class="flex flex-row gap-2">
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text text-black">Gender</span>
                            </div>
                            <input type="text" id="viewGender" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  

                        <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-black">Age</span>
                        </div>
                        <input type="text" id="viewAge" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  
                    </div>

                    <div class="flex flex-row gap-2">
                            <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text text-black">Emergency Contact</span>
                            </div>
                            <input type="text" id="viewEmergencyContact" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  
                        <label class="form-control w-full">
                            <div class="label">
                            <span class="label-text text-black">Birthday</span>
                        </div>
                        <input type="text" id="viewBirthday" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label> 
                    </div>

                    <div class="flex flex-row gap-2">
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text text-black">Case Number</span>
                            </div>
                            <input type="text" id="viewCaseNumber" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  

                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text text-black">Case</span>
                            </div>
                            <input type="text" id="viewCase" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  
                    </div>
                    
                    <div class="flex flex-row gap-2">
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text text-black">Arresting Officers</span>
                            </div>
                            <input type="text" id="viewArrestingOfficers" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  

                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text text-black">IOC</span>
                            </div>
                            <input type="text" id="viewIOC" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  
                    </div>

                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-black">Address</span>
                        </div>
                        <textarea id="viewAddress" class="textarea textarea-bordered textarea-info w-full bg-neutral-200 text-black" readonly></textarea>
                    </label>

                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-black">Date and Time Arrested</span>
                        </div>
                        <input type="text" id="viewDateTimeArrested" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                    </label>

                    <div class="flex flex-row gap-2">
                        <label class="form-control w-full">
                            <div class="label">
                            <span class="label-text text-black">Admitted</span>
                        </div>
                        <input type="text" id="viewAdmitted" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                    </label>  

                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text text-black">Release</span>
                        </div>
                        <input type="text" id="viewRelease" class="input input-bordered input-info w-full bg-neutral-200 text-black" readonly />
                        </label>  
                    </div>
                </div>
            </div>

            <div class="w-2/4 p-5 h-full rounded-lg bg-white">
                <div class="flex flex-row justify-between">
                    <h3 class="text-lg font-bold text-center text-black mb-2">History Log</h3>
                    <?php if ($inmateStatus !== 'released' && $inmateStatus !== 'reviewing'): ?>
                        <button class="btn btn-success" onclick="addLog.showModal()">Add Log</button>
                    <?php else: ?>
                        <button class="btn btn-success" disabled title="Cannot add logs for released or reviewing inmates">Add Log</button>
                    <?php endif; ?>
                </div>
                <div id="logContainer" class="log-container overflow-y-auto flex flex-col text-black h-4/5 mt-5">
                    <?php foreach ($logs as $log): ?>
                        <div class="log-entry bg-neutral-200 rounded-lg p-5 my-2">
                            <p class="my-2"><span class="font-bold">Activity:</span> <?php echo htmlspecialchars($log['activity_type']); ?></p>
                            <p class="my-2"><span class="font-bold">Description:</span> <?php echo htmlspecialchars($log['description']); ?></p>
                            <p class="my-2"><span class="font-bold">Duration:</span> <?php echo htmlspecialchars($log['duration']); ?></p>
                            <p class="my-2"><span class="font-bold">Location:</span> <?php echo htmlspecialchars($log['location']); ?></p>
                            <p class="my-2"><span class="font-bold">Date & Time:</span>  <?php $dateTime = new DateTime($log['created_at']); echo $dateTime->format('m-d-Y h:i:s A'); ?></p>
                            <hr>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <dialog id="addLog" class="modal">
                <div class="modal-box bg-white">
                    <h3 class="text-lg font-bold text-center text-black mb-5">Add Log</h3>
                    <form id="addLogForm" method="dialog" class="space-y-4">    
                        <div>
                            <label class="label">
                                <span class="label-text text-black">Activity or Event Type</span>
                            </label>
                            <input type="text" class="input input-bordered input-info w-full bg-neutral-200 text-black" name="activity_type" required />
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text text-black">Description</span>
                            </label>
                            <textarea class="textarea textarea-info w-full bg-neutral-200 text-black" placeholder="Description" name="description"></textarea>
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text text-black">Duration of Activity (if applicable)</span>
                            </label>
                            <input type="text" class="input input-bordered input-info w-full bg-neutral-200 text-black" name="duration" />
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text text-black">Location</span>
                            </label>
                            <input type="text" class="input input-bordered input-info w-full bg-neutral-200 text-black" name="location" required />
                        </div>
                        <div>
                            <label class="label">
                                <span class="label-text text-black">Documents or Attachments</span>
                            </label>
                            <input type="file" class="file-input file-input-info input-info w-full bg-neutral-200 text-black" name="documents" />
                        </div>
                        <div class="modal-action">
                            <button type="button" class="btn btn-success" onclick="startFacialVerificationForLog()">Verify and Submit</button>
                            <button type="button" class="btn btn-error" onclick="this.closest('dialog').close()">Close</button>
                        </div>
                    </form>
                </div>
            </dialog>

        </div>
    </div>

    <iframe id="printFrame" style="display: none;"></iframe>

    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const id = <?php echo json_encode($id); ?>;
            fetch(`controllers/getInmate.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire('Error!', data.error, 'error').then(() => {
                            window.location.href = 'inmates.php';
                        });
                    } else {
                        document.getElementById('viewPicture').src = `image_inmate/${data.image_path}`;
                        document.getElementById('viewFirstName').value = data.firstname;
                        document.getElementById('viewLastName').value = data.lastname;
                        document.getElementById('viewMiddleName').value = data.middlename;
                        document.getElementById('viewSuffix').value = data.suffix;
                        
                        const birthday = new Date(data.birthday);
                        const today = new Date();
                        let age = today.getFullYear() - birthday.getFullYear();
                        const monthDifference = today.getMonth() - birthday.getMonth();
                        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthday.getDate())) {
                            age--;
                        }

                        const formattedBirthday = birthday.toLocaleDateString('en-US', {        
                            month: 'long',
                            day: 'numeric',
                            year: 'numeric'
                        });

                        document.getElementById('viewAge').value = age;
                        document.getElementById('viewBirthday').value = formattedBirthday;
                        document.getElementById('viewEmergencyContact').value = data.emergency_contact;
                        document.getElementById('viewGender').value = data.gender;
                        document.getElementById('viewCaseNumber').value = data.case_number;
                        document.getElementById('viewCase').value = data.case_detail;
                        document.getElementById('viewAdmitted').value = data.date_admitted;
                        document.getElementById('viewRelease').value = data.date_release;
                        document.getElementById('viewArrestingOfficers').value = data.arresting_officers;
                        document.getElementById('viewIOC').value = data.ioc;
                        document.getElementById('viewAddress').value = data.address;
                        document.getElementById('viewDateTimeArrested').value = data.date_time_arrested;
                    }
                })
                .catch(error => {
                    Swal.fire('Error!', 'An error occurred while fetching inmate data', 'error').then(() => {
                        window.location.href = 'inmates.php';
                    });
                });
        });

        function startFacialVerificationForLog() {
            const formData = new FormData(document.getElementById('addLogForm'));
            sessionStorage.setItem('logFormData', JSON.stringify(Object.fromEntries(formData)));
            window.open('facial_verification_log.php?id=' + <?php echo json_encode($id); ?>, 'FacialVerification', 'width=1000,height=700');
        }

        window.addEventListener('message', function(event) {
            console.log('Message received:', event.data);
            if (event.data === 'logAdded') {
                window.location.reload();
            }
        });

        function printInmateDetails() {
            const printFrame = document.getElementById('printFrame');
            const doc = printFrame.contentWindow.document;
 
            const birthday = new Date(document.getElementById('viewBirthday').value);
            const formattedBirthday = birthday.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });

            const inmateData = {
                picture: document.getElementById('viewPicture').src,
                firstName: document.getElementById('viewFirstName').value,
                lastName: document.getElementById('viewLastName').value,
                middleName: document.getElementById('viewMiddleName').value,
                suffix: document.getElementById('viewSuffix').value,
                gender: document.getElementById('viewGender').value,
                birthday: formattedBirthday, 
                age: document.getElementById('viewAge').value,
                emergencyContact: document.getElementById('viewEmergencyContact').value,
                caseNumber: document.getElementById('viewCaseNumber').value,
                case: document.getElementById('viewCase').value,
                admitted: document.getElementById('viewAdmitted').value,
                release: document.getElementById('viewRelease').value,
                arrestingOfficers: document.getElementById('viewArrestingOfficers').value,
                ioc: document.getElementById('viewIOC').value,
                address: document.getElementById('viewAddress').value,
                dateTimeArrested: document.getElementById('viewDateTimeArrested').value
            };

            const logEntries = document.querySelectorAll('.log-entry');
            const visitorEntries = document.querySelectorAll('.visitor-entry');
            
            doc.open();
            doc.write(`
                <html>
                <head>
                    <title>Inmate Details</title>
                    <link rel="stylesheet" href="src/output.css">
                </head>
                <body>
                    <div class="header">
                        <div style="text-align: center; margin-bottom: 10px; position: relative;">
                            <p style="margin: 5px 0;"><b>NATIONAL POLICE COMMISSION</b></p>
                            <p style="margin: 5px 0; position: relative;">
                                <img src="img/pnp_logo.png" style="width: 60px; position: absolute; left: 0; top: -10;">
                                <b>PHILIPPINE NATIONAL POLICE CAPITAL REGION POLICE OFFICE</b>
                                <img src="img/police-logo.png" style="width: 75px; position: absolute; right: 0; top: -10;">
                            </p>
                            <p style="margin: 5px 0;"><b>MANILA POLICE DISTRICT</b></p>
                            <p style="margin: 5px 0;"><b>MORIONES POLICE STATION (PS-2)</b></p>
                            <p>J. Nolasco corner Morga St. Tondo, Manila</p>
                            <p style="margin: 5px 0;">Generated on ${new Date().toLocaleDateString()}</p>
                        </div>
                        <h2 class="text-center mb-2 text-2xl font-bold">Inmate Details</h2>
                    </div>

                    <div class="flex flex-col gap-2 inmate-details">
                        <img src="${inmateData.picture}" style="width: 200px; height: 200px; display: block; margin: 0 auto;">

                        <div class="detail-row">
                            <p class="text-black font-bold">Name: <span class="text-black font-semibold ml-2">${inmateData.firstName} ${inmateData.middleName} ${inmateData.lastName} ${inmateData.suffix}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Birthday: <span class="text-black font-semibold ml-2">${inmateData.birthday}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Gender: <span class="text-black font-semibold ml-2">${inmateData.gender}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Age: <span class="text-black font-semibold ml-2">${inmateData.age}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Emergency Contact: <span class="text-black font-semibold ml-2">${inmateData.emergencyContact}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Case Number: <span class="text-black font-semibold ml-2">${inmateData.caseNumber}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Case: <span class="text-black font-semibold ml-2">${inmateData.case}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Date Admitted: <span class="text-black font-semibold ml-2">${inmateData.admitted}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Release Date: <span class="text-black font-semibold ml-2">${inmateData.release}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Arresting Officers: <span class="text-black font-semibold ml-2">${inmateData.arrestingOfficers}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">IOC: <span class="text-black font-semibold ml-2">${inmateData.ioc}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Address: <span class="text-black font-semibold ml-2">${inmateData.address}</span></p>
                        </div>
                        <div class="detail-row">
                            <p class="text-black font-bold">Date and Time Arrested: <span class="text-black font-semibold ml-2">${inmateData.dateTimeArrested}</span></p>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <h3 class="text-center mb-2 text-2xl font-bold">History Log</h3>
                    <div class="logs">
            `);

            logEntries.forEach(entry => {
                doc.write(`
                    <div class="log-entry">
                        ${entry.innerHTML}
                    </div>
                `);
            });


            visitorEntries.forEach(entry => {
                doc.write(`
                    <div class="visitor-entry">
                        ${entry.innerHTML}
                    </div>
                `);
            });

            doc.write(`
                    </div>
                </body>
                </html>
            `);
            doc.close();

            setTimeout(() => {
                printFrame.contentWindow.focus();
                printFrame.contentWindow.print();
            }, 500);
        }
    </script>
</body>
</html>