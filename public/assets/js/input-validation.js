// Numeric input validation and formatting
document.addEventListener('DOMContentLoaded', function() {
    // Handle numeric inputs (phone numbers, national codes, etc.)
    const numInputs = document.querySelectorAll('.numinput');
    
    numInputs.forEach(input => {
        // Prevent non-numeric paste
        input.addEventListener('paste', function(e) {
            const pastedData = e.clipboardData.getData('text');
            if (!/^\d+$/.test(pastedData)) {
                e.preventDefault();
            }
        });

        // Filter out non-numeric characters on input
        input.addEventListener('input', function(e) {
            const value = e.target.value;
            if (!/^\d*$/.test(value)) {
                e.target.value = value.replace(/\D/g, '');
            }
        });
    });
    
    // Handle price/amount inputs with comma formatting
    const amountInput = document.getElementById("price");
    if (amountInput) {
        amountInput.addEventListener("input", function(e) {
            let value = this.value.replace(/[^0-9]/g, "");
            if (value.length > 3) {
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }
            this.value = value;
        });
        
        amountInput.addEventListener("paste", function(e) {
            const pastedData = e.clipboardData.getData("text");
            if (!/^\d+$/.test(pastedData)) {
                e.preventDefault();
            }
        });
        
        // Remove commas before form submission
        const form = document.getElementById("form");
        if (form) {
            form.addEventListener("submit", function (e) {
                amountInput.value = amountInput.value.replace(/,/g, "");
            });
        }
    }
});
