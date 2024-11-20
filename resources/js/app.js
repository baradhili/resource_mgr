import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    // Get the dropdown trigger elements
    const dropdownTriggers = document.querySelectorAll('[data-bs-toggle="dropdown"]');

    // Add click event listeners to each dropdown trigger
    dropdownTriggers.forEach(trigger => {
        trigger.addEventListener('click', () => {
            const dropdownMenu = trigger.nextElementSibling;
            if (dropdownMenu.classList.contains('show')) {
                dropdownMenu.classList.remove('show');
            } else {
                dropdownMenu.classList.add('show');
            }
        });
    });

    // Close the dropdown when clicking outside
    window.addEventListener('click', (event) => {
        const dropdownMenus = document.querySelectorAll('.dropdown-menu.show');
        dropdownMenus.forEach(dropdownMenu => {
            if (!dropdownMenu.contains(event.target) && !trigger.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    });
});

document.querySelectorAll('.sidebar-link[data-bs-toggle="collapse"]').forEach(function(link) {
    link.addEventListener('click', function(event) {
        event.preventDefault();
        var target = document.querySelector(this.getAttribute('data-bs-target'));
        if (target.classList.contains('show')) {
            target.classList.remove('show');
            this.classList.add('collapsed');
            this.setAttribute('aria-expanded', 'false');
        } else {
            target.classList.add('show');
            this.classList.remove('collapsed');
            this.setAttribute('aria-expanded', 'true');
        }
    });
});
