document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    const dataKelas = document.getElementById("data-kelas");
    if (!dataKelas) return;

    const header = dataKelas.querySelector(".k-div");
    if (!header) return;

    if (document.getElementById("kelas-tahun-filter")) return;

    const filter = document.createElement("div");
    filter.className = "kelas-filter";
    filter.innerHTML = `
        <label class="kelas-filter-label" for="kelas-tahun-filter">Tahun Ajaran</label>
        <select class="kelas-filter-select" id="kelas-tahun-filter" aria-label="Filter tahun ajaran">
            <option value="">Semua Tahun Ajaran</option>
        </select>
    `;

    const addButton = header.querySelector("#kelas-add-button");
    if (addButton) {
        header.insertBefore(filter, addButton);
    } else {
        header.appendChild(filter);
    }

    const select = document.getElementById("kelas-tahun-filter");
    if (!select) return;

    const emptyState = document.createElement("div");
    emptyState.className = "kelas-filter-empty";
    emptyState.hidden = true;
    emptyState.textContent = "Tidak ada kelas pada tahun ajaran yang dipilih.";
    dataKelas.appendChild(emptyState);

    function getCards() {
        return Array.from(dataKelas.querySelectorAll("[data-kelas-id]"));
    }

    function getYears() {
        return [...new Set(
            getCards()
                .map(card => String(card.dataset.tahun || "").trim())
                .filter(Boolean)
        )].sort((a, b) => b.localeCompare(a, undefined, { numeric: true }));
    }

    function refreshOptions() {
        const current = select.value;
        const years = getYears();

        select.innerHTML = "<option value=\"\">Semua Tahun Ajaran</option>";

        years.forEach(year => {
            const option = document.createElement("option");
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
        });

        if (current && years.includes(current)) {
            select.value = current;
        } else if (years.length > 0) {
            select.value = years[0];
        } else {
            select.value = "";
        }
    }

    function updateSummary(visibleCards) {
        const values = dataKelas.querySelectorAll(".k-div-3 .k-div-4 .k-text-wrapper-4");
        const totalKelas = visibleCards.length;
        const totalSiswa = visibleCards.reduce(
            (total, card) => total + Number(card.dataset.siswa || 0),
            0
        );
        const rataRata = totalKelas > 0 ? Math.round(totalSiswa / totalKelas) : 0;

        if (values[0]) values[0].textContent = totalKelas;
        if (values[1]) values[1].textContent = totalSiswa;
        if (values[2]) values[2].textContent = rataRata;
    }

    function applyFilter() {
        const selectedYear = select.value.trim();
        const cards = getCards();
        const visibleCards = [];

        cards.forEach(card => {
            const visible = !selectedYear || String(card.dataset.tahun || "").trim() === selectedYear;
            card.hidden = !visible;
            if (visible) visibleCards.push(card);
        });

        dataKelas.querySelectorAll(".k-div-6").forEach(section => {
            const hasVisibleCard = section.querySelector("[data-kelas-id]:not([hidden])");
            section.hidden = !hasVisibleCard;
        });

        updateSummary(visibleCards);
        emptyState.hidden = visibleCards.length !== 0;
    }

    select.addEventListener("change", applyFilter);

    refreshOptions();
    applyFilter();

    // Hanya memantau penambahan/penghapusan kartu atau perubahan data kelas.
    // Tidak memantau perubahan teks sehingga tidak membuat loop/performa lambat.
    const observer = new MutationObserver(() => {
        refreshOptions();
        applyFilter();
    });

    observer.observe(dataKelas, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ["data-kelas-id", "data-kelas-name", "data-tahun", "data-siswa", "data-wali-id", "data-wali"]
    });
});
