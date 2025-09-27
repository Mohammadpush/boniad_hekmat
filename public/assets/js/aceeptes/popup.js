    // Success Popup Functions
    function showSuccessPopup(message = 'عملیات با موفقیت انجام شد') {
        const popup = document.getElementById('successPopup');
        const content = document.getElementById('successContent');
        const messageEl = document.getElementById('successMessage');

        messageEl.textContent = message;
        popup.classList.remove('hidden');

        // Animation
        setTimeout(() => {
            content.style.transform = 'scale(1)';
            content.style.opacity = '1';
        }, 10);

        // Auto close after 3 seconds
        setTimeout(() => {
            closeSuccessPopup();
        }, 3000);
    }

    function closeSuccessPopup() {
        const popup = document.getElementById('successPopup');
        const content = document.getElementById('successContent');

        content.style.transform = 'scale(0.95)';
        content.style.opacity = '0';

        setTimeout(() => {
            popup.classList.add('hidden');
        }, 300);
    }

   