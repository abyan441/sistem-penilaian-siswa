document.addEventListener("DOMContentLoaded", function () {
    const backButton = document.getElementById("back-button");
    const downloadButton = document.getElementById("download-pdf-button");
    const reportPaper = document.getElementById("report-paper");

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
        downloadButton.addEventListener("click", function () {
            if (!reportPaper) {
                alert("Tidak dapat menemukan data raport untuk dicetak.");
                return;
            }

            window.print();
        });
    }
});
