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

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenuBtn.classList.toggle('is-active');
            mobileMenu.classList.toggle('is-active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navbar.contains(e.target) && mobileMenu.classList.contains('is-active')) {
                mobileMenuBtn.classList.remove('is-active');
                mobileMenu.classList.remove('is-active');
            }
        });

        // Close menu when pressing Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileMenu.classList.contains('is-active')) {
                mobileMenuBtn.classList.remove('is-active');
                mobileMenu.classList.remove('is-active');
            }
        });
    }
});

Alpine.start();