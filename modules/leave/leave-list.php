<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch Sử Nghỉ Phép | HRMS Enterprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{heading:['Montserrat','sans-serif'],body:['Roboto','sans-serif']}}}}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        body{font-family:'Roboto',sans-serif;margin:0;overflow-x:hidden}

        .parallax-bg{
            position:fixed;inset:0;z-index:0;
            background:url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?w=1920&q=80') center/cover no-repeat;
            background-attachment:fixed;
        }
        .parallax-overlay{position:fixed;inset:0;z-index:1;background:rgba(0,0,0,.55)}

        .cloud{position:fixed;z-index:2;pointer-events:none;opacity:.07;filter:blur(2px)}
        .cloud-1{top:10%;width:420px;height:130px;background:radial-gradient(ellipse,#fff 0%,transparent 70%);animation:drift 38s linear infinite}
        .cloud-2{top:35%;width:500px;height:140px;background:radial-gradient(ellipse,#fff 0%,transparent 70%);animation:drift 52s linear infinite;animation-delay:-15s}
        .cloud-3{top:60%;width:380px;height:110px;background:radial-gradient(ellipse,#fff 0%,transparent 70%);animation:drift 45s linear infinite;animation-delay:-28s}
        @keyframes drift{0%{transform:translateX(-500px)}100%{transform:translateX(110vw)}}

        .glass-card{
            background:rgba(255,255,255,.08);
            backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
            border:1px solid rgba(255,255,255,.1);
            border-radius:1rem;position:relative;overflow:hidden;
            transition:all .4s cubic-bezier(.4,0,.2,1);
        }
        .glass-card::before{
            content:'';position:absolute;inset:-2px;z-index:-1;border-radius:1rem;
            background:linear-gradient(135deg,rgba(34,211,238,.3),rgba(99,102,241,.3),rgba(236,72,153,.3));
            opacity:0;transition:opacity .4s;
        }
        .glass-card:hover::before{opacity:1}
        .glass-card:hover{transform:scale(1.03);box-shadow:0 16px 45px rgba(0,0,0,.4)}

        .badge-pill{padding:5px 12px;border-radius:99px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px}
    </style>
</head>
<body class="text-white min-h-screen">
    <div class="parallax-bg"></div>
    <div class="parallax-overlay"></div>
    <div class="cloud cloud-1"></div>
    <div class="cloud cloud-2"></div>
    <div class="cloud cloud-3"></div>

    <main class="relative z-10 max-w-[960px] mx-auto px-5 py-10">
        <!-- Header -->
        <header class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8" data-aos="fade-down" data-aos-duration="700">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-cyan-400/80 mb-1"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Leave History</p>
                <h1 class="font-heading text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-cyan-200 via-indigo-200 to-pink-200 bg-clip-text text-transparent">
                    Lịch Sử Nghỉ Phép
                </h1>
                <p class="text-slate-400 text-sm mt-1">Danh sách các đơn bạn đã nộp</p>
            </div>
            <a href="leave-request.php" data-aos="fade-left" data-aos-delay="200"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold
                      bg-gradient-to-r from-indigo-600 to-pink-600 hover:from-indigo-500 hover:to-pink-500
                      shadow-lg shadow-indigo-600/25 hover:shadow-indigo-500/35
                      transition-all duration-300 hover:-translate-y-1 cursor-pointer">
                <i class="fa-solid fa-plus"></i> Tạo đơn mới
            </a>
        </header>

        <!-- Cards Grid -->
        <div id="cards-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6 min-h-[200px]">
            <div class="col-span-full flex flex-col items-center py-20 text-cyan-300/60">
                <i class="fa-solid fa-spinner fa-spin text-4xl mb-4"></i>
                <p class="text-lg font-medium">Đang tải dữ liệu...</p>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({once:true,easing:'ease-out-quart'});

        const TYPE_MAP={
            annual:{label:'Phép năm',icon:'fa-calendar-check',color:'text-sky-400'},
            sick:{label:'Nghỉ ốm',icon:'fa-briefcase-medical',color:'text-amber-400'},
            unpaid:{label:'Không lương',icon:'fa-wallet',color:'text-slate-300'},
            maternity:{label:'Thai sản',icon:'fa-baby-carriage',color:'text-pink-400'}
        };
        const STATUS_MAP={
            pending:{label:'Chờ duyệt',bg:'bg-amber-500/15',text:'text-amber-300',border:'border-amber-500/30',icon:'fa-clock'},
            approved:{label:'Đã duyệt',bg:'bg-emerald-500/15',text:'text-emerald-300',border:'border-emerald-500/30',icon:'fa-circle-check'},
            rejected:{label:'Từ chối',bg:'bg-red-500/15',text:'text-red-300',border:'border-red-500/30',icon:'fa-circle-xmark'}
        };

        function fmtDate(s){return new Date(s).toLocaleDateString('vi-VN',{day:'2-digit',month:'2-digit',year:'numeric'});}

        async function loadLeaves(){
            const grid=document.getElementById('cards-grid');
            try{
                const res=await fetch('leave_controller.php',{
                    method:'POST',headers:{'Content-Type':'application/json'},
                    body:JSON.stringify({action:'list',filter:'mine'})
                });
                const json=await res.json();

                if(json.success){
                    if(!json.data.length){
                        grid.innerHTML=`
                        <div class="col-span-full flex flex-col items-center py-20 text-slate-500" data-aos="zoom-in">
                            <i class="fa-regular fa-folder-open text-5xl mb-4"></i>
                            <p class="text-lg font-medium mb-4">Bạn chưa gửi đơn nào</p>
                            <a href="leave-request.php" class="px-5 py-2 rounded-xl bg-indigo-600 text-sm font-bold hover:bg-indigo-500 transition">Tạo đơn đầu tiên</a>
                        </div>`;
                        return;
                    }

                    grid.innerHTML='';
                    json.data.forEach((item,i)=>{
                        const t=TYPE_MAP[item.leave_type]||{label:'Khác',icon:'fa-calendar',color:'text-slate-400'};
                        const s=STATUS_MAP[item.status];
                        const card=document.createElement('div');
                        card.className='glass-card p-6';
                        card.setAttribute('data-aos','fade-up');
                        card.setAttribute('data-aos-delay',(i%6)*100);
                        card.innerHTML=`
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="font-heading text-lg font-bold flex items-center gap-2 ${t.color}">
                                        <i class="fa-solid ${t.icon}"></i> ${t.label}
                                    </h3>
                                    <p class="text-xs text-slate-500 mt-1">Gửi ngày ${fmtDate(item.created_at)}</p>
                                </div>
                                <span class="badge-pill ${s.bg} ${s.text} border ${s.border}">
                                    <i class="fa-solid ${s.icon} mr-1"></i>${s.label}
                                </span>
                            </div>
                            <div class="space-y-2 text-sm mb-4">
                                <div class="flex"><span class="w-20 text-slate-500">Thời gian</span><span class="text-slate-200 font-semibold">${fmtDate(item.start_date)} → ${fmtDate(item.end_date)}</span></div>
                                <div class="flex"><span class="w-20 text-slate-500">Số ngày</span><span class="text-cyan-300 font-bold">${item.days} ngày</span></div>
                            </div>
                            <div class="bg-black/25 rounded-xl p-3 text-[13px] text-slate-300 leading-relaxed border border-white/5">
                                <strong class="text-slate-400">Lý do:</strong> ${item.reason||'Không rõ'}
                            </div>
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

        document.addEventListener('DOMContentLoaded',loadLeaves);
    </script>
</body>
</html>
