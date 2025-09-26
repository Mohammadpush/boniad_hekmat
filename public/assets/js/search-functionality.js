// Search functionality for cards and tables
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        // For card-based layouts (myrequests page)
        const cardContainers = document.querySelectorAll('.card-hover');

        // For dashboard pages (regular table rows)
        const dashboardRows = document.querySelectorAll('table.min-w-full tbody tr');

        // For accepts pages (every other row due to progress bars)
        const acceptRows = document.querySelectorAll('table.min-w-full tbody tr:nth-child(odd)');

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.trim().toLowerCase();

            // If we have cards, search through them
            if (cardContainers.length > 0) {
                cardContainers.forEach(cardContainer => {
                    // Get name from card (h3 element)
                    const nameElement = cardContainer.querySelector('h3')?.textContent?.toLowerCase() || '';

                    // Get grade from card (p element that contains "پایه:")
                    const gradeElement = cardContainer.querySelector('p')?.textContent?.toLowerCase() || '';

                    // Get status from badge (.status-badge element)
                    const statusElement = cardContainer.querySelector('.status-badge')?.textContent?.toLowerCase() || '';

                    // Check for matches in card content
                    const matches = nameElement.includes(searchTerm) ||
                                   gradeElement.includes(searchTerm) ||
                                   statusElement.includes(searchTerm);

                    cardContainer.style.display = matches ? '' : 'none';
                });
            } else {
                // Original table search logic
                const rows = acceptRows.length > 0 ? acceptRows : dashboardRows;

                rows.forEach(row => {
                    // Get text content from different cell positions based on table structure
                    const nameCell = row.querySelector('td:nth-child(2)')?.textContent?.toLowerCase() || '';
                    const usernameCell = row.querySelector('td:nth-child(3)')?.textContent?.toLowerCase() || '';
                    const mobileCell = row.querySelector('td:nth-child(4)')?.textContent?.toLowerCase() || '';
                    const nationalcodeCell = row.querySelector('td:nth-child(3)')?.textContent?.toLowerCase() || '';

                    // Check for matches in any of the cells
                    const matches = nameCell.includes(searchTerm) ||
                                   usernameCell.includes(searchTerm) ||
                                   mobileCell.includes(searchTerm) ||
                                   nationalcodeCell.includes(searchTerm);

                    row.style.display = matches ? '' : 'none';
                });
            }
        });
    }
});
