document.addEventListener("DOMContentLoaded", function () {
    /* =====================================================
       KONFIGURASI
       ===================================================== */

    const form = document.getElementById("grade-form");
    const toast = document.getElementById("nilai-toast");
    const toastClose = toast ? toast.querySelector(".nilai-toast-close") : null;
    let toastTimeout;

    function hideToast() {
        if (!toast) {
            return;
        }

        toast.classList.remove("is-visible");

        window.setTimeout(function () {
            toast.hidden = true;
        }, 180);
    }

    function showToast() {
        if (!toast) {
            return;
        }

        window.clearTimeout(toastTimeout);
        toast.hidden = false;

        window.requestAnimationFrame(function () {
            toast.classList.add("is-visible");
        });

        toastTimeout = window.setTimeout(hideToast, 4200);
    }

    if (toastClose) {
        toastClose.addEventListener("click", hideToast);
    }

    if (!form) {
        return;
    }

    const students = ["bondet", "dina", "dodo", "ereh", "rina", "moana"];

    /* =====================================================
       PREDIKAT NILAI
       ===================================================== */

    function getPredicate(score) {
        if (score >= 90) {
            return "A";
        }

        if (score >= 80) {
            return "B";
        }

        if (score >= 70) {
            return "C";
        }

        return "D";
    }

    /* =====================================================
       UPDATE PREDIKAT CLASS
       ===================================================== */

    function updatePredicateClass(badge, predicate) {
        if (!badge) {
            return;
        }

        badge.classList.remove(
            "predikat-a",
            "predikat-b",
            "predikat-c",
            "predikat-d",
        );

        badge.classList.add("predikat-" + predicate.toLowerCase());
    }

    /* =====================================================
       UPDATE NILAI SISWA
       ===================================================== */

    function updateStudent(student) {
        const taskInput = form.elements[student + "-task"];

        const utsInput = form.elements[student + "-uts"];

        const uasInput = form.elements[student + "-uas"];

        if (!taskInput || !utsInput || !uasInput) {
            return;
        }

        const task = Number(taskInput.value) || 0;

        const uts = Number(utsInput.value) || 0;

        const uas = Number(uasInput.value) || 0;

        /*
         * Bobot:
         * Tugas = 30%
         * UTS   = 30%
         * UAS   = 40%
         */

        const score = Math.round(task * 0.3 + uts * 0.3 + uas * 0.4);

        const predicate = getPredicate(score);

        const output = document.querySelector(
            '[data-student="' + student + '"]',
        );

        const badge = document.querySelector(
            '[data-predicate="' + student + '"]',
        );

        const badgeText = badge ? badge.querySelector("span") : null;

        /* NILAI AKHIR */

        if (output) {
            output.textContent = String(score);
        }

        /* PREDIKAT */

        if (badgeText) {
            badgeText.textContent = predicate;
        }

        updatePredicateClass(badge, predicate);
    }

    /* =====================================================
       VALIDASI INPUT 0 - 100
       ===================================================== */

    function normalizeInput(input) {
        if (!input) {
            return;
        }

        let value = Number(input.value);

        if (!Number.isFinite(value)) {
            input.value = "";

            return;
        }

        if (value < 0) {
            input.value = "0";

            return;
        }

        if (value > 100) {
            input.value = "100";
        }
    }

    /* =====================================================
       INPUT EVENT
       ===================================================== */

    form.addEventListener("input", function (event) {
        const target = event.target;

        if (!target.matches(".nilai-input")) {
            return;
        }

        normalizeInput(target);

        const name = target.name || "";

        const separatorIndex = name.lastIndexOf("-");

        if (separatorIndex === -1) {
            return;
        }

        const student = name.substring(0, separatorIndex);

        if (students.includes(student)) {
            updateStudent(student);
        }
    });

    /* =====================================================
       CHANGE EVENT
       ===================================================== */

    form.addEventListener("change", function (event) {
        const target = event.target;

        if (!target.matches(".nilai-input")) {
            return;
        }

        normalizeInput(target);

        const name = target.name || "";

        const separatorIndex = name.lastIndexOf("-");

        if (separatorIndex === -1) {
            return;
        }

        const student = name.substring(0, separatorIndex);

        if (students.includes(student)) {
            updateStudent(student);
        }
    });

    /* =====================================================
       SUBMIT / SIMPAN NILAI
       ===================================================== */

    form.addEventListener("submit", function (event) {
        event.preventDefault();

        students.forEach(function (student) {
            updateStudent(student);
        });

        /*
         * Untuk tahap convert HTML ke Laravel,
         * data belum dikirim ke database.
         *
         * Saat backend database sudah dibuat,
         * bagian ini dapat diubah menjadi
         * fetch()/POST ke route Laravel.
         */

        showToast();
    });

    /* =====================================================
       INITIAL CALCULATION
       ===================================================== */

    students.forEach(function (student) {
        updateStudent(student);
    });
});
