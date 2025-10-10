// Sidebar Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    const toggleIcon = document.getElementById('toggleIcon');
    const sidebarTitle = document.getElementById('sidebarTitle');
    const menuLabel = document.getElementById('menuLabel');
    const accountLabel = document.getElementById('accountLabel');
    const menuTexts = document.querySelectorAll('.menu-text');
    const menuLinks = document.querySelectorAll('nav a');

    // Check if small screen (< 1022px)
    let isSmallScreen = window.innerWidth < 1022;

    // Set active menu item
    setActiveMenuItem();

    applySidebarState(true, false); // collapsed, no animation

        // For small screens, maintain state but start collapsed
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        applySidebarState(isCollapsed, false); // keep saved state, no animation
        sidebar.style.transform = ''; // Don't hide sidebar


    // Toggle button click handler
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {

                // For small screens, use same logic as desktop but add overlay
                if (!document.body.classList.contains('sidebar-open')) {
                    document.body.classList.add('sidebar-open');
                    expandSidebar();
                    // Add overlay for mobile

                    console.log('sidebar is open');
                } else {
                    document.body.classList.remove('sidebar-open');
                    collapseSidebar();
                    // Remove overlay for mobile

                    console.log('sidebar is close');
                }


        });
    }

    function setActiveMenuItem() {
        const currentPath = window.location.pathname;

        menuLinks.forEach(link => {
            link.classList.remove('active');

            // Check if current URL matches link href
            const linkPath = new URL(link.href).pathname;
            if (currentPath === linkPath || (currentPath.includes(linkPath) && linkPath !== '/')) {
                link.classList.add('active');
            }
        });
    }

    function applySidebarState(collapsed, animate = true) {
        if (!animate) {
            sidebar.style.transition = 'none';
            if (sidebarTitle) sidebarTitle.style.transition = 'none';
            if (menuLabel) menuLabel.style.transition = 'none';
            if (accountLabel) accountLabel.style.transition = 'none';
            menuTexts.forEach(text => text.style.transition = 'none');
        }

        if (collapsed) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-16');

            // Use visibility/opacity instead of hidden class for labels
            if (sidebarTitle) sidebarTitle.classList.add('hidden');
            if (menuLabel) {
                menuLabel.style.opacity = '0';
                menuLabel.style.visibility = 'hidden';
            }
            if (accountLabel) {
                accountLabel.style.opacity = '0';
                accountLabel.style.visibility = 'hidden';
            }
            menuTexts.forEach(text => text.classList.add('hidden'));

            // Set collapsed icon
            if (toggleIcon) {
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>';
            }
        } else {
            sidebar.classList.remove('w-16');
            sidebar.classList.add('w-64');

            if (sidebarTitle) sidebarTitle.classList.remove('hidden');
            if (menuLabel) {
                menuLabel.style.opacity = '1';
                menuLabel.style.visibility = 'visible';
            }
            if (accountLabel) {
                accountLabel.style.opacity = '1';
                accountLabel.style.visibility = 'visible';
            }
            menuTexts.forEach(text => text.classList.remove('hidden'));

            // Set expanded icon
            if (toggleIcon) {
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>';
            }
        }

        if (!animate) {
            // Restore transitions after state is applied
            setTimeout(() => {
                sidebar.style.transition = '';
                if (sidebarTitle) sidebarTitle.style.transition = '';
                if (menuLabel) menuLabel.style.transition = '';
                if (accountLabel) accountLabel.style.transition = '';
                menuTexts.forEach(text => text.style.transition = '');
            }, 50);
        }
    }

    function collapseSidebar() {
        // Add collapsing class for immediate text hiding
        sidebar.classList.add('sidebar-collapsing');

        // Hide text elements immediately but maintain space for labels
        if (sidebarTitle) sidebarTitle.classList.add('hidden');
        if (menuLabel) {
            menuLabel.style.opacity = '0';
            menuLabel.style.visibility = 'hidden';
            console.log('menulabel find', menuLabel);
        }
        if (accountLabel) {
            accountLabel.style.opacity = '0';
            accountLabel.style.visibility = 'hidden';
        }
        menuTexts.forEach(text => text.classList.add('hidden'));

        // Change sidebar width after short delay
        setTimeout(() => {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-16');
            sidebar.classList.remove('sidebar-collapsing');
        }, 100);

        // Change icon - FIXED DIRECTION
        if (toggleIcon) {
            toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>';
        }

        // Save state (only for desktop)
        if (!isSmallScreen) {
            localStorage.setItem('sidebarCollapsed', 'true');
        }
    }

    function expandSidebar() {
        // Add expanding class
        sidebar.classList.add('sidebar-expanding');

        // Change width first
        sidebar.classList.remove('w-16');
        sidebar.classList.add('w-64');

        // Show text elements with delay
        if(document.body.classList.contains('sidebar-open'))
        {

            setTimeout(() => {
                if (document.body.classList.contains('sidebar-open')) {

                    if (sidebarTitle) sidebarTitle.classList.remove('hidden');
                    if (menuLabel) {
                        menuLabel.style.opacity = '1';
                        menuLabel.style.visibility = 'visible';
                        console.log('menu ldsfjl')
                    }
                    if (accountLabel) {
                        accountLabel.style.opacity = '1';
                        accountLabel.style.visibility = 'visible';
                    }
                    menuTexts.forEach(text => text.classList.remove('hidden'));

                    // Remove expanding class after animation
                    setTimeout(() => {
                        sidebar.classList.remove('sidebar-expanding');
                    }, 300);
                }
            }, 200);
        }

        // Change icon back - FIXED DIRECTION
        if (toggleIcon) {
            toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>';
        }

        // Save state (only for desktop)
        if (!isSmallScreen) {
            localStorage.setItem('sidebarCollapsed', 'false');
        }
    }

    // Handle window resize
    window.addEventListener('resize', function() {
        const newIsSmallScreen = window.innerWidth < 1022;
        if (newIsSmallScreen !== isSmallScreen) {
            const wasSmallScreen = isSmallScreen;
            isSmallScreen = newIsSmallScreen;

            // Save current visual state before changing
            const currentlyExpanded = sidebar.classList.contains('w-64');

            if (wasSmallScreen && !isSmallScreen) {
                // Going from mobile to desktop - remove mobile classes
                document.body.classList.remove('sidebar-open');
                sidebar.classList.remove('mobile-open');

                // Apply saved desktop state
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                applySidebarState(isCollapsed, false);
            } else if (!wasSmallScreen && isSmallScreen) {
                // Going from desktop to mobile - save current state
                localStorage.setItem('sidebarCollapsed', currentlyExpanded ? 'false' : 'true');

                // Apply same state but without overlay initially
                applySidebarState(!currentlyExpanded, false);
            }
        }
    });    // Collapse to icons only (for small screens)
    function collapseToIcons() {
        if (isSmallScreen) {
            // Add collapsing animation class first
            sidebar.classList.add('mobile-collapsing');
            document.body.classList.add('sidebar-closing');

            // Hide text elements immediately
            if (sidebarTitle) sidebarTitle.classList.add('hidden');
            if (menuLabel) {
                menuLabel.style.opacity = '0';
                menuLabel.style.visibility = 'hidden';
            }
            if (accountLabel) {
                accountLabel.style.opacity = '0';
                accountLabel.style.visibility = 'hidden';
            }
            menuTexts.forEach(text => text.classList.add('hidden'));

            // After text animation, change width and remove overlay
            setTimeout(() => {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-16');
                sidebar.classList.remove('mobile-open');

                // Remove overlay after animation
                setTimeout(() => {
                    document.body.classList.remove('sidebar-open');
                    document.body.classList.remove('sidebar-closing');
                    sidebar.classList.remove('mobile-collapsing');
                }, 100);
            }, 200);

            // Change icon to expand
            if (toggleIcon) {
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>';
            }
        }
    }

    // Expand as overlay (for small screens)
    function expandAsOverlay() {
        if (isSmallScreen) {
            // Add overlay and classes first
            document.body.classList.add('sidebar-open');
            sidebar.classList.add('mobile-expanding');
            sidebar.classList.remove('w-16');
            sidebar.classList.add('w-64');
            sidebar.classList.add('mobile-open');

            // Show text elements with delay for smooth animation

            setTimeout(() => {

                if (sidebarTitle) sidebarTitle.classList.remove('hidden');
                if (menuLabel) {
                    menuLabel.style.opacity = '1';
                    menuLabel.style.visibility = 'visible';
                }
                if (accountLabel) {
                    accountLabel.style.opacity = '1';
                    accountLabel.style.visibility = 'visible';
                }
                menuTexts.forEach(text => text.classList.remove('hidden'));

                // Remove expanding class after animation completes
                setTimeout(() => {
                    sidebar.classList.remove('mobile-expanding');
                }, 300);
            }, 100);

            // Change icon to collapse
            if (toggleIcon) {
                toggleIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>';
            }
        }
    }

    // Close sidebar when clicking outside (for small screens)
    document.addEventListener('click', function(e) {
        if (isSmallScreen && sidebar.classList.contains('mobile-open')) {
            if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                collapseToIcons();
            }
        }
    });

    // Expose functions globally if needed
    window.sidebarToggle = {
        collapse: collapseSidebar,
        expand: expandSidebar,
        collapseToIcons: collapseToIcons,
        expandAsOverlay: expandAsOverlay
    };
});
