<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HydroFarm – AI-Powered Hydroponic Intelligence</title>
    <meta name="description" content="Platform monitoring dan kendali cerdas berbasis AI untuk pertanian hidroponik.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --green: #16a34a; --green-light: #22c55e; --green-xlight: #dcfce7;
            --blue: #0284c7; --gray-50: #f8fafc; --gray-100: #f1f5f9;
            --gray-200: #e2e8f0; --gray-300: #cbd5e1; --gray-400: #94a3b8;
            --gray-500: #64748b; --gray-600: #475569; --gray-800: #1e293b; --gray-900: #0f172a;
        }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: #fff; color: var(--gray-900); overflow-x: hidden; }

        /* ── NAVBAR ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 0 2rem; height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(255,255,255,0.9); backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--gray-200); transition: box-shadow .3s;
        }
        nav.scrolled { box-shadow: 0 1px 12px rgba(0,0,0,.07); }
        .nav-brand { display: flex; align-items: center; gap: .6rem; text-decoration: none; }
        .nav-brand img { height: 2rem; }
        .nav-brand-name { font-size: 1.1rem; font-weight: 800; color: var(--gray-900); letter-spacing: -.4px; }
        .nav-links { display: flex; align-items: center; gap: 1.5rem; list-style: none; }
        .nav-links a { font-size: .875rem; font-weight: 500; color: var(--gray-500); text-decoration: none; transition: color .2s; }
        .nav-links a:hover { color: var(--green); }
        .btn-nav {
            display: flex; align-items: center; gap: .4rem;
            padding: .45rem 1.1rem; background: var(--green); color: #fff !important;
            border-radius: 8px; font-size: .875rem; font-weight: 600;
            text-decoration: none; transition: all .2s;
        }
        .btn-nav:hover { background: #15803d; box-shadow: 0 3px 12px rgba(22,163,74,.3); transform: translateY(-1px); }
        .btn-nav svg { width: 14px; height: 14px; }

        /* ── HERO ── */
        .hero {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 8rem 2rem 5rem;
            background: linear-gradient(160deg, #ffffff 40%, #f0fdf4 100%);
            position: relative; overflow: hidden;
        }
        .hero::before {
            content: ''; position: absolute; width: 700px; height: 700px;
            top: -200px; left: 50%; transform: translateX(-50%);
            border-radius: 50%;
            background: radial-gradient(circle, rgba(34,197,94,.1), transparent 65%);
            pointer-events: none;
        }
        .hero-content { position: relative; z-index: 1; max-width: 820px; }

        .hero-badge {
            display: inline-flex; align-items: center; gap: .45rem;
            padding: .3rem .85rem; border-radius: 6px;
            background: var(--green-xlight); color: #15803d;
            font-size: .75rem; font-weight: 700; letter-spacing: .5px; text-transform: uppercase;
            margin-bottom: 1.75rem; animation: fadeUp .8s ease both;
        }
        .hero-badge svg { width: 14px; height: 14px; }

        .hero h1 {
            font-size: clamp(2.75rem, 6.5vw, 5rem); font-weight: 900;
            line-height: 1.06; letter-spacing: -2.5px; color: var(--gray-900);
            margin-bottom: 1.5rem; animation: fadeUp .8s .1s ease both;
        }
        .grad { background: linear-gradient(135deg, #16a34a 0%, #22c55e 45%, #0284c7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .hero p { font-size: 1.1rem; color: var(--gray-500); max-width: 600px; margin: 0 auto 2.5rem; line-height: 1.75; animation: fadeUp .8s .2s ease both; }

        .hero-ctas { display: flex; gap: .75rem; justify-content: center; flex-wrap: wrap; animation: fadeUp .8s .3s ease both; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .75rem 1.75rem; background: var(--green); color: #fff;
            border-radius: 8px; font-weight: 700; font-size: .95rem; text-decoration: none;
            transition: all .25s; box-shadow: 0 2px 12px rgba(22,163,74,.25);
        }
        .btn-primary:hover { background: #15803d; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(22,163,74,.35); }
        .btn-outline {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .75rem 1.75rem; border: 1.5px solid var(--gray-300);
            color: var(--gray-700, #374151); border-radius: 8px; font-weight: 600;
            font-size: .95rem; text-decoration: none; background: white; transition: all .25s;
        }
        .btn-outline:hover { border-color: var(--green); color: var(--green); transform: translateY(-2px); }
        .btn-primary svg, .btn-outline svg, .btn-nav svg { flex-shrink: 0; }

        /* ── SOCIAL PROOF ── */
        .proof-bar {
            background: var(--gray-50); border-top: 1px solid var(--gray-200); border-bottom: 1px solid var(--gray-200);
            padding: 2.5rem 2rem;
        }
        .proof-inner { max-width: 1100px; margin: 0 auto; display: flex; flex-wrap: wrap; justify-content: center; gap: 4rem; }
        .proof-item { text-align: center; }
        .proof-num { font-size: 2.4rem; font-weight: 900; color: var(--gray-900); letter-spacing: -1.5px; line-height: 1; }
        .proof-num .accent { color: var(--green); }
        .proof-label { font-size: .8rem; color: var(--gray-400); margin-top: .3rem; font-weight: 500; }

        /* ── SECTIONS ── */
        .section { padding: 6rem 2rem; }
        .section-alt { background: var(--gray-50); }
        .container { max-width: 1100px; margin: 0 auto; }
        .eyebrow { font-size: .7rem; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase; color: var(--green); margin-bottom: .6rem; }
        .sec-title { font-size: clamp(1.8rem, 4vw, 2.8rem); font-weight: 900; letter-spacing: -1px; line-height: 1.1; color: var(--gray-900); margin-bottom: 1rem; }
        .sec-desc { font-size: .95rem; color: var(--gray-500); line-height: 1.75; max-width: 520px; }

        /* ── FEATURES GRID ── */
        .features-head { text-align: center; margin-bottom: 3.5rem; }
        .features-head .sec-desc { margin: 0 auto; }
        .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; }
        .feat-card {
            background: white; border: 1px solid var(--gray-200);
            border-radius: 14px; padding: 1.75rem; transition: all .3s;
        }
        .feat-card:hover { border-color: rgba(22,163,74,.4); transform: translateY(-4px); box-shadow: 0 12px 32px rgba(22,163,74,.09); }
        .feat-icon {
            width: 44px; height: 44px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; margin-bottom: 1.1rem;
            background: var(--green-xlight); color: var(--green);
        }
        .feat-icon svg { width: 22px; height: 22px; }
        .feat-card h3 { font-size: 1rem; font-weight: 700; color: var(--gray-900); margin-bottom: .5rem; }
        .feat-card p { font-size: .875rem; color: var(--gray-500); line-height: 1.7; }

        /* ── HOW IT WORKS ── */
        .how-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
        @media(max-width:768px){.how-grid{grid-template-columns:1fr;gap:3rem;}}
        .steps { display: flex; flex-direction: column; gap: 1.5rem; }
        .step { display: flex; gap: 1.1rem; align-items: flex-start; }
        .step-n {
            width: 36px; height: 36px; min-width: 36px; border-radius: 8px;
            background: var(--green-xlight); border: 1.5px solid rgba(22,163,74,.2);
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: .8rem; color: var(--green);
        }
        .step h4 { font-size: .9rem; font-weight: 700; color: var(--gray-900); margin-bottom: .2rem; }
        .step p { font-size: .83rem; color: var(--gray-500); line-height: 1.65; }

        /* Dashboard mockup */
        .mockup {
            background: white; border: 1px solid var(--gray-200); border-radius: 16px;
            padding: 1.25rem; box-shadow: 0 16px 48px rgba(0,0,0,.08);
        }
        .mockup-bar { display: flex; align-items: center; gap: .35rem; margin-bottom: 1.1rem; }
        .m-dot { width: 9px; height: 9px; border-radius: 50%; }
        .m-r{background:#fca5a5} .m-y{background:#fcd34d} .m-g{background:#86efac}
        .m-title { font-size: .7rem; font-weight: 600; color: var(--gray-400); margin-left: auto; background: var(--gray-100); padding: 2px 8px; border-radius: 4px; }
        .m-cards { display: grid; grid-template-columns: repeat(3,1fr); gap: .6rem; margin-bottom: .75rem; }
        .m-card { background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: 10px; padding: .65rem; }
        .m-label { font-size: .6rem; font-weight: 700; color: var(--gray-400); text-transform: uppercase; letter-spacing: .5px; }
        .m-value { font-size: 1.1rem; font-weight: 900; color: var(--gray-900); line-height: 1.2; }
        .m-badge { font-size: .58rem; font-weight: 600; color: #15803d; background: #dcfce7; border-radius: 4px; padding: 1px 5px; display: inline-block; margin-top: 2px; }
        .m-chart { background: var(--gray-50); border: 1px solid var(--gray-200); border-radius: 10px; padding: .75rem; height: 90px; display: flex; align-items: flex-end; gap: 3px; margin-bottom: .75rem; }
        .bar { flex: 1; border-radius: 3px 3px 0 0; background: linear-gradient(180deg,#22c55e,#16a34a); opacity: .75; transition: opacity .2s; }
        .bar:hover { opacity: 1; }
        .m-status { display: flex; align-items: center; gap: .5rem; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: .45rem .75rem; }
        .m-live { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; animation: pulse 2s infinite; }
        .m-status span { font-size: .68rem; font-weight: 600; color: #166534; }

        /* ── AI SECTION ── */
        .ai-box {
            border: 1px solid var(--gray-200); border-radius: 20px;
            background: linear-gradient(135deg, #f0fdf4, #eff6ff);
            padding: 4rem 2.5rem; text-align: center; position: relative; overflow: hidden;
        }
        .ai-box::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 60% 50% at 50% 0%, rgba(22,163,74,.07), transparent);
        }
        .ai-box > * { position: relative; }
        .ai-box .sec-desc { margin: 0 auto 2.5rem; }
        .chips { display: flex; flex-wrap: wrap; justify-content: center; gap: .65rem; }
        .chip {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .4rem 1rem; background: white; border: 1px solid var(--gray-200);
            border-radius: 50px; font-size: .82rem; font-weight: 500; color: var(--gray-600);
            transition: all .2s; cursor: default;
        }
        .chip svg { width: 15px; height: 15px; color: var(--green); flex-shrink: 0; }
        .chip:hover { border-color: var(--green); color: var(--green); transform: translateY(-2px); box-shadow: 0 4px 10px rgba(22,163,74,.15); }

        /* ── CTA ── */
        .cta { background: var(--gray-900); padding: 7rem 2rem; text-align: center; }
        .cta h2 { font-size: clamp(2rem,5vw,3rem); font-weight: 900; color: white; letter-spacing: -1.5px; margin-bottom: .9rem; }
        .cta h2 .g { background: linear-gradient(135deg,#22c55e,#4ade80); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .cta p { color: var(--gray-400); font-size: 1rem; margin-bottom: 2.25rem; }
        .btn-cta {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .85rem 2.25rem; background: var(--green); color: white;
            border-radius: 8px; font-weight: 700; font-size: 1rem; text-decoration: none;
            transition: all .25s; box-shadow: 0 4px 16px rgba(22,163,74,.35);
        }
        .btn-cta:hover { background: #15803d; transform: translateY(-2px); }
        .btn-cta svg { width: 18px; height: 18px; }

        /* ── FOOTER ── */
        footer { background: var(--gray-900); border-top: 1px solid rgba(255,255,255,.06); padding: 2rem; text-align: center; }
        .foot-brand { display: flex; align-items: center; justify-content: center; gap: .5rem; margin-bottom: .5rem; }
        .foot-brand img { height: 1.75rem; }
        .foot-brand span { font-size: 1rem; font-weight: 800; color: white; }
        footer p { font-size: .8rem; color: var(--gray-500); }

        /* ── UTILS ── */
        @keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(.8)} }
        .fade { opacity: 0; transform: translateY(20px); transition: opacity .6s, transform .6s; }
        .fade.in { opacity: 1; transform: none; }
        @media(max-width:640px){ .nav-links{display:none;} .m-cards{grid-template-columns:repeat(2,1fr);} .proof-inner{gap:2rem;} }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav id="nav">
    <a href="/" class="nav-brand">
        <img src="{{ asset('images/logo.png') }}" alt="HydroFarm">
        <span class="nav-brand-name">HydroFarm</span>
    </a>
    <ul class="nav-links">
        <li><a href="#features">Fitur</a></li>
        <li><a href="#how">Cara Kerja</a></li>
        <li><a href="#ai">Teknologi AI</a></li>
        <li>
            <a href="/hydroponicFarm/login" class="btn-nav">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/></svg>
                Masuk ke Dashboard
            </a>
        </li>
    </ul>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09Z"/></svg>
            Platform Hidroponik Berbasis AI
        </div>
        <h1>Pertanian Hidroponic<br>yang <span class="grad">Lebih Cerdas</span></h1>
        <p>Pantau sensor real-time, kendalikan aktuator otomatis, dan prediksi kondisi tanaman dengan kecerdasan buatan semua dalam satu platform.</p>
        <div class="hero-ctas">
            <a href="/hydroponicFarm/login" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:18px;height:18px"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                Akses Dashboard
            </a>
            <a href="#features" class="btn-outline">
                Pelajari Fitur
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:16px;height:16px"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- STATS -->
<div class="proof-bar">
    <div class="proof-inner">
        <div class="proof-item fade"><div class="proof-num">24<span class="accent">/7</span></div><div class="proof-label">Monitoring Real-Time</div></div>
        <div class="proof-item fade"><div class="proof-num">6<span class="accent">+</span></div><div class="proof-label">Parameter Sensor</div></div>
        <div class="proof-item fade"><div class="proof-num"><span class="accent">AI</span></div><div class="proof-label">Prediksi Cerdas</div></div>
        <div class="proof-item fade"><div class="proof-num">5<span class="accent">m</span></div><div class="proof-label">Interval Update</div></div>
    </div>
</div>

<!-- FEATURES -->
<section class="section" id="features">
    <div class="container">
        <div class="features-head fade">
            <div class="eyebrow">Fitur Utama</div>
            <h2 class="sec-title">Teknologi Lengkap untuk Pertanian Modern</h2>
            <p class="sec-desc">Dari sensor hingga aktuator, dari data historis hingga prediksi masa depan, semua terintegrasi.</p>
        </div>
        <div class="grid-3">
            <div class="feat-card fade">
                <div class="feat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg>
                </div>
                <h3>Monitoring Sensor Real-Time</h3>
                <p>Pantau suhu, kelembaban, tekanan, dan UV dengan update setiap 5 menit melalui grafik interaktif.</p>
            </div>
            <div class="feat-card fade">
                <div class="feat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 0 1-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 0 1 4.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0 1 12 15a9.065 9.065 0 0 0-6.23-.693L5 14.5m14.8.8 1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0 1 12 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>
                </div>
                <h3>Monitoring Kualitas Air</h3>
                <p>Pantau pH, TDS, dan DO untuk memastikan nutrisi air hidroponik selalu dalam kondisi optimal.</p>
            </div>
            <div class="feat-card fade">
                <div class="feat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                </div>
                <h3>Kendali Aktuator Otomatis</h3>
                <p>Kontrol sprinkler dan kipas angin secara manual maupun otomatis berdasarkan kondisi sensor.</p>
            </div>
            <div class="feat-card fade">
                <div class="feat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z"/></svg>
                </div>
                <h3>Prediksi AI & Early Warning</h3>
                <p>AI menganalisis tren data dan memberikan peringatan dini sebelum kondisi tanaman memburuk.</p>
            </div>
            <div class="feat-card fade">
                <div class="feat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/></svg>
                </div>
                <h3>Analitik Data Historis</h3>
                <p>Filter tren 1 jam, 1 hari, 7 hari, atau 30 hari untuk memahami pola pertumbuhan tanaman.</p>
            </div>
            <div class="feat-card fade">
                <div class="feat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                </div>
                <h3>Klasifikasi Kondisi Otomatis</h3>
                <p>Parameter diklasifikasikan otomatis (Sangat Baik, Baik, Kurang Baik dan Sangat Kurang Baik) untuk respons yang cepat dan tepat.</p>
            </div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="section section-alt" id="how">
    <div class="container">
        <div class="how-grid">
            <div>
                <div class="eyebrow fade">Cara Kerja</div>
                <h2 class="sec-title fade">Terintegrasi dari Sensor hingga Aksi</h2>
                <p class="sec-desc fade" style="margin-bottom:2rem">HydroFarm menghubungkan seluruh ekosistem pertanian hidroponic dalam satu platform.</p>
                <div class="steps">
                    <div class="step fade"><div class="step-n">01</div><div><h4>Sensor Mengumpulkan Data</h4><p>Sensor lingkungan dan air mengirim data terus-menerus ke HydroFarm.</p></div></div>
                    <div class="step fade"><div class="step-n">02</div><div><h4>AI Menganalisis & Memprediksi</h4><p>Model AI mengolah data dan menghasilkan prediksi kondisi serta early warning.</p></div></div>
                    <div class="step fade"><div class="step-n">03</div><div><h4>Dashboard Menampilkan Insight</h4><p>Visualisasi dengan grafik dan indikator status real-time yang mudah dipahami.</p></div></div>
                    <div class="step fade"><div class="step-n">04</div><div><h4>Aktuator Merespons Otomatis</h4><p>Sprinkler dan kipas diaktifkan untuk menjaga kondisi optimal tanaman.</p></div></div>
                </div>
            </div>
            <div class="mockup fade">
                <div class="mockup-bar">
                    <div class="m-dot m-r"></div><div class="m-dot m-y"></div><div class="m-dot m-g"></div>
                    <div class="m-title">HydroFarm Live</div>
                </div>
                <div class="m-cards">
                    <div class="m-card"><div class="m-label">Suhu</div><div class="m-value">28.4°</div><div class="m-badge">Normal</div></div>
                    <div class="m-card"><div class="m-label">Kelembaban</div><div class="m-value">72%</div><div class="m-badge">Normal</div></div>
                    <div class="m-card"><div class="m-label">pH Air</div><div class="m-value">6.8</div><div class="m-badge">Optimal</div></div>
                </div>
                <div class="m-chart">
                    <div class="bar" style="height:40%"></div><div class="bar" style="height:65%"></div>
                    <div class="bar" style="height:55%"></div><div class="bar" style="height:82%"></div>
                    <div class="bar" style="height:70%"></div><div class="bar" style="height:93%"></div>
                    <div class="bar" style="height:75%"></div><div class="bar" style="height:85%"></div>
                    <div class="bar" style="height:62%"></div><div class="bar" style="height:96%"></div>
                    <div class="bar" style="height:80%"></div><div class="bar" style="height:78%"></div>
                </div>
                <div class="m-status"><div class="m-live"></div><span>Live · Semua Sensor Normal · Sprinkler: ON</span></div>
            </div>
        </div>
    </div>
</section>

<!-- AI SECTION -->
<section class="section" id="ai">
    <div class="container">
        <div class="ai-box fade">
            <div class="eyebrow">Kecerdasan Buatan</div>
            <h2 class="sec-title">Didukung AI untuk Pertanian<br>yang Lebih Cerdas</h2>
            <p class="sec-desc">HydroFarm mengintegrasikan AI untuk wawasan prediktif, klasifikasi kondisi otomatis, dan keputusan berbasis data.</p>
            <div class="chips">
                <div class="chip"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z"/></svg> Machine Learning</div>
                <div class="chip"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/></svg> Analisis Tren</div>
                <div class="chip"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg> Prediksi Real-Time</div>
                <div class="chip"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg> Klasifikasi Otomatis</div>
                <div class="chip"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0"/></svg> Early Warning</div>
                <div class="chip"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg> Rekomendasi Cerdas</div>
                <div class="chip"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/></svg> Data-Driven Insights</div>
                <div class="chip"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg> Optimasi Otomatis</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta">
    <div class="container fade" style="max-width:680px">
        <h2>Siap Tingkatkan <span class="g">Produktivitas</span> Pertanian?</h2>
        <p>Masuk dan mulai monitoring pertanian hidroponik Anda sekarang juga.</p>
        <a href="/hydroponicFarm/login" class="btn-cta">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75"/></svg>
            Akses Dashboard
        </a>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="foot-brand"><img src="{{ asset('images/logo.png') }}" alt="HydroFarm"><span>HydroFarm</span></div>
    <p>© {{ date('Y') }} HydroFarm — Platform Monitoring Hidroponik Berbasis AI</p>
</footer>

<script>
    const obs = new IntersectionObserver(e => e.forEach(x => { if(x.isIntersecting) x.target.classList.add('in'); }), {threshold:.1});
    document.querySelectorAll('.fade').forEach(el => obs.observe(el));
    window.addEventListener('scroll', () => document.getElementById('nav').classList.toggle('scrolled', scrollY > 40));
</script>
</body>
</html>
