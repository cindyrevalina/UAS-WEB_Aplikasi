// components/Dashboard.js
const Dashboard = {
    data() { 
        return { 
            reports: [],
            showModal: false,
            newReport: { judul_laporan: '', isi_laporan: '', category_id: 1 },
            selectedFile: null // Tempat menyimpan file gambar sementara sebelum dikirim
        } 
    },
    template: `
        <div class="w-full max-w-7xl mx-auto px-4 py-8 animate-fade-in">
            <div class="bg-gradient-to-r from-sky-400 via-sky-300 to-indigo-100 rounded-3xl p-6 sm:p-8 shadow-md border border-sky-100 mb-8 text-slate-800">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                    <div>
                        <span class="bg-white/60 text-sky-700 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full">Panel Kontrol Utama</span>
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight mt-2">Administrasi E-Report</h2>
                        <p class="text-slate-600 text-sm sm:text-base mt-1 font-medium">Validasi, kelola status, dan selesaikan seluruh aduan masyarakat dengan cepat.</p>
                    </div>
                    <button @click="showModal = true" class="w-full sm:w-auto bg-white hover:bg-slate-50 text-sky-600 font-bold px-6 py-3 rounded-2xl text-sm transition-all shadow-sm active:scale-95 flex items-center justify-center space-x-2 shrink-0">
                        <span>✨</span>
                        <span>Buat Pengaduan Baru</span>
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 flex items-center space-x-2">
                        <span class="text-xl">📁</span>
                        <span>Daftar Seluruh Laporan Masuk</span>
                    </h3>
                    <span class="bg-sky-100 text-sky-700 text-xs font-bold px-3 py-1 rounded-full">{{ reports.length }} Total Aduan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[900px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs font-bold uppercase tracking-wider">
                                <th class="p-5 w-[12%] text-center">Foto Bukti</th>
                                <th class="p-5 w-[20%]">Judul Laporan</th>
                                <th class="p-5 w-[38%]">Isi Deskripsi Aduan</th>
                                <th class="p-5 w-[12%] text-center">Status</th>
                                <th class="p-5 w-[18%] text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                            <tr v-if="reports.length === 0">
                                <td colspan="5" class="p-12 text-center text-slate-400 bg-white">
                                    <div class="text-4xl mb-2">🍃</div>
                                    <p class="font-medium">Tidak ada data pengaduan yang terdaftar.</p>
                                </td>
                            </tr>
                            
                            <tr v-for="r in reports" :key="r.id" class="hover:bg-sky-50/20 transition-colors">
                                <td class="p-5 text-center">
                                    <img v-if="r.bukti_gambar" :src="'http://localhost:8080/uploads/' + r.bukti_gambar" class="w-12 h-12 object-cover rounded-xl shadow-xs border border-slate-200 mx-auto" alt="Bukti">
                                    <span v-else class="text-xs text-slate-400 italic">No Image</span>
                                </td>
                                <td class="p-5 font-semibold text-slate-800">
                                    <div class="truncate max-w-[180px]">{{ r.judul_laporan }}</div>
                                </td>
                                <td class="p-5 text-slate-500">
                                    <p class="line-clamp-2 text-xs leading-relaxed max-w-[450px]">{{ r.isi_laporan }}</p>
                                </td>
                                <td class="p-5 text-center whitespace-nowrap">
                                    <span :class="{
                                        'bg-amber-50 text-amber-600 border border-amber-200': r.status === 'Pending', 
                                        'bg-sky-50 text-sky-600 border border-sky-200': r.status === 'Proses', 
                                        'bg-emerald-50 text-emerald-600 border border-emerald-200': r.status === 'Selesai'
                                    }" class="px-2.5 py-1.5 rounded-xl text-xs font-bold border tracking-wide inline-block">
                                        ● {{ r.status }}
                                    </span>
                                </td>
                                <td class="p-5 text-center space-x-1 whitespace-nowrap">
                                    <button @click="updateStatus(r.id, 'Proses')" class="bg-amber-400 hover:bg-amber-500 text-white text-xs font-bold px-2.5 py-1.5 rounded-xl transition-all">⚙️ Proses</button>
                                    <button @click="updateStatus(r.id, 'Selesai')" class="bg-emerald-400 hover:bg-emerald-500 text-white text-xs font-bold px-2.5 py-1.5 rounded-xl transition-all">✓ Selesai</button>
                                    <button @click="deleteReport(r.id)" class="bg-rose-400 hover:bg-rose-500 text-white text-xs font-bold px-2.5 py-1.5 rounded-xl transition-all">🗑️</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div v-if="showModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center z-50 p-4 animate-fade-in">
                <div class="bg-white p-6 sm:p-8 rounded-3xl shadow-2xl border border-slate-100 max-w-lg w-full">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-100 mb-6">
                        <h3 class="text-xl font-extrabold text-slate-800 flex items-center space-x-2">
                            <span>📝</span>
                            <span>Form Pengaduan Baru</span>
                        </h3>
                        <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                    </div>
                    
                    <form @submit.prevent="submitReport" class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Judul Laporan</label>
                            <input v-model="newReport.judul_laporan" type="text" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-50 transition" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Detail Kejadian</label>
                            <textarea v-model="newReport.isi_laporan" rows="3" class="w-full px-4 py-3 rounded-2xl border border-slate-200 text-sm focus:outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-50 transition resize-none" required></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-2">Lampiran Bukti Foto</label>
                            <input type="file" @change="handleFileUpload" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-2xl file:border-0 file:text-xs file:font-bold file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100 cursor-pointer" />
                        </div>

                        <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                            <button @click="showModal = false" type="button" class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold px-5 py-2.5 rounded-2xl text-xs transition">Batal</button>
                            <button type="submit" class="bg-sky-400 hover:bg-sky-500 text-white font-bold px-6 py-2.5 rounded-2xl text-xs transition shadow-sm">Simpan Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `,
    methods: {
        loadReports() {
            axios.get(API_URL + 'reports').then(res => this.reports = res.data);
        },
        handleFileUpload(event) {
            this.selectedFile = event.target.files[0]; // Menyimpan file gambar pilihan user ke data Vue
        },
        submitReport() {
            // Mengubah format pengiriman menjadi FormData agar bisa mengangkut file fisik gambar
            const formData = new FormData();
            formData.append('judul_laporan', this.newReport.judul_laporan);
            formData.append('isi_laporan', this.newReport.isi_laporan);
            formData.append('category_id', this.newReport.category_id);

            if (this.selectedFile) {
                formData.append('bukti_gambar', this.selectedFile);
            }

            axios.post(API_URL + 'reports', formData, {
                headers: { 'Content-Type': 'multipart/form-data' } // Set header khusus multipart-form
            })
            .then(() => {
                this.showModal = false;
                this.newReport = { judul_laporan: '', isi_laporan: '', category_id: 1 };
                this.selectedFile = null;
                this.loadReports(); // Muat ulang tabel otomatis
            })
            .catch(err => alert("Gagal menyimpan laporan baru."));
        },
        updateStatus(id, status) {
            axios.put(API_URL + 'reports/' + id, { status }).then(() => this.loadReports());
        },
        deleteReport(id) {
            if (confirm('Yakin ingin menghapus laporan ini secara permanen?')) {
                axios.delete(API_URL + 'reports/' + id).then(() => this.loadReports());
            }
        }
    },
    mounted() { 
        this.loadReports(); 
    }
};