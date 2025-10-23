/* Combined External JavaScript for Boniad Hekmat Application */

// Search functionality for tables
function initializeTableSearch() {
    const searchInput = document.getElementById('searchInput');
    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('tbody tr');
        const i = null;
        tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchTerm)) {
                row.style.display = '';
                i++;
            } else {
                row.style.display = 'none';

            }
            if (i) {
                
            }
        });
    });
}

// Input validation for numeric fields
function initializeNumericInputs() {
    const numInputs = document.querySelectorAll('.numinput');

    numInputs.forEach(input => {
        // Prevent pasting
        input.addEventListener('paste', function(e) {
            e.preventDefault();
        });

        // Filter input characters
        input.addEventListener('input', function(e) {
            let value = e.target.value;

            // Only allow numbers and commas for price inputs
            if (e.target.name === 'price' || e.target.classList.contains('price-input')) {
                value = value.replace(/[^0-9,]/g, '');

                // Format with commas every 3 digits
                const parts = value.split(',').join('').split('');
                const formatted = [];
                for (let i = parts.length - 1, j = 0; i >= 0; i--, j++) {
                    if (j > 0 && j % 3 === 0) {
                        formatted.unshift(',');
                    }
                    formatted.unshift(parts[i]);
                }
                value = formatted.join('');
            } else {
                // For other numeric inputs, only allow numbers
                value = value.replace(/[^0-9]/g, '');
            }

            e.target.value = value;
        });
    });
}

// Popup notification system
function showPopup(message, type = 'success') {
    // Remove existing popup if any
    const existingPopup = document.querySelector('.popup');
    if (existingPopup) {
        existingPopup.remove();
    }

    // Create new popup
    const popup = document.createElement('div');
    popup.className = `popup ${type}`;
    popup.textContent = message;

    document.body.appendChild(popup);

    // Trigger show animation
    setTimeout(() => {
        popup.classList.add('show');
    }, 10);

    // Auto remove after 3 seconds
    setTimeout(() => {
        popup.classList.remove('show');
        setTimeout(() => {
            if (popup.parentNode) {
                popup.parentNode.removeChild(popup);
            }
        }, 300);
    }, 3000);
}

// Dynamic form functionality
function initializeDynamicForms() {
    // Add row functionality
    const addButtons = document.querySelectorAll('.add-row');
    addButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = document.querySelector(this.dataset.target);
            if (target) {
                const newRow = target.querySelector('tr').cloneNode(true);
                // Clear input values in new row
                newRow.querySelectorAll('input').forEach(input => input.value = '');
                target.appendChild(newRow);
            }
        });
    });

    // Remove row functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            const row = e.target.closest('tr');
            if (row && row.parentNode.children.length > 1) {
                row.remove();
            }
        }
    });
}

// Initialize all functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeTableSearch();
    initializeNumericInputs();
    initializeDynamicForms();
});

// Export functions for global use
window.showPopup = showPopup;
