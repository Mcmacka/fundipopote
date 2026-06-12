
<style>
    /* Container kuu inayozunguka fomu */
    .otp-container {
        max-width: 400px;
        margin: 50px auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
        font-family: Arial, sans-serif;
    }

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
</style>


    <div class="otp-container">
    <form action="{{ route('otp.verify') }}" method="POST" id="otp-form">
        @csrf
        <h2>OTP Verification</h2>
        <p>One Time Password (OTP) has been sent to <strong>{{ $email }}</strong>.</p>

        <div style="display: flex; justify-content: center; margin-bottom: 20px;">
            <input type="text" name="otp_1" maxlength="1" class="otp-input">
            <input type="text" name="otp_2" maxlength="1" class="otp-input">
            <input type="text" name="otp_3" maxlength="1" class="otp-input">
            <input type="text" name="otp_4" maxlength="1" class="otp-input">
            <input type="text" name="otp_5" maxlength="1" class="otp-input">
            <input type="text" name="otp_6" maxlength="1" class="otp-input">
        </div>

        <input type="hidden" name="otp" id="full-otp">

        <button type="submit">Verify OTP</button>
    </form>
</div>

<script>
    const inputs = document.querySelectorAll('.otp-input');
    const fullOtp = document.getElementById('full-otp');

    inputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            // Hamisha focus kwenye kisanduku kinachofuata
            if (e.target.value.length === 1 && index < 5) {
                inputs[index + 1].focus();
            }
            // Unganisha namba zote
            let code = '';
            inputs.forEach(i => code += i.value);
            fullOtp.value = code;
        });
    });
</script>