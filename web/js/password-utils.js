/**
 * Password utilities for user forms
 */
class PasswordUtils {
    /**
     * Generate random password
     * @param {number} length - Password length
     * @returns {string} Generated password
     */
    static generatePassword(length = 12) {
        const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
        let password = '';
        for (let i = 0; i < length; i++) {
            password += charset.charAt(Math.floor(Math.random() * charset.length));
        }
        return password;
    }

    /**
     * Initialize password field with generate and toggle buttons
     * @param {string} inputId - Password input ID
     * @param {string} generateBtnId - Generate button ID
     * @param {string} toggleBtnId - Toggle button ID
     */
    static initPasswordField(inputId, generateBtnId, toggleBtnId) {
        const passwordInput = $('#' + inputId);
        const generateBtn = $('#' + generateBtnId);
        const toggleBtn = $('#' + toggleBtnId);

        // Generate password button
        generateBtn.on('click', function() {
            const password = PasswordUtils.generatePassword();
            passwordInput.val(password);
            
            // Show password when generated
            passwordInput.attr('type', 'text');
            const icon = toggleBtn.find('i');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        });

        // Toggle password visibility
        toggleBtn.on('click', function() {
            const icon = $(this).find('i');
            
            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('bi-eye').addClass('bi-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('bi-eye-slash').addClass('bi-eye');
            }
        });
    }
}

// Auto-initialize common password fields when document is ready
$(document).ready(function() {
    // For create user form
    if ($('#password-input').length) {
        PasswordUtils.initPasswordField('password-input', 'generate-password-btn', 'toggle-password-btn');
    }
    
    // For change password form
    if ($('#new-password-input').length) {
        PasswordUtils.initPasswordField('new-password-input', 'generate-new-password-btn', 'toggle-new-password-btn');
    }
});
