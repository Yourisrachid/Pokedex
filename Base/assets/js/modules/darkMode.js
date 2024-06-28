export function darkMode() {

const toggleDarkMode = document.querySelector('.toggle-darkmode');
const navbar = document.querySelector('.navbar');
const body = document.querySelector('body');

let darkMode = localStorage.getItem('darkMode') === 'true';

if (darkMode) {
    navbar.classList.add('dark');
    body.classList.add('dark');
    toggleDarkMode.textContent = '☀️';  
} else {
    navbar.classList.remove('dark');
    body.classList.remove('dark');
    toggleDarkMode.textContent = '🌙';
}

toggleDarkMode.addEventListener('click', () => {
    darkMode = !darkMode;
    if (darkMode) {
        navbar.classList.add('dark');
        body.classList.add('dark');
        toggleDarkMode.textContent = '☀️';
    } else {
        navbar.classList.remove('dark');
        body.classList.remove('dark');
        toggleDarkMode.textContent = '🌙';
    }

    localStorage.setItem('darkMode', darkMode);
});

}

