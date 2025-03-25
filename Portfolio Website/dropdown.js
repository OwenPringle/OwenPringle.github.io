//script for adding dropdown functionality
function toggleDropdown() {
    const userMenu = document.querySelector('.user-menu');
    userMenu.classList.toggle('active');
}

document.addEventListener('click', (event) => {
    const userMenu = document.querySelector('.user-menu');
    if (!userMenu.contains(event.target)) {
        userMenu.classList.remove('active');
    }
});