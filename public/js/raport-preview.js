document.addEventListener("DOMContentLoaded", function () {
    /*
    |--------------------------------------------------------------------------
    | PRINT RAPORT
    |--------------------------------------------------------------------------
    */

    const printButton = document.querySelector(".print-btn");

    if (printButton) {
        printButton.addEventListener("click", function () {
            window.print();
        });
    }
});
