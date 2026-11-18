import re
import os

filepath = r"c:\xampp\htdocs\pembinaan\index.php"

with open(filepath, "r", encoding="utf-8") as f:
    content = f.read()

# Split HTML and JS
script_start = content.find("<script>")
if script_start == -1:
    print("Script tag not found")
    exit(1)

html_part = content[:script_start + len("<script>")]
js_part = content[script_start + len("<script>"):content.rfind("</script>")]
html_end = content[content.rfind("</script>"):]

# 1. Add apiCall and fetchInitialData
new_js = """
async function apiCall(action, data = {}) {
    data.action = action;
    const res = await fetch('api.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    });
    return await res.json();
}

async function fetchInitialData() {
    showLoading();
    try {
        const res = await apiCall('get_data');
        if (res.status === 'success') {
            masterListMadrasahObj = res.data.madrasah || [];
            masterListPesertaObj = res.data.peserta || [];
            masterListJadwalObj = res.data.jadwal || [];
            masterListKorwilObj = res.data.korwil || [];
            riwayatPresensi = res.data.riwayat || [];
            let dbSettings = res.data.settings || {};
            
            globalActivePeriode = dbSettings['activePeriode'] || 'IMDA I Sesi 1';
            globalActiveTahunAjaran = dbSettings['activeTahunAjaran'] || '1447-1448 H';
            
            updateTampilanBadgePeriode();
            if(document.getElementById('admin-app').style.display === 'block') {
                updateAdminDashboard();
            } else if(document.getElementById('main-app').style.display === 'block') {
                renderAbsensiKorwil();
                renderJadwalKorwil();
                updateDashboard();
            }
        }
    } catch (e) {
        console.error("Gagal mengambil data dari database", e);
    }
    hideLoading();
}
"""

# Replace variable initializations
js_part = re.sub(
    r"let globalActivePeriode = localStorage\.getItem\('activePeriode'\) \|\| 'IMDA I Sesi 1';",
    r"let globalActivePeriode = 'IMDA I Sesi 1';",
    js_part
)
js_part = re.sub(
    r"let globalActiveTahunAjaran = localStorage\.getItem\('activeTahunAjaran'\) \|\| '1447-1448 H';",
    r"let globalActiveTahunAjaran = '1447-1448 H';",
    js_part
)

js_part = re.sub(
    r"let masterListMadrasahObj = JSON\.parse\(localStorage\.getItem\('masterListMadrasahObj'\)\) \|\| \[[^;]+\];",
    r"let masterListMadrasahObj = [];",
    js_part
)

js_part = re.sub(
    r"let masterListJadwalObj = JSON\.parse\(localStorage\.getItem\('masterListJadwalObj'\)\) \|\| \[[^;]+\];",
    r"let masterListJadwalObj = [];",
    js_part
)

js_part = re.sub(
    r"let masterListPesertaObj = JSON\.parse\(localStorage\.getItem\('masterListPesertaObj'\)\) \|\| \[[^;]+\];",
    r"let masterListPesertaObj = [];",
    js_part
)

js_part = re.sub(
    r"let masterListKorwilObj = JSON\.parse\(localStorage\.getItem\('masterListKorwilObj'\)\) \|\| \[[^;]+\];",
    r"let masterListKorwilObj = [];",
    js_part
)

js_part = re.sub(
    r"function getRiwayat\(\) \{ return JSON\.parse\(localStorage\.getItem\('riwayatPresensi'\) \|\| '\[\]'\); \}",
    r"function getRiwayat() { return riwayatPresensi; }",
    js_part
)


# Replace handleLogin
new_handle_login = """
async function handleLogin(event) {
    event.preventDefault();
    const user = document.getElementById('username').value;
    const pass = document.getElementById('password').value;
    
    showLoading();
    try {
        const res = await apiCall('login', {username: user, password: pass});
        if (res.status === 'success') {
            if (res.role === 'admin') {
                document.getElementById('login-screen').style.display = 'none';
                document.getElementById('admin-app').style.display = 'block';
                fetchInitialData();
            } else {
                localStorage.setItem('activeUsername', user);
                localStorage.setItem('activeSpv', res.nama_korwil);
                localStorage.setItem('activeWilayah', res.wilayah);
                document.getElementById('spv-greeting-name').innerText = res.nama_korwil;
                document.getElementById('profil-nama-user').innerText = res.nama_korwil;
                document.getElementById('profil-wilayah-user').innerText = "Koordinator " + res.wilayah;
                document.getElementById('wilayah-header-title').innerText = "Wilayah " + res.wilayah;
                
                document.getElementById('login-screen').style.display = 'none';
                document.getElementById('main-app').style.display = 'block';
                fetchInitialData();
            }
        } else {
            let errMsg = document.getElementById('error-msg');
            errMsg.innerText = res.message || 'Login gagal';
            errMsg.style.display = 'block';
            setTimeout(() => errMsg.style.display = 'none', 3000);
        }
    } catch (e) {
        console.error(e);
        let errMsg = document.getElementById('error-msg');
        errMsg.innerText = 'Kesalahan jaringan';
        errMsg.style.display = 'block';
        setTimeout(() => errMsg.style.display = 'none', 3000);
    }
    hideLoading();
}
"""

js_part = re.sub(r"function handleLogin\(event\).*?hideLoading\(\);\s*\}", new_handle_login, js_part, flags=re.DOTALL)

# Replace triggerExcelImport
new_import = """
function triggerExcelImport(jenis) {
    let input = document.createElement('input');
    input.type = 'file';
    input.accept = '.xlsx, .xls';
    input.onchange = e => {
        let file = e.target.files[0];
        if(!file) return;
        showLoading();
        let reader = new FileReader();
        reader.onload = function(evt) {
            try {
                let data = new Uint8Array(evt.target.result);
                let workbook = XLSX.read(data, {type: 'array'});
                let firstSheetName = workbook.SheetNames[0];
                let worksheet = workbook.Sheets[firstSheetName];
                let json = XLSX.utils.sheet_to_json(worksheet);

                if(jenis === 'metode') {
                    hideLoading();
                    tampilkanNotifInfo(`Berhasil mengimpor Panduan Metode.`, "Impor Metode Sukses", "success");
                    return;
                }

                apiCall('import_data', {type: jenis, data: json}).then(res => {
                    if(res.status === 'success') {
                        tampilkanNotifInfo(`Berhasil mengimpor Data.`, "Impor Sukses", "success");
                        fetchInitialData(); // Refresh data
                    } else {
                        hideLoading();
                        tampilkanNotifInfo("Gagal menyimpan ke database.", "Kesalahan", "warning");
                    }
                }).catch(err => {
                    hideLoading();
                    tampilkanNotifInfo("Kesalahan jaringan.", "Kesalahan", "warning");
                });
            } catch (err) {
                hideLoading();
                tampilkanNotifInfo("Gagal memproses file Excel.", "Kesalahan Sistem", "warning");
            }
        };
        reader.readAsArrayBuffer(file);
    };
    input.click();
}
"""
js_part = re.sub(r"function triggerExcelImport\(jenis\).*?input\.click\(\);\s*\}", new_import, js_part, flags=re.DOTALL)


# Replace simpanAbsensiMurid
new_simpan_absensi = """
async function simpanAbsensiMurid() {
    let lokasi = document.getElementById('label-madrasah-terpilih-header').innerText;
    if(lokasi.includes("Pilih")) {
        tampilkanNotifInfo("Silakan pilih Target Lokasi terlebih dahulu di menu Storage!", "Peringatan", "warning");
        return;
    }
    if(!statusGPS) {
        tampilkanNotifInfo("Silakan lakukan validasi jarak (GPS) di menu Storage terlebih dahulu!", "Peringatan", "warning");
        return;
    }
    if(!statusFoto) {
        tampilkanNotifInfo("Mohon ambil foto dokumentasi di menu Storage terlebih dahulu!", "Peringatan", "warning");
        return;
    }

    showLoading();
    try {
        let wilayahAktif = localStorage.getItem('activeWilayah') || 'Pasuruan';
        let spvAktif = localStorage.getItem('activeSpv') || 'Korwil';
        let jurnalText = document.getElementById('jurnal-input').value || "Pembinaan Rutin";
        
        let checkboxes = document.querySelectorAll('.absensi-checkbox');
        let countHadir = 0;
        let pesertaList = [];

        checkboxes.forEach((cb) => {
            let pesertaId = cb.getAttribute('data-id');
            let isHadir = cb.checked;
            if(isHadir) countHadir++;
            pesertaList.push({id_peserta: pesertaId, status: isHadir ? "Hadir" : "Tidak Hadir"});
        });

        let payload = {
            madrasah: lokasi,
            wilayah: wilayahAktif,
            periode: globalActivePeriode,
            tahunAjaran: globalActiveTahunAjaran,
            spv: spvAktif,
            foto: "", // assuming foto is handled somewhere else or needs base64
            jurnal: jurnalText,
            peserta: pesertaList
        };

        const res = await apiCall('save_presensi', payload);
        if(res.status === 'success') {
            document.getElementById('label-madrasah-terpilih-header').innerText = "Pilih Target Lokasi";
            document.getElementById('jurnal-input').value = "";
            statusGPS = false;
            statusFoto = false;
            document.getElementById('icon-gps').style.background = 'var(--c-teal)';
            document.getElementById('icon-gps').style.color = '#00796b';
            document.getElementById('teks-gps').innerText = 'Ketuk untuk validasi jarak (Maks 20m)';
            document.getElementById('icon-foto').style.background = 'var(--c-pink)';
            document.getElementById('icon-foto').style.color = '#d81b60';
            document.getElementById('teks-foto').innerText = 'Ambil foto dokumentasi (Wajib)';

            tampilkanNotifInfo(`Data kehadiran di ${lokasi} (${countHadir} peserta hadir) berhasil disimpan & disetor ke Admin. Menu storage telah di-reset.`, "Berhasil Disimpan", "success");
            
            await fetchInitialData(); // update local state
            switchTab('tab-beranda', document.querySelectorAll('#main-app .nav-item')[0]);
        } else {
            tampilkanNotifInfo("Gagal menyimpan data presensi.", "Kesalahan", "warning");
        }
    } catch(e) {
        tampilkanNotifInfo("Terjadi kesalahan jaringan.", "Kesalahan", "warning");
    }
    hideLoading();
}
"""
js_part = re.sub(r"function simpanAbsensiMurid\(\).*?\}, 600\);\s*\}", new_simpan_absensi, js_part, flags=re.DOTALL)


# Replace pilihPeriodeBaru and pilihTahunAjaranBaru
js_part = re.sub(
    r"function pilihPeriodeBaru\(newPeriode\).*?hideLoading\(\);\s*\}, 400\);\s*\}",
    """
async function pilihPeriodeBaru(newPeriode) {
    showLoading();
    try {
        await apiCall('save_settings', {key: 'activePeriode', value: newPeriode});
        globalActivePeriode = newPeriode;
        updateTampilanBadgePeriode();
        document.getElementById('modal-ganti-periode').style.display = 'none';
        if(document.getElementById('admin-app').style.display === 'block') updateAdminDashboard();
        else updateDashboard();
    } catch(e) {}
    hideLoading();
}
    """,
    js_part, flags=re.DOTALL
)

js_part = re.sub(
    r"function pilihTahunAjaranBaru\(newTa\).*?hideLoading\(\);\s*\}, 400\);\s*\}",
    """
async function pilihTahunAjaranBaru(newTa) {
    showLoading();
    try {
        await apiCall('save_settings', {key: 'activeTahunAjaran', value: newTa});
        globalActiveTahunAjaran = newTa;
        updateTampilanBadgePeriode();
        document.getElementById('modal-tahun-ajaran').style.display = 'none';
    } catch(e) {}
    hideLoading();
}
    """,
    js_part, flags=re.DOTALL
)


# Replace resetDataAdmin
js_part = re.sub(
    r"function resetDataAdmin\(jenis\).*?\}",
    """
function resetDataAdmin(jenis) {
    tampilkanNotifInfo("Apakah Anda yakin ingin menghapus seluruh data?", "Hapus Data", "warning");
    document.querySelector('#modal-notif-custom .modal-actions').innerHTML = `
        <button class="btn-modal-cancel" onclick="tutupNotifCustom()">Batal</button>
        <button class="btn-modal-save" style="background:#ff4757; box-shadow:0 8px 20px rgba(255,71,87,0.4);" onclick="tutupNotifCustom(); doResetData();">Hapus</button>
    `;
}
async function doResetData() {
    showLoading();
    await apiCall('reset_data');
    await fetchInitialData();
    hideLoading();
    tampilkanNotifInfo("Seluruh data riwayat berhasil dihapus.", "Reset Sukses", "success");
}
    """,
    js_part, flags=re.DOTALL
)

# Replace prosesValidasiDanAbsenMandiri
js_part = re.sub(
    r"function prosesValidasiDanAbsenMandiri\(\).*?hideLoading\(\);\s*\}, 600\);\s*\}",
    """
async function prosesValidasiDanAbsenMandiri() {
    let lokasi = document.getElementById('select-lokasi-mandiri').value;
    let idPeserta = document.getElementById('input-id-mandiri').value.trim().toUpperCase();
    if(!lokasi || !idPeserta) {
        tampilkanNotifInfo("Mohon lengkapi pilihan lokasi dan ID Peserta!", "Peringatan", "warning");
        return;
    }
    showLoading();
    try {
        const res = await apiCall('mandiri', {id_peserta: idPeserta, lokasi: lokasi});
        if(res.status === 'success') {
            document.getElementById('modal-portal-mandiri').style.display = 'none';
            tampilkanNotifInfo("Presensi mandiri berhasil disubmit.", "Berhasil", "success");
        }
    } catch(e) {}
    hideLoading();
}
    """,
    js_part, flags=re.DOTALL
)

# Remove the JSON.parse(localStorage) bits in ekspor function, and adapt logic
# In eksporAbsensiPesertaExcel
export_absen = """
function eksporAbsensiPesertaExcel() {
    showLoading();
    setTimeout(() => {
        let exportData = [];
        let filteredRiwayat = riwayatPresensi.filter(x => x.periode === globalActivePeriode);

        if (filteredRiwayat.length > 0) {
            filteredRiwayat.forEach(item => {
                let hadirCount = 0;
                item.peserta.forEach(p => { if (p.status_kehadiran === 'Hadir') hadirCount++; });
                
                let totalPesertaWilayah = masterListPesertaObj.filter(p => p.Wilayah === item.wilayah).length;
                let tidakHadirCount = Math.max(0, totalPesertaWilayah - hadirCount);
                let persentase = totalPesertaWilayah > 0 ? Math.round((hadirCount / totalPesertaWilayah) * 100) : 0;

                exportData.push({
                    "Tahun Ajaran": item.tahun_ajaran || item.tahunAjaran || globalActiveTahunAjaran,
                    "Sesi IMDA": item.periode,
                    "Lokasi / Madrasah": item.madrasah,
                    "Wilayah": item.wilayah,
                    "Korwil": item.spv,
                    "Total Peserta": totalPesertaWilayah,
                    "Jumlah Hadir": hadirCount,
                    "Jumlah Tidak Hadir": tidakHadirCount,
                    "Persentase Kehadiran (%)": persentase + "%"
                });
            });
        } else {
            let listWilayah = ["Pasuruan", "Malang", "Surabaya"];
            listWilayah.forEach(wil => {
                let totalPesertaWilayah = masterListPesertaObj.filter(p => p.Wilayah === wil).length;
                exportData.push({
                    "Tahun Ajaran": globalActiveTahunAjaran,
                    "Sesi IMDA": globalActivePeriode,
                    "Lokasi / Madrasah": "Belum Dipilih",
                    "Wilayah": wil,
                    "Korwil": "Korwil " + wil,
                    "Total Peserta": totalPesertaWilayah,
                    "Jumlah Hadir": 0,
                    "Jumlah Tidak Hadir": totalPesertaWilayah,
                    "Persentase Kehadiran (%)": "0%"
                });
            });
        }

        let worksheet = XLSX.utils.json_to_sheet(exportData);
        let workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Rekap_Persentase_Wilayah");
        XLSX.writeFile(workbook, `Rekap_Persentase_Wilayah_${globalActivePeriode.replace(/\s+/g, '_')}.xlsx`);

        hideLoading();
        tampilkanNotifInfo("Rekapitulasi persentase wilayah berhasil diunduh.", "Ekspor Berhasil", "success");
    }, 600);
}
"""
js_part = re.sub(r"function eksporAbsensiPesertaExcel\(\).*?\}, 600\);\s*\}", export_absen, js_part, flags=re.DOTALL)


# eksporDetailAbsensiPesertaExcel
export_detail = """
function eksporDetailAbsensiPesertaExcel() {
    showLoading();
    setTimeout(() => {
        let exportData = [];
        let filteredRiwayat = riwayatPresensi.filter(x => x.periode === globalActivePeriode);

        if (filteredRiwayat.length > 0) {
            filteredRiwayat.forEach(item => {
                let pesertaWilayah = masterListPesertaObj.filter(p => p.Wilayah === item.wilayah);

                pesertaWilayah.forEach(p => {
                    let det = item.peserta.find(d => d.id_peserta == p.ID || d.id_peserta == p.id_peserta);
                    let statusKehadiran = det ? det.status_kehadiran : "Tidak Hadir";
                    
                    exportData.push({
                        "Tahun Ajaran": item.tahun_ajaran || item.tahunAjaran || globalActiveTahunAjaran,
                        "Sesi IMDA": item.periode,
                        "Lokasi / Madrasah": item.madrasah,
                        "ID Peserta": p.ID || p.id_peserta,
                        "Nama Peserta": p.Nama || p.nama,
                        "Kelas": p.Kelas || p.kelas,
                        "Lembaga Asal": p.Lembaga || p.lembaga,
                        "Wilayah": p.Wilayah || p.wilayah,
                        "Status Kehadiran": statusKehadiran
                    });
                });
            });
        } else {
            masterListPesertaObj.forEach(p => {
                exportData.push({
                    "Tahun Ajaran": globalActiveTahunAjaran,
                    "Sesi IMDA": globalActivePeriode,
                    "Lokasi / Madrasah": "Belum Dipilih",
                    "ID Peserta": p.ID || p.id_peserta,
                    "Nama Peserta": p.Nama || p.nama,
                    "Kelas": p.Kelas || p.kelas,
                    "Lembaga Asal": p.Lembaga || p.lembaga,
                    "Wilayah": p.Wilayah || p.wilayah,
                    "Status Kehadiran": "Tidak Hadir"
                });
            });
        }

        let worksheet = XLSX.utils.json_to_sheet(exportData);
        let workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Detail_Absensi_Peserta");
        XLSX.writeFile(workbook, `Detail_Absensi_Peserta_${globalActivePeriode.replace(/\s+/g, '_')}.xlsx`);

        hideLoading();
        tampilkanNotifInfo("Rincian absensi per-peserta berhasil diunduh.", "Ekspor Berhasil", "success");
    }, 600);
}
"""
js_part = re.sub(r"function eksporDetailAbsensiPesertaExcel\(\).*?\}, 600\);\s*\}", export_detail, js_part, flags=re.DOTALL)


# eksporSatuLembagaExcel
export_satu = """
function eksporSatuLembagaExcel(namaMadrasah, wilayah) {
    showLoading();
    setTimeout(() => {
        let riwayatItem = riwayatPresensi.find(x => x.madrasah === namaMadrasah && x.wilayah === wilayah && x.periode === globalActivePeriode);
        let pesertaWilayah = masterListPesertaObj.filter(p => p.Wilayah === wilayah);
        let detailData = [];

        pesertaWilayah.forEach(p => {
            let det = riwayatItem ? riwayatItem.peserta.find(d => d.id_peserta == p.ID || d.id_peserta == p.id_peserta) : null;
            let statusKeh = det ? det.status_kehadiran : "Tidak Hadir";
            
            detailData.push({
                "Tahun Ajaran": riwayatItem ? (riwayatItem.tahun_ajaran || riwayatItem.tahunAjaran || globalActiveTahunAjaran) : globalActiveTahunAjaran,
                "Sesi IMDA": globalActivePeriode,
                "Lokasi / Madrasah": namaMadrasah,
                "Wilayah": wilayah,
                "ID Peserta": p.ID || p.id_peserta,
                "Nama Peserta": p.Nama || p.nama,
                "Kelas": p.Kelas || p.kelas,
                "Lembaga Asal": p.Lembaga || p.lembaga,
                "Status Kehadiran": statusKeh
            });
        });

        let workbook = XLSX.utils.book_new();
        let wsDetail = XLSX.utils.json_to_sheet(detailData.length > 0 ? detailData : [{
            "Tahun Ajaran": globalActiveTahunAjaran,
            "Sesi IMDA": globalActivePeriode,
            "Lokasi / Madrasah": namaMadrasah,
            "Wilayah": wilayah,
            "ID Peserta": "-",
            "Nama Peserta": "Belum ada data peserta",
            "Kelas": "-",
            "Lembaga Asal": "-",
            "Status Kehadiran": "-"
        }]);

        XLSX.utils.book_append_sheet(workbook, wsDetail, "Detail_Absensi_Peserta");
        XLSX.writeFile(workbook, `Detail_Absensi_${namaMadrasah.replace(/\s+/g, '_')}_${globalActivePeriode.replace(/\s+/g, '_')}.xlsx`);

        hideLoading();
        tampilkanNotifInfo(`Detail absensi peserta di ${namaMadrasah} berhasil diunduh.`, "Ekspor Berhasil", "success");
    }, 500);
}
"""
js_part = re.sub(r"function eksporSatuLembagaExcel\(namaMadrasah, wilayah\).*?\}, 500\);\s*\}", export_satu, js_part, flags=re.DOTALL)


# Delete localStorage calls from updateAdminDashboard and updateDashboard
# e.g., JSON.parse(localStorage.getItem('absensiHadirData'))
js_part = re.sub(r"let absensiHadirData = JSON\.parse\(localStorage\.getItem\('absensiHadirData'\) \|\| '\{\}'\);", "", js_part)
js_part = re.sub(r"let detailAbsensiPeserta = JSON\.parse\(localStorage\.getItem\('detailAbsensiPeserta'\) \|\| '\{\}'\);", "", js_part)
js_part = re.sub(r"let riwayatPresensi = JSON\.parse\(localStorage\.getItem\('riwayatPresensi'\) \|\| '\[\]'\);", "", js_part)

# Also fix the `getRiwayat()` function if used
js_part = re.sub(r"getRiwayat\(\)", "riwayatPresensi", js_part)

js_part = new_js + "\\n" + js_part

# In window.onload, add fetchInitialData(), but be careful not to execute it if it's already on login screen.
# Actually, the user has to login first, so fetchInitialData is called after login.
# Wait, if they refresh the page, they are logged out. The session isn't persisted on refresh unless activeUsername is checked.
onload_fix = """
window.onload = function() {
    updateTampilanBadgePeriode();
    if(localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
        if(document.getElementById('checkbox-darkmode')) document.getElementById('checkbox-darkmode').checked = true;
        if(document.getElementById('checkbox-darkmode-admin')) document.getElementById('checkbox-darkmode-admin').checked = true;
        if(document.getElementById('teks-status-darkmode')) document.getElementById('teks-status-darkmode').innerText = 'Aktif';
        if(document.getElementById('teks-status-darkmode-admin')) document.getElementById('teks-status-darkmode-admin').innerText = 'Aktif';
    }
    
    // Check if already logged in
    let user = localStorage.getItem('activeUsername');
    if (user) {
        document.getElementById('username').value = user;
        // Optionally auto-login if password was saved, but here we just let them click login.
    }
};
"""
js_part = re.sub(r"window\.onload = function\(\) \{.*?\};", onload_fix, js_part, flags=re.DOTALL)


# Additional fixes for array accesses, because MySQL returns lowercase keys from table columns for our tables.
# Peserta: id_peserta, nama, kelas, lembaga, wilayah
# Madrasah: nama_madrasah, wilayah
# Jadwal: siklus, sesi, lokasi_pembinaan, wilayah, waktu
# Korwil: username, nama_korwil, wilayah, password
# We should map them so existing code doesn't break.
map_data = """
            // Map keys back to uppercase to match existing frontend code expectations
            masterListMadrasahObj = (res.data.madrasah || []).map(m => ({ Nama_Madrasah: m.nama_madrasah, Wilayah: m.wilayah }));
            masterListPesertaObj = (res.data.peserta || []).map(p => ({ ID: p.id_peserta, Nama: p.nama, Kelas: p.kelas, Lembaga: p.lembaga, Wilayah: p.wilayah }));
            masterListJadwalObj = (res.data.jadwal || []).map(j => ({ Siklus: j.siklus, Sesi: j.sesi, Lokasi_Pembinaan: j.lokasi_pembinaan, Wilayah: j.wilayah, Waktu: j.waktu }));
            masterListKorwilObj = (res.data.korwil || []).map(k => ({ Username: k.username, Nama_Korwil: k.nama_korwil, Wilayah: k.wilayah, Password: k.password }));
"""
js_part = js_part.replace("masterListMadrasahObj = res.data.madrasah || [];", map_data)
js_part = js_part.replace("masterListPesertaObj = res.data.peserta || [];", "")
js_part = js_part.replace("masterListJadwalObj = res.data.jadwal || [];", "")
js_part = js_part.replace("masterListKorwilObj = res.data.korwil || [];", "")


# Make sure we remove all `JSON.parse(localStorage.getItem('absensiHadirData'))`
js_part = re.sub(r"let absensiHadirData\s*=\s*JSON\.parse\(localStorage\.getItem\('absensiHadirData'\) \|\| '\{\}'\);", "", js_part)

# Also fix `kirimRekapWhatsApp()` where it reads `absensiHadirData`
kirim_wa = """
function kirimRekapWhatsApp() {
    let wilayahAktif = localStorage.getItem('activeWilayah') || 'Pasuruan';
    let totalPesertaWilayah = masterListPesertaObj.filter(p => p.Wilayah === wilayahAktif).length;
    
    let hadirCount = 0;
    
    // Hitung total hadir dari seluruh lokasi di wilayah ini pada periode aktif
    riwayatPresensi.forEach(item => {
        if(item.wilayah === wilayahAktif && item.periode === globalActivePeriode) {
            item.peserta.forEach(p => {
                if(p.status_kehadiran === 'Hadir') hadirCount++;
            });
        }
    });

    if(hadirCount > totalPesertaWilayah) hadirCount = totalPesertaWilayah;
    let tidakHadirCount = Math.max(0, totalPesertaWilayah - hadirCount);
    let persentase = totalPesertaWilayah > 0 ? Math.round((hadirCount / totalPesertaWilayah) * 100) : 0;

    let pesanWhatsApp = `*Pembinaan ${globalActivePeriode}*` +
        `\\n*Wilayah ${wilayahAktif}*` +
        `\\nTahun ajaran ${globalActiveTahunAjaran}.` +
        `\\n\\n*Total Peserta ${totalPesertaWilayah}*` +
        `\\nHadir : *${hadirCount}*` +
        `\\nTidak Hadir : *${tidakHadirCount}*` +
        `\\n*Prosentase Kehadiran : ${persentase}%*`;

    let urlWA = `https://wa.me/?text=${encodeURIComponent(pesanWhatsApp)}`;
    window.open(urlWA, '_blank');
}
"""
js_part = re.sub(r"function kirimRekapWhatsApp\(\).*?window\.open\(urlWA, '_blank'\);\s*\}", kirim_wa, js_part, flags=re.DOTALL)

with open(filepath, "w", encoding="utf-8") as f:
    f.write(html_part + js_part + html_end)

print("Patching successful")
