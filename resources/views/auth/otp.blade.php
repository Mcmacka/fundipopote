<style>
    /* Hakikisha ukurasa mzima unachukua nafasi yote */
    html, body {
        height: 100%;
        margin: 0;
        display: flex;
        justify-content: center; /* Katikati mlalo */
        align-items: center;     /* Katikati wima */
        background-color: #f4f7f6; /* Rangi ya nyuma ya skrini */
    }

    /* Container kuu */
    .otp-container {
        width: 100%;
        max-width: 400px;
        padding: 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        font-family: Arial, sans-serif;
    }

    /* ... nyingine zote unabaki nazo kama zilivyo ... */
    .otp-input {
        width: 45px;
        height: 50px;
        font-size: 20px;
        border: 2px solid #ddd;
        border-radius: 8px;
        text-align: center;
        margin: 5px;
        outline: none;
    }

    .otp-input:focus { border-color: #007bff; }

    button {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
    }
    
    button:disabled { background-color: #cccccc; cursor: not-allowed; }
</style>
<div class="otp-container">
    <form action="{{ route('otp.verify') }}" method="POST" id="otp-form">
        @csrf
        <h2>OTP Verification</h2>
        <p>One Time Password (OTP) has been sent to <strong>{{ $email }}</strong>.</p>

        <div style="display: flex; justify-content: center; margin-bottom: 20px;">
            <input type="text" name="otp_1" maxlength="1" class="otp-input" required>
            <input type="text" name="otp_2" maxlength="1" class="otp-input" required>
            <input type="text" name="otp_3" maxlength="1" class="otp-input" required>
            <input type="text" name="otp_4" maxlength="1" class="otp-input" required>
            <input type="text" name="otp_5" maxlength="1" class="otp-input" required>
            <input type="text" name="otp_6" maxlength="1" class="otp-input" required>
        </div>

        <input type="hidden" name="otp" id="full-otp">

        <button type="submit">Verify OTP</button>

        <button type="button" id="resendBtn" onclick="resendOtp(event)">Resend OTP</button>
    </form>
</div>

<script>
    const inputs = document.querySelectorAll('.otp-input');
    const fullOtp = document.getElementById('full-otp');
    const resendBtn = document.getElementById('resendBtn');

    // Logic ya kuunganisha input
    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            if (e.target.value.length === 1 && index < 5) {
                inputs[index + 1].focus();
            }
            updateFullOtp();
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !input.value && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    function updateFullOtp() {
        let code = '';
        inputs.forEach(i => code += i.value);
        fullOtp.value = code;
    }

    // Logic ya Resend OTP na Timer
    function resendOtp(e) {
        e.preventDefault();
        
        resendBtn.disabled = true;
        let seconds = 60;
        
        let timer = setInterval(() => {
            seconds--;
            resendBtn.innerText = "wait (" + seconds + "s)";
            
            if (seconds <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                resendBtn.innerText = "Resend OTP";
            }
        }, 1000);

        fetch("{{ route('otp.resend') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                console.log('OTP imetumwa');
            } else {
                alert('Imeshindwa kutuma, jaribu tena.');
            }
        });
    }
</script>