document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    const dataKelas = document.getElementById("data-kelas");
    const select = document.getElementById("kelas-tahun-filter");

    if (!dataKelas || !select) return;

    const cards = () => Array.from(dataKelas.querySelectorAll("[data-kelas-id]"));

    function updateSummary(visibleCards) {
        const values = dataKelas.querySelectorAll(
            ".k-div-3 .k-div-4 .k-text-wrapper-4"
        );

        const totalKelas = visibleCards.length;
        const totalSiswa = visibleCards.reduce(
            (total, card) => total + Number(card.dataset.siswa || 0),
            0
        );

        if (values[0]) values[0].textContent = totalKelas;
        if (values[1]) values[1].textContent = totalSiswa;
    }

    function applyFilter() {
        const selectedYear = select.value.trim();
        const allCards = cards();
        const visibleCards = [];

        allCards.forEach(card => {
            const visible =
                !selectedYear || String(card.dataset.tahun || "").trim() === selectedYear;

            card.hidden = !visible;

            if (visible) visibleCards.push(card);
        });

        dataKelas.querySelectorAll(".k-div-6").forEach(section => {
            const hasVisibleCard = section.querySelector(
                "[data-kelas-id]:not([hidden])"
            );
            section.hidden = !hasVisibleCard;
        });

        updateSummary(visibleCards);

        const emptyState = document.getElementById("kelas-filter-empty");
        if (emptyState) {
            emptyState.hidden = visibleCards.length !== 0;
        }
    }

    select.addEventListener("change", applyFilter);
    applyFilter();
});