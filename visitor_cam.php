<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="src/output.css">
    <title>Capture Image</title>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="flex justify-center items-center h-screen flex-col bg-white">
        <div class=" flex flex-col justify-center items-center">
            <video id="video" class="rounded-md" width="640" height="480" autoplay></video>

            <div class="flex flex-row justify-center items-center gap-5">
                
                <div class="flex flex-col justify-center items-center" id="toggleCamera">
                    <label for="mirrorToggle" class="text-black">Mirror Camera</label>
                    <input type="checkbox" class="toggle toggle-info" id="mirrorToggle"/>
                </div>
                
                <details class="dropdown mt-5">
                    <summary class="btn btn-accent m-1">Choose Camera</summary>
                    <ul class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow" id="cameraList">
                      
                    </ul>
                </details>
                <button class="btn btn-success mt-5" id="capture">Capture</button>
            </div>
            
            <canvas id="canvas" width="640" height="480" style="display:none;"></canvas>
            <img id="photo" alt="The screen capture will appear in this box.">
           
        </div>
        
        <div class="flex justify-center items-center mt-5 gap-5">
            <button class="btn btn-info" id="retake">Retake</button>
            <button class="btn btn-success" id="continue">Continue</button>
        </div>
        
    </div>
    

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const photo = document.getElementById('photo');
        const chooseCamera = document.getElementById('chooseCamera');
        const captureButton = document.getElementById('capture');
        const retakeButton = document.getElementById('retake');
        const continueButton = document.getElementById('continue');
        const mirrorToggle = document.getElementById('mirrorToggle');
        const toggleCamera = document.getElementById('toggleCamera');

        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(function(stream) {
                video.srcObject = stream;
                video.play();
            })
            .catch(function(err) {
                console.log("An error occurred: " + err);
            });

        photo.style.display = 'none'; 
        retakeButton.style.display = 'none';
        continueButton.style.display = 'none'; 

        captureButton.onclick = function() {
            const context = canvas.getContext('2d');

            if (mirrorToggle.checked) {
                context.translate(canvas.width, 0);
                context.scale(-1, 1);
            }

            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            if (mirrorToggle.checked) {
                context.setTransform(1, 0, 0, 1, 0, 0);
            }

            const data = canvas.toDataURL('image/png');
            photo.setAttribute('src', data);
            video.style.display = 'none';
            canvas.style.display = 'none';
            photo.style.display = 'block';
            captureButton.style.display = 'none'; 
            retakeButton.style.display = 'block'; 
            continueButton.style.display = 'block'; 
            document.querySelector('.dropdown').style.display = 'none'; 
            toggleCamera.style.display = 'none';

            const visitorName = window.opener.document.querySelector('input[name="visitor_name"]').value;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "controllers/save_visitor_image.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        window.opener.document.getElementById('visitorImage').value = response.filename;
                        window.opener.document.getElementById('capturedImage').innerHTML = '<img src="' + response.filename + '" alt="Captured Image"/>';
                        window.opener.postMessage({
                            type: 'imageCaptured',
                            imagePath: response.filename
                        }, '*');
                        setTimeout(function() {
                            window.close();
                        }, 0000);
                    } else {
                        console.error('Error saving image:', response.message);
                    }
                }
            };
            xhr.send("imageData=" + encodeURIComponent(data) + "&visitorName=" + encodeURIComponent(visitorName));
        };

        retakeButton.onclick = function() {
            video.style.display = 'block';
            photo.style.display = 'none';
            canvas.style.display = 'none';
            captureButton.style.display = 'block'; 
            retakeButton.style.display = 'none'; 
            continueButton.style.display = 'none'; 
            document.querySelector('.dropdown').style.display = 'block';
            toggleCamera.style.display = 'flex';
        };

        continueButton.onclick = function() {
            const imageData = photo.src;
            const visitorName = window.opener.document.querySelector('input[name="visitor_name"]').value;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "controllers/save_visitor_image.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        window.opener.document.getElementById('visitorImage').value = response.filename;
                        window.opener.document.getElementById('capturedImage').innerHTML = 
                            `<img src="${response.filename}" alt="Captured Image"/>`;
                        window.close();
                    } else {
                        console.error('Error saving image:', response.message);
                    }
                }
            };
            xhr.send("imageData=" + encodeURIComponent(imageData) + "&visitorName=" + encodeURIComponent(visitorName));
        };

        function listCameras() {
            navigator.mediaDevices.enumerateDevices()
                .then(function(devices) {
                    const videoDevices = devices.filter(device => device.kind === 'videoinput');
                    const cameraList = document.getElementById('cameraList');
                    cameraList.innerHTML = '';
                    videoDevices.forEach((device, index) => {
                        const li = document.createElement('li');
                        const a = document.createElement('a');
                        a.textContent = device.label || `Camera ${index + 1}`;
                        a.href = '#';
                        a.addEventListener('click', () => setCamera(device.deviceId));
                        li.appendChild(a);
                        cameraList.appendChild(li);
                    });
                })
                .catch(function(err) {
                    console.log("An error occurred: " + err);
                });
        }

        function setCamera(deviceId) {
            const constraints = {
                video: { deviceId: { exact: deviceId } },
                audio: false
            };
            navigator.mediaDevices.getUserMedia(constraints)
                .then(function(stream) {
                    video.srcObject = stream;
                    video.play();
                })
                .catch(function(err) {
                    console.log("An error occurred: " + err);
                });
        }

        listCameras(); 

        mirrorToggle.addEventListener('change', function() {
            if (this.checked) {
                video.style.transform = 'scaleX(-1)'; 
            } else {
                video.style.transform = 'scaleX(1)'; 
            }
        });

        document.getElementById('capture').addEventListener('click', function() {
            const canvas = document.getElementById('canvas');
            const video = document.getElementById('video');
            const context = canvas.getContext('2d');
            
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/png');
            
            // Send image data and close window immediately
            const visitorName = window.opener.document.querySelector('input[name="visitor_name"]').value;
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "controllers/save_visitor_image.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        window.opener.document.getElementById('visitorImage').value = response.filename;
                        window.opener.document.getElementById('capturedImage').innerHTML = 
                            `<img src="${response.filename}" alt="Captured Image" class="w-32 h-32 object-cover rounded-lg"/>`;
                        window.opener.postMessage({
                            type: 'imageCaptured',
                            imagePath: response.filename
                        }, '*');
                        setTimeout(function() {
                            window.close();
                        }, 1000);
                    } else {
                        console.error('Error saving image:', response.message);
                    }
                }
            };
            xhr.send("imageData=" + encodeURIComponent(imageData) + "&visitorName=" + encodeURIComponent(visitorName));
        });
    </script>
</body>
</html>