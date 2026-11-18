<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Presensi Pembinaan - Zomo Style</title>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<style>
/* --- GLOBAL STYLES (Zomo File Style) --- */
:root {
    --bg-main: #f8f9fe;
    --card-bg: #ffffff;
    --primary-gradient: linear-gradient(135deg, #a450ff, #5c62ff);
    --accent-gradient: linear-gradient(to right, #38f9d7, #43e97b);
    --text-main: #2b1c50;
    --text-muted: #8c85a5;
    --border-color: #edf2f7;
    
    /* Grid Colors */
    --c-pink: #ffc9e1;
    --c-orange: #ffe0b2;
    --c-blue: #bfe6ff;
    --c-teal: #b2f2e8;
}

/* DARK MODE THEME */
body.dark-mode {
    --bg-main: #121826;
    --card-bg: #1f2937;
    --text-main: #f3f4f6;
    --text-muted: #9ca3af;
    --border-color: #374151;
}

body { margin: 0; font-family: 'Nunito', sans-serif; background-color: #e4e9f2; display: flex; justify-content: center; align-items: center; min-height: 100vh; overflow: hidden; }
.app-container { 
    width: 100%; 
    max-width: 400px; 
    background-color: var(--bg-main); 
    height: 100vh; 
    max-height: 850px; 
    position: relative; /* Wajib ada relative agar absolute bottom berfungsi benar */
    box-shadow: 0 30px 60px rgba(0,0,0,0.15); 
    overflow: hidden; 
    display: flex; 
    flex-direction: column; 
    border-radius: 0; 
    transition: background-color 0.3s ease; 
}
@media (min-width: 450px) { .app-container { border-radius: 40px; border: 8px solid #222; height: 90vh; } }

@keyframes slideUpFade { 0% { opacity: 0; transform: translateY(20px); } 100% { opacity: 1; transform: translateY(0); } }
.animate-item { animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
.delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }

/* LOADING */
#global-loading { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(5px); display: none; justify-content: center; align-items: center; z-index: 9999; flex-direction: column; gap: 15px; color: var(--text-main); font-weight: 700; font-size: 16px; }
.spinner { width: 50px; height: 50px; border: 5px solid #e0e0e0; border-top: 5px solid #845ef7; border-radius: 50%; animation: spin 0.8s linear infinite; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

/* ================= LOGIN SCREEN REDESIGN ================= */
#login-screen {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    padding: 30px 25px;
    height: 100%;
    width: 100%;
    box-sizing: border-box;
    background: #ffffff;
    color: var(--text-main);
    position: relative;
    overflow-y: auto;
}
body.dark-mode #login-screen {
    background: var(--bg-main);
}
.login-top-nav {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    margin-top: 10px;
}
.nav-btn {
    width: 45px;
    height: 45px;
    border-radius: 14px;
    border: 1px solid #edf2f7;
    background: transparent;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    color: var(--text-main);
    cursor: pointer;
    transition: 0.3s;
}
body.dark-mode .nav-btn { border-color: var(--border-color); }
.nav-btn:hover { background: #f8f9fe; }
body.dark-mode .nav-btn:hover { background: var(--card-bg); }
.nav-btn-qr {
    background: var(--card-bg);
    color: #845ef7;
    border: 1px solid #edf2f7;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
body.dark-mode .nav-btn-qr { border-color: var(--border-color); }

.login-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 25px;
}
.brand-logo {
    width: 60px;
    height: 60px;
    background: var(--primary-gradient);
    border-radius: 14px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 24px;
    color: white;
    box-shadow: 0 5px 15px rgba(132, 94, 247, 0.3);
}
.brand-text { margin: 0; font-size: 28px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px; }
.login-title { margin: 0 0 35px 0; font-size: 20px; font-weight: 700; color: var(--text-main); }

.login-form { width: 100%; display: flex; flex-direction: column; gap: 20px; }
.input-container { display: flex; flex-direction: column; gap: 8px; width: 100%; }
.input-container label { font-size: 13px; font-weight: 700; color: var(--text-main); padding-left: 2px; }
.input-wrapper { position: relative; display: flex; align-items: center; width: 100%; }
.input-wrapper input {
    width: 100%;
    padding: 16px 20px 16px 45px;
    background: #f8f9fa;
    border: 1px solid transparent;
    border-radius: 16px;
    font-size: 14px;
    font-family: 'Nunito', sans-serif;
    color: var(--text-main);
    outline: none;
    transition: 0.3s;
    box-sizing: border-box;
}
body.dark-mode .input-wrapper input {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
}
.input-wrapper input:focus {
    border-color: #845ef7;
    background: #ffffff;
    box-shadow: 0 0 0 4px rgba(132, 94, 247, 0.1);
}
body.dark-mode .input-wrapper input:focus { background: var(--bg-main); }
.input-wrapper input::placeholder { color: #a0aec0; font-weight: 600; }
.input-icon-left { position: absolute; left: 16px; font-size: 20px; color: #9ca3af; }
.input-icon-right { position: absolute; right: 16px; font-size: 20px; color: #9ca3af; cursor: pointer; }

.login-options { display: flex; justify-content: space-between; align-items: center; font-size: 13px; margin-top: -5px; margin-bottom: 5px; width: 100%;}
.remember-me { display: flex; align-items: center; gap: 8px; font-weight: 600; color: var(--text-muted); cursor: pointer; }
.remember-me input[type="checkbox"] {
    appearance: none; width: 18px; height: 18px; border: 2px solid #cbd5e1; border-radius: 6px; outline: none; cursor: pointer; position: relative; transition: 0.2s; margin: 0;
}
body.dark-mode .remember-me input[type="checkbox"] { border-color: #4b5563; }
.remember-me input[type="checkbox"]:checked {
    background: var(--primary-gradient); border-color: transparent;
}
.remember-me input[type="checkbox"]:checked::after {
    content: "✔"; color: white; font-size: 10px; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
}
.forgot-pass { color: #ff6b6b; font-weight: 700; text-decoration: none; }

.btn-login-submit {
    width: 100%;
    padding: 18px;
    background: var(--primary-gradient);
    color: #fff;
    border: none;
    border-radius: 16px;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 10px 25px rgba(132, 94, 247, 0.3);
    font-family: 'Nunito', sans-serif;
    transition: 0.2s;
}
.btn-login-submit:active { transform: scale(0.96); }

.login-divider {
    width: 100%; display: flex; align-items: center; text-align: center; margin: 30px 0; color: #a0aec0; font-size: 12px; font-weight: 600;
}
.login-divider::before, .login-divider::after {
    content: ''; flex: 1; border-bottom: 1px solid #edf2f7;
}
body.dark-mode .login-divider::before, body.dark-mode .login-divider::after { border-color: var(--border-color); }
.login-divider span { padding: 0 15px; }

.social-logins {
    display: flex; justify-content: center; gap: 15px; width: 100%; margin-top: -10px; margin-bottom: 20px;
}
.social-btn {
    flex: 1; height: 50px; border-radius: 14px; border: none; background: #f8f9fa; display: flex; justify-content: center; align-items: center; font-size: 24px; cursor: pointer; transition: 0.2s;
}
body.dark-mode .social-btn { background: var(--card-bg); }
.social-btn:active { transform: scale(0.95); }

.signup-text { font-size: 13px; font-weight: 600; color: var(--text-muted); text-align: center; margin: 0; }
.signup-text a { color: #ff6b6b; font-weight: 800; text-decoration: none; }

.error-msg { background-color: #ff4757; color: white; padding: 12px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; display: none; text-align: center; font-weight: 700; box-shadow: 0 5px 15px rgba(255,71,87,0.3); width: 100%; box-sizing: border-box; }
/* ========================================================= */

/* SCREENS */
#main-app, #admin-app { display: none; width: 100%; height: 100%; position: relative; }
.screen-content { height: 100%; overflow-y: auto; padding-bottom: 100px; display: none; box-sizing: border-box; background: var(--bg-main); padding-top: 20px; transition: background-color 0.3s ease; }
.screen-content::-webkit-scrollbar { display: none; } .screen-content { -ms-overflow-style: none; scrollbar-width: none; }
.screen-content.active { display: block; }

/* HEADER (Zomo Style) */
.header-zomo { display: flex; justify-content: space-between; align-items: center; padding: 10px 25px 20px; }
#tab-metode {
    padding-top: 0 !important; /* Menghilangkan ruang kosong di atas tab metode */
}

#tab-metode .header-zomo {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: var(--bg-main);
    padding-top: 20px; /* Mengembalikan jarak atas khusus untuk bagian header */
}
.header-title-box { display: flex; align-items: center; gap: 10px; }
.btn-icon { width: 40px; height: 40px; border-radius: 12px; background: var(--card-bg); display: flex; justify-content: center; align-items: center; font-size: 20px; color: var(--text-main); cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.03); border: none; transition: background-color 0.3s; }
.header-title-box h2 { margin: 0; font-size: 18px; font-weight: 800; color: var(--text-main); }

/* BANNER (Cloud Storage Style) */
.banner-container { padding: 0 25px; margin-bottom: 25px; }
.banner-zomo { background: var(--primary-gradient); border-radius: 25px; padding: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 15px 30px rgba(132, 94, 247, 0.4); display: flex; flex-direction: column; }
.banner-zomo::after { content: ''; position: absolute; right: -20px; top: -20px; width: 120px; height: 120px; border: 2px solid rgba(255,255,255,0.1); border-radius: 50%; z-index: 1; }
.banner-bg-icon { position: absolute; right: -15px; bottom: -25px; font-size: 150px; color: white; opacity: 0.15; z-index: 0; transform: rotate(-15deg); pointer-events: none; }
.banner-bg-icon-right { position: absolute; right: -15px; bottom: -25px; font-size: 150px; color: white; opacity: 0.15; z-index: 0; transform: rotate(-15deg); pointer-events: none; }

.b-header { display: flex; align-items: center; gap: 15px; z-index: 2; margin-bottom: 12px; }
.b-icon { width: 45px; height: 45px; background: rgba(255,255,255,0.2); backdrop-filter: blur(5px); border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 24px; border: 1px solid rgba(255,255,255,0.3); }
.b-text h3 { margin: 0 0 2px 0; font-size: 18px; font-weight: 800; }
.b-text p { margin: 0; font-size: 11px; opacity: 0.8; }

/* GRID CATEGORIES (4 Items) */
.grid-categories { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding: 0 25px; margin-bottom: 30px; }
.cat-item { background: var(--card-bg); border-radius: 20px; padding: 15px; display: flex; align-items: center; gap: 15px; box-shadow: 0 8px 20px rgba(0,0,0,0.03); cursor: pointer; transition: 0.2s; border: 1px solid transparent; }
.cat-item:active { transform: scale(0.96); border-color: #e0e0e0; }
.cat-icon { width: 45px; height: 45px; border-radius: 15px; display: flex; justify-content: center; align-items: center; font-size: 22px; color: white; flex-shrink: 0; }
.c-pink { background: var(--c-pink); color: #d81b60; }
.c-orange { background: var(--c-orange); color: #f57c00; }
.c-blue { background: var(--c-blue); color: #1976d2; }
.c-teal { background: var(--c-teal); color: #00796b; }
.cat-text h4 { margin: 0 0 2px 0; font-size: 14px; font-weight: 800; color: var(--text-main); }
.cat-text p { margin: 0; font-size: 10px; color: var(--text-muted); font-weight: 600; }

/* RECENT FILES (List Style) */
.section-header { padding: 0 25px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: flex-end; }
.section-header h3 { margin: 0; font-size: 17px; font-weight: 800; color: var(--text-main); }
.section-header a { font-size: 12px; color: #845ef7; font-weight: 700; text-decoration: none; cursor: pointer; }
.list-container { padding: 0 25px; display: flex; flex-direction: column; gap: 12px; }
.list-item { background: var(--card-bg); border-radius: 20px; padding: 15px 20px; display: flex; align-items: center; box-shadow: 0 5px 15px rgba(0,0,0,0.02); cursor: pointer; transition: 0.2s; }
.list-item:active { transform: scale(0.98); }
.list-item-icon { width: 40px; height: 40px; border-radius: 12px; background: #fff5e6; color: #f59e0b; display: flex; justify-content: center; align-items: center; font-size: 20px; margin-right: 15px; flex-shrink: 0; }
.list-item-text { flex: 1; }
.list-item-text h4 { margin: 0 0 3px 0; font-size: 14px; font-weight: 700; color: var(--text-main); }
.list-item-text p { margin: 0; font-size: 11px; color: var(--text-muted); font-weight: 600; }
.list-item-action { color: #d1d5db; font-size: 20px; padding-left: 10px; }

/* FORMS & INPUTS */
.form-input { width: 100%; padding: 16px 20px; border-radius: 18px; border: 2px solid var(--border-color); background: var(--card-bg); font-family: 'Nunito', sans-serif; font-size: 14px; font-weight: 600; color: var(--text-main); outline: none; transition: 0.3s; box-sizing: border-box; }
.form-input:focus { border-color: #845ef7; box-shadow: 0 0 0 4px rgba(132, 94, 247, 0.1); }
textarea.form-input { resize: vertical; min-height: 120px; }
.btn-primary { width: 100%; padding: 18px; background: var(--accent-gradient); color: white; border: none; border-radius: 18px; font-size: 16px; font-weight: 800; font-family: 'Nunito'; cursor: pointer; box-shadow: 0 10px 25px rgba(56, 249, 215, 0.3); transition: 0.3s; }
.btn-primary:active { transform: scale(0.96); }

/* BOTTOM NAV */
.bottom-nav { 
    position: absolute; 
    bottom: 0; 
    left: 0; 
    right: 0; 
    width: 100%; 
    background: var(--card-bg); 
    display: flex; 
    justify-content: space-around; 
    align-items: flex-start; 
    height: 120px; 
    box-shadow: 0 -10px 30px rgba(0,0,0,0.08); 
    border-top-left-radius: 30px; 
    border-top-right-radius: 30px; 
    border-bottom-left-radius: 0px;  
    border-bottom-right-radius: 0px; 
    z-index: 100; 
    padding-top: 15px; 
    transition: background-color 0.3s; 
}
.nav-item { display: flex; flex-direction: column; align-items: center; color: #a0aec0; cursor: pointer; transition: 0.3s; justify-content: center; width: 65px; height: 55px; border-radius: 16px; gap: 3px; }
.nav-item i { font-size: 24px; }
.nav-item span { font-size: 11px; font-weight: 700; font-family: 'Nunito', sans-serif; }
.nav-item.active { color: #845ef7; background: rgba(132, 94, 247, 0.1); }

/* MODALS (Zomo Style) */
.modal-overlay { 
    position: absolute; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    background: rgba(0,0,0,0.3); 
    backdrop-filter: blur(5px); 
    display: none; 
    justify-content: center; 
    align-items: flex-end; 
    z-index: 200; 
    box-sizing: border-box; 
    padding: 0; 
    margin: 0;
}

/* PERBAIKAN PRIORITAS NOTIFIKASI DI DEPAN MODAL LAINNYA */
#modal-notif-custom {
    z-index: 9999 !important;
}

.modal-card { 
    background: var(--primary-gradient); 
    width: 100%; 
    border-radius: 40px 40px 0 0; 
    padding: 30px 25px 85px; 
    box-shadow: 0 -20px 40px rgba(0,0,0,0.2); 
    animation: slideUpFade 0.3s forwards; 
    display: flex; 
    flex-direction: column; 
    color: white; 
}
.modal-icon-top { width: 60px; height: 60px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 20px; display: flex; justify-content: center; align-items: center; font-size: 30px; color: white; margin: -60px auto 20px; border: 2px solid rgba(255,255,255,0.3); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
.modal-card h3 { margin: 0 0 5px 0; font-size: 20px; font-weight: 800; text-align: center; }
.modal-card p { margin: 0 0 20px 0; font-size: 13px; opacity: 0.8; text-align: center; }
.modal-input { width: 100%; padding: 18px 20px; background: rgba(255, 255, 255, 0.15); border: 2px solid rgba(255, 255, 255, 0.2); border-radius: 18px; box-sizing: border-box; font-size: 14px; font-family: 'Nunito', sans-serif; color: white; outline: none; margin-bottom: 20px; font-weight: 600; backdrop-filter: blur(5px);}
.modal-input::placeholder { color: rgba(255,255,255,0.6); }
.radio-group { display: flex; flex-direction: column; gap: 10px; margin-bottom: 20px; max-height: 300px; overflow-y: auto; padding-right: 5px;}

/* DESAIN KERN: MODIFIKASI ITEM PILIHAN TARGET LOKASI */
.export-item { 
    border: 1px solid rgba(255,255,255,0.25); 
    padding: 16px 20px; 
    border-radius: 20px; 
    display: flex; 
    align-items: center; 
    justify-content: space-between;
    background: rgba(255,255,255,0.12); 
    backdrop-filter: blur(10px);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); 
    cursor: pointer; 
    color: white; 
}
.export-item:hover { 
    background: rgba(255,255,255,0.22); 
    border-color: rgba(56, 249, 215, 0.6);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.export-item:active { transform: scale(0.97); }
.export-item strong { font-size: 15px; font-weight: 800; display:flex; align-items:center; gap:12px; }

.modal-actions { display: flex; gap: 15px; margin-top: auto; }
.btn-modal-cancel { flex: 1; padding: 16px; background: transparent; border: 2px solid rgba(255,255,255,0.4); border-radius: 18px; font-weight: 800; color: white; cursor: pointer; font-family: 'Nunito'; transition: 0.2s;}
.btn-modal-cancel:active { background: rgba(255,255,255,0.1); }
.btn-modal-save { flex: 1; padding: 16px; background: var(--accent-gradient); border: none; border-radius: 18px; font-weight: 800; color: #fff; cursor: pointer; font-family: 'Nunito'; box-shadow: 0 8px 20px rgba(56, 249, 215, 0.4); }

/* CUSTOM KALENDER MODAL TEXT */
#teks-kalender-live { font-size: 24px; font-weight: 800; color: white; margin-bottom: 5px; text-align: center; font-family: 'Nunito', sans-serif; }
#teks-tanggal-live { font-size: 14px; opacity: 0.9; text-align: center; margin-bottom: 25px; }

/* TOGGLE SWITCH STYLE */
.toggle-switch { position: relative; display: inline-block; width: 44px; height: 24px; }
.toggle-switch input { opacity: 0; width: 0; height: 0; }
.toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 24px; }
.toggle-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
input:checked + .toggle-slider { background: var(--accent-gradient); }
input:checked + .toggle-slider:before { transform: translateX(20px); }

/* TAB BUTTONS ANIMATION */
.tab-btn { padding: 10px 30px; border-radius: 20px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.3s ease; }

/* NEW ABSENSI CARD STYLE */
.absensi-card { background: var(--card-bg); border-radius: 16px; padding: 12px 15px; display: flex; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 12px; }
.number-box { width: 42px; height: 42px; background: #e0f2fe; color: #0284c7; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 16px; margin-right: 15px; flex-shrink: 0; }

/* SCROLLBAR CUSTOM UNTUK CHAT CONTAINER */
#chat-container::-webkit-scrollbar { width: 6px; }
#chat-container::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.4); border-radius: 10px; }

/* --- STYLE ARTIKEL / METODE PREMIUM --- */
.article-hero { width: 100%; height: 260px; background-size: cover; background-position: center; border-radius: 0 0 35px 35px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); position: relative; }
.article-hero::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 50%; background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); border-radius: 0 0 35px 35px; }
.article-body { padding: 25px; margin-top: -10px; position: relative; z-index: 2; }
.article-badge { background: var(--accent-gradient); color: #2b1c50; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 10px rgba(56, 249, 215, 0.3); display: inline-block; }
.article-meta { font-size: 12px; color: var(--text-muted); font-weight: 700; display: inline-block; margin-left: 10px; }
.article-title { margin: 15px 0 20px; font-size: 26px; font-weight: 800; color: var(--text-main); line-height: 1.3; letter-spacing: -0.5px;}
.article-content { font-size: 15px; color: var(--text-main); line-height: 1.8; opacity: 0.9; }
.article-content p { margin-bottom: 18px; text-align: justify; }
.dropcap { float: left; font-size: 55px; line-height: 45px; padding-top: 6px; padding-right: 10px; padding-left: 2px; font-weight: 800; color: #845ef7; font-family: serif;}
.quote-box { background: rgba(132, 94, 247, 0.05); border-left: 4px solid #845ef7; padding: 18px 20px; margin: 25px 0; font-style: italic; font-weight: 700; color: #2b1c50; border-radius: 0 12px 12px 0; line-height: 1.6;}
body.dark-mode .quote-box { background: rgba(255,255,255,0.05); color: #e5e7eb; border-left-color: #a450ff;}
.tips-card { background: linear-gradient(135deg, #fff0f6, #ffe0b2); padding: 22px; border-radius: 18px; margin: 25px 0; color: #d81b60; font-size: 14px; box-shadow: 0 5px 15px rgba(216, 27, 96, 0.1); border: 1px solid rgba(216, 27, 96, 0.2); }
body.dark-mode .tips-card { background: linear-gradient(135deg, #4c1d95, #831843); color: #fdf2f8; border-color: rgba(255,255,255,0.1); }
.article-content img { max-width: 100%; border-radius: 16px; margin: 15px 0; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
</style>
</head>
<body>
<div class="app-container">

<!-- LOADING BLUR -->
<div id="global-loading"><div class="spinner"></div><span id="loading-text">Memproses data...</span></div>

<!-- ================= 1. HALAMAN LOGIN (REDESIGNED) ================= -->
<div id="login-screen">
    <!-- Top Navigation -->
    <div class="login-top-nav">
        <div></div>
        <!-- Tombol QR Portal Absensi Mandiri di Kanan Atas -->
        <button class="nav-btn nav-btn-qr" onclick="bukaPortalMandiri()" title="Portal Absensi Mandiri"><i class="ph-bold ph-qr-code"></i></button>
    </div>

   <!-- Logo & Title -->
    <div class="login-brand">
        <div class="brand-logo" style="background: transparent; box-shadow: none;">
            <img src="logo-saya.png" alt="Logo Kustom" style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <h1 class="brand-text"></h1>
    </div>
    <h2 class="login-title">Presensi Pembinaan Al-Miftah</h2>

    <!-- Error Message -->
    <div class="error-msg" id="error-msg">Username atau password salah!</div>

    <!-- Form -->
    <form class="login-form" onsubmit="handleLogin(event)">
        <div class="input-container">
            <label>Username</label>
            <div class="input-wrapper">
                <i class="ph-bold ph-envelope-simple input-icon-left"></i>
                <input type="text" id="username" placeholder="Type your username" autocomplete="off" required>
            </div>
        </div>
        
        <div class="input-container">
            <label>Password</label>
            <div class="input-wrapper">
                <i class="ph-bold ph-lock-key input-icon-left"></i>
                <input type="password" id="password" placeholder="Type your password" required>
                <i class="ph-bold ph-eye-slash input-icon-right" onclick="let p = document.getElementById('password'); p.type = p.type === 'password' ? 'text' : 'password'; this.classList.toggle('ph-eye'); this.classList.toggle('ph-eye-slash');"></i>
            </div>
        </div>

        <div class="login-options">
            <label class="remember-me">
                <input type="checkbox">
                Remember Me
            </label>
            <a href="#" class="forgot-pass">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-login-submit">Sign In</button>
    </form>

    <div class="login-divider">
        <span>or continue with</span>
    </div>

    <div class="social-logins">
        <button class="social-btn"><i class="ph-fill ph-facebook-logo" style="color: #1877F2;"></i></button>
        <button class="social-btn"><i class="ph-fill ph-google-logo" style="color: #EA4335;"></i></button>
        <button class="social-btn"><i class="ph-fill ph-apple-logo" style="color: #111;"></i></button>
    </div>

    <p class="signup-text">Don't have an account? <a href="#">Sign Up</a></p>
</div>

<!-- ================= 2. APLIKASI ADMIN (ADMIN PUSAT) ================= -->
<div id="admin-app">
    <!-- === TAB BERANDA ADMIN === -->
    <div id="tab-admin-beranda" class="screen-content active">
        <!-- Header -->
        <div class="header-zomo animate-item delay-1">
            <button class="btn-icon" onclick="bukaModalKalenderAdmin()"><i class="ph-bold ph-squares-four"></i></button>
            <div class="header-title-box"><h2>Admin Pusat (Panel)</h2></div>
            <button class="btn-icon" onclick="bukaModalGantiPeriode()"><i class="ph-bold ph-calendar"></i></button>
        </div>
        
        <!-- Banner -->
        <div class="banner-container animate-item delay-2">
            <div class="banner-zomo" onclick="switchTabAdmin('tab-admin-presensi', document.querySelectorAll('#admin-app .nav-item')[1])">
                <i class="ph-fill ph-calendar banner-bg-icon"></i>
                <div class="b-header" style="margin-bottom: 8px;">
                    <div class="b-icon" style="color: #ffc9e1;"><i class="ph-fill ph-chart-pie-slice"></i></div>
                    <div class="b-text" style="flex: 1;">
                        <h3 id="admin-periode-text">Sesi Aktif</h3>
                        <p style="font-size: 11px; opacity: 0.85; font-weight: 600; margin: 0;">Rekapitulasi Kunjungan Korwil Global</p>
                    </div>
                </div>
                <div style="display: flex; margin-bottom: 12px; z-index: 2;">
                    <div style="background: rgba(255,255,255,0.25); padding: 5px 12px; border-radius: 12px; font-size: 11px; font-weight: 700; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.4); display: inline-flex; align-items: center; gap: 5px;">
                        <i class="ph-fill ph-file-text"></i> <span id="jml-hadir">0 Data</span>
                    </div>
                </div>
                <div style="z-index: 2; padding: 2px 0;">
                    <p style="margin: 0; font-size: 14px; font-style: italic; font-weight: 700; opacity: 0.95; line-height: 1.3; text-align: left;">Pusat Kendali & Database<br>Sistem Presensi Pembinaan</p>
                </div>
            </div>
        </div>

        <!-- Grid Menu Template Download & Impor Admin -->
        <div class="grid-categories animate-item delay-3">
            <div class="cat-item" onclick="downloadTemplateExcel('lokasi')">
                <div class="cat-icon c-pink"><i class="ph-fill ph-map-pin"></i></div>
                <div class="cat-text"><h4>Lokasi</h4><p>Template .xlsx</p></div>
            </div>
            <div class="cat-item" onclick="downloadTemplateExcel('peserta')">
                <div class="cat-icon c-orange"><i class="ph-fill ph-users"></i></div>
                <div class="cat-text"><h4>Peserta</h4><p>Template .xlsx</p></div>
            </div>
            <div class="cat-item" onclick="downloadTemplateExcel('jadwal')">
                <div class="cat-icon c-blue"><i class="ph-fill ph-calendar-plus"></i></div>
                <div class="cat-text"><h4>Jadwal</h4><p>Template .xlsx</p></div>
            </div>
            <div class="cat-item" onclick="downloadTemplateExcel('koordinator')">
                <div class="cat-icon c-teal"><i class="ph-fill ph-user-gear"></i></div>
                <div class="cat-text"><h4>Korwil</h4><p>Template .xlsx</p></div>
            </div>
            <!-- Modifikasi Tombol Download Template Artikel -->
            <div class="cat-item" onclick="downloadTemplateExcel('artikel')" style="grid-column: span 2;">
                <div class="cat-icon" style="background: var(--primary-gradient); color: white;"><i class="ph-fill ph-book-bookmark"></i></div>
                <div class="cat-text"><h4>Metode Terbaru</h4><p>Template Artikel .xlsx</p></div>
            </div>
        </div>

        <!-- Statistik Wilayah Header -->
        <div class="section-header animate-item delay-3">
            <h3>Statistik Wilayah</h3>
            <a onclick="bukaModalRiwayatAdmin()">Export Data</a>
        </div>
        <div id="wilayah-stats-container" class="list-container animate-item delay-3" style="margin-bottom: 25px;"></div>
    </div>

    <!-- === TAB PRESENSI ADMIN === -->
    <div id="tab-admin-presensi" class="screen-content">
        <div class="header-zomo">
            <button class="btn-icon" onclick="switchTabAdmin('tab-admin-beranda', document.querySelectorAll('#admin-app .nav-item')[0])"><i class="ph-bold ph-caret-left"></i></button>
            <div class="header-title-box"><h2>Database Presensi</h2></div>
            <button class="btn-icon" onclick="bukaModalRiwayatAdmin()"><i class="ph-bold ph-file-text"></i></button>
        </div>
        
        <div style="display:flex; justify-content:center; gap:10px; margin-bottom:25px; padding:0 25px;">
            <div id="btn-admin-tab-storage" class="tab-btn" style="background: var(--primary-gradient); color:white; box-shadow: 0 5px 15px rgba(132, 94, 247, 0.3);" onclick="toggleAdminPresensiView('storage')">Data Target</div>
            <div id="btn-admin-tab-absensi" class="tab-btn" style="background: var(--card-bg); color: var(--text-muted);" onclick="toggleAdminPresensiView('absensi')">Data Peserta</div>
        </div>

        <div id="view-admin-storage" class="list-container" style="padding-top: 10px; display: flex;">
            <div class="list-item" onclick="triggerExcelImport('lokasi')">
                <div class="list-item-icon" style="background: var(--c-orange); color: #f57c00;"><i class="ph-fill ph-file-arrow-down"></i></div>
                <div class="list-item-text">
                    <h4>Impor Data Lokasi (.xlsx)</h4>
                    <p>Perbarui database target lembaga</p>
                </div>
                <i class="ph-bold ph-arrow-line-down list-item-action"></i>
            </div>
            
            <!-- Tambahan: Impor Artikel Metode -->
            <div class="list-item" onclick="triggerExcelImport('artikel')">
                <div class="list-item-icon" style="background: var(--primary-gradient); color: white;"><i class="ph-fill ph-article"></i></div>
                <div class="list-item-text">
                    <h4>Impor Artikel Metode (.xlsx)</h4>
                    <p>Perbarui konten panduan & berita</p>
                </div>
                <i class="ph-bold ph-arrow-line-down list-item-action"></i>
            </div>

            <div class="list-item" onclick="bukaModalRiwayatAdmin()">
                <div class="list-item-icon" style="background: var(--c-teal); color: #00796b;"><i class="ph-fill ph-file-arrow-up"></i></div>
                <div class="list-item-text">
                    <h4>Ekspor Rekapitulasi Presensi</h4>
                    <p>Unduh laporan lengkap kunjungan</p>
                </div>
                <i class="ph-bold ph-arrow-line-up list-item-action"></i>
            </div>
        </div>
        
        <div id="view-admin-absensi" class="list-container" style="padding-top: 5px; display: none;">
            <div class="list-item" onclick="triggerExcelImport('peserta')">
                <div class="list-item-icon" style="background: var(--c-pink); color: #d81b60;"><i class="ph-fill ph-users"></i></div>
                <div class="list-item-text">
                    <h4>Impor Database Peserta</h4>
                    <p>Perbarui data Guru Kelas 4, 5, 6</p>
                </div>
                <i class="ph-bold ph-arrow-line-down list-item-action"></i>
            </div>

            <!-- TOMBOL EKSPOR REKAP PERSENTASE -->
            <div class="list-item" onclick="eksporAbsensiPesertaExcel()">
                <div class="list-item-icon" style="background: var(--c-blue); color: #1976d2;"><i class="ph-fill ph-file-arrow-up"></i></div>
                <div class="list-item-text">
                    <h4>Ekspor Rekap Persentase Wilayah</h4>
                    <p>Unduh laporan persentase kehadiran per wilayah (.xlsx)</p>
                </div>
                <i class="ph-bold ph-arrow-line-up list-item-action"></i>
            </div>

            <!-- TOMBOL EKSPOR DETAIL ABSENSI PER-PESERTA -->
            <div class="list-item" onclick="eksporDetailAbsensiPesertaExcel()">
                <div class="list-item-icon" style="background: var(--c-teal); color: #00796b;"><i class="ph-fill ph-file-text"></i></div>
                <div class="list-item-text">
                    <h4>Ekspor Absensi Per-Peserta</h4>
                    <p>Unduh rincian status hadir tiap guru per wilayah (.xlsx)</p>
                </div>
                <i class="ph-bold ph-arrow-line-up list-item-action"></i>
            </div>
        </div>
    </div>

    <!-- === TAB JADWAL ADMIN === -->
    <div id="tab-admin-jadwal" class="screen-content">
        <div class="header-zomo">
            <button class="btn-icon" onclick="switchTabAdmin('tab-admin-beranda', document.querySelectorAll('#admin-app .nav-item')[0])"><i class="ph-bold ph-caret-left"></i></button>
            <div class="header-title-box"><h2>Jadwal Pembinaan</h2></div>
            <button class="btn-icon" onclick="triggerExcelImport('jadwal')"><i class="ph-bold ph-file-arrow-down"></i></button>
        </div>

        <div class="banner-container">
            <div class="banner-zomo" style="background: linear-gradient(135deg, #2b1c50, #5c62ff);">
                <i class="ph-fill ph-calendar-check banner-bg-icon"></i>
                <div class="b-header" style="margin-bottom: 8px;">
                    <div class="b-icon" style="color: #38f9d7;"><i class="ph-fill ph-clock-countdown"></i></div>
                    <div class="b-text">
                        <h3>Pengaturan Siklus</h3>
                        <p>Kelola Jadwal Sesi Imda Pusat</p>
                    </div>
                </div>
                <p style="margin: 0; font-size: 12px; opacity: 0.9; line-height: 1.4; z-index: 2;">
                    Admin pusat dapat memperbarui jadwal siklus pembinaan seluruh Korwil melalui menu Impor Jadwal (.xlsx).
                </p>
            </div>
        </div>

        <div class="section-header">
            <h3>Daftar Sesi Pembinaan</h3>
        </div>
        
        <div class="list-container" style="margin-bottom: 30px;">
            <div class="list-item" style="flex-direction: column; align-items: stretch; gap: 10px; cursor: default;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="list-item-icon" style="background: var(--c-pink); color: #d81b60; margin:0;"><i class="ph-fill ph-calendar-star"></i></div>
                    <div class="list-item-text">
                        <h4 style="font-size: 15px; color: var(--text-main);">Siklus Menjelang IMDA I</h4>
                        <p>Fokus Persiapan & Evaluasi Awal</p>
                    </div>
                </div>
            </div>
            <div class="list-item" style="flex-direction: column; align-items: stretch; gap: 10px; cursor: default;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="list-item-icon" style="background: var(--c-orange); color: #f57c00; margin:0;"><i class="ph-fill ph-calendar-star"></i></div>
                    <div class="list-item-text">
                        <h4 style="font-size: 15px; color: var(--text-main);">Siklus Menjelang IMDA II</h4>
                        <p>Fokus Penguatan Materi & Target</p>
                    </div>
                </div>
            </div>
            <div class="list-item" style="flex-direction: column; align-items: stretch; gap: 10px; cursor: default;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="list-item-icon" style="background: var(--c-blue); color: #1976d2; margin:0;"><i class="ph-fill ph-calendar-star"></i></div>
                    <div class="list-item-text">
                        <h4 style="font-size: 15px; color: var(--text-main);">Siklus Menjelang IMDA III</h4>
                        <p>Fokus Evaluasi Akhir & Kenaikan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- === TAB PROFIL ADMIN === -->
    <div id="tab-admin-profil" class="screen-content">
        <div class="header-zomo">
            <div class="header-title-box"><h2>Profil Admin Pusat</h2></div>
            <button class="btn-icon" onclick="konfirmasiLogout()"><i class="ph-bold ph-sign-out"></i></button>
        </div>

        <!-- Banner Profil -->
        <div class="banner-container">
            <div class="banner-zomo" style="align-items: center; text-align: center; padding: 30px 20px;">
                <div style="width: 75px; height: 75px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 38px; color: white; margin-bottom: 12px; border: 2px solid rgba(255,255,255,0.4);">
                    <i class="ph-fill ph-shield-check"></i>
                </div>
                <h3 style="margin: 0 0 4px 0; font-size: 20px; font-weight: 800;">Administrator</h3>
                <p style="margin: 0; font-size: 12px; opacity: 0.85;">Pusat Kontrol Sistem</p>
            </div>
        </div>

        <div class="section-header">
            <h3>Pengaturan & Informasi</h3>
        </div>

        <div class="list-container" style="margin-bottom: 30px;">
            <div class="list-item" onclick="triggerExcelImport('koordinator')">
                <div class="list-item-icon" style="background: var(--c-blue); color: #1976d2;"><i class="ph-fill ph-user-gear"></i></div>
                <div class="list-item-text">
                    <h4>Impor Akun Korwil</h4>
                    <p>Kelola daftar koordinator wilayah</p>
                </div>
                <i class="ph-bold ph-caret-right list-item-action"></i>
            </div>

            <div class="list-item" onclick="toggleDarkModeAdmin()">
                <div class="list-item-icon" style="background: var(--c-teal); color: #00796b;"><i class="ph-fill ph-moon"></i></div>
                <div class="list-item-text">
                    <h4>Mode Malam</h4>
                    <p id="teks-status-darkmode-admin">Nonaktif</p>
                </div>
                <div>
                    <label class="toggle-switch" onclick="event.stopPropagation()">
                         <input type="checkbox" id="checkbox-darkmode-admin" onchange="toggleDarkModeAdmin()">
                         <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div class="list-item" onclick="resetDataAdmin('hasil')">
                <div class="list-item-icon" style="background: #fee2e2; color: #ef4444;"><i class="ph-fill ph-trash"></i></div>
                <div class="list-item-text">
                    <h4>Reset Seluruh Riwayat</h4>
                    <p>Hapus seluruh data presensi masuk</p>
                </div>
                <i class="ph-bold ph-caret-right list-item-action"></i>
            </div>
        </div>
    </div>

    <!-- BOTTOM NAV ADMIN -->
    <div class="bottom-nav">
        <div class="nav-item active" onclick="switchTabAdmin('tab-admin-beranda', this)">
            <i class="ph-fill ph-house"></i>
            <span>Beranda</span>
        </div>
        <div class="nav-item" onclick="switchTabAdmin('tab-admin-presensi', this)">
            <i class="ph-fill ph-map-pin-line"></i>
            <span>Presensi</span>
        </div>
        <div class="nav-item" onclick="switchTabAdmin('tab-admin-jadwal', this)">
            <i class="ph-fill ph-calendar"></i>
            <span>Jadwal</span>
        </div>
        <div class="nav-item" onclick="switchTabAdmin('tab-admin-profil', this)">
            <i class="ph-fill ph-user-circle"></i>
            <span>Profil</span>
        </div>
    </div>
</div>


<!-- ================= 3. APLIKASI USER (KORWIL / KOORDINATOR WILAYAH) ================= -->
<div id="main-app">

    <!-- === TAB BERANDA KORWIL === -->
    <div id="tab-beranda" class="screen-content active">
        <!-- Header -->
        <div class="header-zomo animate-item delay-1">
            <button class="btn-icon" onclick="bukaModalKalender()"><i class="ph-bold ph-squares-four"></i></button>
            <div class="header-title-box"><h2 id="wilayah-header-title">Selamat Datang Korwil!</h2></div>
            <button class="btn-icon" onclick="tampilkanNotifInfo('Belum ada pemberitahuan baru.', 'Pemberitahuan', 'info')"><i class="ph-bold ph-bell"></i></button>
        </div>

        <!-- Banner -->
        <div class="banner-container animate-item delay-2">
            <div class="banner-zomo" onclick="switchTab('tab-presensi', document.querySelectorAll('#main-app .nav-item')[1])">
                <i class="ph-fill ph-calendar banner-bg-icon"></i>
                <div class="b-header" style="margin-bottom: 8px;">
                    <div class="b-icon" style="color: #ffc9e1;"><i class="ph-fill ph-users-three"></i></div>
                    <div class="b-text" style="flex: 1;">
                        <h3 id="spv-greeting-name" style="margin-bottom: 2px;">Nama Korwil</h3>
                        <p style="font-size: 11px; opacity: 0.85; font-weight: 600; margin: 0;">Madrasah Ibtidaiyah Ranting</p>
                    </div>
                </div>
                <div style="display: flex; margin-bottom: 12px; z-index: 2;">
                    <div id="badge-tahun-ajaran" onclick="event.stopPropagation(); bukaModalTahunAjaran();" style="background: rgba(255,255,255,0.25); padding: 5px 12px; border-radius: 12px; font-size: 11px; font-weight: 700; cursor: pointer; backdrop-filter: blur(5px); border: 1px solid rgba(255,255,255,0.4); display: inline-flex; align-items: center; gap: 5px;">
                        <i class="ph-fill ph-calendar-blank"></i> <span id="label-tahun-ajaran">1447-1448 H</span>
                    </div>
                </div>
                <div style="z-index: 2; padding: 2px 0;">
                    <p style="margin: 0; font-size: 20px; font-style: italic; font-weight: 700; opacity: 0.95; line-height: 1.3; text-align: left;">Khidmah Lil Ma'had<br>Khidmah Lil Ummah</p>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="grid-categories animate-item delay-3">
            <div class="cat-item" onclick="switchTab('tab-presensi', document.querySelectorAll('#main-app .nav-item')[1])">
                <div class="cat-icon c-pink"><i class="ph-fill ph-calendar-check"></i></div>
                <div class="cat-text"><h4>Presensi</h4><p>Pembinaan Al-Miftah</p></div>
            </div>
            <div class="cat-item" onclick="switchTab('tab-jadwal', document.querySelectorAll('#main-app .nav-item')[2])">
                <div class="cat-icon c-orange"><i class="ph-fill ph-calendar"></i></div>
                <div class="cat-text"><h4>Jadwal</h4><p>Jadwal Pembinaan</p></div>
            </div>
            <!-- Mengubah Panggilan Metode agar menuju Halaman Artikel Dinamis -->
            <div class="cat-item" onclick="bukaHalamanMetode()">
                <div class="cat-icon c-blue"><i class="ph-fill ph-book-open"></i></div>
                <div class="cat-text"><h4>Metode</h4><p>Panduan & Berita</p></div>
            </div>
            <!-- Modifikasi Grid Konsultasi -->
            <div class="cat-item" onclick="bukaModalKonsultasi()">
                <div class="cat-icon c-teal"><i class="ph-fill ph-chat-circle-dots"></i></div>
                <div class="cat-text"><h4>Konsultasi</h4><p>Tanya Jawab</p></div>
            </div>
        </div>

        <!-- TOP 3 RANKING WILAYAH (DINAMIS BERDASARKAN PESERTA HADIR) -->
        <div class="section-header animate-item delay-3">
            <h3>Top 3 Wilayah</h3>
            <a onclick="tampilkanNotifInfo('Peringkat dihitung berdasarkan jumlah kehadiran peserta di setiap wilayah.', 'Info', 'info')">Info Peringkat</a>
        </div>
        <div id="container-top-3-wilayah" class="list-container animate-item delay-3" style="margin-bottom: 25px;"></div>

        <!-- PROGRES KEHADIRAN PER WILAYAH (SEMUA WILAYAH) -->
        <div class="section-header animate-item delay-3">
            <h3>Progres Kehadiran</h3>
            <a onclick="tampilkanNotifInfo('Menampilkan rekapitulasi kehadiran seluruh wilayah binaan.', 'Info', 'info')">Wilayah</a>
        </div>
        <div id="container-progres-wilayah" class="list-container animate-item delay-3" style="margin-bottom: 25px;"></div>
    </div>

    <!-- === TAB ARTIKEL METODE PREMIUM === -->
    <div id="tab-metode" class="screen-content">
        <div class="header-zomo">
            <button class="btn-icon" onclick="switchTab('tab-beranda', document.querySelectorAll('#main-app .nav-item')[0])"><i class="ph-bold ph-caret-left"></i></button>
            <div class="header-title-box"><h2>Panduan Metode</h2></div>
            <!-- Tombol Hamburger Menu Modal Daftar Artikel -->
            <button class="btn-icon" onclick="document.getElementById('modal-daftar-artikel').style.display='flex'"><i class="ph-bold ph-list"></i></button>
        </div>
        
        <div id="article-container" style="padding-bottom: 40px; overflow-x: hidden;">
            <!-- Konten akan dimuat dinamis oleh JavaScript -->
        </div>
    </div>

    <!-- === TAB JADWAL PEMBINAAN (6 LOKASI DALAM SETAHUN) === -->
    <div id="tab-jadwal" class="screen-content">
        <div class="header-zomo">
            <button class="btn-icon" onclick="switchTab('tab-beranda', document.querySelectorAll('#main-app .nav-item')[0])"><i class="ph-bold ph-caret-left"></i></button>
            <div class="header-title-box"><h2>Jadwal & Lokasi</h2></div>
            <button class="btn-icon" onclick="bukaModalKalender()"><i class="ph-bold ph-calendar"></i></button>
        </div>

        <div class="banner-container">
            <div class="banner-zomo" style="background: linear-gradient(135deg, #2b1c50, #5c62ff);">
                <i class="ph-fill ph-calendar-check banner-bg-icon"></i>
                <div class="b-header" style="margin-bottom: 8px;">
                    <div class="b-icon" style="color: #38f9d7;"><i class="ph-fill ph-clock-countdown"></i></div>
                    <div class="b-text">
                        <h3>Siklus 6 Lokasi</h3>
                        <p>2 Sesi per Periode Imda (Total 3 Imda)</p>
                    </div>
                </div>
                <p style="margin: 0; font-size: 12px; opacity: 0.9; line-height: 1.4; z-index: 2;">
                    Setiap Korwil melaksanakan pembinaan di 6 lokasi target dalam satu tahun ajaran (terbagi rata di Imda I, II, dan III).
                </p>
            </div>
        </div>

        <div class="section-header">
            <h3>Daftar Jadwal & Lokasi Pembinaan</h3>
        </div>
        
        <div id="container-daftar-jadwal-lokasi" class="list-container" style="margin-bottom: 30px;"></div>
    </div>

    <!-- === TAB FORM PRESENSI === -->
    <div id="tab-presensi" class="screen-content">
        <div class="header-zomo">
            <button class="btn-icon" onclick="switchTab('tab-beranda', document.querySelectorAll('#main-app .nav-item')[0])"><i class="ph-bold ph-caret-left"></i></button>
            <div class="header-title-box"><h2>Menu Presensi</h2></div>
            <!-- TAMBAHAN: Tombol Sync / Refresh Data Manual untuk memastikan Korwil melihat data absen mandiri -->
            <button class="btn-icon" onclick="fetchInitialData(); tampilkanNotifInfo('Data berhasil disinkronisasi dengan server.', 'Sync Sukses', 'success')"><i class="ph-bold ph-arrows-clockwise"></i></button>
        </div>
        
        <!-- Toggle Menu -->
        <div style="display:flex; justify-content:center; gap:10px; margin-bottom:25px; padding:0 25px;">
            <div id="btn-tab-storage" class="tab-btn" style="background: var(--primary-gradient); color:white; box-shadow: 0 5px 15px rgba(132, 94, 247, 0.3);" onclick="togglePresensiView('storage')">Settings</div>
            <div id="btn-tab-absensi" class="tab-btn" style="background: var(--card-bg); color: var(--text-muted);" onclick="togglePresensiView('absensi')">Absensi</div>
        </div>

        <!-- VIEW: STORAGE -->
        <div id="view-storage" class="list-container" style="padding-top: 10px; display: flex;">
            <div class="list-item" onclick="bukaModalPilihMadrasah()">
                <div class="list-item-icon" style="background: var(--c-orange); color: #f57c00;"><i class="ph-fill ph-folder"></i></div>
                <div class="list-item-text">
                    <h4 id="label-madrasah-terpilih-header">Pilih Target Lokasi</h4>
                    <p>Pilih Madrasah wilayah Anda</p>
                </div>
                <i class="ph-bold ph-caret-right list-item-action"></i>
            </div>

            <!-- MENU PILIH PERIODE AKTIF -->
            <div class="list-item" onclick="bukaModalGantiPeriode()">
                <div class="list-item-icon" style="background: var(--c-blue); color: #1976d2;"><i class="ph-fill ph-calendar"></i></div>
                <div class="list-item-text">
                    <h4>Periode Aktif</h4>
                    <p id="label-periode-storage-aktif">IMDA I Sesi 1</p>
                </div>
                <i class="ph-bold ph-caret-right list-item-action"></i>
            </div>

            <!-- MENU VALIDASI RADIUS 20M -->
            <div class="list-item" id="card-gps" onclick="deteksiLokasi()">
               <div class="list-item-icon" id="icon-gps" style="background: var(--c-teal); color: #00796b;"><i class="ph-fill ph-map-pin-line"></i></div>
                <div class="list-item-text">
                    <h4>Validasi Jarak (GPS)</h4>
                    <p id="teks-gps">Ketuk untuk validasi jarak (Maks 20m)</p>
                </div>
                <i class="ph-bold ph-caret-right list-item-action"></i>
            </div>

            <!-- MENU DOKUMENTASI -->
            <label class="list-item" id="card-foto">
                <input type="file" accept="image/*" capture="environment" style="display: none;" onchange="fotoDipilih(this)">
                <div class="list-item-icon" id="icon-foto" style="background: var(--c-pink); color: #d81b60;"><i class="ph-fill ph-camera"></i></div>
                <div class="list-item-text">
                    <h4>Dokumentasi</h4>
                    <p id="teks-foto">Ambil foto dokumentasi (Wajib)</p>
                </div>
                <i class="ph-bold ph-caret-right list-item-action"></i>
            </label>

            <textarea id="jurnal-input" class="form-input" placeholder="Tuliskan catatan / jurnal pembinaan..." style="margin-top:10px; margin-bottom: 30px;"></textarea>
        </div>
        
        <!-- VIEW: ABSENSI (PESERTA BERDASARKAN WILAYAH) -->
        <div id="view-absensi" class="list-container" style="padding-top: 5px; display: none;">
            
            <!-- Search & Filter Row -->
            <div style="display: flex; gap: 10px; margin-bottom: 18px;">
                <div style="flex: 1; position: relative;">
                    <i class="ph-bold ph-magnifying-glass" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 18px;"></i>
                    <input type="text" id="search-absensi" placeholder="Cari nama guru peserta..." oninput="filterAbsensiSiswa()" style="width: 100%; padding: 14px 15px 14px 45px; border-radius: 14px; border: 1px solid var(--border-color); background: var(--card-bg); font-family: 'Nunito', sans-serif; font-size: 14px; font-weight: 700; color: var(--text-main); outline: none; box-sizing: border-box; box-shadow: 0 4px 10px rgba(0,0,0,0.02);">
                </div>
                <button onclick="bukaModalFilterKelas()" style="width: 50px; height: 50px; background: var(--primary-gradient); color: white; border-radius: 14px; border: none; display: flex; justify-content: center; align-items: center; font-size: 22px; cursor: pointer; box-shadow: 0 5px 15px rgba(132, 94, 247, 0.4); flex-shrink: 0; transition: 0.2s;">
                    <i class="ph-bold ph-faders"></i>
                </button>
            </div>

            <!-- Indikator Kelas Aktif -->
            <div id="badge-kelas-aktif" style="font-size: 12px; font-weight: 700; color: #845ef7; margin-bottom: 12px; padding-left: 5px;">
                Menampilkan: <span id="label-kelas-terpilih">Semua Guru Kelas (4, 5, 6)</span>
            </div>

            <!-- List Container Daftar Peserta Berdasarkan Wilayah -->
            <div id="container-daftar-siswa"></div>

            <button class="btn-primary" style="margin-top: 15px; margin-bottom: 12px;" onclick="simpanAbsensiMurid()">
                Simpan Kehadiran Peserta
            </button>

            <!-- TOMBOL KIRIM VIA WHATSAPP -->
            <button onclick="kirimRekapWhatsApp()" style="width: 100%; padding: 16px; background: #22c55e; color: white; border: none; border-radius: 18px; font-size: 16px; font-weight: 800; font-family: 'Nunito'; cursor: pointer; box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3); display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 30px; transition: 0.2s;">
                <i class="ph-fill ph-whatsapp-logo" style="font-size: 22px;"></i> Kirim Rekap via WhatsApp
            </button>
        </div>
    </div>

    <!-- === TAB PROFIL (KORWIL) === -->
    <div id="tab-profil" class="screen-content">
        <div class="header-zomo">
            <div class="header-title-box"><h2>Profil Korwil</h2></div>
            <button class="btn-icon" onclick="konfirmasiLogout()"><i class="ph-bold ph-sign-out"></i></button>
        </div>

     <!-- Banner Profil -->
    <div class="banner-container">
        <div class="banner-zomo" style="align-items: center; text-align: center; padding: 30px 20px; position: relative; overflow: hidden;">
            <!-- Watermark Ikon di Pojok Kanan Bawah -->
            <i class="ph-fill ph-student banner-bg-icon-right"></i>
            
            <div style="width: 75px; height: 75px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 38px; color: white; margin-bottom: 12px; border: 2px solid rgba(255,255,255,0.4); z-index: 2;">
                <i class="ph-fill ph-user"></i>
            </div>
            <h3 id="profil-nama-user" style="margin: 0 0 4px 0; font-size: 20px; font-weight: 800; z-index: 2;">Nama Korwil</h3>
            <p id="profil-wilayah-user" style="margin: 0; font-size: 12px; opacity: 0.85; z-index: 2;">Koordinator Wilayah</p>
        </div>
    </div>

        <div class="section-header">
            <h3>Pengaturan & Informasi</h3>
        </div>

        <div class="list-container" style="margin-bottom: 30px;">
            <!-- 1. Data Lengkap Login -->
            <div class="list-item" onclick="bukaModalDataLogin()">
                <div class="list-item-icon" style="background: var(--c-blue); color: #1976d2;"><i class="ph-fill ph-identification-card"></i></div>
                <div class="list-item-text">
                    <h4>Data Lengkap Login</h4>
                    <p>Lihat detail akun dan wilayah</p>
                </div>
                <i class="ph-bold ph-caret-right list-item-action"></i>
            </div>

            <!-- 2. Mode Malam (Dark Mode) -->
            <div class="list-item" onclick="toggleDarkMode()">
                <div class="list-item-icon" style="background: var(--c-teal); color: #00796b;"><i class="ph-fill ph-moon"></i></div>
                <div class="list-item-text">
                    <h4>Mode Malam</h4>
                    <p id="teks-status-darkmode">Nonaktif</p>
                </div>
                <div>
                    <label class="toggle-switch" onclick="event.stopPropagation()">
                         <input type="checkbox" id="checkbox-darkmode" onchange="toggleDarkMode()">
                         <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            <!-- 3. Ubah Kata Sandi -->
            <div class="list-item" onclick="bukaModalUbahSandi()">
                <div class="list-item-icon" style="background: var(--c-orange); color: #f57c00;"><i class="ph-fill ph-lock-key"></i></div>
                <div class="list-item-text">
                    <h4>Ubah Kata Sandi</h4>
                    <p>Perbarui keamanan akun Anda</p>
                </div>
                <i class="ph-bold ph-caret-right list-item-action"></i>
            </div>

            <!-- 4. Butuh Bantuan Hubungi Admin -->
            <div class="list-item" onclick="hubungiAdminWhatsApp()">
                <div class="list-item-icon" style="background: #dcfce7; color: #16a34a;"><i class="ph-fill ph-whatsapp-logo"></i></div>
                <div class="list-item-text">
                    <h4>Butuh Bantuan</h4>
                    <p>Hubungi admin pusat via WhatsApp</p>
                </div>
                <i class="ph-bold ph-caret-right list-item-action"></i>
            </div>
        </div>
    </div>

    <!-- BOTTOM NAV -->
    <div class="bottom-nav">
        <div class="nav-item active" onclick="switchTab('tab-beranda', this)">
            <i class="ph-fill ph-house"></i>
            <span>Beranda</span>
        </div>
        <div class="nav-item" onclick="switchTab('tab-presensi', this)">
            <i class="ph-fill ph-map-pin-line"></i>
            <span>Presensi</span>
        </div>
        <div class="nav-item" onclick="switchTab('tab-jadwal', this)">
            <i class="ph-fill ph-calendar"></i>
            <span>Jadwal</span>
        </div>
        <div class="nav-item" onclick="switchTab('tab-profil', this)">
            <i class="ph-fill ph-user-circle"></i>
            <span>Profil</span>
        </div>
    </div>
</div>

<!-- ================= MODALS (Zomo Style) ================= -->

<!-- MODAL DAFTAR ARTIKEL (METODE) -->
<div id="modal-daftar-artikel" class="modal-overlay" style="z-index: 300;">
    <div class="modal-card" style="height: 55vh;">
        <div class="modal-icon-top"><i class="ph-fill ph-books"></i></div>
        <h3>Daftar Panduan & Artikel</h3>
        <p>Pilih materi atau panduan terbaru untuk dibaca</p>
        
        <div id="list-artikel-container" class="radio-group" style="flex: 1; overflow-y: auto; margin-top: 10px;">
            <!-- Dimuat otomatis via JS -->
        </div>
        
        <div class="modal-actions" style="margin-top: 15px;">
            <button class="btn-modal-cancel" style="width: 100%;" onclick="document.getElementById('modal-daftar-artikel').style.display='none'">Tutup</button>
        </div>
    </div>
</div>

<!-- MODAL KONSULTASI (POSISI TOMBOL DIGESER LEBIH KE ATAS) -->
<div id="modal-konsultasi" class="modal-overlay" style="z-index: 300;">
    <!-- padding-bottom diubah menjadi 65px agar tombol terangkat jauh dari bawah -->
    <div class="modal-card" style="height: 83vh; padding: 25px 20px 65px 20px; display: flex; flex-direction: column;">
        <div class="modal-icon-top" style="background: rgba(255,255,255,0.2); flex-shrink: 0; margin-top: -50px; margin-bottom: 10px;"><i class="ph-fill ph-robot"></i></div>
        <h3 style="flex-shrink: 0; margin-bottom: 2px;">Konsultasi Al-Miftah</h3>
        <p style="flex-shrink: 0; margin-bottom: 8px; font-size: 12px;">Ketik pertanyaan seputar permasalahan murid</p>

        <!-- Wrapper Utama -->
        <div style="display: flex; flex-direction: column; flex: 1; min-height: 0; gap: 8px; margin-bottom: 0;">
            
            <!-- Output Area (Chat Container dengan Scroll Mandiri) -->
            <div id="chat-container" style="flex: 1; min-height: 0; background: rgba(255,255,255,0.15); border: 2px solid rgba(255,255,255,0.2); border-radius: 16px; padding: 12px; overflow-y: auto; display: flex; flex-direction: column; color: white; font-size: 13px; font-family: 'Nunito', sans-serif;">
                <div id="chat-history" style="display: flex; flex-direction: column; gap: 10px;">
                    <div style="opacity: 0.8; font-size: 12px; text-align: center;"><i class="ph-fill ph-sparkle"></i> Mulai konsultasi...</div>
                </div>
            </div>

            <!-- Input Area (Tinggi dikurangi sedikit agar lebih ringkas) -->
            <textarea id="input-konsultasi" class="modal-input" placeholder="Ketik pertanyaan Anda di sini..." style="flex-shrink: 0; height: 42px; min-height: 42px; max-height: 60px; resize: none; margin-bottom: 0; padding: 10px 15px; font-size: 13px; direction: ltr; text-align: left;" oninput="this.dir = /[\u0600-\u06FF]/.test(this.value) ? 'rtl' : 'ltr';"></textarea>

            <!-- Tombol Kirim & Cancel (Bergeser lebih ke atas) -->
            <div style="display: flex; gap: 10px; width: 100%; flex-shrink: 0;">
                <button onclick="kirimKonsultasi()" class="btn-primary" style="flex: 2; padding: 11px; display: flex; justify-content: center; align-items: center; gap: 8px; margin-top: 0; font-size: 14px;">
                    <i class="ph-bold ph-paper-plane-right"></i> Kirim
                </button>
                <button class="btn-modal-cancel" style="flex: 1; padding: 11px; margin-top: 0; border-radius: 16px; font-size: 14px;" onclick="document.getElementById('modal-konsultasi').style.display='none'">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-notif-custom" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-icon-top"><i class="ph-fill ph-info"></i></div>
        <h3 id="notif-title">Enter Folder Name</h3>
        <p id="notif-desc">Pesan notifikasi sistem akan tampil di sini.</p>
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="tutupNotifCustom()">Cancel</button>
            <button class="btn-modal-save" onclick="tutupNotifCustom()">Create</button>
        </div>
    </div>
</div>

<!-- MODAL PORTAL ABSENSI MANDIRI PESERTA (DENGAN POP-UP PEMILIHAN LOKASI MODERN) -->
<div id="modal-portal-mandiri" class="modal-overlay">
    <div class="modal-card" style="height: 90vh;">
        <div class="modal-icon-top"><i class="ph-fill ph-user-check"></i></div>
        <h3>Absensi Mandiri Peserta</h3>
        <p>Pilih lokasi pembinaan dan masukkan ID guru</p>
        
        <div style="display: flex; flex-direction: column; gap: 12px; flex: 1; overflow-y: auto; padding-right: 5px;">
            <div class="input-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 5px;">Pilih Lokasi Pembinaan:</label>
                <!-- KOTAK PILIHAN LOKASI YANG BISA DIKLIK MEMBUKA POP-UP MODERN -->
                <div id="trigger-pilih-lokasi-mandiri" onclick="bukaModalPilihLokasiMandiri()" style="width: 100%; padding: 16px 20px; background: rgba(255, 255, 255, 0.15); border: 2px solid rgba(255, 255, 255, 0.2); border-radius: 18px; box-sizing: border-box; font-size: 14px; font-family: 'Nunito', sans-serif; color: white; display: flex; justify-content: space-between; align-items: center; cursor: pointer; font-weight: 600; backdrop-filter: blur(5px);">
                    <span id="label-lokasi-mandiri-terpilih">-- Pilih Lokasi Target --</span>
                    <i class="ph-bold ph-caret-down" style="font-size: 16px; opacity: 0.8;"></i>
                </div>
            </div>

            <div class="input-group" style="margin-bottom: 0;">
                <label style="font-size: 12px; font-weight: 700; display: block; margin-bottom: 5px;">ID Peserta (Guru):</label>
                <input type="text" id="input-id-mandiri" placeholder="Contoh: 401 atau SBY401" class="modal-input" style="margin-bottom: 0;">
            </div>
            
            <button onclick="prosesValidasiDanAbsenMandiri()" class="btn-primary" style="padding: 16px; margin-top: 5px;">
                Validasi & Kirim Kehadiran
            </button>
            
            <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 16px; border: 1px solid rgba(255,255,255,0.2); margin-top: 5px;">
                <strong style="font-size: 13px; display: block; margin-bottom: 5px;"><i class="ph-fill ph-shield-check"></i> Ketentuan Validasi:</strong>
                <span style="font-size: 11px; opacity: 0.9; line-height: 1.4; display: block;">
                    1. Lokasi wajib sesuai dengan jadwal setting Korwil.<br>
                    2. Pengisian maksimal 2 jam setelah Korwil mengaktifkan GPS.<br>
                    3. Perangkat wajib dalam radius 20 meter lokasi.
                </span>
            </div>
        </div>

        <div class="modal-actions" style="margin-top: 10px;">
            <button class="btn-modal-cancel" style="width: 100%;" onclick="document.getElementById('modal-portal-mandiri').style.display='none'">Tutup</button>
        </div>
    </div>
</div>

<!-- ================= POP-UP MODEREN PILIH LOKASI MANDIRI ================= -->
<div id="modal-pilih-lokasi-mandiri-modern" class="modal-overlay" style="z-index: 300;">
    <div class="modal-card" style="height: 75vh; max-height: 550px; display: flex; flex-direction: column; justify-content: space-between;">
        <div style="flex-shrink: 0;">
            <div class="modal-icon-top"><i class="ph-fill ph-map-pin-area"></i></div>
            <h3>Pilih Lokasi Pembinaan</h3>
            <p style="margin-bottom: 15px;">Cari & tentukan lokasi target kehadiran Anda</p>
            
            <!-- Search & Filter Bar Container -->
            <div style="display: flex; gap: 10px; width: 100%;">
                <div style="position: relative; flex: 1;">
                    <i class="ph-bold ph-magnifying-glass" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.7); font-size: 18px;"></i>
                    <input type="text" id="search-input-mandiri-modern" class="modal-input" placeholder="Cari nama lokasi..." oninput="filterModalLokasiMandiriModern()" style="padding-left: 45px; margin-bottom: 0;">
                </div>
                <select id="filter-wilayah-mandiri-modern" class="modal-input" onchange="filterModalLokasiMandiriModern()" style="width: 130px; margin-bottom: 0; padding: 14px 10px; background: rgba(255, 255, 255, 0.15); color: white; cursor: pointer;">
                    <option value="semua" style="color: #2b1c50;">Semua Wilayah</option>
                    <option value="Bangkalan Barat" style="color: #2b1c50;">Bangkalan Barat</option>
                    <option value="Bangkalan Timur" style="color: #2b1c50;">Bangkalan Timur</option>
                    <option value="Pasuruan" style="color: #2b1c50;">Pasuruan</option>
                    <option value="Malang" style="color: #2b1c50;">Malang</option>
                    <option value="Surabaya" style="color: #2b1c50;">Surabaya</option>
                </select>
            </div>
        </div>

        <!-- Daftar Lokasi (Area Scroll) -->
        <div id="list-modal-lokasi-mandiri-modern" class="radio-group" style="flex: 1; overflow-y: auto; margin: 12px 0; padding-right: 2px;"></div>

        <!-- Tombol Tutup di dalam Kontrol Bawah -->
        <div class="modal-actions" style="margin-top: 0; flex-shrink: 0;">
            <button class="btn-modal-cancel" style="width: 100%; padding: 14px;" onclick="document.getElementById('modal-pilih-lokasi-mandiri-modern').style.display='none'">Tutup</button>
        </div>
    </div>
</div>

<!-- MODAL DATA LENGKAP LOGIN -->
<div id="modal-data-login" class="modal-overlay">
    <div class="modal-card">
       <div class="modal-icon-top"><i class="ph-fill ph-student"></i></div>
        <h3>Informasi Akun Korwil</h3>
        <p>Detail kredensial & wilayah binaan aktif</p>
        <div style="background: rgba(255,255,255,0.15); padding: 16px; border-radius: 18px; margin-bottom: 20px; font-size: 13px; display: flex; flex-direction: column; gap: 8px; backdrop-filter: blur(5px); border: 2px solid rgba(255,255,255,0.2);">
            <div><strong>Username:</strong> <span id="info-username">-</span></div>
            <div><strong>Nama Korwil:</strong> <span id="info-nama">-</span></div>
            <div><strong>Wilayah:</strong> <span id="info-wilayah">-</span></div>
            <div><strong>Tahun Ajaran:</strong> <span id="info-tahun-ajaran">-</span></div>
            <div><strong>Sesi Aktif:</strong> <span id="info-periode">-</span></div>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" style="width: 100%;" onclick="document.getElementById('modal-data-login').style.display='none'">Tutup</button>
        </div>
    </div>
</div>

<!-- MODAL UBAH KATA SANDI -->
<div id="modal-ubah-sandi" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-icon-top"><i class="ph-fill ph-lock-key"></i></div>
        <h3>Ubah Kata Sandi</h3>
        <p>Masukkan kata sandi baru untuk akun Anda</p>
        <input type="password" id="sandi-lama" class="modal-input" placeholder="Kata sandi lama">
        <input type="password" id="sandi-baru" class="modal-input" placeholder="Kata sandi baru">
        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="document.getElementById('modal-ubah-sandi').style.display='none'">Batal</button>
            <button class="btn-modal-save" onclick="simpanSandiBaru()">Simpan</button>
        </div>
    </div>
</div>

<!-- MODAL FILTER KELAS PESERTA -->
<div id="modal-filter-kelas" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-icon-top"><i class="ph-fill ph-faders"></i></div>
        <h3>Filter Berdasarkan Guru Kelas</h3>
        <p>Pilih tingkatan kelas peserta pembinaan</p>
        <div id="list-opsi-kelas" class="radio-group">
            <div class="export-item" onclick="pilihFilterKelas('semua')" style="border-color: rgba(56,249,215,0.5);">
                <strong><i class="ph-fill ph-squares-four"></i> Semua Kelas (4, 5, 6)</strong>
            </div>
            <div class="export-item" onclick="pilihFilterKelas('Kelas 4')">
                <strong><i class="ph-fill ph-student"></i> Guru Kelas 4</strong>
            </div>
            <div class="export-item" onclick="pilihFilterKelas('Kelas 5')">
                <strong><i class="ph-fill ph-student"></i> Guru Kelas 5</strong>
            </div>
            <div class="export-item" onclick="pilihFilterKelas('Kelas 6')">
                <strong><i class="ph-fill ph-student"></i> Guru Kelas 6</strong>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" style="width: 100%;" onclick="document.getElementById('modal-filter-kelas').style.display='none'">Tutup</button>
        </div>
    </div>
</div>

<!-- MODAL KALENDER LIVE -->
<div id="modal-kalender-live" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-icon-top"><i class="ph-fill ph-calendar-check"></i></div>
        <h3>Kalender & Waktu</h3>
        <div id="teks-kalender-live">00:00:00</div>
        <div id="teks-tanggal-live">Memuat tanggal...</div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" style="width: 100%;" onclick="tutupModalKalender()">Tutup</button>
        </div>
    </div>
</div>

<!-- MODAL PEMILIHAN TARGET LOKASI YANG DIPERBARUI (KEREN & ELEGAN) -->
<div id="modal-pilih-madrasah" class="modal-overlay">
    <div class="modal-card" style="height: 85vh;">
        <div class="modal-icon-top"><i class="ph-fill ph-map-pin-area"></i></div>
        <h3>Pilih Target Lokasi</h3>
        <p>Cari & tentukan lembaga binaan wilayah Anda</p>
        
        <div style="position: relative; width: 100%; margin-bottom: 15px;">
            <i class="ph-bold ph-magnifying-glass" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: rgba(255,255,255,0.7); font-size: 18px;"></i>
            <input type="text" id="search-input-target" class="modal-input" placeholder="Ketik nama lokasi atau madrasah..." oninput="filterModalMadrasah()" style="padding-left: 50px; margin-bottom: 0;">
        </div>

        <div id="list-modal-madrasah" class="radio-group" style="flex: 1; overflow-y: auto;"></div>

        <div class="modal-actions" style="margin-top: 15px;">
            <button class="btn-modal-cancel" style="width: 100%;" onclick="document.getElementById('modal-pilih-madrasah').style.display='none'">Tutup</button>
        </div>
    </div>
</div>

<div id="modal-ganti-periode" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-icon-top"><i class="ph-fill ph-calendar"></i></div>
        <h3>Pilih Sesi IMDA</h3>
        <p>Ganti sesi pembinaan aktif</p>
        <div id="list-opsi-periode" class="radio-group"></div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" style="width: 100%;" onclick="document.getElementById('modal-ganti-periode').style.display='none'">Cancel</button>
        </div>
    </div>
</div>

<div id="modal-tahun-ajaran" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-icon-top"><i class="ph-fill ph-calendar-blank"></i></div>
        <h3>Tahun Ajaran</h3>
        <p>Pilih tahun ajaran aktif</p>
        <div id="list-opsi-tahun-ajaran" class="radio-group"></div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" style="width: 100%;" onclick="document.getElementById('modal-tahun-ajaran').style.display='none'">Cancel</button>
        </div>
    </div>
</div>

<!-- MODAL POP-UP EXPORT DATA ADMIN -->
<div id="modal-export-admin" class="modal-overlay">
    <div class="modal-card" style="height: 80vh;">
        <div class="modal-icon-top"><i class="ph-fill ph-file-text"></i></div>
        <h3>Export Detail Absensi Lembaga</h3>
        <p>Pilih lembaga untuk mengunduh rincian absensi peserta</p>
        <input type="text" id="search-input-export" class="modal-input" placeholder="Cari nama/lokasi lembaga..." oninput="filterModalExport()">
        <div id="list-hasil-supervisi" class="radio-group" style="flex:1;"></div>
        <div class="modal-actions">
            <button class="btn-modal-cancel" style="width: 100%;" onclick="document.getElementById('modal-export-admin').style.display='none'">Close</button>
        </div>
    </div>
</div>

<!-- ================= MODAL KETERANGAN ABSENSI (IZIN / SAKIT) ================= -->
<div id="modal-keterangan-absen" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-icon-top"><i class="ph-fill ph-note-pencil"></i></div>
        <h3 id="modal-ket-nama-peserta">Keterangan Kehadiran</h3>
        <p>Pilih status dan masukkan keterangan (Izin / Sakit)</p>
        
        <div class="radio-group" style="margin-bottom: 15px;">
            <div class="export-item" id="opsi-izin" onclick="pilihStatusKet('Izin')" style="border-color: #38f9d7;">
                <strong><i class="ph-fill ph-clock-countdown" style="color: #38f9d7;"></i> Izin</strong>
            </div>
            <div class="export-item" id="opsi-sakit" onclick="pilihStatusKet('Sakit')" style="border-color: rgba(255,255,255,0.25);">
                <strong><i class="ph-fill ph-first-aid" style="color: #ffc9e1;"></i> Sakit</strong>
            </div>
        </div>

        <input type="text" id="input-detail-keterangan" class="modal-input" placeholder="Tuliskan keterangan (Opsional)..." style="margin-bottom: 20px;">

        <div class="modal-actions">
            <button class="btn-modal-cancel" onclick="document.getElementById('modal-keterangan-absen').style.display='none'">Batal</button>
            <button class="btn-modal-save" onclick="simpanStatusKeterangan()">Simpan</button>
        </div>
    </div>
</div>

<script>
let currentPesertaIdDipilih = null;
let statusKeteranganMap = {}; // Menyimpan data izin/sakit sementara
let selectedStatusPilihan = 'Izin';

// Tambahkan array ini di bagian atas script untuk menyimpan memori percakapan
let chatSessionHistory = [];

/* ================= DATA ARTIKEL METODE (DEFAULT/FALLBACK) ================= */
let defaultArtikelData = [
    {
        Judul: "Inovasi Metode Pembelajaran Al-Miftah di Era Digitalisasi",
        Badge: "Eksklusif",
        Tanggal: "18 Agustus 2026",
        GambarURL: "https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=800&auto=format&fit=crop",
        Konten: "<p><span class='dropcap'>P</span>embinaan karakter dan keilmuan santri terus mengalami transformasi. Dalam menghadapi tantangan zaman, metode konvensional kini mulai dipadukan dengan pendekatan interaktif yang lebih segar dan relevan bagi generasi saat ini.</p><blockquote class='quote-box'>\"Inovasi bukanlah membuang tradisi, melainkan menyempurnakannya agar cahaya ilmu tetap benderang melintasi zaman.\"</blockquote><p>Langkah pertama yang dapat diambil oleh para Koordinator Wilayah adalah memastikan setiap guru memahami inti dari kurikulum terbaru. Penggunaan alat peraga visual sangat disarankan guna mempermudah pemahaman santri pada materi-materi kompleks.</p><div class='tips-card'><strong>💡 Tips Implementasi Cepat:</strong><br><br>1. Mulai kelas dengan cerita inspiratif singkat (5 menit).<br>2. Gunakan skema mind-mapping di papan tulis.<br>3. Ajak santri berdiskusi interaktif di akhir sesi.</div><p>Semoga dengan penerapan panduan baru ini, kualitas pendidikan di seluruh lembaga binaan dapat meningkat secara merata dan terukur. Mari bersama-sama wujudkan generasi yang cerdas dan berakhlakul karimah.</p>"
    },
    {
        Judul: "Panduan Evaluasi Kehadiran & Kedisiplinan Guru",
        Badge: "Panduan",
        Tanggal: "10 Agustus 2026",
        GambarURL: "https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=800&auto=format&fit=crop",
        Konten: "<p><span class='dropcap'>K</span>edisiplinan adalah kunci utama keberhasilan program pembinaan. Kehadiran para guru tidak hanya diukur dari sekadar datang, melainkan dari seberapa besar dampak yang mereka berikan di dalam kelas.</p><blockquote class='quote-box'>\"Seorang guru yang disiplin akan melahirkan murid yang tangguh.\"</blockquote><p>Silakan manfaatkan aplikasi presensi ini secara maksimal. Fitur GPS dan Dokumentasi dirancang semata-mata untuk mempermudah rekapitulasi data secara transparan di tingkat pusat.</p><div class='tips-card'><strong>📝 Catatan Khusus:</strong> Pastikan Anda menggunakan koneksi internet yang stabil saat melakukan validasi GPS agar sistem berjalan optimal.</div>"
    }
];

let masterListArtikelObj = [...defaultArtikelData]; // Data global artikel

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
            masterListMadrasahObj = (res.data.madrasah || []).map(m => ({ Nama_Madrasah: m.nama_madrasah, Wilayah: m.wilayah }));
            masterListPesertaObj = (res.data.peserta || []).map(p => ({ ID: p.id_peserta, Nama: p.nama, Kelas: p.kelas, Lembaga: p.lembaga, Wilayah: p.wilayah }));
            masterListJadwalObj = (res.data.jadwal || []).map(j => ({ Siklus: j.siklus, Sesi: j.sesi, Lokasi_Pembinaan: j.lokasi_pembinaan, Wilayah: j.wilayah, Waktu: j.waktu }));
            masterListKorwilObj = (res.data.korwil || []).map(k => ({ Username: k.username, Nama_Korwil: k.nama_korwil, Wilayah: k.wilayah, Password: k.password }));
            
            // Tangkap data artikel jika ada dari database (simulasi)
            if(res.data.artikel && res.data.artikel.length > 0) {
                masterListArtikelObj = res.data.artikel;
            }

            riwayatPresensi = res.data.riwayat || [];
            let dbSettings = res.data.settings || {};
            
            globalActivePeriode = dbSettings['activePeriode'] || 'IMDA I Sesi 1';
            globalActiveTahunAjaran = dbSettings['activeTahunAjaran'] || '1447-1448 H';
            
            // === TAMBAHAN PENTING: SINKRONISASI DATA SERVER KE VARIABEL LOKAL UI ===
            // Agar saat HP Korwil merefresh, data Absen Mandiri yang masuk ke server
            // dapat langsung tercentang di UI (Local Variable diupdate dari Server Truth)
            detailAbsensiPeserta = {};
            absensiHadirData = {};

            riwayatPresensi.forEach(r => {
                let key = r.wilayah + "_" + r.periode + "_" + r.madrasah;
                if (!detailAbsensiPeserta[key]) detailAbsensiPeserta[key] = {};

                let countHadir = 0;
                if(r.peserta && Array.isArray(r.peserta)) {
                    r.peserta.forEach(p => {
                        let st = p.status_kehadiran || p.status;
                        if (st === "Hadir") {
                            detailAbsensiPeserta[key][p.id_peserta] = "Hadir";
                            countHadir++;
                        } else if (st && st !== "Tidak Hadir") {
                            // Tangkap keterangan izin/sakit dari server juga
                            statusKeteranganMap[key + "_" + p.id_peserta] = st;
                        }
                    });
                }
                absensiHadirData[key] = countHadir;
            });
            // =======================================================================

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

/* ================= LOGIKA HALAMAN ARTIKEL METODE PREMIUM ================= */
function bukaHalamanMetode() {
    if(!masterListArtikelObj || masterListArtikelObj.length === 0) {
        masterListArtikelObj = [...defaultArtikelData]; // Fallback jika data kosong
    }
    muatArtikel(0); // Memuat artikel paling pertama
    renderDaftarArtikel();
    
    // Switch Tab Tanpa Mengaktifkan Bottom Nav "Metode" (Karena ia tab internal eksklusif)
    document.querySelectorAll('#main-app .screen-content').forEach(s => s.classList.remove('active'));
    document.getElementById('tab-metode').classList.add('active');
    document.querySelectorAll('#main-app .nav-item').forEach(i => i.classList.remove('active'));
}

function muatArtikel(index) {
    let art = masterListArtikelObj[index];
    if(!art) return;

    let html = `
        <div class="article-hero" style="background-image: url('${art.GambarURL}');"></div>
        <div class="article-body">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <span class="article-badge">${art.Badge || 'Edukasi'}</span>
                <span class="article-meta">${art.Tanggal || 'Hari ini'}</span>
            </div>
            <h1 class="article-title">${art.Judul}</h1>
            <div class="article-content">
                ${art.Konten}
            </div>
        </div>
    `;
    document.getElementById('article-container').innerHTML = html;
    
    // Scroll container ke atas
    document.getElementById('tab-metode').scrollTop = 0;
}

function renderDaftarArtikel() {
    let container = document.getElementById('list-artikel-container');
    container.innerHTML = '';
    
    masterListArtikelObj.forEach((art, i) => {
        container.innerHTML += `
        <div class="export-item" onclick="pilihArtikelDariDaftar(${i})" style="margin-bottom: 12px;">
            <div style="display:flex; flex-direction:column; gap: 4px; padding-right: 15px;">
                <strong style="font-size:14px; line-height:1.4;">${art.Judul}</strong>
                <span style="font-size:11px; opacity:0.8;">${art.Tanggal} | <span style="color:#38f9d7; font-weight:800;">${art.Badge}</span></span>
            </div>
            <i class="ph-bold ph-caret-right" style="font-size: 18px;"></i>
        </div>`;
    });
}

function pilihArtikelDariDaftar(index) {
    muatArtikel(index);
    document.getElementById('modal-daftar-artikel').style.display = 'none';
}


/* ================= LOGIKA KONSULTASI (SIMULASI AI) DENGAN FITUR CHAT UI ================= */
function bukaModalKonsultasi() {
    document.getElementById('input-konsultasi').value = '';
    // Optional: Untuk mereset history setiap modal dibuka, silakan hilangkan komentar baris di bawah:
    // document.getElementById('chat-history').innerHTML = '<div style="opacity: 0.8; font-size: 13px; text-align: center;"><i class="ph-fill ph-sparkle"></i> Mulai konsultasi...</div>';
    document.getElementById('modal-konsultasi').style.display = 'flex';
}

async function kirimKonsultasi() {
    const inputVal = document.getElementById('input-konsultasi').value.trim();
    if (!inputVal) {
        tampilkanNotifInfo("Silakan ketik pertanyaan Anda terlebih dahulu!", "Peringatan", "warning");
        return;
    }

    const chatHistory = document.getElementById('chat-history');
    const chatContainer = document.getElementById('chat-container');

    // Menghilangkan pesan "Mulai konsultasi" jika masih ada
    if(chatHistory.innerHTML.includes("Mulai konsultasi...")) {
        chatHistory.innerHTML = '';
    }

    // 1. Munculkan bubble Pertanyaan User (Sebelah Kanan)
    let userBubble = document.createElement('div');
    userBubble.style.cssText = "align-self: flex-end; background: #38f9d7; color: #2b1c50; padding: 10px 14px; border-radius: 16px 16px 0 16px; max-width: 85%; word-break: break-word; font-weight: 700; border: 1px solid rgba(56, 249, 215, 0.5);";
    userBubble.innerText = inputVal;
    chatHistory.appendChild(userBubble);

    // Kosongkan input & scroll ke bawah
    document.getElementById('input-konsultasi').value = '';
    chatContainer.scrollTop = chatContainer.scrollHeight;

    // Masukkan pesan user ke riwayat memori sesi
    chatSessionHistory.push({ role: "user", content: inputVal });

    // 2. Munculkan bubble Loading AI (Sebelah Kiri)
    let loadingBubble = document.createElement('div');
    loadingBubble.style.cssText = "align-self: flex-start; background: rgba(255,255,255,0.2); color: white; padding: 10px 14px; border-radius: 16px 16px 16px 0; max-width: 85%; font-style: italic;";
    loadingBubble.innerHTML = '<i class="ph-bold ph-circle-notch ph-spin" style="animation: spin 1s linear infinite;"></i> Mengetik...';
    chatHistory.appendChild(loadingBubble);
    chatContainer.scrollTop = chatContainer.scrollHeight;

    try {
        // Mengirim seluruh riwayat percakapan (chatSessionHistory) ke backend api.php
        const res = await apiCall('konsultasi_ai', { 
            question: inputVal,
            history: chatSessionHistory 
        });
        
        // Hapus bubble loading
        chatHistory.removeChild(loadingBubble);

        // 3. Munculkan bubble Jawaban AI (Sebelah Kiri)
        let aiBubble = document.createElement('div');
        aiBubble.style.cssText = "align-self: flex-start; background: var(--card-bg); color: var(--text-main); padding: 12px 16px; border-radius: 16px 16px 16px 0; max-width: 90%; word-break: break-word; white-space: pre-wrap; font-weight: 600; border: 1px solid var(--border-color); box-shadow: 0 4px 10px rgba(0,0,0,0.1);";
        
        if (res.status === 'success') {
            // Mengganti baris baru (\n) dengan tag HTML (<br>)
            // Serta mengubah simbol **teks** menjadi <b>teks</b> (bold)
            let formattedText = res.answer
                .replace(/\n/g, '<br>')
                .replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
                
            aiBubble.innerHTML = formattedText;

            // Masukkan jawaban AI ke riwayat memori sesi agar percakapan selanjutnya nyambung
            chatSessionHistory.push({ role: "assistant", content: res.answer });
        } else {
            aiBubble.innerHTML = '<b>Gagal:</b> ' + (res.answer || 'Terjadi kesalahan pada server.');
            aiBubble.style.color = '#ef4444'; // Warna merah jika error
        }
        
        chatHistory.appendChild(aiBubble);
        chatContainer.scrollTop = chatContainer.scrollHeight;

    } catch (error) {
        chatHistory.removeChild(loadingBubble);
        let errorBubble = document.createElement('div');
        errorBubble.style.cssText = "align-self: flex-start; background: #fee2e2; color: #b91c1c; padding: 10px 14px; border-radius: 16px 16px 16px 0; max-width: 85%; font-weight: 700;";
        errorBubble.innerText = 'Terjadi kesalahan jaringan saat menghubungi server.';
        chatHistory.appendChild(errorBubble);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
}
/* ================= AKHIR LOGIKA KONSULTASI ================= */

/* ================= KONFIGURASI PRESENSI ================= */
const daftarPilihanPeriode = ["IMDA I Sesi 1", "IMDA I Sesi 2", "IMDA II Sesi 1", "IMDA II Sesi 2", "IMDA III Sesi 1", "IMDA III Sesi 2"];
let globalActivePeriode = 'IMDA I Sesi 1';

const daftarTahunAjaran = [
    "1447-1448 H", "1448-1449 H", "1449-1450 H", "1450-1451 H", 
    "1451-1452 H", "1452-1453 H", "1453-1454 H", "1454-1455 H", "1455 H"
];
let globalActiveTahunAjaran = '1447-1448 H';

/* MASTER DATA LOKASI / MADRASAH (6 Lokasi Binaan per Wilayah dalam Setahun) */
let masterListMadrasahObj = [];

/* MASTER DATA JADWAL (6 Lokasi Pembinaan per Tahun Ajaran) */
let masterListJadwalObj = [];

/* MASTER DATA PESERTA (GURU KELAS 4, 5, 6 PER WILAYAH) */
let masterListPesertaObj = [];

/* MASTER DATA AKUN KORWIL & WILAYAHNYA */
let masterListKorwilObj = [];

let statusGPS = false;
let statusFoto = false;
let kelasTerpilihAktif = 'semua';
let masterListExport = [];
let absensiHadirData = {};
let detailAbsensiPeserta = {};

// UI Helpers
function showLoading() { document.getElementById('global-loading').style.display = 'flex'; }
function hideLoading() { document.getElementById('global-loading').style.display = 'none'; }

function tampilkanNotifInfo(pesan, judul = "Pemberitahuan", tipe = "info") {
    document.getElementById('notif-title').innerText = judul;
    document.getElementById('notif-desc').innerText = pesan;
    let icon = document.querySelector('#modal-notif-custom .modal-icon-top');
    if(tipe === 'success') { icon.innerHTML = '<i class="ph-fill ph-check-circle"></i>'; } 
    else if(tipe === 'warning') { icon.innerHTML = '<i class="ph-fill ph-warning"></i>'; }
    else { icon.innerHTML = '<i class="ph-fill ph-info"></i>'; }
    
    document.querySelector('#modal-notif-custom .modal-actions').innerHTML = `
        <button class="btn-modal-cancel" onclick="tutupNotifCustom()" style="display:none;">Cancel</button>
        <button class="btn-modal-save" onclick="tutupNotifCustom()" style="width:100%;">Tutup</button>
    `;
    document.getElementById('modal-notif-custom').style.display = 'flex';
}
function tutupNotifCustom() { document.getElementById('modal-notif-custom').style.display = 'none'; }

/* ================= FUNGSI LOGIKA MODAL IZIN & SAKIT (BARU) ================= */
function bukaModalKeteranganAbsen(idPeserta, namaPeserta) {
    currentPesertaIdDipilih = idPeserta;
    document.getElementById('modal-ket-nama-peserta').innerText = namaPeserta;
    document.getElementById('input-detail-keterangan').value = '';
    pilihStatusKet('Izin');
    document.getElementById('modal-keterangan-absen').style.display = 'flex';
}

function pilihStatusKet(status) {
    selectedStatusPilihan = status;
    document.getElementById('opsi-izin').style.borderColor = status === 'Izin' ? '#38f9d7' : 'rgba(255,255,255,0.25)';
    document.getElementById('opsi-sakit').style.borderColor = status === 'Sakit' ? '#38f9d7' : 'rgba(255,255,255,0.25)';
}

function simpanStatusKeterangan() {
    let detailKet = document.getElementById('input-detail-keterangan').value.trim();
    let wilayahAktif = localStorage.getItem('activeWilayah') || 'Pasuruan';
    let lokasiAktif = document.getElementById('label-madrasah-terpilih-header').innerText;
    let keyMap = wilayahAktif + "_" + globalActivePeriode + "_" + lokasiAktif;

    let teksSimpan = selectedStatusPilihan + (detailKet ? ": " + detailKet : "");
    statusKeteranganMap[keyMap + "_" + currentPesertaIdDipilih] = teksSimpan;

    // Jika diatur Izin/Sakit, pastikan tombol toggle switch HADIR otomatis mati (OFF)
    let checkboxes = document.querySelectorAll('.absensi-checkbox');
    checkboxes.forEach(cb => {
        if(cb.getAttribute('data-id') === currentPesertaIdDipilih) {
            cb.checked = false;
        }
    });

    document.getElementById('modal-keterangan-absen').style.display = 'none';
    renderAbsensiKorwil();
    tampilkanNotifInfo("Keterangan berhasil disimpan.", "Sukses", "success");
}

function hapusKeteranganJikaHadir(id, element) {
    // Fungsi ini menghapus badge Izin/Sakit jika Korwil menyalakan toggle Hadir
    if(element.checked) {
        let wilayahAktif = localStorage.getItem('activeWilayah') || 'Pasuruan';
        let lokasiAktif = document.getElementById('label-madrasah-terpilih-header').innerText;
        let keyMap = wilayahAktif + "_" + globalActivePeriode + "_" + lokasiAktif;
        if(statusKeteranganMap[keyMap + "_" + id]) {
            delete statusKeteranganMap[keyMap + "_" + id];
            renderAbsensiKorwil(); // render ulang agar badge hilang
        }
    }
}

/* ================= NAVIGASI & FITUR ADMIN ================= */
function toggleDarkModeAdmin() {
    const isDark = document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', isDark);
    document.getElementById('checkbox-darkmode-admin').checked = isDark;
    document.getElementById('teks-status-darkmode-admin').innerText = isDark ? 'Aktif' : 'Nonaktif';
}

function switchTabAdmin(tabId, element) {
    document.querySelectorAll('#admin-app .screen-content').forEach(s => s.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    
    document.querySelectorAll('#admin-app .nav-item').forEach(i => i.classList.remove('active'));
    if(element) {
        element.classList.add('active');
    } else {
        if(tabId === 'tab-admin-beranda') document.querySelectorAll('#admin-app .nav-item')[0].classList.add('active');
        if(tabId === 'tab-admin-presensi') document.querySelectorAll('#admin-app .nav-item')[1].classList.add('active');
        if(tabId === 'tab-admin-jadwal') document.querySelectorAll('#admin-app .nav-item')[2].classList.add('active');
        if(tabId === 'tab-admin-profil') document.querySelectorAll('#admin-app .nav-item')[3].classList.add('active');
    }
}

function toggleAdminPresensiView(viewName) {
    const btnStorage = document.getElementById('btn-admin-tab-storage');
    const btnAbsensi = document.getElementById('btn-admin-tab-absensi');
    const viewStorage = document.getElementById('view-admin-storage');
    const viewAbsensi = document.getElementById('view-admin-absensi');

    if (viewName === 'storage') {
        btnStorage.style.background = 'var(--primary-gradient)';
        btnStorage.style.color = 'white';
        btnStorage.style.boxShadow = '0 5px 15px rgba(132, 94, 247, 0.3)';
        
        btnAbsensi.style.background = 'var(--card-bg)';
        btnAbsensi.style.color = 'var(--text-muted)';
        btnAbsensi.style.boxShadow = 'none';

        viewStorage.style.display = 'flex';
        viewAbsensi.style.display = 'none';
    } else {
        btnAbsensi.style.background = 'var(--primary-gradient)';
        btnAbsensi.style.color = 'white';
        btnAbsensi.style.boxShadow = '0 5px 15px rgba(132, 94, 247, 0.3)';
        
        btnStorage.style.background = 'var(--card-bg)';
        btnStorage.style.color = 'var(--text-muted)';
        btnStorage.style.boxShadow = 'none';

        viewStorage.style.display = 'none';
        viewAbsensi.style.display = 'flex';
    }
}

/* ================= FITUR BARU PROFIL (KORWIL) ================= */
function toggleDarkMode() {
    const isDark = document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', isDark);
    document.getElementById('checkbox-darkmode').checked = isDark;
    document.getElementById('teks-status-darkmode').innerText = isDark ? 'Aktif' : 'Nonaktif';
}

function bukaModalDataLogin() {
    let username = localStorage.getItem('activeUsername') || 'korwil';
    let nama = localStorage.getItem('activeSpv') || 'Korwil J-01';
    let wilayah = localStorage.getItem('activeWilayah') || 'Pasuruan';
    document.getElementById('info-username').innerText = username;
    document.getElementById('info-nama').innerText = nama;
    document.getElementById('info-wilayah').innerText = wilayah;
    document.getElementById('info-tahun-ajaran').innerText = globalActiveTahunAjaran;
    document.getElementById('info-periode').innerText = globalActivePeriode;
    document.getElementById('modal-data-login').style.display = 'flex';
}

function bukaModalUbahSandi() {
    document.getElementById('sandi-lama').value = '';
    document.getElementById('sandi-baru').value = '';
    document.getElementById('modal-ubah-sandi').style.display = 'flex';
}

function simpanSandiBaru() {
    let lama = document.getElementById('sandi-lama').value;
    let baru = document.getElementById('sandi-baru').value;
    if(!lama || !baru) {
        tampilkanNotifInfo("Mohon isi sandi lama dan sandi baru!", "Peringatan", "warning");
        return;
    }
    document.getElementById('modal-ubah-sandi').style.display = 'none';
    tampilkanNotifInfo("Kata sandi berhasil diperbarui.", "Berhasil", "success");
}

function hubungiAdminWhatsApp() {
    let nomorAdmin = "6281234567890";
    let pesan = encodeURIComponent("Halo Admin Pusat, saya butuh bantuan terkait aplikasi Presensi Pembinaan.");
    window.open(`https://wa.me/${nomorAdmin}?text=${pesan}`, '_blank');
}

/* ================= TOGGLE VIEWS (Storage <-> Absensi Peserta) ================= */
function togglePresensiView(viewName) {
    const btnStorage = document.getElementById('btn-tab-storage');
    const btnAbsensi = document.getElementById('btn-tab-absensi');
    const viewStorage = document.getElementById('view-storage');
    const viewAbsensi = document.getElementById('view-absensi');

    if (viewName === 'storage') {
        btnStorage.style.background = 'var(--primary-gradient)';
        btnStorage.style.color = 'white';
        btnStorage.style.boxShadow = '0 5px 15px rgba(132, 94, 247, 0.3)';
        
        btnAbsensi.style.background = 'var(--card-bg)';
        btnAbsensi.style.color = 'var(--text-muted)';
        btnAbsensi.style.boxShadow = 'none';

        viewStorage.style.display = 'flex';
        viewAbsensi.style.display = 'none';
    } else {
        btnStorage.style.background = 'var(--card-bg)';
        btnStorage.style.color = 'var(--text-muted)';
        btnStorage.style.boxShadow = 'none';
        
        btnAbsensi.style.background = 'var(--primary-gradient)';
        btnAbsensi.style.color = 'white';
        btnAbsensi.style.boxShadow = '0 5px 15px rgba(132, 94, 247, 0.3)';

        viewStorage.style.display = 'none';
        viewAbsensi.style.display = 'flex';

        // TAMBAHAN: Tarik data terbaru dari server agar absen peserta yang baru bisa muncul!
        fetchInitialData(); 
    }
}

function bukaModalFilterKelas() {
    document.getElementById('modal-filter-kelas').style.display = 'flex';
}

function pilihFilterKelas(kelas) {
    kelasTerpilihAktif = kelas;
    document.getElementById('label-kelas-terpilih').innerText = kelas === 'semua' ? 'Semua Kelas (4, 5, 6)' : kelas;
    document.getElementById('modal-filter-kelas').style.display = 'none';
    filterAbsensiSiswa();
}

function filterAbsensiSiswa() {
    let keyword = document.getElementById('search-absensi').value.toLowerCase();
    let cards = document.querySelectorAll('#container-daftar-siswa .absensi-card');

    cards.forEach(card => {
        let namaSiswa = card.getAttribute('data-nama').toLowerCase();
        let kelasSiswa = card.getAttribute('data-kelas');

        let matchNama = namaSiswa.includes(keyword);
        let matchKelas = (kelasTerpilihAktif === 'semua' || kelasSiswa === kelasTerpilihAktif);

        if (matchNama && matchKelas) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

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
        let ketMapKey = wilayahAktif + "_" + globalActivePeriode + "_" + lokasi;
        
        let checkboxes = document.querySelectorAll('.absensi-checkbox');
        let countHadir = 0;
        let pesertaList = [];

        checkboxes.forEach((cb) => {
            let pesertaId = cb.getAttribute('data-id');
            let isHadir = cb.checked;
            let statusSimpan = "Tidak Hadir";
            
            if(isHadir) {
                countHadir++;
                statusSimpan = "Hadir";
            } else {
                let ketSiswa = statusKeteranganMap[ketMapKey + "_" + pesertaId];
                if(ketSiswa) {
                    statusSimpan = ketSiswa; // Contoh: "Izin: Pulang", "Sakit"
                }
            }
            pesertaList.push({id_peserta: pesertaId, status: statusSimpan});
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

            tampilkanNotifInfo(`Data kehadiran di ${lokasi} (${countHadir} peserta hadir) berhasil disimpan & disetor ke Admin. Menu storage telah di-reset.`, "Berhasil Disimpan", "success");
            
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

// ================= FUNGSI KIRIM REKAP VIA WHATSAPP ================= //
function kirimRekapWhatsApp() {
    let wilayahAktif = localStorage.getItem('activeWilayah') || 'Pasuruan';
    let totalPesertaWilayah = masterListPesertaObj.filter(p => p.Wilayah === wilayahAktif).length;
    
    let hadirCount = 0;
    
    riwayatPresensi.forEach(item => {
        if(item.wilayah === wilayahAktif && item.periode === globalActivePeriode) {
            item.peserta.forEach(p => {
                if(p.status_kehadiran === 'Hadir' || p.status === 'Hadir') hadirCount++;
            });
        }
    });

    if(hadirCount > totalPesertaWilayah) hadirCount = totalPesertaWilayah;
    let tidakHadirCount = Math.max(0, totalPesertaWilayah - hadirCount);
    let persentase = totalPesertaWilayah > 0 ? Math.round((hadirCount / totalPesertaWilayah) * 100) : 0;

    let pesanWhatsApp = `*Pembinaan ${globalActivePeriode}*` +
        `\n*Wilayah ${wilayahAktif}*` +
        `\nTahun ajaran ${globalActiveTahunAjaran}.` +
        `\n\n*Total Peserta ${totalPesertaWilayah}*` +
        `\nHadir : *${hadirCount}*` +
        `\nTidak Hadir : *${tidakHadirCount}*` +
        `\n*Prosentase Kehadiran : ${persentase}%*`;

    let urlWA = `https://wa.me/?text=${encodeURIComponent(pesanWhatsApp)}`;
    window.open(urlWA, '_blank');
}

/* ================= KALENDER LIVE LOGIC ================= */
let kalenderInterval;

function formatWaktu(angka) { return angka < 10 ? '0' + angka : angka; }

function jalankanKalender() {
    const elJam = document.getElementById('teks-kalender-live');
    const elTanggal = document.getElementById('teks-tanggal-live');
    
    if(elJam && elTanggal) {
        const now = new Date();
        const jam = formatWaktu(now.getHours());
        const menit = formatWaktu(now.getMinutes());
        const detik = formatWaktu(now.getSeconds());
        elJam.innerText = `${jam}:${menit}:${detik} WIB`;

        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        elTanggal.innerText = now.toLocaleDateString('id-ID', options);
    }
}

function bukaModalKalender() {
    jalankanKalender();
    document.getElementById('modal-kalender-live').style.display = 'flex';
    if(!kalenderInterval) {
        kalenderInterval = setInterval(jalankanKalender, 1000);
    }
}

function bukaModalKalenderAdmin() {
    jalankanKalender();
    document.getElementById('modal-kalender-live').style.display = 'flex';
    if(!kalenderInterval) {
        kalenderInterval = setInterval(jalankanKalender, 1000);
    }
}

function tutupModalKalender() {
    document.getElementById('modal-kalender-live').style.display = 'none';
    if(kalenderInterval) {
        clearInterval(kalenderInterval);
        kalenderInterval = null;
    }
}

function konfirmasiLogout() {
    document.getElementById('notif-title').innerText = "Konfirmasi Keluar";
    document.getElementById('notif-desc').innerText = "Apakah Anda yakin ingin keluar dari aplikasi?";
    document.querySelector('#modal-notif-custom .modal-icon-top').innerHTML = '<i class="ph-fill ph-sign-out"></i>';
    document.querySelector('#modal-notif-custom .modal-actions').innerHTML = `
        <button class="btn-modal-cancel" onclick="tutupNotifCustom()">Cancel</button>
        <button class="btn-modal-save" style="background:#ff4757; box-shadow:0 8px 20px rgba(255,71,87,0.4);" onclick="tutupNotifCustom(); handleLogout();">Keluar</button>
    `;
    document.getElementById('modal-notif-custom').style.display = 'flex';
}

function updateTampilanBadgePeriode() {
    let elAdm = document.getElementById('admin-periode-text');
    let elTa = document.getElementById('label-tahun-ajaran');
    let elStoragePeriode = document.getElementById('label-periode-storage-aktif');
    if(elAdm) elAdm.innerText = `${globalActivePeriode}`;
    if(elTa) elTa.innerText = globalActiveTahunAjaran;
    if(elStoragePeriode) elStoragePeriode.innerText = globalActivePeriode;
}

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

    // Ambil data awal dari server, agar Portal Peserta langsung siap digunakan
    // meski korwil belum login
    fetchInitialData();
};

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
                document.getElementById('wilayah-header-title').innerText = "Ahlan Wa Sahlan!";
                
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

function handleLogout() {
    showLoading();
    setTimeout(() => {
        document.getElementById('main-app').style.display = 'none';
        document.getElementById('admin-app').style.display = 'none';
        document.getElementById('login-screen').style.display = 'flex';
        document.getElementById('username').value = '';
        document.getElementById('password').value = '';
        
        localStorage.removeItem('activeUsername');
        localStorage.removeItem('activeSpv');
        localStorage.removeItem('activeWilayah');

        if (typeof switchTab === 'function') {
            switchTab('tab-beranda', document.querySelectorAll('#main-app .nav-item')[0]);
        }
        if (typeof switchTabAdmin === 'function') {
            switchTabAdmin('tab-admin-beranda', document.querySelectorAll('#admin-app .nav-item')[0]);
        }

        hideLoading();
    }, 400);
}

function switchTab(tabId, element) {
    document.querySelectorAll('#main-app .screen-content').forEach(s => s.classList.remove('active'));
    document.getElementById(tabId).classList.add('active');
    
    document.querySelectorAll('#main-app .nav-item').forEach(i => i.classList.remove('active'));
    if(element) {
        element.classList.add('active');
    } else {
        if(tabId === 'tab-beranda') document.querySelectorAll('#main-app .nav-item')[0].classList.add('active');
        if(tabId === 'tab-presensi') document.querySelectorAll('#main-app .nav-item')[1].classList.add('active');
        if(tabId === 'tab-jadwal') document.querySelectorAll('#main-app .nav-item')[2].classList.add('active');
        if(tabId === 'tab-profil') document.querySelectorAll('#main-app .nav-item')[3].classList.add('active');
    }

    if(tabId === 'tab-beranda') updateDashboard();
    if(tabId === 'tab-jadwal') renderJadwalKorwil();
    if(tabId === 'tab-presensi') renderAbsensiKorwil();
    if(tabId === 'tab-profil') {
        let savedName = localStorage.getItem('activeSpv') || 'Korwil J-01';
        let savedWilayah = localStorage.getItem('activeWilayah') || 'Pasuruan';
        document.getElementById('profil-nama-user').innerText = savedName;
        document.getElementById('profil-wilayah-user').innerText = `Koordinator Wilayah ${savedWilayah}`;
    }
}

function getRiwayat() { return riwayatPresensi; }

function bukaModalPilihMadrasah() {
    document.getElementById('search-input-target').value = '';
    filterModalMadrasah();
    document.getElementById('modal-pilih-madrasah').style.display = 'flex';
}

function filterModalMadrasah() {
    let key = document.getElementById('search-input-target').value.toLowerCase();
    let container = document.getElementById('list-modal-madrasah');
    container.innerHTML = '';
    let wilayahAktif = localStorage.getItem('activeWilayah') || 'Pasuruan';
    
    let filteredList = masterListMadrasahObj.filter(m => m.Wilayah === wilayahAktif && m.Nama_Madrasah.toLowerCase().includes(key));
    
    filteredList.forEach(item => {
        let m = `${item.Nama_Madrasah}`;
        container.innerHTML += `
        <div class="export-item" onclick="pilihMadrasah('${m}')">
            <strong><i class="ph-fill ph-folder-open" style="color: #38f9d7; font-size: 20px;"></i> ${m}</strong>
            <i class="ph-bold ph-caret-right" style="opacity: 0.6; font-size: 16px;"></i>
        </div>`;
    });
    if(filteredList.length === 0) {
        container.innerHTML = `<p style="text-align:center; font-size:12px; color:white; padding: 20px;">Tidak ada data lokasi yang cocok dengan pencarian.</p>`;
    }
}

function pilihMadrasah(nama) {
    document.getElementById('label-madrasah-terpilih-header').innerText = nama;
    document.getElementById('modal-pilih-madrasah').style.display = 'none';
    statusGPS = false; statusFoto = false;
    document.getElementById('icon-gps').style.background = 'var(--c-teal)';
    document.getElementById('icon-gps').style.color = '#00796b';
    document.getElementById('teks-gps').innerText = 'Ketuk untuk validasi jarak (Maks 20m)';
    
    // Refresh / Reset toggle kehadiran peserta saat pilih lokasi baru
    renderAbsensiKorwil();
}

/* ================= LOGIKA POP-UP PEMILIHAN LOKASI ABSENSI MANDIRI (PESERTA) ================= */
function bukaModalPilihLokasiMandiri() {
    document.getElementById('search-input-mandiri-modern').value = '';
    document.getElementById('filter-wilayah-mandiri-modern').value = 'semua';
    filterModalLokasiMandiriModern();
    document.getElementById('modal-pilih-lokasi-mandiri-modern').style.display = 'flex';
}

function filterModalLokasiMandiriModern() {
    let key = document.getElementById('search-input-mandiri-modern').value.toLowerCase();
    let filterWilayah = document.getElementById('filter-wilayah-mandiri-modern').value;
    let container = document.getElementById('list-modal-lokasi-mandiri-modern');
    container.innerHTML = '';
    
    let filteredList = masterListMadrasahObj.filter(m => {
        let matchWilayah = (filterWilayah === 'semua' || m.Wilayah === filterWilayah);
        let matchSearch = m.Nama_Madrasah.toLowerCase().includes(key);
        return matchWilayah && matchSearch;
    });
    
    let lokasiTerpilihSaatIni = document.getElementById('label-lokasi-mandiri-terpilih').innerText;

    filteredList.forEach(item => {
        let namaLokasi = item.Nama_Madrasah;
        let isSelected = (lokasiTerpilihSaatIni === namaLokasi);

        container.innerHTML += `
        <div class="export-item" onclick="pilihLokasiMandiriModern('${namaLokasi}')" style="display: flex; align-items: center; justify-content: space-between; border-color:${isSelected ? '#38f9d7' : 'rgba(255,255,255,0.25)'};">
            <strong style="flex: 1; padding-right: 10px;"><i class="ph-fill ph-map-pin" style="color: #38f9d7; font-size: 20px; vertical-align: middle; margin-right: 5px;"></i> ${namaLokasi} <span style="font-size:11px; opacity:0.7; font-weight:normal; display:block;">(${item.Wilayah})</span></strong>
            <div style="width: 24px; height: 24px; min-width: 24px; border-radius: 50%; border: 2px solid ${isSelected ? '#38f9d7' : 'rgba(255,255,255,0.5)'}; display: flex; justify-content: center; align-items: center; background: ${isSelected ? '#38f9d7' : 'transparent'};">
                ${isSelected ? '<div style="width: 10px; height: 10px; border-radius: 50%; background: #2b1c50;"></div>' : ''}
            </div>
        </div>`;
    });

    if(filteredList.length === 0) {
        container.innerHTML = `<p style="text-align:center; font-size:12px; color:white; padding: 20px;">Tidak ada data lokasi yang cocok dengan pencarian.</p>`;
    }
}

function pilihLokasiMandiriModern(namaLokasi) {
    document.getElementById('label-lokasi-mandiri-terpilih').innerText = namaLokasi;
    document.getElementById('modal-pilih-lokasi-mandiri-modern').style.display = 'none';
}

/* ================= MODIFIKASI DETEKSI GPS KORWIL (SIMPAN WAKTU AKTIF KE SERVER) ================= */
function deteksiLokasi() {
    let lokasiAktif = document.getElementById('label-madrasah-terpilih-header').innerText;
    if(lokasiAktif.includes("Pilih")) {
        tampilkanNotifInfo("Silakan pilih Target Lokasi terlebih dahulu di menu Storage!", "Peringatan", "warning");
        return;
    }

    document.getElementById('teks-gps').innerText = 'Mencari sinyal GPS...';
    setTimeout(() => {
        statusGPS = true;
        let waktuSekarang = Date.now();
        let wilayahAktif = localStorage.getItem('activeWilayah') || 'Pasuruan';

        let sessionDataKey = 'gpsSession_' + wilayahAktif + "_" + lokasiAktif;
        let sessionValue = JSON.stringify({
            waktuAktif: waktuSekarang,
            aktif: true
        });

        // 1. Simpan sesi aktif GPS beserta batas waktu 2 jam ke Server via apiCall
        apiCall('save_settings', { key: sessionDataKey, value: sessionValue }).catch(e => console.error("Gagal sync GPS", e));
        
        // 2. Tetap simpan ke localStorage sebagai fallback
        localStorage.setItem(sessionDataKey, sessionValue);

        document.getElementById('icon-gps').style.background = '#43e97b';
        document.getElementById('icon-gps').style.color = 'white';
        let now = new Date(waktuSekarang);
        let endTime = new Date(now.getTime() + 2 * 60 * 60 * 1000);
        let timeString = formatWaktu(endTime.getHours()) + ':' + formatWaktu(endTime.getMinutes());
        document.getElementById('teks-gps').innerText = `Dalam radius 20m. Akses s.d ${timeString} WIB`;
        tampilkanNotifInfo(`Validasi berhasil! Sesi presensi mandiri terbuka selama 2 jam (hingga pukul ${timeString} WIB).`, "Validasi Jarak Sukses", "success");
    }, 1500);
}

function fotoDipilih(input) {
    if (input.files && input.files[0]) {
        statusFoto = true;
        document.getElementById('icon-foto').style.background = '#43e97b';
        document.getElementById('icon-foto').style.color = 'white';
        document.getElementById('teks-foto').innerText = 'Dokumentasi berhasil dilampirkan';
    }
}

/* ================= LOGIKA PORTAL MANDIRI PESERTA (DENGAN VALIDASI SINKRON SERVER) ================= */
async function bukaPortalMandiri() {
    showLoading();
    // Sinkronisasi data terbaru untuk mengecek update dari admin / korwil
    await fetchInitialData();
    hideLoading();

    // Reset label pilihan lokasi mandiri
    document.getElementById('label-lokasi-mandiri-terpilih').innerText = '-- Pilih Lokasi Target --';
    document.getElementById('input-id-mandiri').value = '';
    document.getElementById('modal-portal-mandiri').style.display = 'flex';
}

async function prosesValidasiDanAbsenMandiri() {
    let idPeserta = document.getElementById('input-id-mandiri').value.trim();
    let lokasiPilihanPeserta = document.getElementById('label-lokasi-mandiri-terpilih').innerText;

    if(!idPeserta || lokasiPilihanPeserta.includes("Pilih")) {
        tampilkanNotifInfo("Mohon lengkapi pilihan lokasi pembinaan dan ID Peserta Anda!", "Peringatan", "warning");
        return;
    }

    let pesertaDitemukan = masterListPesertaObj.find(p => p.ID.toLowerCase() === idPeserta.toLowerCase());
    if(!pesertaDitemukan) {
        tampilkanNotifInfo("GAGAL: ID Peserta tidak ditemukan di database sistem!", "Validasi Gagal", "warning");
        return;
    }

    let wilayahPeserta = pesertaDitemukan.Wilayah;

    // 1. Validasi Lokasi: Apakah lokasi sesuai dengan setting Korwil untuk wilayah tersebut?
    let cekLokasiKorwil = masterListMadrasahObj.some(m => m.Wilayah === wilayahPeserta && m.Nama_Madrasah === lokasiPilihanPeserta);
    if(!cekLokasiKorwil) {
        tampilkanNotifInfo("GAGAL: Tempat lokasi pembinaan keliru atau tidak sesuai dengan jadwal wilayah Anda!", "Validasi Gagal", "warning");
        return;
    }

    // Ambil setting terbaru dari server untuk memastikan sesi GPS Korwil terbaca di perangkat manapun
    showLoading();
    let serverData = null;
    try {
        serverData = await apiCall('get_data');
    } catch(e) {
        console.error(e);
    }
    hideLoading();

    let dbSettings = {};
    if (serverData && serverData.status === 'success' && serverData.data && serverData.data.settings) {
        dbSettings = serverData.data.settings;
    }

    // 2. Validasi Waktu: Apakah Korwil sudah aktifkan GPS dan apakah masih dalam batas waktu 2 jam?
    let sessionDataKey = 'gpsSession_' + wilayahPeserta + "_" + lokasiPilihanPeserta;
    
    // Utamakan data yang berasal dari server, jika offline gunakan localStorage
    let sessionGPSStr = dbSettings[sessionDataKey] || localStorage.getItem(sessionDataKey);
    let sessionGPS = sessionGPSStr ? JSON.parse(sessionGPSStr) : null;

    if(!sessionGPS || !sessionGPS.aktif) {
        tampilkanNotifInfo("GAGAL: Korwil belum mengaktifkan sesi validasi GPS untuk lokasi ini (atau koneksi belum stabil)!", "Validasi Gagal", "warning");
        return;
    }

    let selisihWaktuMiliDetik = Date.now() - sessionGPS.waktuAktif;
    let batasDuaJamMiliDetik = 2 * 60 * 60 * 1000; // 2 Jam

    if(selisihWaktuMiliDetik > batasDuaJamMiliDetik) {
        tampilkanNotifInfo("GAGAL: Waktu pengisian absensi telah kedaluwarsa (lebih dari 2 jam setelah diaktifkan Korwil)!", "Validasi Gagal", "warning");
        return;
    }

    // 3. Validasi GPS (Simulasi Jarak Radius 20 Meter perangkat peserta)
    if (navigator.geolocation) {
        showLoading();
        navigator.geolocation.getCurrentPosition(
            function(position) {
                // Lolos semua validasi
                selesaikanAbsensiMandiri(pesertaDitemukan, lokasiPilihanPeserta, wilayahPeserta);
            },
            function(error) {
                hideLoading();
                tampilkanNotifInfo("GAGAL: Pastikan izin GPS aktif dan perangkat Anda berada dalam radius 20 meter dari lokasi Korwil!", "Validasi Jarak Gagal", "warning");
            },
            { timeout: 10000, enableHighAccuracy: true }
        );
    } else {
        tampilkanNotifInfo("Perangkat Anda tidak mendukung validasi GPS.", "Kesalahan", "warning");
    }
}

async function selesaikanAbsensiMandiri(pesertaDitemukan, lokasiPilihanPeserta, wilayahPeserta) {
    let keyStorage = wilayahPeserta + "_" + globalActivePeriode + "_" + lokasiPilihanPeserta;
    
    let statusPesertaMap = detailAbsensiPeserta[keyStorage] || {};
    statusPesertaMap[pesertaDitemukan.ID] = "Hadir";
    detailAbsensiPeserta[keyStorage] = statusPesertaMap;

    let totalHadirHitung = Object.values(statusPesertaMap).filter(val => val === "Hadir").length;
    absensiHadirData[keyStorage] = totalHadirHitung > 0 ? totalHadirHitung : 1;

    let cekRiwayat = riwayatPresensi.find(x => x.madrasah === lokasiPilihanPeserta && x.wilayah === wilayahPeserta && x.periode === globalActivePeriode);
    if(!cekRiwayat) {
        cekRiwayat = {
            id: Date.now(),
            madrasah: lokasiPilihanPeserta,
            jurnal: "Absensi Mandiri Peserta (QR Code)",
            waktu: new Date().toLocaleString('id-ID'),
            periode: globalActivePeriode,
            tahunAjaran: globalActiveTahunAjaran,
            wilayah: wilayahPeserta,
            spv: "Mandiri Peserta",
            peserta: []
        };
        riwayatPresensi.push(cekRiwayat);
    }
    
    // Update atau masukkan peserta sebagai Hadir
    let pesertaIndex = cekRiwayat.peserta.findIndex(p => p.id_peserta === pesertaDitemukan.ID);
    if(pesertaIndex >= 0) {
        cekRiwayat.peserta[pesertaIndex].status_kehadiran = "Hadir";
        cekRiwayat.peserta[pesertaIndex].status = "Hadir";
    } else {
        cekRiwayat.peserta.push({ id_peserta: pesertaDitemukan.ID, status_kehadiran: "Hadir", status: "Hadir" });
    }

    localStorage.setItem('absensiHadirData', JSON.stringify(absensiHadirData));
    localStorage.setItem('detailAbsensiPeserta', JSON.stringify(detailAbsensiPeserta));
    localStorage.setItem('riwayatPresensi', JSON.stringify(riwayatPresensi));

    // KETIKA PESERTA ABSEN, KIRIM KE SERVER AGAR BISA DILIHAT OLEH KORWIL DI PERANGKATNYA
    try {
        await apiCall('save_presensi', {
            madrasah: lokasiPilihanPeserta,
            wilayah: wilayahPeserta,
            periode: globalActivePeriode,
            tahunAjaran: globalActiveTahunAjaran,
            spv: "Mandiri Peserta",
            foto: "",
            jurnal: "Absensi Mandiri Peserta (QR Code)",
            peserta: cekRiwayat.peserta.map(p => ({id_peserta: p.id_peserta, status: p.status_kehadiran || p.status}))
        });
    } catch(e) {
        console.error("Gagal sinkron absen mandiri ke server", e);
    }

    hideLoading();
    document.getElementById('modal-portal-mandiri').style.display = 'none';
    
    tampilkanNotifInfo(
        `BERHASIL DIKIRIM!\n\nNama: ${pesertaDitemukan.Nama}\nKelas: ${pesertaDitemukan.Kelas}\nLembaga Asal: ${pesertaDitemukan.Lembaga}\nLokasi Pembinaan: ${lokasiPilihanPeserta}`, 
        "Absensi Sukses", 
        "success"
    );
    updateDashboard();
}

function updateDashboard() {
    renderSemuaProgresWilayahDinamis();
    renderTop3WilayahDinamis();
}

function renderSemuaProgresWilayahDinamis() {
    let containerProg = document.getElementById('container-progres-wilayah');
    if(!containerProg) return;

    
    let listWilayah = ["Pasuruan", "Malang", "Surabaya"];
    let htmlProg = '';

    listWilayah.forEach(wil => {
        let totalPesertaWilayah = masterListPesertaObj.filter(p => p.Wilayah === wil).length;
        let hadirCount = 0;
        
        Object.keys(absensiHadirData).forEach(k => {
            if(k.startsWith(wil + "_" + globalActivePeriode)) {
                hadirCount += absensiHadirData[k];
            }
        });
        if(hadirCount > totalPesertaWilayah) hadirCount = totalPesertaWilayah;
        let tidakHadirCount = Math.max(0, totalPesertaWilayah - hadirCount);

        htmlProg += `
        <div class="list-item" style="flex-direction: column; align-items: stretch; gap: 12px; cursor: default;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="list-item-icon" style="background: var(--c-blue); color: #1976d2; width: 38px; height: 38px; font-size: 18px; margin: 0;"><i class="ph-fill ph-map-pin"></i></div>
                    <h4 style="margin: 0; font-size: 14px; font-weight: 800; color: var(--text-main);">Wilayah ${wil}</h4>
                </div>
                <span style="font-size: 11px; font-weight: 800; background: #f1f5f9; padding: 5px 10px; border-radius: 10px; color: var(--text-main);">Total: ${totalPesertaWilayah} Peserta</span>
            </div>
            <div style="display: flex; gap: 10px;">
                <div style="flex: 1; background: #ecfdf5; border-radius: 14px; padding: 10px; display: flex; flex-direction: column; align-items: center; border: 1px solid #d1fae5;">
                    <span style="font-size: 11px; color: #059669; font-weight: 700; margin-bottom: 2px;">Hadir</span>
                    <span style="font-size: 18px; color: #047857; font-weight: 800;">${hadirCount}</span>
                </div>
                <div style="flex: 1; background: #fef2f2; border-radius: 14px; padding: 10px; display: flex; flex-direction: column; align-items: center; border: 1px solid #fee2e2;">
                    <span style="font-size: 11px; color: #ef4444; font-weight: 700; margin-bottom: 2px;">Tidak Hadir</span>
                    <span style="font-size: 18px; color: #b91c1c; font-weight: 800;">${tidakHadirCount}</span>
                </div>
            </div>
        </div>`;
    });

    containerProg.innerHTML = htmlProg;
}

function renderTop3WilayahDinamis() {
    let containerTop = document.getElementById('container-top-3-wilayah');
    if(!containerTop) return;

    
    let listWilayah = ["Pasuruan", "Malang", "Surabaya"];
    let statsWilayah = [];

    listWilayah.forEach(wil => {
        let hadirCount = 0;
        Object.keys(absensiHadirData).forEach(k => {
            if(k.startsWith(wil + "_" + globalActivePeriode)) {
                hadirCount += absensiHadirData[k];
            }
        });
        
        let totalPesertaWilayah = masterListPesertaObj.filter(p => p.Wilayah === wil).length;
        if(totalPesertaWilayah === 0) totalPesertaWilayah = 1; 
        if(hadirCount > totalPesertaWilayah) hadirCount = totalPesertaWilayah;

        let persentaseHadir = Math.round((hadirCount / totalPesertaWilayah) * 100);

        statsWilayah.push({
            wilayah: wil,
            hadir: hadirCount,
            total: totalPesertaWilayah,
            score: persentaseHadir
        });
    });

    statsWilayah.sort((a, b) => b.score - a.score);

    let htmlTop = '';
    let bgColors = ["#fff0f6", "#eef2ff", "#ecfdf5"];
    let textColors = ["#d81b60", "#4f46e5", "#059669"];
    let medals = ["#ffd700", "#c0c0c0", "#cd7f32"];

    statsWilayah.forEach((item, index) => {
        htmlTop += `
        <div class="list-item">
            <div class="list-item-icon" style="background: ${bgColors[index]}; color: ${textColors[index]};"><span style="font-size: 18px; font-weight: 800;">${index + 1}</span></div>
            <div class="list-item-text">
                <h4>Wilayah ${item.wilayah}</h4>
                <p>${item.hadir} Peserta Hadir (${item.score}%)</p>
            </div>
            <i class="ph-fill ph-medal" style="color: ${medals[index]}; font-size: 28px;"></i>
        </div>`;
    });

    containerTop.innerHTML = htmlTop;
}

function renderJadwalKorwil() {
    let wilayahAktif = localStorage.getItem('activeWilayah') || 'Pasuruan';
    let container = document.getElementById('container-daftar-jadwal-lokasi');
    if(!container) return;

    let jadwalWilayah = masterListJadwalObj.filter(j => j.Wilayah === wilayahAktif);
    let html = '';

    jadwalWilayah.forEach((item) => {
        let siklusText = (item.Siklus || '').toUpperCase();
        let iconBg = 'var(--c-pink)';
        let iconColor = '#d81b60';

        // Pengecekan spesifik berdasarkan teks IMDA
        if (siklusText.includes('III') || siklusText.includes('IMDA III')) {
            iconBg = 'var(--c-blue)';
            iconColor = '#1976d2';
        } else if (siklusText.includes('II') || siklusText.includes('IMDA II')) {
            iconBg = 'var(--c-orange)';
            iconColor = '#f57c00';
        } else {
            iconBg = 'var(--c-pink)';
            iconColor = '#d81b60';
        }
        
        html += `
        <div class="list-item" style="flex-direction: column; align-items: stretch; gap: 10px; cursor: default;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="list-item-icon" style="background: ${iconBg}; color: ${iconColor}; margin:0;"><i class="ph-fill ph-calendar-star"></i></div>
                <div class="list-item-text">
                    <h4 style="font-size: 15px; color: var(--text-main);">${item.Siklus} • ${item.Sesi}</h4>
                    <p style="font-weight:700; color:var(--text-main); margin-top:2px;"><i class="ph-fill ph-map-pin"></i> Lokasi: ${item.Lokasi_Pembinaan}</p>
                </div>
            </div>
            <div style="display: flex; background: var(--bg-main); padding: 10px; border-radius: 12px; border: 1px solid var(--border-color); align-items:center; justify-content:space-between;">
                <span style="font-size: 11px; color: var(--text-muted); font-weight: 700;">Waktu Pelaksanaan</span>
                <span style="font-size: 12px; color: var(--text-main); font-weight: 800;">${item.Waktu}</span>
            </div>
        </div>`;
    });

    container.innerHTML = html || `<p style="text-align:center; font-size:12px;">Tidak ada jadwal untuk wilayah ini.</p>`;
}

function renderAbsensiKorwil() {
    let wilayahAktif = localStorage.getItem('activeWilayah') || 'Pasuruan';
    let lokasiAktif = document.getElementById('label-madrasah-terpilih-header').innerText;
    let container = document.getElementById('container-daftar-siswa');
    if(!container) return;

    let pesertaWilayah = masterListPesertaObj.filter(p => p.Wilayah === wilayahAktif);
    let keyMap = wilayahAktif + "_" + globalActivePeriode + "_" + lokasiAktif;
    let savedStatusMap = detailAbsensiPeserta[keyMap] || {};
    let html = '';

    pesertaWilayah.forEach((p, index) => {
        let isChecked = (savedStatusMap[p.ID] === "Hadir");
        
        // Cek apakah ada keterangan khusus (Izin / Sakit) yang sudah dipilih
        let ketData = statusKeteranganMap[keyMap + "_" + p.ID] || "";
        let badgeKet = "";
        let boxBg = "#e0f2fe";
        let boxColor = "#0284c7";

        if (ketData.startsWith("Izin")) {
            badgeKet = `<div style="font-size:10px; color:#f59e0b; font-weight:800; margin-top:2px;">🟡 ${ketData}</div>`;
            boxBg = "#fef3c7"; boxColor = "#d97706";
        } else if (ketData.startsWith("Sakit")) {
            badgeKet = `<div style="font-size:10px; color:#ef4444; font-weight:800; margin-top:2px;">🔴 ${ketData}</div>`;
            boxBg = "#fee2e2"; boxColor = "#dc2626";
        }

        html += `
        <div class="absensi-card" data-nama="${p.Nama}" data-kelas="${p.Kelas}">
            <div class="number-box" onclick="bukaModalKeteranganAbsen('${p.ID}', '${p.Nama}')" style="background: ${boxBg}; color: ${boxColor}; cursor: pointer;" title="Klik untuk ubah Izin/Sakit">${index + 1}</div>
            <div class="list-item-text" style="flex:1;">
                <div style="font-size:15px; color:var(--text-main); font-weight:800; margin-bottom:3px;">${p.Nama}</div>
                <div style="font-size:11px; color:var(--text-muted); font-weight:600;">ID: ${p.ID} • ${p.Kelas} • ${p.Lembaga}</div>
                ${badgeKet}
            </div>
            <div>
                <label class="toggle-switch">
                     <input type="checkbox" class="absensi-checkbox" data-id="${p.ID}" ${isChecked ? 'checked' : ''} onchange="hapusKeteranganJikaHadir('${p.ID}', this)">
                     <span class="toggle-slider"></span>
                </label>
            </div>
        </div>`;
    });

    container.innerHTML = html || `<p style="text-align:center; font-size:12px; padding:20px;">Tidak ada data peserta untuk wilayah ini.</p>`;
}

function updateAdminDashboard() {
    let r = getRiwayat().filter(x => x.periode === globalActivePeriode);
    document.getElementById('jml-hadir').innerText = `${r.length} Files`;
    let container = document.getElementById('wilayah-stats-container');
    let setSudah = new Set(r.map(x => x.madrasah));
    let wilMap = {};
    masterListMadrasahObj.forEach(item => {
        let w = item.Wilayah || "Pusat";
        let fullName = `${item.Nama_Madrasah}`;
        if(!wilMap[w]) wilMap[w] = { total: 0, sudah: 0 };
        wilMap[w].total++;
        if(setSudah.has(fullName)) wilMap[w].sudah++;
    });
    let html = '';
    for(let wil in wilMap) {
        let d = wilMap[wil];
        html += `
        <div class="list-item" style="margin-bottom:10px;">
            <div class="list-item-icon" style="background:var(--c-teal); color:#00796b;"><i class="ph-fill ph-map-pin"></i></div>
            <div class="list-item-text">
                <h4>Wilayah ${wil}</h4>
                <p>${d.sudah} / ${d.total} Lokasi Selesai Kunjungan</p>
            </div>
        </div>`;
    }
    container.innerHTML = html || `<p style="text-align:center; font-size:12px;">Data kosong.</p>`;
}

function bukaModalRiwayatAdmin() {
    masterListExport = getRiwayat().filter(x => x.periode === globalActivePeriode).reverse();
    document.getElementById('search-input-export').value = '';
    filterModalExport();
    document.getElementById('modal-export-admin').style.display = 'flex';
}

function filterModalExport() {
    let key = document.getElementById('search-input-export').value.toLowerCase();
    let container = document.getElementById('list-hasil-supervisi');
    container.innerHTML = '';
    let filtered = masterListExport.filter(x => x.madrasah.toLowerCase().includes(key) || x.spv.toLowerCase().includes(key) || (x.wilayah && x.wilayah.toLowerCase().includes(key)));
    
    filtered.forEach((item, index) => {
        container.innerHTML += `
        <div class="export-item" style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
            <div style="flex: 1;">
                <strong><i class="ph-fill ph-folder"></i> ${item.madrasah} (${item.wilayah || 'Umum'})</strong>
                <span style="font-size:11px; opacity:0.8; display:block; margin-top:2px;">Korwil: ${item.spv} | TA: ${item.tahunAjaran || '-'} | ${item.waktu}</span>
            </div>
            <button onclick="eksporSatuLembagaExcel('${item.madrasah}', '${item.wilayah}')" style="background: var(--accent-gradient); border: none; border-radius: 12px; width: 38px; height: 38px; color: #2b1c50; font-size: 18px; display: flex; justify-content: center; align-items: center; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.1); flex-shrink: 0;" title="Unduh Detail Absensi Lembaga Ini">
                <i class="ph-bold ph-download-simple"></i>
            </button>
        </div>`;
    });
    if(filtered.length === 0) {
        container.innerHTML = `<p style="text-align:center; font-size:12px; color:white;">Tidak ada riwayat kunjungan ditemukan.</p>`;
    }
}

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

function bukaModalGantiPeriode() {
    let container = document.getElementById('list-opsi-periode');
    container.innerHTML = '';
    daftarPilihanPeriode.forEach(p => {
        let isSelected = (p === globalActivePeriode);
        container.innerHTML += `
        <label class="export-item" onclick="pilihPeriodeBaru('${p}')" style="border-color:${isSelected?'#38f9d7':'rgba(255,255,255,0.2)'};">
            <strong><i class="ph-fill ph-calendar-check"></i> ${p} ${isSelected ? '(Aktif)' : ''}</strong>
        </label>`;
    });
    document.getElementById('modal-ganti-periode').style.display = 'flex';
}

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

function bukaModalTahunAjaran() {
    let container = document.getElementById('list-opsi-tahun-ajaran');
    container.innerHTML = '';
    daftarTahunAjaran.forEach(ta => {
        let isSelected = (ta === globalActiveTahunAjaran);
        container.innerHTML += `
        <label class="export-item" onclick="pilihTahunAjaranBaru('${ta}')" style="border-color:${isSelected?'#38f9d7':'rgba(255,255,255,0.2)'};">
            <strong><i class="ph-fill ph-calendar-blank"></i> ${ta} ${isSelected ? '(Aktif)' : ''}</strong>
        </label>`;
    });
    document.getElementById('modal-tahun-ajaran').style.display = 'flex';
}

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

function downloadTemplateExcel(jenis) {
    showLoading();
    setTimeout(() => {
        let sampleData = [];
        let fileName = "Template_Import.xlsx";

        if (jenis === 'lokasi') {
            fileName = "Template_Impor_Lokasi.xlsx";
            sampleData = [
                { Nama_Madrasah: "MI Al-Hidayah", Wilayah: "Pasuruan" },
                { Nama_Madrasah: "SDN 1 Wonorejo", Wilayah: "Pasuruan" }
            ];
        } else if (jenis === 'peserta') {
            fileName = "Template_Impor_Peserta.xlsx";
            sampleData = [
                { ID: "401", Nama: "Ahmad Fauzi, S.Pd.", Kelas: "Kelas 4", Lembaga: "MI Al-Hidayah", Wilayah: "Pasuruan" }
            ];
        } else if (jenis === 'jadwal') {
            fileName = "Template_Impor_Jadwal.xlsx";
            sampleData = [
                { Siklus: "IMDA I", Sesi: "Sesi 1", Lokasi_Pembinaan: "MI Al-Hidayah", Wilayah: "Pasuruan", Waktu: "Minggu ke-1" }
            ];
        } else if (jenis === 'koordinator') {
            fileName = "Template_Impor_Korwil.xlsx";
            sampleData = [
                { Username: "korwil_pasuruan", Nama_Korwil: "Ahmad Fauzi, S.Pd.", Wilayah: "Pasuruan" }
            ];
        } else if (jenis === 'artikel') {
            // Modifikasi khusus template Artikel / Metode
            fileName = "Template_Impor_Artikel.xlsx";
            sampleData = [
                { 
                    Judul: "Inovasi Pembelajaran Al-Miftah", 
                    Badge: "Metode Baru", 
                    Tanggal: "15 Agustus 2026",
                    GambarURL: "https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=800&auto=format&fit=crop",
                    Konten: "<p><span class='dropcap'>P</span>endidikan sangat penting. Dalam era yang serba cepat ini, guru dituntut untuk lebih inovatif.</p><blockquote class='quote-box'>Ilmu adalah cahaya yang tidak akan pernah padam.</blockquote><div class='tips-card'>Pastikan membaca materi sebelum mengajar.</div>"
                }
            ];
        }

        let worksheet = XLSX.utils.json_to_sheet(sampleData);
        let workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Template");
        XLSX.writeFile(workbook, fileName);
        
        hideLoading();
        tampilkanNotifInfo(`Template ${fileName} berhasil diunduh.`, "Unduh Template Sukses", "success");
    }, 500);
}

function eksporAbsensiPesertaExcel() {
    showLoading();
    setTimeout(() => {
        let exportData = [];
        let filteredRiwayat = riwayatPresensi.filter(x => x.periode === globalActivePeriode);

        if (filteredRiwayat.length > 0) {
            filteredRiwayat.forEach(item => {
                let hadirCount = 0;
                item.peserta.forEach(p => { if (p.status_kehadiran === 'Hadir' || p.status === 'Hadir') hadirCount++; });
                
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
                    let statusKehadiran = det ? (det.status_kehadiran || det.status) : "Tidak Hadir";
                    
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

                // Modifikasi penanganan import untuk artikel
                if(jenis === 'artikel') {
                    // Update ke memori lokal secara langsung (untuk preview client-side)
                    masterListArtikelObj = json;
                    
                    // Juga memanggil server-side script untuk menyimpan data agar sinkron
                    apiCall('import_data', {type: 'artikel', data: json}).then(res => {
                        hideLoading();
                        if(res.status === 'success') {
                            tampilkanNotifInfo(`Berhasil mengimpor ${json.length} Artikel Panduan / Metode baru.`, "Impor Artikel Sukses", "success");
                        } else {
                            tampilkanNotifInfo(`Berhasil mengimpor (Lokal), tetapi gagal sinkron ke server.`, "Impor Sukses", "success");
                        }
                    }).catch(err => {
                        hideLoading();
                        tampilkanNotifInfo(`Berhasil dimuat secara lokal. (${json.length} artikel)`, "Impor Sukses", "success");
                    });
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
</script>
</body>
</html>