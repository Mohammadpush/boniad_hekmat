/**
 * سیستم چاپ فشرده و بهینه برای درخواست‌ها
 * هدف: چاپ تمام اطلاعات در کمترین فضای ممکن
 */

// تابع کمکی برای ایجاد ردیف جدول
function createInfoRow(label, value) {
    if (!value || value.trim() === '') return '';
    return `
        <div class="info-row">
            <span class="info-label">${label}:</span>
            <span class="info-value">${value}</span>
        </div>
    `;
}

function printRequest() {
    // دریافت اطلاعات از مودال
    const requestData = {
        // اطلاعات شخصی
        name: document.getElementById('modalNameDisplay')?.textContent?.trim() || '',
        nationalCode: document.getElementById('modalNationalCodeDisplay')?.textContent?.trim() || '',
        birthdate: document.getElementById('modalBirthdateDisplay')?.textContent?.trim() || '',
        phone: document.getElementById('modalPhoneDisplay')?.textContent?.trim() || '',
        telephone: document.getElementById('modalTelephoneDisplay')?.textContent?.trim() || '',

        // اطلاعات تحصیلی
        grade: document.getElementById('modalGradeDisplay')?.textContent?.trim() || '',
        school: document.getElementById('modalSchoolDisplay')?.textContent?.trim() || '',
        principal: document.getElementById('modalPrincipalDisplay')?.textContent?.trim() || '',
        major: document.getElementById('modalMajor')?.textContent?.trim() || '',
        gpa: document.getElementById('modalGPA')?.textContent?.trim() || '',

        // آدرس و اطلاعات تکمیلی
        address: document.getElementById('modalAddressDisplay')?.textContent?.trim() || '',

        // اطلاعات والدین
        fatherName: document.getElementById('modalFatherName')?.textContent?.trim() || '',
        fatherJob: document.getElementById('modalFatherJob')?.textContent?.trim() || '',
        fatherPhone: document.getElementById('modalFatherPhone')?.textContent?.trim() || '',
        fatherIncome: document.getElementById('modalFatherIncome')?.textContent?.trim() || '',
        motherName: document.getElementById('modalMotherName')?.textContent?.trim() || '',
        motherJob: document.getElementById('modalMotherJob')?.textContent?.trim() || '',
        motherPhone: document.getElementById('modalMotherPhone')?.textContent?.trim() || '',
        motherIncome: document.getElementById('modalMotherIncome')?.textContent?.trim() || '',

        // بخش انگیزه‌نامه و سوالات
        motivation: document.getElementById('modalMotivation')?.textContent?.trim() || '',
        spend: document.getElementById('modalSpend')?.textContent?.trim() || '',
        howAmI: document.getElementById('modalHowAmI')?.textContent?.trim() || '',
        future: document.getElementById('modalFuture')?.textContent?.trim() || '',
        favoriteMajor: document.getElementById('modalFavoriteMajor')?.textContent?.trim() || '',
        helpOthers: document.getElementById('modalHelpOthers')?.textContent?.trim() || '',
        suggestion: document.getElementById('modalSuggestion')?.textContent?.trim() || '',

        // وضعیت و تاریخ
        status: document.getElementById('modalStatusBadge')?.textContent?.trim() || '',

        // تصویر
        imgSrc: document.getElementById('modalProfileImg')?.src || '',

        // تاریخ ثبت - فرض بر این است که در جایی نمایش داده می‌شود
        createdAt: new Date().toLocaleDateString('fa-IR')
    };

    // ایجاد پنجره چاپ
    const printWindow = window.open('', '_blank', 'width=800,height=600');

    if (!printWindow) {
        alert('لطفاً مسدودسازی پنجره‌های بازشو را غیرفعال کنید');
        return;
    }

    // HTML فشرده برای چاپ
    const printHTML = `
<!DOCTYPE html>
<html dir="rtl" lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>چاپ درخواست - ${requestData.name}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Tahoma, Arial, sans-serif;
            font-size: 8pt;
            line-height: 1.2;
            color: #000;
            padding: 3mm;
        }

        /* بهینه‌سازی برای چاپ */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 5mm 8mm;
            }
        }

        /* هدر فشرده */
        .header {
            text-align: center;
            border-bottom: 1.5px solid #000;
            padding-bottom: 2mm;
            margin-bottom: 2mm;
        }

        .header h1 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 0.5mm;
        }

        .header .date {
            font-size: 7pt;
            color: #333;
        }

        /* لی‌اوت دو ستونه فشرده */
        .content {
            display: grid;
            grid-template-columns: 70px 1fr;
            gap: 3mm;
        }

        /* تصویر کوچک */
        .photo-section {
            text-align: center;
        }

        .photo {
            width: 65px;
            height: 65px;
            border: 0.5px solid #000;
            object-fit: cover;
            display: block;
        }

        .status-badge {
            font-size: 6pt;
            margin-top: 1.5mm;
            padding: 0.5mm 1.5mm;
            border: 0.5px solid #000;
            display: inline-block;
            font-weight: bold;
        }

        /* بخش‌های اطلاعاتی فشرده */
        .info-section {
            width: 100%;
        }

        .section {
            margin-bottom: 2mm;
            border: 0.5px solid #000;
            padding: 1.5mm;
        }

        .section-title {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 1mm;
            padding-bottom: 0.5mm;
            border-bottom: 0.5px solid #000;
            background: #e8e8e8;
            padding: 0.5mm 1.5mm;
            margin: -1.5mm -1.5mm 1mm -1.5mm;
        }

        /* جدول فشرده - دو ستونه برای استفاده بهتر از فضا */
        .info-table {
            width: 100%;
            font-size: 7pt;
            border-collapse: collapse;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5mm 2mm;
        }

        .info-row {
            display: flex;
            align-items: baseline;
            padding: 0.5mm 0;
            border-bottom: 0.5px dotted #ccc;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: bold;
            min-width: 40px;
            margin-left: 2mm;
            flex-shrink: 0;
        }

        .info-value {
            flex: 1;
            word-break: break-word;
        }

        /* آدرس به صورت کامل */
        .full-width {
            grid-column: 1 / -1;
        }

        .address-text {
            font-size: 7pt;
            line-height: 1.3;
            padding: 1mm;
            background: #f5f5f5;
        }

        /* فوتر */
        .footer {
            margin-top: 3mm;
            padding-top: 1.5mm;
            border-top: 0.5px solid #000;
            font-size: 6pt;
            text-align: center;
            color: #666;
        }

        /* دکمه چاپ */
        .print-btn {
            position: fixed;
            top: 10px;
            left: 10px;
            padding: 8px 16px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: Tahoma;
            font-size: 11pt;
            font-weight: bold;
            z-index: 1000;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .print-btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <!-- دکمه چاپ -->
    <button class="print-btn no-print" onclick="window.print()">🖨️ چاپ</button>

    <!-- هدر -->
    <div class="header">
        <h1>📋 فرم درخواست</h1>
        <div class="date">تاریخ ثبت: ${requestData.createdAt}</div>
    </div>

    <!-- محتوای اصلی -->
    <div class="content">
        <!-- ستون تصویر و وضعیت -->
        <div class="photo-section">
            ${requestData.imgSrc ? `<img src="${requestData.imgSrc}" alt="عکس" class="photo">` : '<div class="photo" style="display:flex;align-items:center;justify-content:center;background:#f0f0f0;">بدون عکس</div>'}
            <div class="status-badge">${requestData.status || 'در انتظار'}</div>
        </div>

        <!-- ستون اطلاعات -->
        <div class="info-section">
            <!-- بخش اطلاعات شخصی -->
            <div class="section">
                <div class="section-title">👤 اطلاعات شخصی</div>
                <div class="info-table">
                    ${createInfoRow('نام', requestData.name)}
                    ${createInfoRow('کد ملی', requestData.nationalCode)}
                    ${createInfoRow('تولد', requestData.birthdate)}
                    ${createInfoRow('موبایل', requestData.phone)}
                    ${requestData.telephone ? createInfoRow('تلفن', requestData.telephone) : ''}
                </div>
            </div>

            <!-- بخش اطلاعات تحصیلی -->
            <div class="section">
                <div class="section-title">🎓 اطلاعات تحصیلی</div>
                <div class="info-table">
                    ${createInfoRow('پایه', requestData.grade)}
                    ${requestData.major ? createInfoRow('رشته', requestData.major) : ''}
                    ${requestData.school ? createInfoRow('مدرسه', requestData.school) : ''}
                    ${requestData.principal ? createInfoRow('مدیر', requestData.principal) : ''}
                    ${requestData.gpa ? createInfoRow('معدل', requestData.gpa) : ''}
                </div>
            </div>

            <!-- بخش اطلاعات خانواده -->
            ${(requestData.fatherName || requestData.motherName) ? `
            <div class="section">
                <div class="section-title">👨‍👩‍👧 اطلاعات خانواده</div>
                <div class="info-table">
                    ${requestData.fatherName ? createInfoRow('پدر', requestData.fatherName) : ''}
                    ${requestData.fatherJob ? createInfoRow('شغل پدر', requestData.fatherJob) : ''}
                    ${requestData.fatherPhone ? createInfoRow('تلفن پدر', requestData.fatherPhone) : ''}
                    ${requestData.fatherIncome ? createInfoRow('درآمد پدر', requestData.fatherIncome) : ''}
                    ${requestData.motherName ? createInfoRow('مادر', requestData.motherName) : ''}
                    ${requestData.motherJob ? createInfoRow('شغل مادر', requestData.motherJob) : ''}
                    ${requestData.motherPhone ? createInfoRow('تلفن مادر', requestData.motherPhone) : ''}
                    ${requestData.motherIncome ? createInfoRow('درآمد مادر', requestData.motherIncome) : ''}
                </div>
            </div>
            ` : ''}

            <!-- آدرس -->
            ${requestData.address ? `
            <div class="section">
                <div class="section-title">📍 آدرس</div>
                <div class="address-text">${requestData.address}</div>
            </div>
            ` : ''}

            <!-- بخش سوالات و انگیزه‌ها -->
            ${(requestData.motivation || requestData.spend || requestData.howAmI || requestData.future || requestData.favoriteMajor || requestData.helpOthers) ? `
            <div class="section">
                <div class="section-title">💭 سوالات نهایی و انگیزه‌ها</div>
                <div style="display: flex; flex-direction: column; gap: 2mm;">
                    ${requestData.motivation ? `
                    <div style="border-bottom: 0.5px dotted #ccc; padding-bottom: 1mm; margin-bottom: 1mm;">
                        <div style="font-weight: bold; font-size: 7pt; margin-bottom: 0.5mm;">✍️ انگیزه درخواست بورسیه:</div>
                        <div style="font-size: 7pt; line-height: 1.4;">${requestData.motivation}</div>
                    </div>
                    ` : ''}
                    ${requestData.spend ? `
                    <div style="border-bottom: 0.5px dotted #ccc; padding-bottom: 1mm; margin-bottom: 1mm;">
                        <div style="font-weight: bold; font-size: 7pt; margin-bottom: 0.5mm;">💰 نحوه استفاده از کمک مالی:</div>
                        <div style="font-size: 7pt; line-height: 1.4;">${requestData.spend}</div>
                    </div>
                    ` : ''}
                    ${requestData.howAmI ? `
                    <div style="border-bottom: 0.5px dotted #ccc; padding-bottom: 1mm; margin-bottom: 1mm;">
                        <div style="font-weight: bold; font-size: 7pt; margin-bottom: 0.5mm;">🙋 معرفی خود:</div>
                        <div style="font-size: 7pt; line-height: 1.4;">${requestData.howAmI}</div>
                    </div>
                    ` : ''}
                    ${requestData.future ? `
                    <div style="border-bottom: 0.5px dotted #ccc; padding-bottom: 1mm; margin-bottom: 1mm;">
                        <div style="font-weight: bold; font-size: 7pt; margin-bottom: 0.5mm;">🎯 برنامه‌های آینده:</div>
                        <div style="font-size: 7pt; line-height: 1.4;">${requestData.future}</div>
                    </div>
                    ` : ''}
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2mm;">
                        ${requestData.favoriteMajor ? `
                        <div>
                            <span style="font-weight: bold; font-size: 7pt;">📚 رشته مورد علاقه:</span>
                            <span style="font-size: 7pt;">${requestData.favoriteMajor}</span>
                        </div>
                        ` : ''}
                        ${requestData.helpOthers ? `
                        <div>
                            <span style="font-weight: bold; font-size: 7pt;">🤝 آمادگی کمک:</span>
                            <span style="font-size: 7pt;">${requestData.helpOthers}</span>
                        </div>
                        ` : ''}
                    </div>
                    ${requestData.suggestion ? `
                    <div style="margin-top: 1mm; background: #fffbea; padding: 1.5mm; border: 0.5px solid #f59e0b; border-radius: 2mm;">
                        <div style="font-weight: bold; font-size: 7pt; margin-bottom: 0.5mm;">💡 پیشنهادات:</div>
                        <div style="font-size: 7pt; line-height: 1.4;">${requestData.suggestion}</div>
                    </div>
                    ` : ''}
                </div>
            </div>
            ` : ''}
        </div>
    </div>

    <!-- فوتر -->
    <div class="footer">
        چاپ شده از سیستم مدیریت درخواست‌ها | ${new Date().toLocaleDateString('fa-IR')} - ${new Date().toLocaleTimeString('fa-IR')}
    </div>
</body>
</html>
    `;

    // نوشتن محتوا در پنجره جدید
    printWindow.document.write(printHTML);
    printWindow.document.close();

    // فوکوس روی پنجره چاپ
    printWindow.focus();
}

// اتصال به دکمه چاپ
document.addEventListener('DOMContentLoaded', function() {
    const printBtn = document.getElementById('printRequestBtn');
    if (printBtn) {
        printBtn.addEventListener('click', printRequest);
    }
});
