<div class="terms-container">
    <h2>Terms and Conditions</h2>
    
    <div class="terms-box">
        <h3>1. Acceptance of Terms</h3>
        <p>By accessing or using the FundiPopote platform, you agree to be bound by these Terms and Conditions. If you do not agree, please do not use our services.</p>

        <h3>2. Service Usage</h3>
        <p>FundiPopote connects customers with skilled technicians. We act as an intermediary platform and are not responsible for the direct execution of services provided by third-party technicians. Users must ensure safe and professional conduct during interactions.</p>

        <h3>3. User Responsibilities</h3>
        <p>You agree to provide accurate information during registration and maintain the confidentiality of your account. You are prohibited from using the system for any illegal activities or fraudulent bookings.</p>

        <h3>4. Payments and Refunds</h3>
        <p>All payments made through the platform are subject to our payment policy. Service fees are non-refundable once the work has been commenced by the technician, unless otherwise stated in our specific refund policy.</p>

        <h3>5. Limitation of Liability</h3>
        <p>FundiPopote shall not be held liable for any damages, losses, or disputes arising from the performance of services by technicians. We encourage users to verify technician credentials before booking.</p>

        <h3>6. Privacy Policy</h3>
        <p>Your personal data is handled in accordance with our Privacy Policy. By using our service, you consent to the collection and use of your data as described therein.</p>
    </div>

    <form action="{{ route('terms.accept') }}" method="POST">
        @csrf
        <input type="hidden" name="accept" value="1">
        
        <button type="submit" class="btn-agree">I Agree</button>
    </form>
</div>

<style>
    .terms-container { max-width: 600px; margin: 40px auto; padding: 20px; font-family: sans-serif; }
    .terms-box { 
        height: 300px; 
        overflow-y: scroll; 
        border: 1px solid #ddd; 
        padding: 20px; 
        margin-bottom: 20px; 
        background: #f9f9f9;
        border-radius: 8px;
    }
    .btn-agree { 
        width: 100%; 
        padding: 15px; 
        background-color: #28a745; 
        color: white; 
        border: none; 
        border-radius: 5px; 
        font-size: 18px; 
        cursor: pointer; 
    }
    .btn-agree:hover { background-color: #218838; }
</style>