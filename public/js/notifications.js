(function () {
    const toastId = "app-toast";
    let hideTimer;

    function getToast() {
        let toast = document.getElementById(toastId);

        if (toast) {
            return toast;
        }

        toast = document.createElement("div");
        toast.id = toastId;
        toast.className = "app-toast";
        toast.setAttribute("role", "status");
        toast.setAttribute("aria-live", "polite");
        toast.setAttribute("aria-atomic", "true");
        toast.hidden = true;
        toast.innerHTML = `
            <span class="app-toast-icon" aria-hidden="true"></span>
            <span class="app-toast-copy">
                <strong class="app-toast-title"></strong>
                <span class="app-toast-message"></span>
            </span>
            <button class="app-toast-close" type="button" aria-label="Tutup pemberitahuan">&times;</button>
        `;

        document.body.appendChild(toast);
        toast
            .querySelector(".app-toast-close")
            .addEventListener("click", hideToast);

        return toast;
    }

    function hideToast() {
        const toast = document.getElementById(toastId);

        if (!toast) {
            return;
        }

        window.clearTimeout(hideTimer);
        toast.classList.remove("is-visible");
        hideTimer = window.setTimeout(function () {
            toast.hidden = true;
        }, 180);
    }

    window.showAppToast = function (message, type) {
        const toast = getToast();
        const isSuccess = type === "success";
        const title = toast.querySelector(".app-toast-title");

        toast.classList.toggle("is-success", isSuccess);
        toast.classList.toggle("is-error", !isSuccess);
        title.textContent = isSuccess ? "Berhasil" : "Periksa kembali";
        toast.querySelector(".app-toast-message").textContent = message;
        toast.hidden = false;

        window.requestAnimationFrame(function () {
            toast.classList.add("is-visible");
        });

        window.clearTimeout(hideTimer);
        hideTimer = window.setTimeout(hideToast, 4200);
    };
})();
