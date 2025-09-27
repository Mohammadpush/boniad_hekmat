 // Scholarship Modal Functions
    function openScholarshipModal(requestId) {
        const modal = document.getElementById('scholarshipModal');
        const content = document.getElementById('modalContent');

        document.getElementById('modalRequestId').value = requestId;
        document.getElementById('scholarshipForm').action = '{{ route("unified.storemessage", ":id") }}'.replace(':id', requestId);

        modal.classList.remove('hidden');

        // Animation
        setTimeout(() => {
            content.style.transform = 'scale(1)';
            content.style.opacity = '1';
        }, 10);
    }

    function closeScholarshipModal() {
        const modal = document.getElementById('scholarshipModal');
        const content = document.getElementById('modalContent');

        content.style.transform = 'scale(0.95)';
        content.style.opacity = '0';

        setTimeout(() => {
            modal.classList.add('hidden');
            // Reset form
            document.getElementById('scholarshipForm').reset();
        }, 300);
    }

    // Close modal when clicking outside
    document.getElementById('scholarshipModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeScholarshipModal();
        }
    });

    // Form submission with success popup
    document.getElementById('scholarshipForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const actionUrl = this.action;

        // Show loading on submit button
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>در حال پردازش...';
        submitBtn.disabled = true;

        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
            }
        })
        .then(response => {
            if (response.ok) {
                return response.text();
            }
            throw new Error('Network response was not ok');
        })
        .then(data => {
            closeScholarshipModal();
            showSuccessPopup('بورسیه با موفقیت تعیین شد');

            // Reload page after success
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        })
        .catch(error => {
            console.error('Error:', error);
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            // Show error message
            alert('خطا در ارسال اطلاعات. لطفاً دوباره تلاش کنید.');
        });
    });
