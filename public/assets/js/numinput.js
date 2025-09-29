const numInputs = document.querySelectorAll('.num-input'); // یا '.num-input' اگر از کلاس استفاده می‌کنید

numInputs.forEach(numInput => {
    // بررسی هنگام تایپ
    numInput.addEventListener("input", function(e) {
        const value = e.target.value;
        // فقط اعداد (اعشاری و منفی هم مجاز هستند)
        if (!/^-?\d*\.?\d*$/.test(value) && value !== "") {
            e.target.value = e.target.value.replace(/[^0-9.-]/g, "");
        }
    });

    // بررسی هنگام پیست کردن
    numInput.addEventListener("paste", function(e) {
        e.preventDefault(); // جلوگیری از پیست پیش‌فرض
        const pastedData = e.clipboardData?.getData("text") || "";
        // فقط اعداد (اعشاری و منفی هم مجاز هستند)
        if (/^-?\d*\.?\d*$/.test(pastedData)) {
            numInput.value = pastedData;
        }
    });
});
