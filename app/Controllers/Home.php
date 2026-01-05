<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clothify API | Documentation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #FFF5F7; }
        h1 { font-family: 'Playfair Display', serif; }
        .bg-gradient-soft { background: linear-gradient(135deg, #FFF5F7 0%, #FDF2FF 100%); }
        .custom-scroll::-webkit-scrollbar { width: 5px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #FCE7F3; border-radius: 10px; }
    </style>
</head>
<body class="bg-gradient-soft min-h-screen flex items-center justify-center">
    <main class="max-w-[1400px] w-full grid md:grid-cols-[1fr_1.2fr] gap-6 items-center px-6 md:px-10 py-8">
        
        <div class="space-y-8">
            <div class="space-y-4">
                <span class="px-4 py-1.5 rounded-full bg-white/60 text-pink-400 text-[10px] font-bold tracking-[0.2em] uppercase shadow-sm border border-pink-50">Fashion Catalog System</span>
                <h1 class="text-8xl md:text-10xl font-bold text-[#2D2D5F] leading-[1.1]">
                    Welcome to <br> 
                    <span class="text-purple-400 italic font-medium">Clothify API</span>
                </h1>
                <p class="text-xl text-gray-500 max-w-lg leading-relaxed">
                    Powering seamless fashion discoveries
                </p>
            </div>

            <a href="https://github.com/kenlyn270/Fashion_Catalog_UAS_TST" target="_blank" 
               class="group block bg-white/80 backdrop-blur p-6 rounded-[30px] shadow-lg border border-white hover:scale-[1.01] transition duration-300 max-w-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-bold text-[#2D2D5F]">Documentation</p>
                        <p class="text-[10px] text-gray-400 mt-0.5 uppercase font-bold tracking-widest">Kunjungi GitHub untuk info detail</p>
                    </div>
                    <div class="bg-pink-50 text-pink-500 p-3 rounded-2xl group-hover:bg-pink-500 group-hover:text-white transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.041-1.416-4.041-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="bg-white/90 backdrop-blur-xl rounded-[45px] p-10 shadow-2xl border border-white h-[80vh] overflow-y-auto custom-scroll space-y-8">
            <h3 class="text-2xl font-bold text-[#2D2D5F] flex items-center gap-2.5">
                <span class="text-3xl">🚀</span> API Reference
            </h3>

            <div class="space-y-4">
                <p class="text-[11px] font-bold text-pink-400 uppercase tracking-[0.25em]">Core Resources</p>
                <div class="grid gap-3">
                    <div class="bg-pink-50/40 p-5 rounded-[22px] border border-pink-100 hover:bg-pink-50 transition">
                        <p class="text-base font-bold text-[#2D2D5F]">GET /products</p>
                        <p class="text-xs text-gray-500 mt-1">Mengambil semua daftar produk Clothify (dengan pagination).</p>
                    </div>
                    <div class="bg-pink-50/40 p-5 rounded-[22px] border border-pink-100 hover:bg-pink-50 transition">
                        <p class="text-base font-bold text-[#2D2D5F]">GET /products/{id}</p>
                        <p class="text-xs text-gray-500 mt-1">Mengambil informasi detail produk berdasarkan ID.</p>
                    </div>
                    <div class="bg-pink-50/40 p-5 rounded-[22px] border border-pink-100 hover:bg-pink-50 transition">
                        <p class="text-base font-bold text-[#2D2D5F]">GET /products/search</p>
                        <p class="text-xs text-gray-500 mt-1">Pencarian produk berdasarkan nama, kategori, atau tags.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-[11px] font-bold text-blue-400 uppercase tracking-[0.25em]">Metadata</p>
                <div class="grid gap-3">
                    <div class="bg-blue-50/40 p-5 rounded-[22px] border border-blue-100 hover:bg-blue-50 transition">
                        <p class="text-base font-bold text-[#2D2D5F]">GET /products/categories</p>
                        <p class="text-xs text-gray-500 mt-1">Menampilkan semua kategori produk yang tersedia.</p>
                    </div>
                    <div class="bg-blue-50/40 p-5 rounded-[22px] border border-blue-100 hover:bg-blue-50 transition">
                        <p class="text-base font-bold text-[#2D2D5F]">GET /products/tags</p>
                        <p class="text-xs text-gray-500 mt-1">Menampilkan semua daftar tags produk yang unik.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <p class="text-[11px] font-bold text-purple-400 uppercase tracking-[0.25em]">Smart Recommendation</p>
                <div class="grid gap-3">
                    <div class="bg-purple-50/40 p-5 rounded-[22px] border border-purple-100 hover:bg-purple-50 transition">
                        <p class="text-base font-bold text-[#2D2D5F]">POST /products/recommend</p>
                        <p class="text-xs text-purple-600 font-medium italic mt-1">✨ Integrasi Gemini AI: Analisis body type & tips gaya.</p>
                    </div>
                    <div class="bg-purple-50/40 p-5 rounded-[22px] border border-purple-100 hover:bg-purple-50 transition">
                        <p class="text-base font-bold text-[#2D2D5F]">GET /products/recommendations</p>
                        <p class="text-xs text-gray-500 mt-1">Rekomendasi produk berdasarkan tag body type & style.</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4 pt-2">
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.25em]">Admin Restricted</p>
                <div class="bg-gray-100/60 p-5 rounded-[22px] border border-gray-200">
                    <p class="text-base font-bold text-gray-400 flex justify-between items-center">
                        POST /products <span class="text-[10px] bg-white px-2.5 py-1 rounded-full shadow-sm">🔒 Admin Only</span>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Menambahkan item baru ke katalog (Butuh X-API-KEY).</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>