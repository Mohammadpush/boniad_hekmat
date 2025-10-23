// این صفحه مخصوص درخواست ها می باشد
const countinars = document.querySelectorAll('[id^="cardcontainar-"]');

countinars.forEach((container) => {
    const containerId = container.id.replace('cardcontainar-', '');
    const messageBtn = document.getElementById('messageBtn-' + containerId);
    container.addEventListener('mouseenter', () => {
        console.log('Container ID:', containerId);
        messageBtn.classList.remove('opacity-0', 'invisible');
        messageBtn.classList.add('opacity-100');
    });
    container.addEventListener('mouseleave', () => {
        messageBtn.classList.remove('opacity-100');
        messageBtn.classList.add('opacity-0', 'invisible');
    });
});
