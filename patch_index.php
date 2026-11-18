<?php
$filepath = 'c:/xampp/htdocs/pembinaan/index.php';
$content = file_get_contents($filepath);

$script_start = strpos($content, '<script>');
if ($script_start === false) {
    echo "Script tag not found\n";
    exit(1);
}

$html_part = substr($content, 0, $script_start + strlen('<script>'));
$end_script_pos = strrpos($content, '</script>');
$js_part = substr($content, $script_start + strlen('<script>'), $end_script_pos - ($script_start + strlen('<script>')));
$html_end = substr($content, $end_script_pos);

$new_js = <<<EOD

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
            // Map keys back to uppercase to match existing frontend code expectations
            masterListMadrasahObj = (res.data.madrasah || []).map(m => ({ Nama_Madrasah: m.nama_madrasah, Wilayah: m.wilayah }));
            masterListPesertaObj = (res.data.peserta || []).map(p => ({ ID: p.id_peserta, Nama: p.nama, Kelas: p.kelas, Lembaga: p.lembaga, Wilayah: p.wilayah }));
            masterListJadwalObj = (res.data.jadwal || []).map(j => ({ Siklus: j.siklus, Sesi: j.sesi, Lokasi_Pembinaan: j.lokasi_pembinaan, Wilayah: j.wilayah, Waktu: j.waktu }));
            masterListKorwilObj = (res.data.korwil || []).map(k => ({ Username: k.username, Nama_Korwil: k.nama_korwil, Wilayah: k.wilayah, Password: k.password }));
            
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
EOD;

$js_part = preg_replace("/let globalActivePeriode = localStorage\.getItem\('activePeriode'\) \|\| 'IMDA I Sesi 1';/", "let globalActivePeriode = 'IMDA I Sesi 1';", $js_part);
$js_part = preg_replace("/let globalActiveTahunAjaran = localStorage\.getItem\('activeTahunAjaran'\) \|\| '1447-1448 H';/", "let globalActiveTahunAjaran = '1447-1448 H';", $js_part);

$js_part = preg_replace("/let masterListMadrasahObj = JSON\.parse\(localStorage\.getItem\('masterListMadrasahObj'\)\) \|\| \[.*?\];/s", "let masterListMadrasahObj = [];", $js_part);
$js_part = preg_replace("/let masterListJadwalObj = JSON\.parse\(localStorage\.getItem\('masterListJadwalObj'\)\) \|\| \[.*?\];/s", "let masterListJadwalObj = [];", $js_part);
$js_part = preg_replace("/let masterListPesertaObj = JSON\.parse\(localStorage\.getItem\('masterListPesertaObj'\)\) \|\| \[.*?\];/s", "let masterListPesertaObj = [];", $js_part);
$js_part = preg_replace("/let masterListKorwilObj = JSON\.parse\(localStorage\.getItem\('masterListKorwilObj'\)\) \|\| \[.*?\];/s", "let masterListKorwilObj = [];", $js_part);

$js_part = preg_replace("/function getRiwayat\(\) \{ return JSON\.parse\(localStorage\.getItem\('riwayatPresensi'\) \|\| '\[\]'\); \}/", "function getRiwayat() { return riwayatPresensi; }", $js_part);

$new_handle_login = <<<EOD
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
EOD;
$js_part = preg_replace("/function handleLogin\(event\).*?hideLoading\(\);\s*\}/s", $new_handle_login, $js_part);

$new_import = <<<EOD
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
                        fetchInitialData();
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
EOD;
$js_part = preg_replace("/function triggerExcelImport\(jenis\).*?input\.click\(\);\s*\}/s", $new_import, $js_part);

$new_simpan_absensi = <<<EOD
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
            foto: "",
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

            tampilkanNotifInfo(`Data kehadiran di \${lokasi} (\${countHadir} peserta hadir) berhasil disimpan & disetor ke Admin. Menu storage telah di-reset.`, "Berhasil Disimpan", "success");
            
            await fetchInitialData();
            switchTab('tab-beranda', document.querySelectorAll('#main-app .nav-item')[0]);
        } else {
            tampilkanNotifInfo("Gagal menyimpan data presensi.", "Kesalahan", "warning");
        }
    } catch(e) {
        tampilkanNotifInfo("Terjadi kesalahan jaringan.", "Kesalahan", "warning");
    }
    hideLoading();
}
EOD;
$js_part = preg_replace("/function simpanAbsensiMurid\(\).*?\}, 600\);\s*\}/s", $new_simpan_absensi, $js_part);

$new_periode = <<<EOD
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
EOD;
$js_part = preg_replace("/function pilihPeriodeBaru\(newPeriode\).*?hideLoading\(\);\s*\}, 400\);\s*\}/s", $new_periode, $js_part);

$new_ta = <<<EOD
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
EOD;
$js_part = preg_replace("/function pilihTahunAjaranBaru\(newTa\).*?hideLoading\(\);\s*\}, 400\);\s*\}/s", $new_ta, $js_part);

$new_reset = <<<EOD
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
EOD;
$js_part = preg_replace("/function resetDataAdmin\(jenis\).*?\}/s", $new_reset, $js_part);

$new_mandiri = <<<EOD
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
EOD;
$js_part = preg_replace("/function prosesValidasiDanAbsenMandiri\(\).*?hideLoading\(\);\s*\}, 600\);\s*\}/s", $new_mandiri, $js_part);

$export_absen = <<<EOD
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
        XLSX.writeFile(workbook, `Rekap_Persentase_Wilayah_\${globalActivePeriode.replace(/\s+/g, '_')}.xlsx`);

        hideLoading();
        tampilkanNotifInfo("Rekapitulasi persentase wilayah berhasil diunduh.", "Ekspor Berhasil", "success");
    }, 600);
}
EOD;
$js_part = preg_replace("/function eksporAbsensiPesertaExcel\(\).*?\}, 600\);\s*\}/s", $export_absen, $js_part);

$export_detail = <<<EOD
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
        XLSX.writeFile(workbook, `Detail_Absensi_Peserta_\${globalActivePeriode.replace(/\s+/g, '_')}.xlsx`);

        hideLoading();
        tampilkanNotifInfo("Rincian absensi per-peserta berhasil diunduh.", "Ekspor Berhasil", "success");
    }, 600);
}
EOD;
$js_part = preg_replace("/function eksporDetailAbsensiPesertaExcel\(\).*?\}, 600\);\s*\}/s", $export_detail, $js_part);

$export_satu = <<<EOD
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
        XLSX.writeFile(workbook, `Detail_Absensi_\${namaMadrasah.replace(/\s+/g, '_')}_\${globalActivePeriode.replace(/\s+/g, '_')}.xlsx`);

        hideLoading();
        tampilkanNotifInfo(`Detail absensi peserta di \${namaMadrasah} berhasil diunduh.`, "Ekspor Berhasil", "success");
    }, 500);
}
EOD;
$js_part = preg_replace("/function eksporSatuLembagaExcel\(namaMadrasah, wilayah\).*?\}, 500\);\s*\}/s", $export_satu, $js_part);

$js_part = preg_replace("/let absensiHadirData = JSON\.parse\(localStorage\.getItem\('absensiHadirData'\) \|\| '\{\}'\);/", "", $js_part);
$js_part = preg_replace("/let detailAbsensiPeserta = JSON\.parse\(localStorage\.getItem\('detailAbsensiPeserta'\) \|\| '\{\}'\);/", "", $js_part);
$js_part = preg_replace("/let riwayatPresensi = JSON\.parse\(localStorage\.getItem\('riwayatPresensi'\) \|\| '\[\]'\);/", "", $js_part);
$js_part = preg_replace("/let absensiHadirData\s*=\s*JSON\.parse\(localStorage\.getItem\('absensiHadirData'\) \|\| '\{\}'\);/", "", $js_part);


$kirim_wa = <<<EOD
function kirimRekapWhatsApp() {
    let wilayahAktif = localStorage.getItem('activeWilayah') || 'Pasuruan';
    let totalPesertaWilayah = masterListPesertaObj.filter(p => p.Wilayah === wilayahAktif).length;
    
    let hadirCount = 0;
    
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

    let pesanWhatsApp = `*Pembinaan \${globalActivePeriode}*` +
        `\\n*Wilayah \${wilayahAktif}*` +
        `\\nTahun ajaran \${globalActiveTahunAjaran}.` +
        `\\n\\n*Total Peserta \${totalPesertaWilayah}*` +
        `\\nHadir : *\${hadirCount}*` +
        `\\nTidak Hadir : *\${tidakHadirCount}*` +
        `\\n*Prosentase Kehadiran : \${persentase}%*`;

    let urlWA = `https://wa.me/?text=\${encodeURIComponent(pesanWhatsApp)}`;
    window.open(urlWA, '_blank');
}
EOD;
$js_part = preg_replace("/function kirimRekapWhatsApp\(\).*?window\.open\(urlWA, '_blank'\);\s*\}/s", $kirim_wa, $js_part);

$onload_fix = <<<EOD
window.onload = function() {
    updateTampilanBadgePeriode();
    if(localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
        if(document.getElementById('checkbox-darkmode')) document.getElementById('checkbox-darkmode').checked = true;
        if(document.getElementById('checkbox-darkmode-admin')) document.getElementById('checkbox-darkmode-admin').checked = true;
        if(document.getElementById('teks-status-darkmode')) document.getElementById('teks-status-darkmode').innerText = 'Aktif';
        if(document.getElementById('teks-status-darkmode-admin')) document.getElementById('teks-status-darkmode-admin').innerText = 'Aktif';
    }
    
    let user = localStorage.getItem('activeUsername');
    if (user) {
        document.getElementById('username').value = user;
    }
};
EOD;
$js_part = preg_replace("/window\.onload = function\(\) \{.*?\};/s", $onload_fix, $js_part);

$js_part = $new_js . "\n" . $js_part;

file_put_contents($filepath, $html_part . $js_part . $html_end);
echo "Patch successful\n";
?>
