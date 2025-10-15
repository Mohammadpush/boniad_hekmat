    // Siblings count and rank relationship
    const siblingsCount = document.getElementById('siblings_count');
    const siblingsRank = document.getElementById('siblings_rank');
    const siblingsIconsContainer = document.getElementById('siblings-icons-container');

    if (siblingsCount && siblingsRank && siblingsIconsContainer) {
        // Function to update siblings rank icons
        window.updateSiblingsRank = function() {
            const count = parseInt(siblingsCount.value);

            // Clear previous icons
            siblingsIconsContainer.innerHTML = '';

            if (!count || count < 1 || count > 20) {
                // If no valid count, show message
                siblingsIconsContainer.innerHTML = '<span class="text-gray-400 text-sm">ابتدا تعداد فرزندان را وارد کنید</span>';
                siblingsRank.value = '';
                return;
            }

            // Generate person icons based on siblings count
            for (let i = 1; i <= count; i++) {
                const iconWrapper = document.createElement('div');
                iconWrapper.className = 'w-14 h-14 p-2 border-2 border-gray-300 rounded-xl cursor-pointer transition-all duration-200 hover:border-blue-400 hover:bg-blue-50 flex items-center justify-center group';
                iconWrapper.dataset.rank = i;

                const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                icon.setAttribute('fill', 'none');
                icon.setAttribute('viewBox', '0 0 24 24');
                icon.setAttribute('stroke-width', '1.5');
                icon.setAttribute('stroke', 'currentColor');
                icon.className = 'w-8 h-8 text-gray-500 group-hover:text-blue-600 transition-colors duration-200';

                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                path.setAttribute('d', 'M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z');

                icon.appendChild(path);
                iconWrapper.appendChild(icon);

                // Add rank number below icon
                const rankNumber = document.createElement('div');
                rankNumber.className = 'absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-gray-200 text-gray-600 rounded-full w-6 h-6 flex items-center justify-center text-xs font-medium transition-all duration-200';
                rankNumber.textContent = i;
                iconWrapper.style.position = 'relative';
                iconWrapper.appendChild(rankNumber);

                // Add click event
                iconWrapper.addEventListener('click', function() {
                    // Reset all icons to outline
                    siblingsIconsContainer.querySelectorAll('[data-rank]').forEach(wrapper => {
                        wrapper.className = 'w-14 h-14 p-2 border-2 border-gray-300 rounded-xl cursor-pointer transition-all duration-200 hover:border-blue-400 hover:bg-blue-50 flex items-center justify-center group';
                        const svg = wrapper.querySelector('svg');
                        svg.setAttribute('fill', 'none');
                        svg.className = 'w-8 h-8 text-gray-500 group-hover:text-blue-600 transition-colors duration-200';
                        const numberDiv = wrapper.querySelector('div');
                        numberDiv.className = 'absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-gray-200 text-gray-600 rounded-full w-6 h-6 flex items-center justify-center text-xs font-medium transition-all duration-200';
                    });

                    // Set selected icon to solid
                    this.className = 'w-14 h-14 p-2 border-2 border-blue-500 rounded-xl cursor-pointer transition-all duration-200 bg-blue-100 flex items-center justify-center group';
                    const selectedSvg = this.querySelector('svg');
                    selectedSvg.setAttribute('fill', 'currentColor');
                    selectedSvg.className = 'w-8 h-8 text-blue-600 transition-colors duration-200';
                    const selectedNumber = this.querySelector('div');
                    selectedNumber.className = 'absolute -bottom-2 left-1/2 transform -translate-x-1/2 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-medium transition-all duration-200';

                    // Set hidden input value
                    siblingsRank.value = i;

                    // Clear any previous errors
                    clearFieldError('siblings_rank');

                    // Save form data
                    saveFormData();
                });

                siblingsIconsContainer.appendChild(iconWrapper);
            }

            // Clear any previous errors
            clearFieldError('siblings_rank');
        };

        // Listen for changes in siblings count
        siblingsCount.addEventListener('input', updateSiblingsRank);
        siblingsCount.addEventListener('change', updateSiblingsRank);

        // Initialize on page load if value exists
        if (siblingsCount.value) {
            updateSiblingsRank();
        }
    }
