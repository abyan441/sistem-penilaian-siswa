document.addEventListener("DOMContentLoaded", function () {
    const accountButton = document.querySelector(".frame-10");
    const accountDropdown = document.querySelector("#account-dropdown");

    const settingsToggle = document.querySelector(".account-settings-toggle");

    const settingsSubmenu = document.querySelector("#account-settings-submenu");

    function closeAccountDropdown() {
        if (!accountButton || !accountDropdown) {
            return;
        }

        accountDropdown.hidden = true;
        accountButton.setAttribute("aria-expanded", "false");

        if (settingsToggle && settingsSubmenu) {
            settingsToggle.setAttribute("aria-expanded", "false");
            settingsSubmenu.hidden = true;
            settingsToggle.classList.remove("is-open");
        }
    }

    function openAccountDropdown() {
        if (!accountButton || !accountDropdown) {
            return;
        }

        accountDropdown.hidden = false;
        accountButton.setAttribute("aria-expanded", "true");
    }

    if (accountButton && accountDropdown) {
        accountButton.addEventListener("click", function (event) {
            event.stopPropagation();

            const isOpen = !accountDropdown.hidden;

            if (isOpen) {
                closeAccountDropdown();
            } else {
                openAccountDropdown();
            }
        });
    }

    if (settingsToggle && settingsSubmenu) {
        settingsToggle.addEventListener("click", function (event) {
            event.stopPropagation();

            const isOpen = !settingsSubmenu.hidden;

            settingsSubmenu.hidden = isOpen;

            settingsToggle.setAttribute("aria-expanded", String(!isOpen));

            settingsToggle.classList.toggle("is-open", !isOpen);
        });

        settingsSubmenu.querySelectorAll("button").forEach(function (item) {
            item.addEventListener("click", function (event) {
                event.stopPropagation();
            });
        });
    }

    document.addEventListener("click", function (event) {
        if (
            accountDropdown &&
            !accountDropdown.hidden &&
            !accountDropdown.contains(event.target) &&
            !accountButton.contains(event.target)
        ) {
            closeAccountDropdown();
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            closeAccountDropdown();
        }
    });
});
