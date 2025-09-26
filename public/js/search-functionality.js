document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Search Functionality Script Started');

    const searchInput = document.getElementById('searchInput');
    console.log('📝 Search Input Element:', searchInput);

    if (!searchInput) {
        console.error('❌ Search input element not found!');
        return;
    }

    searchInput.addEventListener('input', function() {
        const filter = this.value.toLowerCase().trim();
        console.log('🔤 Search Filter:', filter);

        const table = document.querySelector('table');
        console.log('📊 Table Element:', table);

        if (!table) {
            console.warn('⚠️ No table found to filter');
            return;
        }

        const rows = table.querySelectorAll('tbody tr');
        console.log('📋 Total Rows Found:', rows.length);

        let visibleCount = 0;

        rows.forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            let hasMatch = false;

            // Search in all cells
            cells.forEach(cell => {
                const cellText = cell.textContent.toLowerCase();
                if (cellText.includes(filter)) {
                    hasMatch = true;
                }
            });

            if (hasMatch || filter === '') {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        console.log(`👀 Visible Rows: ${visibleCount}/${rows.length}`);

        // Show/hide no results message
        let noResultsMsg = document.getElementById('noResultsMessage');
        if (visibleCount === 0 && filter !== '') {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('tr');
                noResultsMsg.id = 'noResultsMessage';
                noResultsMsg.innerHTML = '<td colspan="100%" class="text-center py-8 text-gray-500">هیچ نتیجه‌ای یافت نشد</td>';
                table.querySelector('tbody').appendChild(noResultsMsg);
                console.log('📄 No results message created');
            }
            noResultsMsg.style.display = '';
        } else if (noResultsMsg) {
            noResultsMsg.style.display = 'none';
        }
    });

    console.log('✅ Search Functionality Initialized Successfully!');
});
