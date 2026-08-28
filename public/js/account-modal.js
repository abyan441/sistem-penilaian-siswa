document.addEventListener("DOMContentLoaded", function () {
    const emailModal = document.querySelector("#change-email-modal");
    const passwordModal = document.querySelector("#change-password-modal");

    const modalTriggers = document.querySelectorAll("[data-open-account-modal]");
    const modalCloseButtons = document.querySelectorAll("[data-close-account-modal]");
    const passwordToggles = document.querySelectorAll("[data-password-toggle]");

    let lastFocusedElement = null;

    function getModal(type) {
        if (type === "email") return emailModal;
        if (type === "password") return passwordModal;
        return null;
    }

    function closeAccountDropdown() {
        const accountDropdown = document.querySelector("#account-dropdown");
        const accountButton = document.querySelector(".frame-8 > .frame-10");
        const settingsToggle = document.querySelector(".account-settings-toggle");
        const settingsSubmenu = document.querySelector("#account-settings-submenu");

        if (accountDropdown) accountDropdown.hidden = true;
        if (accountButton) accountButton.setAttribute("aria-expanded", "false");
        if (settingsToggle) {
            settingsToggle.setAttribute("aria-expanded", "false");
            settingsToggle.classList.remove("is-open");
        }
        if (settingsSubmenu) settingsSubmenu.hidden = true;
    }

    function openModal(modal) {
        if (!modal) return;

        lastFocusedElement = document.activeElement;
        closeAccountDropdown();
        modal.hidden = false;
        document.body.classList.add("account-modal-open");

        const firstInput = modal.querySelector("input:not([readonly])");
        if (firstInput) {
            window.setTimeout(function () {
                firstInput.focus();
            }, 50);
        }
    }

    function closeModal(modal) {
        if (!modal) return;

        modal.hidden = true;
        document.body.classList.remove("account-modal-open");

        const activeModal = document.querySelector(".account-modal:not([hidden])");
        if (!activeModal && lastFocusedElement && typeof lastFocusedElement.focus === "function") {
            lastFocusedElement.focus();
            lastFocusedElement = null;
        }
    }

    modalTriggers.forEach(function (trigger) {
        trigger.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation();
            openModal(getModal(trigger.dataset.openAccountModal));
        });
    });

    modalCloseButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            closeModal(button.closest(".account-modal"));
        });
    });

    document.addEventListener("keydown", function (event) {
        if (event.key !== "Escape") return;

        const activeModal = document.querySelector(".account-modal:not([hidden])");
        if (activeModal) closeModal(activeModal);
    });

    passwordToggles.forEach(function (toggle) {
        toggle.addEventListener("click", function () {
            const input = document.getElementById(toggle.dataset.passwordToggle);
            if (!input) return;

            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";
            toggle.setAttribute(
                "aria-label",
                isPassword ? "Sembunyikan password" : "Tampilkan password",
            );
        });
    });

    /*
     * Validasi ringan di browser.
     * Form tetap dikirim ke Laravel agar perubahan benar-benar tersimpan
     * di database dan validasi keamanan dilakukan di server.
     */
    const emailForm = document.querySelector("#change-email-form");
    if (emailForm) {
        emailForm.addEventListener("submit", function (event) {
            const newEmail = document.querySelector("#new-email");
            const confirmEmail = document.querySelector("#confirm-email");

            if (newEmail && confirmEmail && newEmail.value !== confirmEmail.value) {
                event.preventDefault();
                showAppToast("Email baru dan konfirmasi email harus sama.");
                confirmEmail.focus();
            }
        });
    }

    const passwordForm = document.querySelector("#change-password-form");
    if (passwordForm) {
        passwordForm.addEventListener("submit", function (event) {
            const newPassword = document.querySelector("#new-password");
            const confirmPassword = document.querySelector("#confirm-password");

            if (newPassword && confirmPassword && newPassword.value !== confirmPassword.value) {
                event.preventDefault();
                showAppToast("Password baru dan konfirmasi password harus sama.");
                confirmPassword.focus();
            }
        });
    }
});
