document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (event) => {
            const identifier = document.getElementById('identifier');
            const password = document.getElementById('password');
            let valid = true;
            const invalidEmail = identifier.value.includes('@')
                && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(identifier.value);
            if (identifier.value.trim() === '' || password.value === '') {
                valid = false;
            } else if (invalidEmail) {
                valid = false;
            }
            identifier.setCustomValidity(invalidEmail ? 'Format email tidak valid.' : '');
            identifier.classList.toggle('is-invalid', identifier.value.trim() === '' || invalidEmail);
            const identifierFeedback = identifier.parentElement.querySelector('.invalid-feedback');
            if (identifierFeedback) {
                identifierFeedback.textContent = invalidEmail ? 'Format email tidak valid.' : 'Masukkan email atau username.';
            }
            password.classList.toggle('is-invalid', password.value === '');
            if (!valid) event.preventDefault();
        });
    }

    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        const password = document.getElementById('password');
        const confirmation = document.getElementById('password_confirmation');
        const validateConfirmation = () => {
            const passwordsMismatch = confirmation.value !== '' && password.value !== confirmation.value;
            confirmation.setCustomValidity(passwordsMismatch ? 'Konfirmasi kata sandi tidak sama.' : '');
        };

        password.addEventListener('input', validateConfirmation);
        confirmation.addEventListener('input', validateConfirmation);
        registerForm.addEventListener('submit', (event) => {
            validateConfirmation();
            if (!registerForm.checkValidity()) {
                event.preventDefault();
                registerForm.classList.add('was-validated');
            }
        });
    }
});
