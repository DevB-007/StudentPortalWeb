document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('regForm');
    const name = document.getElementById('fullName');
    const email = document.getElementById('email');
    const mobile = document.getElementById('mobile');
    const course = document.getElementById('course');
    const year = document.getElementById('year');
    const password = document.getElementById('password');
    const confirmPass = document.getElementById('confirmPassword');
    const terms = document.getElementById('terms');
    const captchaInput = document.getElementById('captchaInput');
    const meter = document.getElementById('strengthMeter');

    let captchaValue = '';

    const patterns = {
        name: /^[A-Za-z\s]{3,50}$/,
        email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        mobile: /^[0-9]{10}$/
    };

    function generateCaptcha() {
        const canvas = document.getElementById('captchaCanvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        captchaValue = '';
        for (let i = 0; i < 6; i++) {
            captchaValue += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        ctx.font = '24px Arial';
        ctx.fillStyle = document.body.classList.contains('dark-mode') ? '#e0e0e0' : '#333333';
        ctx.fillText(captchaValue, 25, 35);

        for (let i = 0; i < 5; i++) {
            ctx.beginPath();
            ctx.moveTo(Math.random() * canvas.width, Math.random() * canvas.height);
            ctx.lineTo(Math.random() * canvas.width, Math.random() * canvas.height);
            ctx.strokeStyle = '#888888';
            ctx.stroke();
        }
    }

    function showError(element, msgId, message) {
        element.classList.add('input-error');
        element.classList.remove('input-success');
        const errorSpan = document.getElementById(msgId);
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.style.display = 'block';
        }
    }

    function showSuccess(element, msgId) {
        element.classList.remove('input-error');
        element.classList.add('input-success');
        const errorSpan = document.getElementById(msgId);
        if (errorSpan) {
            errorSpan.textContent = '';
            errorSpan.style.display = 'none';
        }
    }

    function validateField(element, pattern, msgId, errorMsg) {
        if (!pattern.test(element.value.trim())) {
            showError(element, msgId, errorMsg);
            return false;
        }
        showSuccess(element, msgId);
        return true;
    }

    function checkPasswordStrength(val) {
        let strength = 0;
        if (val.length >= 8) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;

        if (meter) {
            switch (strength) {
                case 0: meter.style.width = '0%'; break;
                case 1: meter.style.width = '25%'; meter.style.backgroundColor = '#d32f2f'; break;
                case 2: meter.style.width = '50%'; meter.style.backgroundColor = '#ff9800'; break;
                case 3: meter.style.width = '75%'; meter.style.backgroundColor = '#fbc02d'; break;
                case 4: meter.style.width = '100%'; meter.style.backgroundColor = '#388e3c'; break;
            }
        }
        return strength >= 3;
    }

    if (name) name.addEventListener('keyup', () => validateField(name, patterns.name, 'nameErr', 'Enter a valid full name'));
    if (email) email.addEventListener('keyup', () => validateField(email, patterns.email, 'emailErr', 'Enter a valid email address'));
    if (mobile) mobile.addEventListener('keyup', () => validateField(mobile, patterns.mobile, 'mobileErr', 'Enter a 10-digit mobile number'));

    if (password) {
        password.addEventListener('keyup', () => {
            const isStrong = checkPasswordStrength(password.value);
            if (!isStrong) {
                showError(password, 'passErr', 'Password must be 8+ chars with a number and uppercase letter');
            } else {
                showSuccess(password, 'passErr');
            }
            if (confirmPass && confirmPass.value) {
                if (password.value !== confirmPass.value) {
                    showError(confirmPass, 'confPassErr', 'Passwords do not match');
                } else {
                    showSuccess(confirmPass, 'confPassErr');
                }
            }
        });
    }

    if (confirmPass) {
        confirmPass.addEventListener('keyup', () => {
            if (password.value !== confirmPass.value || confirmPass.value === '') {
                showError(confirmPass, 'confPassErr', 'Passwords do not match');
            } else {
                showSuccess(confirmPass, 'confPassErr');
            }
        });
    }

    const captchaCanvas = document.getElementById('captchaCanvas');
    if (captchaCanvas) {
        captchaCanvas.addEventListener('click', generateCaptcha);
        generateCaptcha();
    }

    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            let isValid = true;

            if (!validateField(name, patterns.name, 'nameErr', 'Enter a valid full name')) isValid = false;
            if (!validateField(email, patterns.email, 'emailErr', 'Enter a valid email address')) isValid = false;
            if (!validateField(mobile, patterns.mobile, 'mobileErr', 'Enter a 10-digit mobile number')) isValid = false;

            if (course.value === '') {
                showError(course, 'courseErr', 'Please select a course');
                isValid = false;
            } else {
                showSuccess(course, 'courseErr');
            }

            if (year.value === '') {
                showError(year, 'yearErr', 'Please select a year');
                isValid = false;
            } else {
                showSuccess(year, 'yearErr');
            }

            const gender = document.querySelector('input[name="gender"]:checked');
            if (!gender) {
                document.getElementById('genderErr').textContent = 'Please select your gender';
                document.getElementById('genderErr').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('genderErr').style.display = 'none';
            }

            const isPassStrong = checkPasswordStrength(password.value);
            if (!isPassStrong) {
                showError(password, 'passErr', 'Password is not strong enough');
                isValid = false;
            }

            if (password.value !== confirmPass.value || confirmPass.value === '') {
                showError(confirmPass, 'confPassErr', 'Passwords do not match');
                isValid = false;
            }

            console.log("Computer expects: " + captchaValue);
            console.log("You typed: " + captchaInput.value.trim().toUpperCase());

            if (captchaInput.value.trim().toUpperCase() !== captchaValue) {
                showError(captchaInput, 'captchaErr', 'Incorrect CAPTCHA');
                generateCaptcha();
                captchaInput.value = '';
                isValid = false;
            } else {
                showSuccess(captchaInput, 'captchaErr');
            }

            if (!terms.checked) {
                document.getElementById('termsErr').textContent = 'You must accept the terms';
                document.getElementById('termsErr').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('termsErr').style.display = 'none';
            }

            if (isValid) {
                form.submit();
            }
        });
    }
});