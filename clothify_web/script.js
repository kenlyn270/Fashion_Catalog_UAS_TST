const API_BASE_URL = "https://clothify.otwdochub.my.id";

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

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

function handleAuth(type) {
    const formId = type === 'login' ? 'login-form' : 'register-form';
    const email = document.querySelector(`#${formId} input[type="email"]`).value;
    const password = document.querySelector(`#${formId} input[type="password"]`).value;

    if (!email || !password) {
        alert("Isi dulu dong email sama passwordnyaaa~");
        return;
    }

    if (type === 'register') {
        auth.createUserWithEmailAndPassword(email, password)
            .then((userCredential) => {
                loginSuccess();
            })
            .catch((error) => {
                if (error.code === 'auth/email-already-in-use') {
                    alert("❌ Email ini sudah terdaftar");
                } else if (error.code === 'auth/weak-password') {
                    alert("⚠️ Password minimal 6 karakter ya");
                } else {
                    alert("❌ Gagal register: " + error.message);
                }
            });
    } else {
        auth.signInWithEmailAndPassword(email, password)
            .then((userCredential) => {
                loginSuccess();
            })
            .catch((error) => {
                if (error.code === 'auth/user-not-found') {
                    alert("❌ Email belum terdaftar");
                } else if (error.code === 'auth/wrong-password') {
                    alert("❌ Password salah");
                } else {
                    alert("❌ Login gagal: " + error.message);
                }
            });
    }
}

function loginSuccess() {
    document.querySelector('main').classList.add('hidden');
    document.getElementById('auth-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('catalog-section').classList.remove('hidden');
    loadCatalog();
}

async function loadCatalog(category = '') {
    const listContainer = document.getElementById('product-list');
    if (!listContainer) return;

    listContainer.innerHTML = '<div class="col-span-full text-center text-gray-400 animate-pulse">Curating your aura...</div>';
    
    try {
        let url = `${API_BASE_URL}/products?limit=100`; 
        if (category) url = `${API_BASE_URL}/products/search?category=${category}`;

        const response = await fetch(url);
        const data = await response.json();
        const products = data.products || [];

        listContainer.innerHTML = ''; 

        products.forEach(prod => {
            listContainer.innerHTML += `
                <div onclick="openDetail(${prod.id})" class="group bg-white p-2 rounded-[24px] border border-pink-50 hover:shadow-md transition-all duration-500 cursor-pointer flex flex-col">
                    <div class="relative overflow-hidden rounded-[18px] aspect-[4/5] bg-gray-50">
                        <img src="${prod.image_url}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    </div>
                    
                    <div class="mt-2 px-1">
                        <p class="text-[8px] font-bold text-pink-400 uppercase tracking-tighter">${prod.brand || 'Clothify'}</p>
                        <h3 class="font-bold text-[#2D2D5F] text-[10px] mt-0.5 leading-tight truncate">${prod.name}</h3>
                        <p class="text-pink-600 font-black text-xs mt-1">Rp ${Number(prod.price).toLocaleString('id-ID')}</p>
                    </div>
                </div>
            `;
        });
    } catch (error) {
        console.error("Gagal load katalog Ken:", error);
    }
}

async function openDetail(id) {
    try {
        const res = await fetch(`${API_BASE_URL}/products/${id}`);
        const data = await res.json();
        const prod = data.product;

        document.getElementById('side-detail-placeholder').classList.add('hidden');
        const contentArea = document.getElementById('side-detail-content');
        contentArea.classList.remove('hidden');
        contentArea.innerHTML = '<p class="text-center py-10 text-pink-400 animate-pulse text-xs font-bold">Mencari inspirasi yang benar-benar pas... ✨</p>';

        const inspiraRes = await fetch(`https://inspira-container.otwdochub.my.id/api/looks`);
        const inspiraData = await inspiraRes.json();
        const allLooks = inspiraData.data || inspiraData;

        const myProductTags = prod.tags.split(',').map(t => t.trim().toLowerCase());

        const filteredLooks = [];
        const seenKeys = new Set();

        allLooks.forEach(look => {
        const hasMatch = look.item_details.some(item => {
            const inspiraTags = item.tags.map(t => t.toLowerCase());
            return myProductTags.every(tag => inspiraTags.includes(tag));
        });

        const key = (look.image_url || `id:${look.id}`).trim().toLowerCase();

        if (hasMatch && !seenKeys.has(key)) {
            filteredLooks.push(look);
            seenKeys.add(key);
        }
        });
        renderSidePanel(prod, filteredLooks);

    } catch (error) {
        console.error("Gagal nyocokin tags Ken:", error);
    }
}

function renderSidePanel(prod, filteredLooks) {
    const contentArea = document.getElementById('side-detail-content');
    
    contentArea.innerHTML = `
        <div class="animate-fadeIn">
            <img src="${prod.image_url}" class="w-full rounded-[30px] shadow-lg mb-6 aspect-[3/4] object-cover">
            
            <div class="flex justify-between items-start mb-2">
                <div>
                    <p class="text-[10px] font-bold text-pink-400 uppercase tracking-widest">${prod.brand}</p>
                    <h2 class="text-2xl font-bold text-[#2D2D5F]">${prod.name}</h2>
                </div>
                <span class="bg-pink-50 text-pink-500 text-[10px] px-3 py-1 rounded-full font-bold uppercase">${prod.category}</span>
            </div>
            
            <p class="text-xl font-black text-pink-600 mb-4">Rp ${Number(prod.price).toLocaleString('id-ID')}</p>
            <p class="text-[10px] text-gray-400 leading-relaxed mb-6 italic">${prod.tags}</p>

            <div class="pt-6 border-t border-pink-50">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-[#2D2D5F] italic" style="font-family: 'Playfair Display', serif;">Style Inspiration ✨</h4>
                    <span class="text-[9px] text-gray-400">${filteredLooks.length} looks found</span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    ${filteredLooks.length > 0 ? filteredLooks.map(look => `
                        <div class="bg-pink-50/50 p-2 rounded-2xl group/ins cursor-pointer" onclick="window.open('${look.image_url}', '_blank')">
                            <img src="${look.image_url}" class="rounded-xl aspect-square object-cover mb-2 group-hover/ins:opacity-80 transition">
                            <p class="text-[8px] text-center font-bold text-gray-400 uppercase truncate">${look.title || 'Inspiration'}</p>
                        </div>
                    `).join('') : '<p class="text-[10px] text-gray-400 col-span-2 text-center py-4">Mini belum nemu inspirasi yang bener-bener pas nih... 🌸</p>'}
                </div>
            </div>

            <a href="${prod.product_url}" target="_blank" class="block w-full text-center bg-gradient-to-r from-pink-400 to-purple-400 text-white py-4 rounded-2xl mt-8 font-bold shadow-lg hover:scale-[1.02] transition">
                Get This Look
            </a>
        </div>
    `;
}

async function analyzeAura() {
    const shoulder = document.getElementById('shoulder').value;
    const waist = document.getElementById('waist').value;
    const thigh = document.getElementById('thigh').value;
    const style = document.getElementById('style-pref').value;

    if (!shoulder || !waist) {
        alert("Isi minimal bahu dan pinggang dulu yaa ✨");
        return;
    }

    const responseArea = document.getElementById('ai-response-area');
    const tipsEl = document.getElementById('ai-tips');

    responseArea.classList.remove('hidden');
    tipsEl.innerHTML = "Analyzing your aura... ✨";

    try {
        const res = await fetch(`${API_BASE_URL}/products/recommend`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                shoulder: Number(shoulder),
                waist: Number(waist),
                thigh: Number(thigh),
                style: style
            })
        });

        const data = await res.json();
        console.log("AI RESPONSE:", data);

        if (!data.ai) {
            tipsEl.innerHTML = "AI belum bisa kasih hasil 😭 coba lagi yaa";
            return;
        }

        const tipsText = `
✨ <b>Body Type:</b> ${data.ai.bodyType}<br><br>
💡 <b>Tips:</b>
<ul class="list-disc pl-4">
  <li>${data.ai.tips[0]}</li>
  <li>${data.ai.tips[1]}</li>
</ul>
<br>
🏷 <b>Recommended Tags:</b><br>
${data.ai.recommendedTags.map(t => `<span class="inline-block bg-pink-100 text-pink-600 text-[10px] px-2 py-1 rounded-full mr-1 mt-1">${t}</span>`).join("")}
        `;

        tipsEl.innerHTML = tipsText;

    } catch (err) {
        console.error(err);
        tipsEl.innerHTML = "Server error 😭 (cek console yaa)";
    }
}



async function searchProduct() {
    const query = document.getElementById('search-input').value;
    const listContainer = document.getElementById('product-list');
    listContainer.innerHTML = '<div class="col-span-full text-center py-20 text-gray-400 animate-pulse">Searching for your aura...</div>';
    
    const res = await fetch(`${API_BASE_URL}/products/search?query=${query}`);
    const data = await res.json();
    loadCatalogUI(data.products);
}

async function loadProductsByTags(tagsArr) {
  const tags = encodeURIComponent(tagsArr.join(","));
  const res = await fetch(`${API_BASE_URL}/products/search?tags=${tags}`);
  const data = await res.json();
  loadCatalogUI(data.products);
}


function filterCategory(cat) {
    loadCatalog(cat);
}

function logout() {
    auth.signOut().then(() => {
        location.reload();
    });
}