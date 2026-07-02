<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Chấm Công | HRMS Enterprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{heading:['Montserrat','sans-serif'],body:['Roboto','sans-serif']}}}}</script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        body{font-family:'Roboto',sans-serif;margin:0;overflow-x:hidden}
        .parallax-bg{position:fixed;inset:0;z-index:0;background:url('https://images.unsplash.com/photo-1519681393784-d120267933ba?w=1920&q=80') center/cover no-repeat;background-attachment:fixed}
        .parallax-overlay{position:fixed;inset:0;z-index:1;background:rgba(15,23,42,.6)}
        .cloud{position:fixed;z-index:2;pointer-events:none;opacity:.06;filter:blur(2px)}
        .cloud-1{top:10%;width:420px;height:120px;background:radial-gradient(ellipse,#fff 0%,transparent 70%);animation:drift 38s linear infinite}
        .cloud-2{top:50%;width:500px;height:140px;background:radial-gradient(ellipse,#fff 0%,transparent 70%);animation:drift 50s linear infinite;animation-delay:-20s}
        @keyframes drift{0%{transform:translateX(-500px)}100%{transform:translateX(110vw)}}

        .glass-card{background:rgba(255,255,255,.08);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.1);border-radius:1rem;position:relative;overflow:hidden;transition:all .4s cubic-bezier(.4,0,.2,1)}
        .glass-card::before{content:'';position:absolute;inset:-2px;z-index:-1;border-radius:1rem;background:linear-gradient(135deg,rgba(52,211,153,.3),rgba(34,211,238,.3),rgba(99,102,241,.3));opacity:0;transition:opacity .4s}
        .glass-card:hover::before{opacity:1}
        .glass-card:hover{transform:scale(1.05);box-shadow:0 16px 45px rgba(0,0,0,.4)}

        .badge-pill{padding:4px 10px;border-radius:99px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px}
    </style>
</head>
<body class="text-white min-h-screen">
    <div class="parallax-bg"></div>
    <div class="parallax-overlay"></div>
    <div class="cloud cloud-1"></div>
    <div class="cloud cloud-2"></div>

    <main class="relative z-10 max-w-[960px] mx-auto px-5 py-10">
        <header class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8" data-aos="fade-down" data-aos-duration="700">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-emerald-400/80 mb-1"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Attendance History</p>
                <h1 class="font-heading text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-emerald-300 via-teal-200 to-cyan-200 bg-clip-text text-transparent">
                    Lịch Sử Chấm Công
                </h1>
                <p class="text-slate-400 text-sm mt-1">Toàn bộ dữ liệu check-in / check-out</p>
            </div>
            <div class="flex gap-3" data-aos="fade-left" data-aos-delay="200">
                <a href="check-in.php" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-white/10 border border-white/20 backdrop-blur-md hover:bg-white/20 transition-all hover:-translate-y-1 cursor-pointer">
                    <i class="fa-solid fa-right-to-bracket"></i> Check-in
                </a>
                <a href="check-out.php" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-bold bg-white/10 border border-white/20 backdrop-blur-md hover:bg-white/20 transition-all hover:-translate-y-1 cursor-pointer">
                    <i class="fa-solid fa-right-from-bracket"></i> Check-out
                </a>
            </div>
        </header>

        <div id="cards-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 min-h-[200px]">
            <div class="col-span-full flex flex-col items-center py-20 text-emerald-300/60">
                <i class="fa-solid fa-spinner fa-spin text-4xl mb-4"></i>
                <p class="text-lg font-medium">Đang tải dữ liệu...</p>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({once:true,easing:'ease-out-quart'});

        function fmtDate(s){return new Date(s).toLocaleDateString('vi-VN',{weekday:'short',day:'2-digit',month:'2-digit',year:'numeric'});}

        async function loadAttendance(){
            const grid=document.getElementById('cards-grid');
            try{
                const res=await fetch('attendance_controller.php',{
                    method:'POST',headers:{'Content-Type':'application/json'},
                    body:JSON.stringify({action:'list',filter:'all'})
                });
                const json=await res.json();

                if(json.success){
                    if(!json.data.length){
                        grid.innerHTML=`<div class="col-span-full flex flex-col items-center py-20 text-slate-500" data-aos="zoom-in"><i class="fa-regular fa-folder-open text-5xl mb-4"></i><p class="text-lg font-medium">Chưa có dữ liệu chấm công</p></div>`;
                        return;
                    }

                    grid.innerHTML='';
                    json.data.forEach((item,i)=>{
                        const isPres = item.status==='present';
                        const card=document.createElement('div');
                        card.className='glass-card p-5';
                        card.setAttribute('data-aos','fade-up');
                        card.setAttribute('data-aos-delay',(i%9)*60);

                        card.innerHTML=`
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-heading text-base font-bold text-white">${fmtDate(item.date)}</h3>
                                <span class="badge-pill ${isPres?'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30':'bg-amber-500/15 text-amber-300 border border-amber-500/30'}">
                                    <i class="fa-solid ${isPres?'fa-check-circle':'fa-clock'} mr-1"></i>${isPres?'Đúng giờ':'Đi trễ'}
                                </span>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-500/15 flex items-center justify-center text-emerald-400"><i class="fa-solid fa-arrow-right-to-bracket"></i></div>
                                    <div><p class="text-slate-500 text-xs">Giờ vào</p><p class="text-white font-bold">${item.check_in}</p></div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-orange-500/15 flex items-center justify-center text-orange-400"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
                                    <div><p class="text-slate-500 text-xs">Giờ ra</p><p class="text-white font-bold">${item.check_out||'<span class=\'text-amber-400 text-xs\'>Chưa check-out</span>'}</p></div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-cyan-500/15 flex items-center justify-center text-cyan-400"><i class="fa-solid fa-hourglass-half"></i></div>
                                    <div><p class="text-slate-500 text-xs">Tổng giờ</p><p class="text-cyan-300 font-bold">${item.working_hours||'-'}</p></div>
                                </div>
                            </div>
                            ${item.employee_id?`<div class="mt-3 pt-3 border-t border-white/5 text-xs text-slate-500"><i class="fa-solid fa-user mr-1"></i>EMP-${item.employee_id}</div>`:''}
                        `;
                        grid.appendChild(card);
                    });
                    AOS.refresh();
                }else{
                    grid.innerHTML=`<div class="col-span-full text-center py-16 text-red-400">${json.message}</div>`;
                }
            }catch(e){
                grid.innerHTML=`<div class="col-span-full text-center py-16 text-red-400"><i class="fa-solid fa-wifi mr-2"></i>Lỗi kết nối máy chủ</div>`;
            }
        }

        document.addEventListener('DOMContentLoaded',loadAttendance);
    </script>
</body>
</html>
