    // Copy text function
    function copyText(text) {
        navigator.clipboard.writeText(text).then(function() {
            showSuccessPopup('شماره کارت کپی شد');
        }).catch(function() {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showSuccessPopup('شماره کارت کپی شد');
        });
    }