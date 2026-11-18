<?php
header('Content-Type: application/json');
require_once 'koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);$action = isset($_POST['action']) ?$_POST['action'] : (isset($input['action']) ?$input['action'] : '');

if (empty($action)) {
    echo json_encode(['status' => 'error', 'message' => 'No action specified']);
    exit;
}

switch ($action) {
    case 'login':
        $user =$input['username'] ?? '';
        $pass =$input['password'] ?? '';
        if ($user === 'admin' &&$pass === 'admin123') {
            echo json_encode(['status' => 'success', 'role' => 'admin']);
        } else {
            $stmt =$conn->prepare("SELECT * FROM korwil WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $user, $pass);$stmt->execute();
            $res =$stmt->get_result();
            if ($res->num_rows > 0) {
                $row =$res->fetch_assoc();
                echo json_encode([
                    'status' => 'success', 
                    'role' => 'korwil', 
                    'nama_korwil' => $row['nama_korwil'], 
                    'wilayah' => $row['wilayah']
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Username atau password salah']);
            }
        }
        break;

    case 'get_data':
        $data = [];
        
        // Settings
        $res =$conn->query("SELECT * FROM settings");
        $data['settings'] = [];
        while($r = $res->fetch_assoc()){$data['settings'][$r['setting_key']] =$r['setting_value'];
        }
        
        // Master Data
        $data['madrasah'] =$conn->query("SELECT * FROM madrasah")->fetch_all(MYSQLI_ASSOC);
        $data['peserta'] =$conn->query("SELECT * FROM peserta")->fetch_all(MYSQLI_ASSOC);
        $data['jadwal'] =$conn->query("SELECT * FROM jadwal")->fetch_all(MYSQLI_ASSOC);
        $data['korwil'] =$conn->query("SELECT * FROM korwil")->fetch_all(MYSQLI_ASSOC);
        
        // Riwayat
        $data['riwayat'] = [];
        $resPresensi =$conn->query("SELECT * FROM presensi ORDER BY waktu ASC");
        while ($r = $resPresensi->fetch_assoc()) {$id_presensi = $r['id'];$r['peserta'] = [];
            $resDetail =$conn->query("SELECT id_peserta, status_kehadiran FROM detail_absensi WHERE id_presensi = $id_presensi");
            while ($d =$resDetail->fetch_assoc()) {
                $r['peserta'][] =$d;
            }
            $data['riwayat'][] =$r;
        }

        echo json_encode(['status' => 'success', 'data' => $data]);
        break;

    case 'save_settings':
        $key =$input['key'] ?? '';
        $value =$input['value'] ?? '';
        $stmt =$conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
        $stmt->bind_param("sss", $key, $value,$value);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        break;

    case 'import_data':
        $type =$input['type'] ?? '';
        $data =$input['data'] ?? [];
        if (!is_array($data)) {
            echo json_encode(['status' => 'error', 'message' => 'Data is not an array']);
            exit;
        }
        
        if ($type === 'lokasi') {$conn->query("TRUNCATE TABLE madrasah");
            $stmt =$conn->prepare("INSERT INTO madrasah (nama_madrasah, wilayah) VALUES (?, ?)");
            foreach($data as$row) {
                $nama =$row['Nama_Madrasah'] ?? '';
                $wil =$row['Wilayah'] ?? '';
                $stmt->bind_param("ss", $nama, $wil);$stmt->execute();
            }
        } elseif ($type === 'peserta') {$conn->query("TRUNCATE TABLE peserta");
            $stmt =$conn->prepare("INSERT INTO peserta (id_peserta, nama, kelas, lembaga, wilayah) VALUES (?, ?, ?, ?, ?)");
            foreach($data as $row) {$idp = $row['ID'] ?? '';$nama = $row['Nama'] ?? '';$kelas = $row['Kelas'] ?? '';$lem = $row['Lembaga'] ?? '';$wil = $row['Wilayah'] ?? '';$stmt->bind_param("sssss", $idp,$nama, $kelas,$lem, $wil);$stmt->execute();
            }
        } elseif ($type === 'jadwal') {$conn->query("TRUNCATE TABLE jadwal");
            $stmt =$conn->prepare("INSERT INTO jadwal (siklus, sesi, lokasi_pembinaan, wilayah, waktu) VALUES (?, ?, ?, ?, ?)");
            foreach($data as $row) {$sik = $row['Siklus'] ?? '';$ses = $row['Sesi'] ?? '';$lok = $row['Lokasi_Pembinaan'] ?? '';$wil = $row['Wilayah'] ?? '';$wak = $row['Waktu'] ?? '';$stmt->bind_param("sssss", $sik,$ses, $lok,$wil, $wak);$stmt->execute();
            }
        } elseif ($type === 'koordinator') {$conn->query("TRUNCATE TABLE korwil");
            $stmt =$conn->prepare("INSERT INTO korwil (username, nama_korwil, wilayah, password) VALUES (?, ?, ?, ?)");
            foreach($data as$row) {
                $usr =$row['Username'] ?? '';
                $nama =$row['Nama_Korwil'] ?? '';
                $wil =$row['Wilayah'] ?? '';
                $pass =$row['Password'] ?? '12345';
                $stmt->bind_param("ssss", $usr, $nama,$wil, $pass);$stmt->execute();
            }
        }
        echo json_encode(['status' => 'success']);
        break;

    case 'save_presensi':
        $madrasah =$input['madrasah'] ?? '';
        $wilayah =$input['wilayah'] ?? '';
        $periode =$input['periode'] ?? '';
        $tahun_ajaran =$input['tahunAjaran'] ?? '';
        $spv =$input['spv'] ?? '';
        $waktu =$input['waktu'] ?? date('Y-m-d H:i:s');
        $lat =$input['latitude'] ?? 0;
        $lng =$input['longitude'] ?? 0;
        $foto =$input['foto'] ?? '';
        $jurnal =$input['jurnal'] ?? '';
        $peserta_list =$input['peserta'] ?? []; 

        $conn->query("DELETE FROM presensi WHERE madrasah = '$madrasah' AND wilayah = '$wilayah' AND periode = '$periode'");

        $stmt =$conn->prepare("INSERT INTO presensi (madrasah, wilayah, periode, tahun_ajaran, spv, waktu, latitude, longitude, foto, jurnal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssddss", $madrasah,$wilayah, $periode,$tahun_ajaran, $spv,$waktu, $lat,$lng, $foto,$jurnal);
        
        if ($stmt->execute()) {
            $id_presensi =$stmt->insert_id;
            $stmtDet =$conn->prepare("INSERT INTO detail_absensi (id_presensi, id_peserta, status_kehadiran) VALUES (?, ?, ?)");
            foreach($peserta_list as $p) {$idp = $p['id_peserta'];$stat = $p['status'];$stmtDet->bind_param("iss", $id_presensi,$idp, $stat);$stmtDet->execute();
            }
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => $conn->error]);
        }
        break;

    case 'reset_data':
        $conn->query("DELETE FROM presensi");
        echo json_encode(['status' => 'success']);
        break;
    
    case 'mandiri':
        $id_peserta =$input['id_peserta'] ?? '';
        $lokasi =$input['lokasi'] ?? '';
        $waktu =$input['waktu'] ?? date('Y-m-d H:i:s');
        echo json_encode(['status' => 'success']);
        break;

    // ==============================================================================
    // BLOCK BARU UNTUK KONSULTASI GROQ AI
    // ==============================================================================
    case 'konsultasi_ai':
        $userQuestion =$input['question'] ?? '';
        $chatHistory =$input['history'] ?? []; // Menangkap history dari JavaScript
        
        // API Key diambil dari konfigurasi lokal koneksi.php atau environment
        $apiKey = $groqApiKey ?? getenv('GROQ_API_KEY') ?? ''; 
        
        if (empty($userQuestion)) {
            echo json_encode(['status' => 'error', 'answer' => 'Pertanyaan tidak boleh kosong.']);
            exit;
        }

        // Endpoint Groq API (OpenAI compatible)
        $url = "https://api.groq.com/openai/v1/chat/completions";
        
        // Susun array messages dasar dengan instruksi sistem (system prompt)
        $messages = [
            [
                "role" => "system",
                "content" => "Anda adalah asisten ahli untuk koordinator pembinaan pendidikan dan bahasa Arab. Jawablah secara akurat, informatif, namun ringkas (tidak terlalu panjang). Jawab dalam bahasa Indonesia. Jika ada pertanyaan bahasa Arab, tanggapi dengan benar."
            ]
        ];

        // Jika ada history dari JavaScript, masukkan ke dalam payload messages
        if (is_array($chatHistory) && !empty($chatHistory)) {
            foreach ($chatHistory as$msg) {
                if (isset($msg['role']) && isset($msg['content'])) {$messages[] = [
                        "role" => $msg['role'],
                        "content" => $msg['content']
                    ];
                }
            }
        } else {
            // Fallback jika history kosong
            $messages[] = [
                "role" => "user",
                "content" => $userQuestion
            ];
        }

        // Susunan data JSON untuk Groq
        $payloadData = [
            "model" => "llama-3.3-70b-versatile",
            "messages" => $messages
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payloadData));
        
        // Mematikan verifikasi SSL untuk mencegah error CURL di server lokal seperti XAMPP
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            echo json_encode(['status' => 'error', 'answer' => 'Terjadi kesalahan CURL: ' . $err]);
        } else {
            $result = json_decode($response, true);
            
            // Mengecek apakah respons berhasil dan memiliki teks jawaban dari Groq
            if (isset($result['choices'][0]['message']['content'])) {
                $aiAnswer =$result['choices'][0]['message']['content'];
                echo json_encode(['status' => 'success', 'answer' => $aiAnswer]);
            } else {
                // Jika error, ambil pesan error dari Groq
                $errorMsg =$result['error']['message'] ?? 'Respons dari Groq tidak sesuai format.';
                echo json_encode(['status' => 'error', 'answer' => 'Gagal dari API: ' . $errorMsg]);
            }
        }
        break;
    // ==============================================================================

    default:
        echo json_encode(['status' => 'error', 'message' => 'Unknown action']);
        break;
}

$conn->close();
?>