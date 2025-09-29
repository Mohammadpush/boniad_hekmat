// اسکریپت مخصوص صفحه افزودن پیام
document.addEventListener('DOMContentLoaded', function() {
    // Initialize auto-resize textarea
    initAutoResizeTextarea();

    // Initialize form validation
    initFormValidation();

    // Initialize character counters
    initCharacterCounters();

    // Initialize price formatting
    initPriceFormatting();
});

function initAutoResizeTextarea() {
    const textarea = document.getElementById('description');
    if (!textarea) return;

    // Set initial height
    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';

    // Auto-resize on input
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 300) + 'px';
    });

    // Auto-resize on focus (for prefilled content)
    textarea.addEventListener('focus', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 300) + 'px';
    });
}

function initFormValidation() {
    const form = document.querySelector('form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate required fields
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                showFieldError(field, 'این فیلد الزامی است.');
            } else {
                clearFieldError(field);
            }
        });

        // Validate description length
        const description = document.getElementById('description');
        if (description && description.value.trim().length < 10) {
            isValid = false;
            showFieldError(description, 'متن پیام باید حداقل 10 کاراکتر باشد.');
        }

        // Validate price (if provided)
        const price = document.getElementById('price');
        if (price && price.value && (isNaN(price.value) || price.value < 0)) {
            isValid = false;
            showFieldError(price, 'مبلغ وارد شده معتبر نیست.');
        }

        if (!isValid) {
            e.preventDefault();
            // Focus on first error field
            const firstError = form.querySelector('.border-red-500');
            if (firstError) {
                firstError.focus();
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Real-time validation
    const inputs = form.querySelectorAll('input, textarea, select');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });

        input.addEventListener('input', function() {
            if (this.classList.contains('border-red-500')) {
                validateField(this);
            }
        });
    });
}

function validateField(field) {
    let isValid = true;

    // Check required fields
    if (field.hasAttribute('required') && !field.value.trim()) {
        isValid = false;
        showFieldError(field, 'این فیلد الزامی است.');
    }
    // Special validation for description
    else if (field.id === 'description' && field.value.trim().length > 0 && field.value.trim().length < 10) {
        isValid = false;
        showFieldError(field, 'متن پیام باید حداقل 10 کاراکتر باشد.');
    }
    // Special validation for price
    else if (field.id === 'price' && field.value && (isNaN(field.value) || field.value < 0)) {
        isValid = false;
        showFieldError(field, 'مبلغ وارد شده معتبر نیست.');
    }
    else {
        clearFieldError(field);
    }

    return isValid;
}

function showFieldError(field, message) {
    // Add error styling
    field.classList.add('border-red-500', 'bg-red-50');
    field.classList.remove('border-gray-300', 'bg-gray-50');

    // Remove existing error message
    const existingError = field.parentNode.querySelector('.field-error-message');
    if (existingError) {
        existingError.remove();
    }

    // Add error message
    const errorDiv = document.createElement('p');
    errorDiv.className = 'field-error-message text-red-500 text-sm mt-1';
    errorDiv.textContent = message;
    field.parentNode.appendChild(errorDiv);
}

function clearFieldError(field) {
    // Remove error styling
    field.classList.remove('border-red-500', 'bg-red-50');
    field.classList.add('border-gray-300');

    // Remove error message
    const errorMessage = field.parentNode.querySelector('.field-error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

function initCharacterCounters() {
    const title = document.getElementById('title');
    const description = document.getElementById('description');

    if (title) {
        addCharacterCounter(title, 100);
    }

    if (description) {
        addCharacterCounter(description, 1000);
    }
}

function addCharacterCounter(field, maxLength) {
    const counter = document.createElement('div');
    counter.className = 'text-sm text-gray-500 mt-1 text-left';
    counter.id = field.id + '_counter';
    field.parentNode.appendChild(counter);

    function updateCounter() {
        const current = field.value.length;
        counter.textContent = `${current}/${maxLength}`;

        if (current > maxLength * 0.8) {
            counter.classList.remove('text-gray-500');
            counter.classList.add('text-yellow-600');
        }
        if (current > maxLength * 0.95) {
            counter.classList.remove('text-yellow-600');
            counter.classList.add('text-red-600');
        }
        if (current <= maxLength * 0.8) {
            counter.classList.remove('text-yellow-600', 'text-red-600');
            counter.classList.add('text-gray-500');
        }
    }

    field.addEventListener('input', updateCounter);
    updateCounter();
}

function initPriceFormatting() {
    const priceField = document.getElementById('price');
    if (!priceField) return;

    priceField.addEventListener('input', function(e) {
        // Remove non-numeric characters
        let value = e.target.value.replace(/\D/g, '');

        // Update the field value
        e.target.value = value;
    });

    priceField.addEventListener('blur', function(e) {
        if (e.target.value) {
            // Format number with commas for display
            const formatted = new Intl.NumberFormat('fa-IR').format(e.target.value);
            // You could show this in a helper text if needed
        }
    });
}

// Helper functions
function showSuccessMessage(message) {
    const alert = document.createElement('div');
    alert.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    alert.textContent = message;

    document.body.appendChild(alert);

    setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(alert);
        }, 300);
    }, 3000);
}

function showErrorMessage(message) {
    const alert = document.createElement('div');
    alert.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    alert.textContent = message;

    document.body.appendChild(alert);

    setTimeout(() => {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(100%)';
        setTimeout(() => {
            document.body.removeChild(alert);
        }, 300);
    }, 3000);
}
