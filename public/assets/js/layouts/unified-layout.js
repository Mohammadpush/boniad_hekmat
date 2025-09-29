/**
 * اسکریپت Layout اصلی
 */

class UnifiedLayoutManager {
    constructor() {
        this.sidebar = null;
        this.navbarToggle = null;
        this.navbarMenu = null;
        this.overlay = null;

        this.init();
    }

    init() {
        this.cacheElements();
        this.setupEventListeners();
        this.createMobileOverlay();
    }

    cacheElements() {
        this.navbarToggle = document.querySelector('.navbar-toggle');
        this.navbarMenu = document.querySelector('.navbar-menu');
        this.sidebar = document.querySelector('.layout-sidebar');
    }

    createMobileOverlay() {
        // Create overlay for mobile menu
        this.overlay = document.createElement('div');
        this.overlay.className = 'mobile-overlay';
        document.body.appendChild(this.overlay);

        // Close menu when clicking overlay
        this.overlay.addEventListener('click', () => {
            this.closeMobileMenu();
        });
    }

    setupEventListeners() {
        if (!this.navbarToggle || !this.navbarMenu) return;

        // Toggle menu با کلیک روی hamburger
        this.navbarToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleMobileMenu();
        });

        // Close menu when clicking on a link
        const menuLinks = this.navbarMenu.querySelectorAll('.lamp, a');
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                this.closeMobileMenu();
            });
        });

        // Close menu when window is resized above 658px
        window.addEventListener('resize', () => {
            if (window.innerWidth > 658) {
                this.closeMobileMenu();
            }
        });

        // Close menu with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isMobileMenuOpen()) {
                this.closeMobileMenu();
            }
        });

        // Handle body scroll when menu is open
        this.handleBodyScroll();
    }

    toggleMobileMenu() {
        const isOpen = this.isMobileMenuOpen();

        if (isOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    }

    openMobileMenu() {
        this.navbarToggle.classList.add('active');
        this.navbarMenu.classList.add('active');
        this.overlay.classList.add('active');

        // Prevent body scroll on mobile
        if (window.innerWidth <= 658) {
            document.body.style.overflow = 'hidden';
        }

        // Focus first menu item for accessibility
        const firstMenuItem = this.navbarMenu.querySelector('a, button');
        if (firstMenuItem) {
            setTimeout(() => firstMenuItem.focus(), 300);
        }
    }

    closeMobileMenu() {
        this.navbarToggle?.classList.remove('active');
        this.navbarMenu?.classList.remove('active');
        this.overlay?.classList.remove('active');

        // Restore body scroll
        document.body.style.overflow = '';
    }

    isMobileMenuOpen() {
        return this.navbarToggle?.classList.contains('active') || false;
    }

    handleBodyScroll() {
        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;
            const header = document.querySelector('.layout-header');

            if (!header) return;

            // Hide/show header on scroll (mobile only)
            if (window.innerWidth <= 658) {
                if (currentScrollY > lastScrollY && currentScrollY > 100) {
                    // Scrolling down
                    header.style.transform = 'translateY(-100%)';
                } else {
                    // Scrolling up
                    header.style.transform = 'translateY(0)';
                }
            } else {
                header.style.transform = 'translateY(0)';
            }

            lastScrollY = currentScrollY;
        });
    }

    // Public methods for external use
    getSidebarState() {
        return {
            isOpen: this.isMobileMenuOpen(),
            isMobile: window.innerWidth <= 658
        };
    }

    // برای سازگاری با کدهای قدیمی
    toggle() {
        this.toggleMobileMenu();
    }

    close() {
        this.closeMobileMenu();
    }

    open() {
        this.openMobileMenu();
    }
}

// Initialize layout manager
let layoutManager;

document.addEventListener('DOMContentLoaded', function() {
    layoutManager = new UnifiedLayoutManager();
});

// Export for use in other modules
if (typeof window !== 'undefined') {
    window.UnifiedLayoutManager = UnifiedLayoutManager;
    window.getLayoutManager = () => layoutManager;
}
