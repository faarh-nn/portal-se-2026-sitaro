import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Navbar scroll behavior
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.getElementById('navbar');

    if (navbar) {
        const handleScroll = () => {
            if (window.scrollY > 20) {
                navbar.classList.add('navbar--scrolled');
            } else {
                navbar.classList.remove('navbar--scrolled');
            }
        };

        window.addEventListener('scroll', handleScroll);
        handleScroll();
    }
});

Alpine.start();