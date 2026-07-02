<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng Điều Khiển Phê Duyệt | HRMS Enterprise</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        heading: ['Montserrat', 'sans-serif'],
                        body: ['Inter', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- AOS (Animate On Scroll) -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        /* ── ANIMATED GRADIENT BG ── */
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(-45deg, #1a0a0a, #2d0f23, #0f2027, #1b1b2f, #44001a);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            overflow-x: hidden;
        }
        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ── CSS PARTICLES ── */
        .particles-layer { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .dot {
            position: absolute; border-radius: 50%;
            background: rgba(255,255,255,0.15);
            box-shadow: 0 0 6px 2px rgba(220,50,80,0.25);
            animation: rise var(--d) linear infinite;
            animation-delay: var(--delay);
        }
        @keyframes rise {
            0%   { transform: translateY(110vh) scale(.8); opacity: 0; }
            15%  { opacity: .7; }
            85%  { opacity: .5; }
            100% { transform: translateY(-5vh) scale(1.4); opacity: 0; }
        }

        /* ── GLASSMORPHISM CARD ── */
        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255,255,255,0.08);
            transition: all .35s cubic-bezier(.4,0,.2,1);
        }
        .glass:hover {
            transform: translateY(-6px);
            border-color: rgba(220,50,80,0.35);
            box-shadow: 0 12px 40px rgba(220,50,80,0.15), 0 0 80px -20px rgba(220,50,80,0.12);
        }

        /* ── TOAST ── */
        .toast-wrap { position: fixed; bottom: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; }
        .toast-item {
            padding: 14px 22px; border-radius: 12px; font-size: 14px; font-weight: 500;
            color: #fff; backdrop-filter: blur(12px);
            animation: slideIn .35s cubic-bezier(.175,.885,.32,1.275);
        }
        @keyframes slideIn { from { transform: translateX(120%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOut { from { opacity: 1; } to { transform: translateX(120%); opacity: 0; } }

        /* ── FILTER PILL ACTIVE ── */
        .filter-pill.active {
            background: linear-gradient(135deg, #dc3545, #c0392b) !important;
            color: #fff !important;
            box-shadow: 0 4px 14px rgba(220,53,69,.45);
        }

        /* ── SCROLLBAR ── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.12); border-radius: 99px; }
    </style>
</head>

<body class="text-white">

    <!-- Particles -->
    <div class="particles-layer" id="particles"></div>

    <!-- Main -->
    <main class="relative z-10 max-w-[1200px] mx-auto px-5 py-10">

        <!-- HEADER -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-5 mb-8" data-aos="fade-down" data-aos-duration="700">
            <div>
                <p class="text-sm font-medium tracking-widest uppercase text-rose-400/80 mb-1">
                    <i class="fa-solid fa-shield-halved mr-1"></i> Manager Dashboard
                </p>
                <h1 class="font-heading text-4xl md:text-5xl font-extrabold leading-tight"
                    style="background:linear-gradient(to right,#fca5a5,#fecdd3,#fff1f2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">
                    Phê Duyệt Nghỉ Phép
                </h1>
                <p class="text-slate-400 text-sm mt-2">Xem xét và xử lý đơn xin nghỉ của nhân viên</p>
            </div>

            <!-- FILTER PILLS -->
            <div class="flex gap-2 bg-black/30 p-1.5 rounded-xl backdrop-blur-md border border-white/5" data-aos="fade-left" data-aos-delay="200">
                <button class="filter-pill active px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 cursor-pointer" data-filter="all">Tất cả</button>
                <button class="filter-pill px-4 py-2 rounded-lg text-sm font-semibold text-slate-400 hover:text-white hover:bg-white/10 transition-all duration-300 cursor-pointer" data-filter="pending">Đang chờ</button>
                <button class="filter-pill px-4 py-2 rounded-lg text-sm font-semibold text-slate-400 hover:text-white hover:bg-white/10 transition-all duration-300 cursor-pointer" data-filter="approved">Đã duyệt</button>
                <button class="filter-pill px-4 py-2 rounded-lg text-sm font-semibold text-slate-400 hover:text-white hover:bg-white/10 transition-all duration-300 cursor-pointer" data-filter="rejected">Từ chối</button>
            </div>
        </header>

        <!-- CARDS GRID -->
        <div id="cards-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 min-h-[200px]">
            <div class="col-span-full flex flex-col items-center justify-center py-16 text-rose-300/70">
                <i class="fa-solid fa-spinner fa-spin text-4xl mb-4"></i>
                <p class="text-lg font-medium">Đang tải dữ liệu...</p>
            </div>
        </div>
    </main>

    <!-- TOAST CONTAINER -->
    <div class="toast-wrap" id="toast-wrap"></div>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

    <script>
        AOS.init({ once: true, easing: 'ease-out-quart' });

        // ── PARTICLES ──
        (function(){
            const c = document.getElementById('particles');
            for(let i=0;i<25;i++){
                const d = document.createElement('div'); d.className='dot';
                const s = Math.random()*4+2;
                d.style.width=s+'px'; d.style.height=s+'px';
                d.style.left=Math.random()*100+'%';
                d.style.setProperty('--d',(Math.random()*8+7)+'s');
                d.style.setProperty('--delay',(Math.random()*6)+'s');
                c.appendChild(d);
            }
        })();

        // ── DATA & MAPS ──
        let allData = [];
        const TYPE_MAP = {
            annual:    { label:'Phép năm',    icon:'fa-calendar-check',  color:'text-sky-400' },
            sick:      { label:'Nghỉ ốm',     icon:'fa-briefcase-medical', color:'text-amber-400' },
            unpaid:    { label:'Không lương',  icon:'fa-wallet',          color:'text-slate-400' },
            maternity: { label:'Thai sản',     icon:'fa-baby-carriage',   color:'text-pink-400' }
        };
        const STATUS_MAP = {
            pending:  { label:'Chờ duyệt', bg:'bg-amber-500/15', text:'text-amber-300', border:'border-amber-500/30', icon:'fa-clock' },
            approved: { label:'Đã duyệt',  bg:'bg-emerald-500/15', text:'text-emerald-300', border:'border-emerald-500/30', icon:'fa-circle-check' },
            rejected: { label:'Từ chối',    bg:'bg-red-500/15', text:'text-red-300', border:'border-red-500/30', icon:'fa-circle-xmark' }
        };

        function fmtDate(s){ return new Date(s).toLocaleDateString('vi-VN',{day:'2-digit',month:'2-digit',year:'numeric'}); }

        // ── TOAST ──
        function toast(msg, ok=true){
            const w=document.getElementById('toast-wrap'), t=document.createElement('div');
            t.className=`toast-item ${ok?'bg-emerald-600/80 border border-emerald-400/30':'bg-red-600/80 border border-red-400/30'}`;
            t.innerHTML=`<i class="fa-solid ${ok?'fa-check-circle':'fa-triangle-exclamation'} mr-2"></i>${msg}`;
            w.appendChild(t);
            setTimeout(()=>{ t.style.animation='slideOut .3s forwards'; setTimeout(()=>t.remove(),300); },3500);
        }

        // ── RENDER ──
        function render(filter='all'){
            const grid=document.getElementById('cards-grid');
            const list = filter==='all' ? allData : allData.filter(x=>x.status===filter);

            if(!list.length){
                grid.innerHTML=`
                    <div class="col-span-full flex flex-col items-center justify-center py-20 text-slate-500" data-aos="zoom-in">
                        <i class="fa-regular fa-folder-open text-5xl mb-4"></i>
                        <p class="text-lg font-medium">Không có đơn nào</p>
                    </div>`;
                return;
            }

            grid.innerHTML='';
            list.forEach((item, i)=>{
                const t = TYPE_MAP[item.leave_type]||{label:'Khác',icon:'fa-calendar',color:'text-slate-400'};
                const s = STATUS_MAP[item.status];

                let actions='';
                if(item.status==='pending'){
                    actions=`
                    <div class="flex gap-3 mt-auto pt-4 border-t border-white/5">
                        <button onclick="processLeave(${item.id},'approve',this)"
                            class="flex-1 py-2.5 rounded-xl text-sm font-bold flex items-center justify-center gap-2
                                   bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400
                                   shadow-lg shadow-emerald-600/20 hover:shadow-emerald-500/30 transition-all duration-300 hover:-translate-y-0.5 cursor-pointer">
                            <i class="fa-solid fa-check"></i> Duyệt
                        </button>
                        <button onclick="processLeave(${item.id},'reject',this)"
                            class="flex-1 py-2.5 rounded-xl text-sm font-bold flex items-center justify-center gap-2
                                   bg-gradient-to-r from-red-600 to-red-500 hover:from-red-500 hover:to-red-400
                                   shadow-lg shadow-red-600/20 hover:shadow-red-500/30 transition-all duration-300 hover:-translate-y-0.5 cursor-pointer">
                            <i class="fa-solid fa-xmark"></i> Từ chối
                        </button>
                    </div>`;
                }

                const card=document.createElement('div');
                card.className='glass rounded-2xl p-6 flex flex-col';
                card.setAttribute('data-aos','fade-up');
                card.setAttribute('data-aos-delay', (i%6)*80);

                card.innerHTML=`
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="inline-block bg-white/10 text-xs font-bold px-2.5 py-1 rounded-md mb-2 tracking-wide">EMP-${item.employee_id}</span>
                            <h3 class="font-heading text-lg font-bold flex items-center gap-2 ${t.color}">
                                <i class="fa-solid ${t.icon}"></i> ${t.label}
                            </h3>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider ${s.bg} ${s.text} border ${s.border}">
                            <i class="fa-solid ${s.icon} mr-1"></i>${s.label}
                        </span>
                    </div>
                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex"><span class="w-20 text-slate-500 font-medium">Ngày gửi</span><span class="text-slate-200 font-semibold">${fmtDate(item.created_at)}</span></div>
                        <div class="flex"><span class="w-20 text-slate-500 font-medium">Nghỉ từ</span><span class="text-slate-200 font-semibold">${fmtDate(item.start_date)} → ${fmtDate(item.end_date)}</span></div>
                        <div class="flex"><span class="w-20 text-slate-500 font-medium">Số ngày</span><span class="text-rose-300 font-bold">${item.days} ngày</span></div>
                    </div>
                    <div class="bg-black/25 rounded-xl p-3 text-[13px] text-slate-300 leading-relaxed border border-white/5 max-h-20 overflow-y-auto mb-4">
                        <strong class="text-slate-400">Lý do:</strong> ${item.reason||'Không rõ'}
                    </div>
                    ${actions}
                `;
                grid.appendChild(card);
            });
            AOS.refresh();
        }

        // ── FETCH DATA ──
        async function loadData(){
            try{
                const res = await fetch('leave_controller.php',{
                    method:'POST', headers:{'Content-Type':'application/json'},
                    body: JSON.stringify({action:'list', filter:'all'})
                });
                const json = await res.json();
                if(json.success){ allData=json.data; render('all'); }
                else { document.getElementById('cards-grid').innerHTML=`<div class="col-span-full text-center py-16 text-red-400">${json.message}</div>`; }
            }catch(e){
                document.getElementById('cards-grid').innerHTML=`<div class="col-span-full text-center py-16 text-red-400"><i class="fa-solid fa-wifi mr-2"></i>Lỗi kết nối</div>`;
            }
        }

        // ── APPROVE / REJECT ──
        window.processLeave = async function(id, action, btn){
            const card = btn.closest('.glass');
            card.querySelectorAll('button').forEach(b=>{ b.disabled=true; b.classList.add('opacity-50','cursor-not-allowed'); });
            btn.innerHTML=`<i class="fa-solid fa-spinner fa-spin mr-1"></i> Đang xử lý...`;

            try{
                const res = await fetch('leave_controller.php',{
                    method:'POST', headers:{'Content-Type':'application/json'},
                    body: JSON.stringify({ action: action, leave_id: id })
                });
                const json = await res.json();
                if(json.success){
                    toast(json.message, true);
                    const target = allData.find(x=>x.id==id);
                    if(target) target.status = action==='approve'?'approved':'rejected';
                    const activeFilter = document.querySelector('.filter-pill.active').dataset.filter;
                    render(activeFilter);
                } else { toast(json.message, false); }
            }catch(e){ toast('Lỗi kết nối máy chủ!', false); }
        };

        // ── FILTER CLICK ──
        document.querySelectorAll('.filter-pill').forEach(btn=>{
            btn.addEventListener('click', function(){
                document.querySelectorAll('.filter-pill').forEach(b=>b.classList.remove('active'));
                this.classList.add('active');
                render(this.dataset.filter);
            });
        });

        document.addEventListener('DOMContentLoaded', loadData);
    </script>
</body>
</html>
