// Popup functionality for modals and dialogs
document.addEventListener('DOMContentLoaded', function() {
    const openpopup = document.getElementById('openpopup');
    const popup = document.getElementById('popup');
    const closepopup = document.getElementById('closepopup');

    console.log('openpopup:', openpopup, 'popup:', popup, 'closepopup:', closepopup);

    // دیباگ اولیه
    if (popup) {
        console.log('Initial classes:', popup.classList.toString());
        console.log('Initial has hidden:', popup.classList.contains('hidden'));
        console.log('Initial hidden attr:', popup.hasAttribute('hidden'));
        if (popup.hasAttribute('hidden')) {
            popup.removeAttribute('hidden'); // force remove attribute
            console.log('Removed hidden attribute');
        }
    }

    if (closepopup && popup) {
        closepopup.addEventListener('click', function() {
            popup.classList.add('hidden');
            popup.style.display = 'none'; // force close
            console.log('Added hidden + force none');
        });
    }

    if (openpopup && popup) {
        openpopup.addEventListener('click', function() {
            console.log('Before remove - Classes:', popup.classList.toString()); // دیباگ قبل
            console.log('Before remove - Has hidden:', popup.classList.contains('hidden'));

            popup.classList.remove('hidden');
            popup.removeAttribute('hidden'); // extra: attribute رو هم remove کن

            // Force reflow/repaint (حل browser update issue)
            popup.offsetHeight; // trigger reflow

            popup.style.display = 'Block'; // force Tailwind layout (inset-0 flex)

            console.log('After remove - Classes:', popup.classList.toString()); // دیباگ بعد
            console.log('After remove - Has hidden:', popup.classList.contains('hidden'));
            console.log('After remove - Computed display:', window.getComputedStyle(popup).display);
        });
    } else {
        console.error('عناصر پیدا نشد!');
    }

    // Close on outside click
    if (popup) {
        popup.addEventListener('click', function(e) {
            if (e.target === popup) {
                popup.classList.add('hidden');
                popup.style.display = 'none';
                console.log('Closed on outside');
            }
        });
    }
});

// بقیه توابع (copyText و showPopup) بدون تغییر بمونن
