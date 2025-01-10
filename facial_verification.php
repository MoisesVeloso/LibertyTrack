<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css"> 
    <title>Facial Verification</title>
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-dvh">
    <div class="bg-white shadow-lg rounded-lg p-6 w-96">
        <h1 class="text-black mb-5 font-semibold text-xl text-center">Facial Verification</h1>
        <video id="video" class="rounded-md w-full" width="640" height="480" autoplay></video>
        <canvas id="canvas" class="rounded-md w-full" width="640" height="480" style="display:none;"></canvas>

        <div class="flex flex-row justify-center items-center gap-5 mt-5">
            <button class="btn btn-success" id="capture">Capture</button>
            <button class="btn btn-success" id="verify" style="display:none;">Verify</button>
            <button class="btn btn-info" id="retake" style="display:none;">Retake</button>
        </div>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const captureButton = document.getElementById('capture');
        const verifyButton = document.getElementById('verify');
        const retakeButton = document.getElementById('retake');

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                video.play();
            })
            .catch(err => {
                console.error("Error accessing webcam: ", err);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error accessing webcam!'
                });
            });

        // Capture the image
        captureButton.addEventListener('click', () => {
            canvas.style.display = 'block';
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            captureButton.style.display = 'none';
            verifyButton.style.display = 'inline';
            retakeButton.style.display = 'inline';
            video.style.display = 'none'; 
        });

        // Retake the image
        retakeButton.addEventListener('click', () => {
            canvas.style.display = 'none';
            video.style.display = 'block'; 
            captureButton.style.display = 'inline';
            verifyButton.style.display = 'none';
            retakeButton.style.display = 'none';
        });

        // Verify the image
        verifyButton.addEventListener('click', () => {
            retakeButton.style.display = 'none';
            verifyButton.innerHTML = '<span class="align-middle loading loading-spinner"></span>Verifying';
            verifyButton.disabled = true;

            canvas.toBlob(blob => {
                if (!blob) {
                    console.error("Failed to create blob from canvas.");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to capture image!'
                    });
                    verifyButton.innerHTML = 'Verify';
                    verifyButton.disabled = false;
                    return;
                }

                const formData = new FormData();
                formData.append('image', blob, 'captured_image.png');
                const inmateId = new URLSearchParams(window.location.search).get('id');
                console.log('Inmate ID:', inmateId);
                formData.append('inmate_id', inmateId);

                fetch('controllers/verify.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Verified!',
                            text: 'Verification successful!',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const formDataJson = sessionStorage.getItem('editFormData');
                                if (!formDataJson) {
                                    throw new Error('No form data found');
                                }

                                const formData = new FormData();
                                const jsonData = JSON.parse(formDataJson);
                                
                                for (const [key, value] of Object.entries(jsonData)) {
                                    formData.append(key, value);
                                }
                                formData.append('id', inmateId);

                                fetch('controllers/editInmate.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: data.noChanges ? 'info' : 'success',
                                            title: data.noChanges ? 'No Changes' : 'Saved!',
                                            text: data.message
                                        }).then(() => {
                                            if (!data.noChanges) {
                                                window.opener.postMessage('updateSuccess', '*');
                                            }
                                            window.close();
                                        });
                                    } else {
                                        Swal.fire('Error!', 'Failed to update inmate details.', 'error');
                                        verifyButton.innerHTML = 'Verify';
                                        retakeButton.style.display = 'inline';
                                        verifyButton.disabled = false;
                                    }
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Verification Failed',
                            text: data.error || 'Please retake the image.'
                        });
                        verifyButton.innerHTML = 'Verify';
                        retakeButton.style.display = 'inline';
                        verifyButton.disabled = false;
                    }
                })
                .catch(error => {
                    console.error("Error during verification: ", error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Error during verification!'
                    });
                    verifyButton.innerHTML = 'Verify';
                    verifyButton.disabled = false;
                });
            }, 'image/png');
        });
    });
    </script>

</body>
</html>
