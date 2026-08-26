document.addEventListener("DOMContentLoaded", function () {
    const menuButton = document.querySelector(".mobile-menu-toggle");
    const sidebar = document.querySelector(".dashboard .frame");
    const menu = document.querySelector("#mobile-navigation");

    function closeMobileMenu() {
        if (!menuButton || !sidebar || !menu) {
            return;
        }

        sidebar.classList.remove("mobile-menu-open");
        menuButton.setAttribute("aria-expanded", "false");
        menuButton.setAttribute("aria-label", "Buka menu navigasi");
    }

    if (menuButton && sidebar && menu) {
        menuButton.addEventListener("click", function () {
            const isOpen = sidebar.classList.toggle("mobile-menu-open");

            menuButton.setAttribute("aria-expanded", String(isOpen));
            menuButton.setAttribute(
                "aria-label",
                isOpen ? "Tutup menu navigasi" : "Buka menu navigasi",
            );
        });

        menu.querySelectorAll("a").forEach(function (link) {
            link.addEventListener("click", function () {
                if (window.innerWidth <= 768) {
                    closeMobileMenu();
                }
            });
        });
    }

    window.addEventListener("resize", function () {
        if (window.innerWidth > 768) {
            closeMobileMenu();
        }
    });
});
