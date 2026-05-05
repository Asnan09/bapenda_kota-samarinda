// assets/js/form.js
// Validasi ringan dan tampilan nama file untuk formulir PBB-P2.
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("formPengajuan");
    if (!form) return;

    const inputNik = form.querySelector('input[name="nik"]');
    const fileInputs = form.querySelectorAll('input[type="file"]');

    fileInputs.forEach(function (input) {
        input.addEventListener("change", function () {
            const uploadItem = input.closest(".upload-item");
            const uploadInfo = uploadItem ? uploadItem.querySelector(".upload-info") : null;
            if (!uploadInfo) return;

            let fileLabel = uploadInfo.querySelector(".file-name-badge");
            if (!fileLabel) {
                fileLabel = document.createElement("span");
                fileLabel.className = "file-name-badge";
                fileLabel.style.display = "block";
                fileLabel.style.marginTop = "8px";
                fileLabel.style.fontSize = "11px";
                fileLabel.style.color = "#2563eb";
                fileLabel.style.fontWeight = "700";
                uploadInfo.appendChild(fileLabel);
            }

            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (file.size > maxSize) {
                    alert("Gagal: Ukuran file '" + file.name + "' melebihi kapasitas 2MB. Silakan kompres file Anda atau pilih file yang lebih kecil.");
                    input.value = ""; // Reset input
                    fileLabel.textContent = "";
                    return;
                }
                fileLabel.textContent = "✓ " + file.name;
            } else {
                fileLabel.textContent = "";
            }
        });
    });

    form.addEventListener("submit", function (event) {
        const inputNik = form.querySelector('input[name="nik"]');
        const nilaiNik = inputNik ? inputNik.value.replace(/\D/g, "") : "";
        
        if (nilaiNik.length < 15 || nilaiNik.length > 16) {
            event.preventDefault();
            alert("NIK / NPWP harus berisi 15 sampai 16 digit angka.");
            if (inputNik) {
                inputNik.focus();
            }
            return;
        }

        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Mengirim Pengajuan...';
            btn.style.opacity = "0.7";
            btn.style.cursor = "not-allowed";
        }
    });
});
