<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="src/output.css" rel="stylesheet">
  <title>Reset Password Verification</title>
</head>
<body>

  <div class="flex justify-center h-screen items-center" style="background-image: url(img/Backgroundblur.png);">
    <div class="bg-slate-200 w-1/4 p-5 rounded-md">
      <h1 class="text-center text-black text-xl font-semibold mb-5">Reset Password Verification</h1>

      <form class="flex flex-col gap-4 px-2" action="controllers/reset_verification_code.php" method="post">
        <div class="text-center text-black mb-3">
          <p>Please enter the 6-digit verification code sent to your email</p>
        </div>

        <div class="flex justify-center gap-2 w-full">
          <input type="text" id="code1" oninput="handleInput(event, 1)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
          <input type="text" id="code2" oninput="handleInput(event, 2)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
          <input type="text" id="code3" oninput="handleInput(event, 3)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
          <input type="text" id="code4" oninput="handleInput(event, 4)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
          <input type="text" id="code5" oninput="handleInput(event, 5)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
          <input type="text" id="code6" oninput="handleInput(event, 6)" maxlength="1" class="code-input w-1/6 h-12 text-center text-xl input input-bordered input-accent text-black bg-white" name="code[]" required />
        </div>

        <div class="flex justify-center mt-5">
          <button class="btn btn-success w-2/4 text-white">Verify Code</button>
        </div>
      </form>

      <div class="flex justify-center my-5 flex-col text-center">
        <p class="text-black">Didn't receive the code?</p>
        <a href="resend-reset-code.php" class="text-blue-500">Resend Code</a>
      </div>
    </div>    
  </div>

  <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script>
    const inputs = document.querySelectorAll('input[name="code[]"]');
    
    inputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 1);
            
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace') {
                if (this.value === '') {
                    if (index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                        e.preventDefault();
                    }
                } else {
                    this.value = '';
                }
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = e.clipboardData.getData('text');
            const numbers = pastedText.replace(/[^0-9]/g, '');
            
            for (let i = 0; i < numbers.length && i + index < inputs.length; i++) {
                inputs[i + index].value = numbers[i];
                if (i + index < inputs.length - 1) {
                    inputs[i + index + 1].focus();
                }
            }
        });
    });

    window.addEventListener('load', function() {
        inputs[0].focus();
    });
  </script>

</body>
</html>