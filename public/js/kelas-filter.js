document.addEventListener("DOMContentLoaded", () => {
    "use strict";

    const dataKelas = document.getElementById("data-kelas");
    const header = dataKelas?.querySelector(":scope > .k-div");
    const titleBlock = header?.querySelector(":scope > .k-div-2");

    if (!dataKelas || !header || !titleBlock) return;

    const filter = document.createElement("div");
    filter.className = "kelas-filter";
    filter.innerHTML = `
        <label class="kelas-filter-label" for="kelas-tahun-filter">
            Tahun Ajaran
        </label>
        <select class="kelas-filter-select" id="kelas-tahun-filter" aria-label="Filter tahun ajaran">
            <option value="">Semua Tahun Ajaran</option>
        </select>
    `;

    const addButton = header.querySelector(":scope > #kelas-add-button");
    header.insertBefore(filter, addButton || null);

    const select = filter.querySelector("#kelas-tahun-filter");

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
        if (!select) return;

        const selected = select.value;
        const years = getYears();

        select.innerHTML = '<option value="">Semua Tahun Ajaran</option>';

        years.forEach(year => {
            const option = document.createElement("option");
            option.value = year;
            option.textContent = year;
            select.appendChild(option);
        });

        if (selected && years.includes(selected)) {
            select.value = selected;
        } else if (!selected && years.length > 0) {
            // Tampilkan tahun ajaran terbaru secara default agar halaman tidak menumpuk.
            select.value = years[0];
        } else {
            select.value = "";
        }
    }

    function updateSummary(visibleCards) {
        const summaryValues = dataKelas.querySelectorAll(
            ".k-div-3 .k-div-4 .k-text-wrapper-4"
        );

        const totalKelas = visibleCards.length;
        const totalSiswa = visibleCards.reduce(
            (total, card) => total + Number(card.dataset.siswa || 0),
            0
        );
        const rataRata = totalKelas > 0
            ? Math.round(totalSiswa / totalKelas)
            : 0;

        if (summaryValues[0]) summaryValues[0].textContent = totalKelas;
        if (summaryValues[1]) summaryValues[1].textContent = totalSiswa;
        if (summaryValues[2]) summaryValues[2].textContent = rataRata;
    }

    function applyFilter() {
        const selectedYear = String(select?.value || "").trim();
        const cards = getCards();
        const visibleCards = [];

        cards.forEach(card => {
            const visible = !selectedYear || String(card.dataset.tahun || "").trim() === selectedYear;
            card.hidden = !visible;
            if (visible) visibleCards.push(card);
        });

        dataKelas.querySelectorAll(":scope > .k-div-6").forEach(section => {
            const hasVisibleCard = section.querySelector("[data-kelas-id]:not([hidden])");
            section.hidden = !hasVisibleCard;
        });

        updateSummary(visibleCards);
        emptyState.hidden = visibleCards.length > 0;
    }

    select?.addEventListener("change", applyFilter);

    refreshOptions();
    applyFilter();

    // Menyesuaikan filter saat kelas ditambah atau diedit melalui kelas.js.
    const observer = new MutationObserver(() => {
        const current = select?.value || "";
        refreshOptions();
        if (current && select?.value !== current) {
            select.value = current;
        }
        applyFilter();
    });

    observer.observe(dataKelas, {
        childList: true,
        subtree: true,
        characterData: true,
    });
});
