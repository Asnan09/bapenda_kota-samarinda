// assets/js/form.js
// Validasi ringan di sisi browser sebelum form dikirim.
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formPengajuan");
    if (!form) return;

    form.addEventListener("submit", function (event) {
        const nikInput = form.querySelector('input[name="nik"]');
        const nik = nikInput.value.trim();

        if (!/^[0-9]{16}$/.test(nik)) {
            event.preventDefault();
            alert("NIK harus berisi 16 angka.");
        }
    });
});
