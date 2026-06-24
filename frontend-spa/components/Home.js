// components/Home.js
const Home = {
    data() {
        return {
            reports: []
        }
    },
    template: `
        <div class="text-center my-12 animate-fade-in">
            <h1 class="text-4xl font-extrabold text-slate-800 tracking-tight">Sistem Pelaporan Pengaduan Layanan Masyarakat</h1>
            <p class="text-slate-500 mt-2 text-lg">Suara Anda adalah langkah awal perubahan. Laporkan masalah di sekitar Anda dengan aman.</p>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-sky-100 max-w-4xl mx-auto">
            <h2 class="text-xl font-bold text-slate-700 mb-6 flex items-center space-x-2">
                <span>📢 Pengaduan Publik Terbaru</span>
            </h2>
            
            <div v-if="reports.length === 0" class="text-center text-slate-400 py-12 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                Belum ada data aduan masyarakat yang masuk.
            </div>

            <div class="space-y-4">
                <div v-for="r in reports" :key="r.id" class="p-5 rounded-xl border border-slate-100 bg-slate-50/50 hover:border-sky-200 transition-all shadow-xs">
                    <div class="flex justify-between items-start gap-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg mb-1">{{ r.judul_laporan }}</h3>
                            <p class="text-slate-600 text-sm leading-relaxed">{{ r.isi_laporan }}</p>
                        </div>
                        <span :class="{
                            'bg-amber-100 text-amber-700 border border-amber-200': r.status === 'Pending', 
                            'bg-sky-100 text-sky-700 border border-sky-200': r.status === 'Proses', 
                            'bg-emerald-100 text-emerald-700 border border-emerald-200': r.status === 'Selesai'
                        }" class="px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-wider shrink-0">
                            {{ r.status }}
                        </span>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t border-slate-100 text-xs text-slate-400 flex flex-wrap gap-4">
                        <span class="flex items-center">👤 Pelapor: <strong class="text-slate-600 ml-1">{{ r.nama_lengkap || 'Anonim' }}</strong></span>
                        <span class="flex items-center">📂 Kategori: <strong class="text-slate-600 ml-1">{{ r.nama_kategori || 'Umum' }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    `,
    mounted() {
        axios.get(API_URL + 'reports')
            .then(res => {
                this.reports = res.data;
            })
            .catch(err => console.error("Gagal memuat data pengaduan publik."));
    }
};