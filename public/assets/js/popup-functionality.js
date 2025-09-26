// Popup functionality for modals and dialogs
document.addEventListener('DOMContentLoaded', function() {
    // Handle popup open/close functionality
    const openpopups = document.querySelectorAll('[id*="openpopup"]');
    const popup = document.getElementById('popup');
    const closepopup = document.getElementById('closepopup');
    
    if (closepopup && popup) {
        closepopup.addEventListener('click', function() {
            popup.classList.add('hidden');
        });
    }
    
    if (openpopups.length > 0 && popup) {
        openpopups.forEach(openpopup => {
            openpopup.addEventListener('click', function() {
                popup.classList.remove('hidden');
            });
        });
    }
    
    // Close popup when clicking outside
    if (popup) {
        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                popup.classList.add('hidden');
            }
        });
    }
});

// Copy text functionality
function copyText(text) {
    navigator.clipboard.writeText(text)
        .then(() => {
            showPopup('متن با موفقیت کپی شد!', 'success');
        })
        .catch(err => {
            showPopup('خطا در کپی کردن متن!', 'error');
            console.error('خطا در کپی: ', err);
        });
}

// Show notification popup
function showPopup(message, type) {
    const popup = document.createElement('div');
    popup.className = `popup ${type}`;
    popup.innerText = message;
    document.body.appendChild(popup);
    
    setTimeout(() => {
        popup.classList.add('show');
        setTimeout(() => {
            popup.classList.remove('show');
            setTimeout(() => {
                popup.remove();
            }, 300);
        }, 2000);
    }, 10);
}
