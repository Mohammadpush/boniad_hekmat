// Dynamic form functionality for adding/removing inputs
document.addEventListener('DOMContentLoaded', function() {
    // Handle dynamic about/skills input fields
    const aboutContainer = document.getElementById('about-container');
    
    if (aboutContainer) {
        aboutContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('add-about')) {
                const newInput = document.createElement('div');
                newInput.className = 'about-input-group flex flex-row-reverse';
                newInput.innerHTML = `
                    <input type="text" name="abouts[]" placeholder="تخصص"
                           class="flex-1 px-3 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" class="remove-about px-3 py-2 bg-red-500 text-white rounded-r-md hover:bg-red-600 transition">
                        -
                    </button>
                `;
                aboutContainer.appendChild(newInput);
            }
            
            if (e.target.classList.contains('remove-about')) {
                e.target.closest('.about-input-group').remove();
            }
        });
    }
});
