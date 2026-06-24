// components/Login.js
const Login = {
    data() {
        return {
            username: '',
            password: '',
            loading: false
        }
    },
    template: `
        <div class="max-w-md mx-auto my-16 bg-white p-8 rounded-2xl shadow-sm border border-sky-100">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-slate-800">Masuk Administrator</h2>
                <p class="text-sm text-slate-400 mt-1">Gunakan akun Anda untuk masuk ke panel E-Report</p>
            </div>
            
            <form @submit.prevent="handleLogin" class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Username</label>
                    <input v-model="username" type="text" placeholder="Masukkan username admin" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-100 transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-1">Password</label>
                    <input v-model="password" type="password" placeholder="••••••••" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:border-sky-400 focus:ring-2 focus:ring-sky-100 transition" required>
                </div>
                
                <button type="submit" :disabled="loading" class="w-full bg-sky-400 hover:bg-sky-500 text-white font-semibold py-2.5 rounded-xl transition shadow-sm shadow-sky-100 flex justify-center items-center disabled:opacity-50">
                    <span v-if="loading">Memproses...</span>
                    <span v-else>Autentikasi Sesi</span>
                </button>
            </form>
        </div>
    `,
    methods: {
        handleLogin() {
            this.loading = true;
            axios.post(API_URL + 'login', { username: this.username, password: this.password })
                .then(res => {
                    localStorage.setItem('token', res.data.token);
                    localStorage.setItem('isLoggedIn', 'true');
                    this.$root.checkLogin(); 
                    this.$router.push('/dashboard');
                })
                .catch(err => {
                    alert('Kredensial login salah atau akun tidak ditemukan!');
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }
};