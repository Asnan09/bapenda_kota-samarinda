// assets/js/form.js
// Validasi ringan dan tampilan nama file untuk formulir PBB-P2.
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formPengajuan");
    if (!form) return;

    const inputNik = form.querySelector('input[name="nik"]');
    const fileInputs = form.querySelectorAll('input[type="file"]');

    fileInputs.forEach(function (input) {
        input.addEventListener("change", function () {
            const uploadCard = input.closest("label");
            const fileLabel = uploadCard ? uploadCard.querySelector("[data-file-label]") : null;
            if (!fileLabel) return;

            if (input.files && input.files.length > 0) {
                fileLabel.textContent = input.files[0].name;
            } else {
                fileLabel.textContent = "Belum ada file dipilih";
            }
        });
    });

    form.addEventListener("submit", function (event) {
        const nilaiNik = inputNik ? inputNik.value.replace(/\D/g, "") : "";
        if (nilaiNik.length < 15 || nilaiNik.length > 16) {
            event.preventDefault();
            alert("NIK / NPWP harus berisi 15 sampai 16 digit angka.");
            if (inputNik) {
                inputNik.focus();
            }
        }
    });
});
