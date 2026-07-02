<!DOCTYPE html>
<html lang="vi" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Xin Nghỉ Phép | HRMS Enterprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{heading:['Montserrat','sans-serif'],body:['Roboto','sans-serif']}}}}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <style>
        body{font-family:'Roboto',sans-serif;margin:0;overflow-x:hidden}

        /* ── PARALLAX BG ── */
        .parallax-bg{
            position:fixed;inset:0;z-index:0;
            background:url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1920&q=80') center/cover no-repeat;
            background-attachment:fixed;
        }
        .parallax-overlay{position:fixed;inset:0;z-index:1;background:rgba(0,0,0,.55)}

        /* ── FLOATING CLOUDS ── */
        .cloud{position:fixed;z-index:2;pointer-events:none;opacity:.08;filter:blur(2px)}
        .cloud-1{top:8%;width:400px;height:120px;background:radial-gradient(ellipse,#fff 0%,transparent 70%);animation:drift 35s linear infinite}
        .cloud-2{top:22%;width:550px;height:150px;background:radial-gradient(ellipse,#fff 0%,transparent 70%);animation:drift 50s linear infinite;animation-delay:-12s}
        .cloud-3{top:55%;width:350px;height:100px;background:radial-gradient(ellipse,#fff 0%,transparent 70%);animation:drift 42s linear infinite;animation-delay:-25s}
        @keyframes drift{0%{transform:translateX(-500px)}100%{transform:translateX(110vw)}}

        /* ── GLASS ── */
        .glass-card{
            background:rgba(255,255,255,.08);
            backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
            border:1px solid rgba(255,255,255,.12);
            border-radius:1rem;
            position:relative;overflow:hidden;
            transition:all .4s cubic-bezier(.4,0,.2,1);
        }
        .glass-card::before{
            content:'';position:absolute;inset:-2px;z-index:-1;border-radius:1rem;
            background:linear-gradient(135deg,rgba(99,102,241,.3),rgba(236,72,153,.3),rgba(34,211,238,.3));
            opacity:0;transition:opacity .4s;
        }
        .glass-card:hover::before{opacity:1}
        .glass-card:hover{transform:translateY(-4px);box-shadow:0 20px 50px rgba(0,0,0,.4)}

        /* ── INPUT ── */
        .form-input{
            width:100%;padding:14px 18px;
            background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.1);
            border-radius:.75rem;color:#fff;font-size:15px;font-family:'Roboto',sans-serif;
            transition:all .3s;
        }
        .form-input:focus{outline:none;border-color:#818cf8;box-shadow:0 0 0 3px rgba(129,140,248,.2);background:rgba(0,0,0,.5)}
        .form-input::placeholder{color:rgba(255,255,255,.35)}
        select.form-input option{background:#1e293b;color:#fff}

        /* ── PULSE RING BUTTON ── */
        .btn-pulse{position:relative;overflow:visible}
        .btn-pulse .ring{
            position:absolute;inset:-4px;border-radius:inherit;
            border:2px solid rgba(99,102,241,.5);
            animation:pulseRing 2s ease-out infinite;
        }
        @keyframes pulseRing{0%{transform:scale(1);opacity:.7}100%{transform:scale(1.15);opacity:0}}

        /* ── ALERT ── */
        .alert-slide{animation:slideDown .5s cubic-bezier(.34,1.56,.64,1)}
        @keyframes slideDown{from{opacity:0;transform:translateY(-15px) scale(.96)}to{opacity:1;transform:translateY(0) scale(1)}}
    </style>
</head>
<body class="text-white min-h-screen">
    <div class="parallax-bg"></div>
    <div class="parallax-overlay"></div>
    <div class="cloud cloud-1"></div>
    <div class="cloud cloud-2"></div>
    <div class="cloud cloud-3"></div>

    <main class="relative z-10 flex items-center justify-center min-h-screen px-4 py-10">
        <div class="w-full max-w-xl" data-aos="fade-up" data-aos-duration="900">
            <div class="glass-card p-8 md:p-10">
                <!-- Header -->
                <div class="text-center mb-8" data-aos="zoom-in" data-aos-delay="200">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center text-2xl shadow-lg shadow-indigo-500/30 rotate-[-8deg]">
                        <i class="fa-solid fa-paper-plane"></i>
                    </div>
                    <h1 class="font-heading text-3xl md:text-4xl font-extrabold bg-gradient-to-r from-indigo-300 via-pink-200 to-cyan-200 bg-clip-text text-transparent">
                        Đơn Xin Nghỉ Phép
                    </h1>
                    <p class="text-slate-400 text-sm mt-2">Hệ thống phê duyệt tự động HRMS</p>
                </div>

                <!-- Alert -->
                <div id="alert-box" class="hidden mb-6 px-4 py-3 rounded-xl text-sm font-medium alert-slide"></div>

                <!-- Form -->
                <form id="leave-form" class="space-y-5">
                    <div data-aos="fade-right" data-aos-delay="300">
                        <label class="block text-xs font-semibold text-slate-300 mb-2 tracking-wider uppercase">Loại nghỉ phép</label>
                        <select id="leave_type" class="form-input" required>
                            <option value="">-- Chọn loại nghỉ --</option>
                            <option value="annual">🌴 Nghỉ phép năm (Có lương)</option>
                            <option value="sick">🏥 Nghỉ ốm (Cần giấy tờ y tế)</option>
                            <option value="unpaid">💼 Nghỉ không lương</option>
                            <option value="maternity">👶 Nghỉ thai sản</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4" data-aos="fade-right" data-aos-delay="400">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-2 tracking-wider uppercase">Từ ngày</label>
                            <input type="date" id="start_date" class="form-input" required>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-2 tracking-wider uppercase">Đến ngày</label>
                            <input type="date" id="end_date" class="form-input" required>
                        </div>
                    </div>

                    <div data-aos="fade-right" data-aos-delay="500">
                        <label class="block text-xs font-semibold text-slate-300 mb-2 tracking-wider uppercase">Lý do nghỉ</label>
                        <textarea id="reason" class="form-input" rows="3" placeholder="Mô tả chi tiết lý do xin nghỉ..." required></textarea>
                    </div>

                    <div data-aos="fade-up" data-aos-delay="600">
                        <button type="submit" id="btn-submit"
                            class="btn-pulse w-full py-4 rounded-xl font-heading font-bold text-lg
                                   bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600
                                   hover:from-indigo-500 hover:via-purple-500 hover:to-pink-500
                                   shadow-xl shadow-purple-600/25 hover:shadow-purple-500/40
                                   transition-all duration-300 hover:-translate-y-1 flex items-center justify-center gap-3 cursor-pointer">
                            <span class="ring rounded-xl"></span>
                            <i class="fa-solid fa-paper-plane"></i>
                            <span id="btn-text">Gửi Yêu Cầu</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center" data-aos="fade-up" data-aos-delay="700">
                    <a href="leave-list.php" class="text-indigo-300 hover:text-white text-sm font-medium transition-colors">
                        <i class="fa-solid fa-list-check mr-1"></i> Xem danh sách đơn đã gửi
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({once:true,easing:'ease-out-quart'});

        const form=document.getElementById('leave-form'),btn=document.getElementById('btn-submit'),
              btnText=document.getElementById('btn-text'),alertBox=document.getElementById('alert-box');

        function showAlert(type,msg){
            alertBox.className=`mb-6 px-4 py-3 rounded-xl text-sm font-medium alert-slide flex items-center gap-2 ${type==='success'?'bg-emerald-500/20 border border-emerald-500/30 text-emerald-200':'bg-red-500/20 border border-red-500/30 text-red-200'}`;
            alertBox.innerHTML=`<i class="fa-solid ${type==='success'?'fa-circle-check':'fa-triangle-exclamation'}"></i> ${msg}`;
            alertBox.classList.remove('hidden');
        }

        form.addEventListener('submit',async e=>{
            e.preventDefault();
            const s=document.getElementById('start_date').value, en=document.getElementById('end_date').value;
            if(new Date(en)<new Date(s)){showAlert('error','Ngày kết thúc không thể trước ngày bắt đầu!');return;}

            btn.disabled=true; btnText.textContent='Đang xử lý...'; alertBox.classList.add('hidden');

            try{
                const res=await fetch('leave_controller.php',{
                    method:'POST',headers:{'Content-Type':'application/json'},
                    body:JSON.stringify({action:'create',leave_type:document.getElementById('leave_type').value,start_date:s,end_date:en,reason:document.getElementById('reason').value})
                });
                const json=await res.json();
                showAlert(json.success?'success':'error',json.message);
                if(json.success) form.reset();
            }catch(err){showAlert('error','Mất kết nối đến máy chủ!');}

            btn.disabled=false; btnText.textContent='Gửi Yêu Cầu';
        });
    </script>
</body>
</html>
