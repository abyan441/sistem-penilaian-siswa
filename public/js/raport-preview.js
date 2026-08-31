document.addEventListener("DOMContentLoaded", function () {
    const backButton = document.getElementById("back-button");
    const downloadButton = document.getElementById("download-pdf-button");
    const reportPaper = document.getElementById("report-paper");

    function showMessage(message, type = "error") {
        if (typeof showAppToast === "function") {
            showAppToast(message, type);
            return;
        }

        window.alert(message);
    }

    function printReport() {
        if (!reportPaper) {
            showMessage("Tidak dapat menemukan data raport untuk dicetak.");
            return;
        }

        window.print();
    }

    if (backButton) {
        backButton.addEventListener("click", function () {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = "/raport";
            }
        });
    }

    if (downloadButton) {
        downloadButton.addEventListener("click", printReport);
    }

    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.get("print") === "1") {
        setTimeout(printReport, 500);
    }
});
