<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in | HRMS Enterprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{heading:['Montserrat','sans-serif'],body:['Roboto','sans-serif']}}}}</script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        body{font-family:'Roboto',sans-serif;margin:0;overflow-x:hidden}
        .parallax-bg{position:fixed;inset:0;z-index:0;background:url('https://images.unsplash.com/photo-1441974231531-c6227db76b6e?w=1920&q=80') center/cover no-repeat;background-attachment:fixed}
        .parallax-overlay{position:fixed;inset:0;z-index:1;background:rgba(15,23,42,.6)}
        .cloud{position:fixed;z-index:2;pointer-events:none;opacity:.07;filter:blur(2px)}
        .cloud-1{top:8%;width:400px;height:120px;background:radial-gradient(ellipse,#fff 0%,transparent 70%);animation:drift 36s linear infinite}
        .cloud-2{top:30%;width:520px;height:140px;background:radial-gradient(ellipse,#fff 0%,transparent 70%);animation:drift 48s linear infinite;animation-delay:-14s}
        @keyframes drift{0%{transform:translateX(-500px)}100%{transform:translateX(110vw)}}

        .glass{background:rgba(255,255,255,.1);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,.2);box-shadow:0 25px 50px rgba(0,0,0,.3);border-radius:1.5rem;transition:all .4s}
        .glass:hover{border-color:rgba(52,211,153,.4);box-shadow:0 30px 60px rgba(0,0,0,.4),0 0 80px -20px rgba(52,211,153,.15)}

        /* Digital Clock */
        .clock{font-family:'Montserrat',sans-serif;font-size:4.5rem;font-weight:900;letter-spacing:.08em;
            background:linear-gradient(to right,#6ee7b7,#34d399,#a7f3d0);-webkit-background-clip:text;-webkit-text-fill-color:transparent;
            text-shadow:none;filter:drop-shadow(0 0 20px rgba(52,211,153,.3))}
        .clock-blink{animation:blink 1s step-end infinite}
        @keyframes blink{50%{opacity:0}}
        .clock-date{color:#94a3b8;font-size:.875rem;font-weight:500;letter-spacing:.1em}

        /* Glow Button */
        .btn-glow{position:relative;overflow:visible}
        .btn-glow::after{content:'';position:absolute;inset:-3px;border-radius:inherit;background:linear-gradient(135deg,#10b981,#34d399,#6ee7b7);opacity:0;z-index:-1;filter:blur(12px);transition:opacity .4s}
        .btn-glow:hover::after{opacity:.6}

        .alert-anim{animation:popUp .5s cubic-bezier(.34,1.56,.64,1)}
        @keyframes popUp{from{opacity:0;transform:translateY(-10px) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)}}
    </style>
</head>
<body class="text-white min-h-screen">
    <div class="parallax-bg"></div>
    <div class="parallax-overlay"></div>
    <div class="cloud cloud-1"></div>
    <div class="cloud cloud-2"></div>

    <main class="relative z-10 flex items-center justify-center min-h-screen px-4 py-10">
        <div class="w-full max-w-md" data-aos="fade-up" data-aos-duration="900">
            <div class="glass p-10 text-center">
                <!-- Icon -->
                <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-2xl shadow-lg shadow-emerald-500/30 rotate-[-6deg]" data-aos="zoom-in" data-aos-delay="200">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </div>

                <h1 class="font-heading text-3xl md:text-4xl font-extrabold mb-1 bg-gradient-to-r from-emerald-300 via-teal-200 to-cyan-200 bg-clip-text text-transparent" data-aos="zoom-in" data-aos-delay="300">
                    Chấm Công Vào
                </h1>
                <p class="text-slate-400 text-sm mb-8">Ghi nhận giờ bắt đầu làm việc</p>

                <!-- Digital Clock -->
                <div class="mb-8" data-aos="fade-up" data-aos-delay="400">
                    <div class="clock" id="live-clock">--:--:--</div>
                    <div class="clock-date mt-2" id="live-date"></div>
                </div>

                <!-- Alert -->
                <div id="alert-box" class="hidden mb-6 px-4 py-3 rounded-xl text-sm font-medium text-left alert-anim"></div>

                <!-- Check-in Button -->
                <form id="checkin-form" data-aos="fade-up" data-aos-delay="500">
                    <button type="submit" id="btn-checkin"
                        class="btn-glow w-full py-4 rounded-2xl font-heading font-bold text-lg
                               bg-gradient-to-r from-emerald-600 to-teal-500
                               hover:from-emerald-500 hover:to-teal-400
                               shadow-xl shadow-emerald-600/25 hover:shadow-emerald-500/40
                               transition-all duration-300 hover:-translate-y-1 cursor-pointer
                               flex items-center justify-center gap-3">
                        <i class="fa-solid fa-fingerprint text-xl"></i>
                        <span id="btn-text">Check In Now</span>
                    </button>
                </form>

                <div class="mt-6 flex justify-center gap-4 text-sm" data-aos="fade-up" data-aos-delay="600">
                    <a href="check-out.php" class="text-emerald-300 hover:text-white transition"><i class="fa-solid fa-right-from-bracket mr-1"></i>Check-out</a>
                    <span class="text-slate-600">|</span>
                    <a href="attendance-list.php" class="text-emerald-300 hover:text-white transition"><i class="fa-solid fa-clock-rotate-left mr-1"></i>Lịch sử</a>
                </div>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({once:true,easing:'ease-out-quart'});

        // ── LIVE CLOCK ──
        function tick(){
            const now=new Date();
            const h=String(now.getHours()).padStart(2,'0'), m=String(now.getMinutes()).padStart(2,'0'), s=String(now.getSeconds()).padStart(2,'0');
            document.getElementById('live-clock').innerHTML=`${h}<span class="clock-blink">:</span>${m}<span class="clock-blink">:</span>${s}`;
            document.getElementById('live-date').textContent=now.toLocaleDateString('vi-VN',{weekday:'long',day:'2-digit',month:'long',year:'numeric'});
        }
        tick(); setInterval(tick,1000);

        // ── CHECK-IN FETCH ──
        const form=document.getElementById('checkin-form'), btn=document.getElementById('btn-checkin'),
              btnText=document.getElementById('btn-text'), alertBox=document.getElementById('alert-box');

        function showAlert(type,msg){
            alertBox.className=`mb-6 px-4 py-3 rounded-xl text-sm font-medium text-left alert-anim flex items-center gap-2 ${type==='success'?'bg-emerald-500/20 border border-emerald-500/30 text-emerald-200':'bg-red-500/20 border border-red-500/30 text-red-200'}`;
            alertBox.innerHTML=`<i class="fa-solid ${type==='success'?'fa-circle-check':'fa-triangle-exclamation'}"></i> ${msg}`;
            alertBox.classList.remove('hidden');
        }

        form.addEventListener('submit',async e=>{
            e.preventDefault();
            btn.disabled=true; btnText.textContent='Đang xử lý...'; alertBox.classList.add('hidden');
            try{
                const res=await fetch('attendance_controller.php?action=checkIn',{method:'POST'});
                const json=await res.json();
                showAlert(json.success?'success':'error',json.message);
            }catch(err){showAlert('error','Lỗi kết nối máy chủ');}
            btnText.textContent='Check In Now'; btn.disabled=false;
        });
    </script>
</body>
</html>