<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SiPustaka') · SiPustaka</title>
    <style>
        :root{font-family:Georgia,"Times New Roman",serif;color:#0f172a;background:#eff6ff;font-synthesis:none;--ink:#0f172a;--blue:#2563eb;--blue-strong:#1d4ed8;--blue-soft:#dbeafe;--red:#b91c1c;--line:#dbe3f0;--paper:#f8fbff;--muted:#4b5d7a}
        *{box-sizing:border-box}body{margin:0;min-height:100vh;background:radial-gradient(ellipse at 92% 6%,#dbeafe 0,transparent 30%),#eff6ff}a{color:inherit}button,input,select,textarea{font:inherit}button,.button{border:0;background:var(--blue);color:white;padding:10px 15px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:14px}button:hover,.button:hover{background:var(--blue-strong)}button:disabled{opacity:.55;cursor:wait}.button.secondary,button.secondary{background:transparent;color:var(--ink);border:1px solid var(--line)}.button.danger,button.danger{background:var(--red)}
        .topbar{height:68px;border-bottom:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 clamp(18px,5vw,70px);background:rgba(248,251,255,.9)}.brand{font-weight:bold;font-size:19px;text-decoration:none;letter-spacing:0}.brand span{color:#60a5fa}.nav{display:flex;align-items:center;gap:22px;font:14px Arial,sans-serif}.nav a{text-decoration:none}.nav a:hover{text-decoration:underline}.nav form{margin:0}.nav button{font:inherit;background:transparent;color:var(--ink);padding:8px 0}.container{width:min(1120px,calc(100% - 36px));margin:34px auto 70px}.page-head{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:24px}.eyebrow{font:11px Arial,sans-serif;text-transform:uppercase;letter-spacing:1px;color:var(--muted);margin:0 0 8px}h1,h2,h3,p{margin-top:0}h1{font-size:clamp(29px,4vw,43px);line-height:1.04;font-weight:500;letter-spacing:0;margin-bottom:0}h2{font-size:22px;font-weight:500;margin-bottom:5px}h3{font-size:17px;font-weight:500}.subtitle{font:14px/1.6 Arial,sans-serif;color:var(--muted);margin:8px 0 0}.panel{background:var(--paper);border:1px solid var(--line);padding:22px;margin-bottom:20px}.panel-head{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:17px}.panel-head p{font:13px Arial,sans-serif;color:var(--muted);margin:5px 0 0}.metrics{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:22px}.metric{border-top:3px solid var(--blue);background:var(--paper);padding:17px 18px}.metric small{font:12px Arial,sans-serif;color:var(--muted)}.metric strong{display:block;font-size:32px;font-weight:500;margin-top:8px}.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px}.form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:13px}.field{display:flex;flex-direction:column;gap:6px;min-width:0}.field.full{grid-column:1/-1}.field label{font:12px Arial,sans-serif;color:#465148}.field input,.field select,.field textarea,.search input{width:100%;border:1px solid #cbd0c7;background:#fff;padding:10px 11px;color:var(--ink);font:14px Arial,sans-serif;border-radius:0}.field input:focus,.field select:focus,.search input:focus{outline:2px solid #83a88e;outline-offset:1px}.field input[aria-invalid="true"],.field select[aria-invalid="true"]{border-color:var(--red)}.field-error{font:11px/1.4 Arial,sans-serif;color:var(--red)}.hint{font:11px Arial,sans-serif;color:var(--muted)}.form-actions{display:flex;justify-content:flex-end;gap:8px;margin-top:15px}.table-wrap{overflow-x:auto}table{width:100%;border-collapse:collapse;font:13px/1.5 Arial,sans-serif;min-width:650px}th{text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);font-weight:600;background:#f4f5ef}th,td{padding:11px 12px;border-bottom:1px solid #e3e5de;vertical-align:middle}tbody tr:last-child td{border-bottom:0}.muted{color:var(--muted)}.tag{display:inline-block;padding:4px 8px;background:var(--mint);font-size:11px}.tag.returned{background:#e8e8e1}.row-actions{display:flex;align-items:center;gap:8px;white-space:nowrap}.row-actions form{margin:0}.small-btn{padding:6px 9px;font:12px Arial,sans-serif}.inline-edit{padding:12px;background:#f6f7f2;border-bottom:1px solid var(--line)}details summary{cursor:pointer;color:var(--blue);font:12px Arial,sans-serif}.search{display:flex;gap:8px;max-width:390px}.search input{min-width:150px}.pagination{display:flex;justify-content:space-between;align-items:center;margin-top:15px;font:12px Arial,sans-serif;color:var(--muted)}.pagination nav{display:flex;gap:5px}.pagination a,.pagination span{padding:6px 9px;border:1px solid var(--line);text-decoration:none}.notice{padding:11px 13px;margin:0 0 15px;font:13px Arial,sans-serif;background:#dbeafe;border-left:3px solid var(--blue)}.notice.error{background:#f6e8e3;border-color:var(--red);color:#69291f}.errors{font:12px Arial,sans-serif;color:var(--red);margin-top:8px}.toast{position:fixed;right:20px;bottom:20px;background:var(--ink);color:white;padding:13px 17px;font:13px Arial,sans-serif;z-index:5;opacity:0;transform:translateY(8px);transition:.2s;pointer-events:none}.toast.show{opacity:1;transform:translateY(0)}.auth-wrap{min-height:calc(100vh - 68px);display:grid;grid-template-columns:1.08fr .92fr;max-width:1040px;margin:auto;padding:35px 20px;align-items:center;gap:48px}.auth-copy{padding:22px 10px}.auth-copy h1{max-width:520px}.auth-copy p{font:15px/1.7 Arial,sans-serif;color:var(--muted);max-width:470px;margin:17px 0 25px}.auth-mark{font-size:90px;line-height:.8;color:#9eb39f;margin-bottom:24px}.auth-card{background:var(--paper);border:1px solid var(--line);padding:30px;box-shadow:8px 8px 0 #e1e3d9}.auth-card .field{margin-bottom:14px}.auth-card h2{margin-bottom:5px}.auth-foot{font:13px Arial,sans-serif;color:var(--muted);margin-top:18px}.auth-foot a{color:var(--blue)}.welcome-actions{display:flex;gap:10px}.status-cell{min-width:128px}.mobile-label{display:none}
        @media(max-width:760px){.topbar{padding:0 18px}.nav{gap:12px;font-size:12px}.container{margin-top:25px}.page-head{align-items:flex-start;flex-direction:column}.metrics{grid-template-columns:1fr 1fr}.grid-2{grid-template-columns:1fr}.auth-wrap{grid-template-columns:1fr;gap:12px;max-width:520px;align-content:center}.auth-copy{padding:10px 4px 0}.auth-mark{font-size:62px;margin-bottom:18px}.auth-copy p{margin:12px 0 16px}.auth-card{padding:22px}.panel{padding:16px}.form-grid{grid-template-columns:1fr}.field.full{grid-column:auto}.search{width:100%;max-width:none}.search input{flex:1}.hide-mobile{display:none}}
        @media(max-width:440px){.nav{gap:9px}.brand{font-size:17px}.nav a{font-size:11px}.metrics{gap:8px}.metric{padding:13px}.metric strong{font-size:27px}.welcome-actions{flex-wrap:wrap}}
    </style>
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ auth()->check() ? route('dashboard') : route('home') }}">Si<span>Pustaka</span></a>
        <nav class="nav">
            @auth
                <a href="{{ route('dashboard') }}">Ringkasan</a>
                <a href="{{ route('pendaftaran.index') }}">Peminjaman</a>
                @if (auth()->user()->isAdmin()) <a href="{{ route('users.index') }}">Pengguna</a> @endif
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Keluar</button></form>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}">Daftar</a>
            @endauth
        </nav>
    </header>
    @yield('content')
    <div class="toast" role="status" aria-live="polite"></div>
    <script>
        const toast = (message) => {
            const element = document.querySelector('.toast');
            if (!element) return;
            element.textContent = message;
            element.classList.add('show');
            window.setTimeout(() => element.classList.remove('show'), 2600);
        };
        document.querySelectorAll('form[data-ajax]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const button = form.querySelector('[type="submit"]');
                const errors = form.querySelector('[data-errors]');
                if (errors) errors.textContent = '';
                form.querySelectorAll('.field-error').forEach((error) => error.remove());
                form.querySelectorAll('[aria-invalid="true"]').forEach((control) => control.removeAttribute('aria-invalid'));
                if (button) button.disabled = true;
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content},
                        body: new FormData(form),
                    });
                    const result = await response.json();
                    if (!response.ok) {
                        if (result.errors) {
                            Object.entries(result.errors).forEach(([name, messages]) => {
                                const control = form.querySelector(`[name="${CSS.escape(name)}"]`);
                                const field = control?.closest('.field');
                                if (control && field) {
                                    control.setAttribute('aria-invalid', 'true');
                                    const message = document.createElement('span');
                                    message.className = 'field-error';
                                    message.textContent = messages[0];
                                    field.append(message);
                                } else if (errors) {
                                    errors.textContent = messages[0];
                                }
                            });
                        } else if (errors) {
                            errors.textContent = result.message || 'Tidak dapat menyimpan perubahan.';
                        } else {
                            toast(result.message || 'Tidak dapat menyimpan perubahan.');
                        }
                        return;
                    }
                    toast(result.message || 'Tersimpan.');
                    if (form.dataset.reload !== 'false') window.setTimeout(() => window.location.reload(), 450);
                    form.dispatchEvent(new CustomEvent('ajax:success', {detail: result, bubbles: true}));
                } catch {
                    toast('Koneksi gagal. Silakan coba lagi.');
                } finally {
                    if (button) button.disabled = false;
                }
            });
        });
        document.querySelectorAll('[data-ajax-delete]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                if (!window.confirm(form.dataset.confirm || 'Hapus data ini?')) return;
                const response = await fetch(form.action, {method:'POST',headers:{'Accept':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body:new FormData(form)});
                const result = await response.json();
                if (!response.ok) { toast(result.message || 'Data tidak dapat dihapus.'); return; }
                toast(result.message);
                window.setTimeout(() => window.location.reload(), 350);
            });
        });
    </script>
</body>
</html>