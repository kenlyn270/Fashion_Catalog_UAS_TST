const API_BASE_URL = "https://clothify.otwdochub.my.id";

// 1. Mock Data API Teman (Inspirasi OOTD)
// Nanti kalau API teman sudah jadi, ganti fetch ke URL dia
const getInspirationFromFriend = (category) => {
    return [
        { id: 1, img: "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400", title: "Street Style Look" },
        { id: 2, img: "https://images.unsplash.com/photo-1529139513055-07f9e279c5fb?w=400", title: "Minimalist Chic" }
    ];
};

// 2. Fungsi Load Catalog
// async function loadCatalog() {
//     const response = await fetch(`${API_BASE_URL}/products`);
//     const data = await response.json();
    
//     const container = document.getElementById('app');
//     container.innerHTML = `
//         <header class="text-center my-12">
//             <h2 class="text-4xl mb-2">Find Your Fashion Here</h2>
//             <p class="text-gray-400">Discover pieces that speak to your style.</p>
//         </header>
//         <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="product-list"></div>
//     `;

//     const list = document.getElementById('product-list');
//     data.products.forEach(prod => {
//         list.innerHTML += `
//             <div onclick="openDetail(${prod.id})" class="cursor-pointer group">
//                 <div class="overflow-hidden rounded-2xl bg-pastel-pink aspect-[3/4]">
//                     <img src="${prod.image_url}" class="w-full h-full object-cover group-hover:scale-105 transition">
//                 </div>
//                 <h3 class="mt-4 font-semibold text-lg">${prod.name}</h3>
//                 <p class="text-purple-400 font-medium">Rp ${prod.price}</p>
//             </div>
//         `;
//     });
// }

// 3. Fungsi Detail (Integrasi 2 API)
async function openDetail(id) {
    const res = await fetch(`${API_BASE_URL}/products/${id}`);
    const product = await res.json();
    
    // Ambil inspirasi dari 'API Teman'
    const inspirations = getInspirationFromFriend(product.category);

    const modal = document.getElementById('modal');
    const content = document.getElementById('modal-content');
    
    content.innerHTML = `
        <div class="flex-1">
            <img src="${product.image_url}" class="rounded-2xl w-full">
            <h2 class="text-3xl mt-4">${product.name}</h2>
            <p class="text-gray-600 mt-2">${product.description}</p>
        </div>
        <div class="flex-1 bg-pastel-purple/30 p-6 rounded-2xl">
            <h3 class="text-xl font-bold mb-4 italic text-purple-500">Style Inspiration</h3>
            <div class="grid grid-cols-2 gap-4">
                ${inspirations.map(ins => `
                    <div>
                        <img src="${ins.img}" class="rounded-lg shadow-sm">
                        <p class="text-xs mt-2 text-center">${ins.title}</p>
                    </div>
                `).join('')}
            </div>
            <button class="w-full bg-purple-400 text-white py-3 rounded-full mt-8 hover:bg-purple-500">Buy Now</button>
        </div>
    `;
    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

// Fungsi buka/tutup Modal
function toggleModal() {
    console.log("Tombol diklik!");
    const modal = document.getElementById('auth-modal');
    
    if (modal) {
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } else {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    } else {
        console.error("Modal dengan ID auth-modal nggak ketemu, Ken!");
    }
}

function switchTab(type) {
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    const tabLogin = document.getElementById('tab-login');
    const tabRegister = document.getElementById('tab-register');

    if (type === 'login') {
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        tabLogin.className = "text-pink-400 font-bold border-b-2 border-pink-400 pb-2 transition cursor-pointer";
        tabRegister.className = "text-gray-400 font-bold border-b-2 border-transparent pb-2 transition cursor-pointer";
    } else {
        loginForm.classList.add('hidden');
        registerForm.classList.remove('hidden');
        tabLogin.className = "text-gray-400 font-bold border-b-2 border-transparent pb-2 transition cursor-pointer";
        tabRegister.className = "text-pink-400 font-bold border-b-2 border-pink-400 pb-2 transition cursor-pointer";
    }
}

// Simulasi Auth (Nanti hubungin ke Controller CI kamu)
// Hubungkan script.js ke index.html di paling bawah sebelum </body>
// <script src="script.js"></script>

function handleAuth(type) {
    // Ambil input dari form
    const formId = type === 'login' ? 'login-form' : 'register-form';
    const email = document.querySelector(`#${formId} input[type="email"]`).value;
    const password = document.querySelector(`#${formId} input[type="password"]`).value;

    if (!email || !password) {
        alert("Isi dulu dong email sama passwordnyaaa~");
        return;
    }

    if (type === 'register') {
        // Proses Register ke Firebase
        auth.createUserWithEmailAndPassword(email, password)
            .then((userCredential) => {
                // alert("Hore! Akun Ken berhasil dibuat ✨");
                loginSuccess();
            })
            // .catch((error) => alert("Yah gagal regis: " + error.message));
    } else {
        // Proses Login ke Firebase
        auth.signInWithEmailAndPassword(email, password)
            .then((userCredential) => {
                // alert("Welcome back, Ken! ❤️");
                loginSuccess();
            })
            // .catch((error) => alert("Ups, login gagal: " + error.message));
    }
}

function loginSuccess() {
    // Sembunyikan Landing & Modal
    document.querySelector('main').classList.add('hidden');
    document.getElementById('auth-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Tampilkan Katalog
    document.getElementById('catalog-section').classList.remove('hidden');
    loadCatalog();
}

// Update fungsi loadCatalog agar tampilannya makin cantik & pastel
async function loadCatalog(category = '') {
    const listContainer = document.getElementById('product-list');
    if (!listContainer) return;

    // Tampilkan skeleton/loading
    listContainer.innerHTML = '<div class="col-span-full text-center text-gray-400 animate-pulse">Curating your aura...</div>';
    
    try {
        // Gunakan URL yang benar sesuai endpoint search kamu
        let url = `${API_BASE_URL}/products?limit=30`;
        if (category) {
            url = `${API_BASE_URL}/products/search?category=${category}`;
        }

        const response = await fetch(url);
        if (!response.ok) throw new Error('Network response was not ok');
        
        const data = await response.json();
        const products = data.products || []; // Pastikan ambil array products

        listContainer.innerHTML = ''; // Bersihkan loading

        if (products.length === 0) {
            listContainer.innerHTML = '<p class="col-span-full text-center text-gray-400">No products found in this category.</p>';
            return;
        }

        products.forEach(prod => {
            listContainer.innerHTML += `
                <div onclick="openDetail(${prod.id})" class="group cursor-pointer flex flex-col items-center">
                    <div class="relative w-full rounded-[40px] bg-white overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 aspect-[3/4]">
                        <img src="${prod.image_url}" 
                            alt="${prod.name}" 
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-700"
                            onerror="this.src='assets/placeholder.png'"> <div class="absolute bottom-4 left-4 right-4 p-4 bg-white/70 backdrop-blur-md rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <p class="text-[10px] uppercase font-bold text-pink-500">${prod.brand || 'Urbanwear'}</p>
                            <p class="text-sm font-bold text-[#2D2D5F]">Rp ${Number(prod.price).toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                    <div class="mt-4 text-center">
                        <h3 class="text-md font-bold text-[#2D2D5F]">${prod.name}</h3>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest">${prod.category}</p>
                    </div>
                </div>
            `;
        });
    } catch (error) {
        console.error("Error loading products:", error);
        listContainer.innerHTML = `
            <div class="col-span-full text-center py-20">
                <p class="text-red-400 font-medium">Ups! Gagal mengambil data dari API :(</p>
                <p class="text-xs text-gray-400 mt-2">Pastikan server API kamu sudah aktif dan mengizinkan CORS ya, Ken!</p>
                <button onclick="loadCatalog()" class="mt-4 text-pink-400 underline text-sm">Coba Lagi</button>
            </div>
        `;
    }
}

// Fungsi Filter
function filterCategory(cat) {
    loadCatalog(cat);
}

// Tambahkan fungsi logout biar lengkap
function logout() {
    auth.signOut().then(() => {
        location.reload(); // Refresh ke landing page
    });
}