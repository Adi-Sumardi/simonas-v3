import { useState } from 'react';
import { useForm, router } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';

interface Pendidikan { id: number; jenjang: string; institusi: string; jurusan: string|null; tahun_masuk: string|null; tahun_lulus: string|null; is_current: boolean }

export function ProfilPendidikan({ items }: { items: Pendidikan[] }) {
    const [adding, setAdding] = useState(false);
    const { data, setData, post, processing, reset } = useForm({
        jenjang: 'S1', institusi: '', jurusan: '', tahun_masuk: '', tahun_lulus: '', is_current: false,
    });
    const LEVELS = ['SMA/MA', 'D3', 'D4', 'S1', 'S2', 'S3', 'Profesi', 'Lainnya'];

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/alumni/profil/pendidikan', { onSuccess: () => { reset(); setAdding(false); } });
    }
    function remove(id: number) {
        if (!confirm('Hapus pendidikan ini?')) return;
        router.delete(`/alumni/profil/pendidikan/${id}`, { preserveScroll: true });
    }

    return (
        <div className="glass-card rounded-2xl overflow-hidden">
            <div className="px-6 py-4 border-b border-white/30 flex items-center justify-between">
                <h3 className="font-bold text-on-surface flex items-center gap-2">
                    <Icon name="school" className="text-blue-600" filled /> Pendidikan
                </h3>
                <button onClick={() => setAdding(!adding)} className="flex items-center gap-1 text-xs font-bold text-emerald-600 hover:underline">
                    <Icon name={adding ? 'close' : 'add'} className="text-sm" /> {adding ? 'Batal' : 'Tambah'}
                </button>
            </div>

            {adding && (
                <form onSubmit={submit} className="p-5 border-b border-white/20 bg-surface-container/30 space-y-3">
                    <div className="flex flex-wrap gap-1.5">
                        {LEVELS.map(l => (
                            <button type="button" key={l} onClick={() => setData('jenjang', l)}
                                className={`px-3 py-1 rounded-full text-xs font-bold border transition-all ${data.jenjang === l ? 'bg-blue-500 text-white border-blue-600' : 'border-white/30 text-on-surface-variant'}`}>
                                {l}
                            </button>
                        ))}
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input value={data.institusi} onChange={e => setData('institusi', e.target.value)} placeholder="Nama Universitas / Sekolah *" className="glass-input text-sm w-full" required />
                        <input value={data.jurusan} onChange={e => setData('jurusan', e.target.value)} placeholder="Jurusan / Prodi" className="glass-input text-sm w-full" />
                        <input value={data.tahun_masuk} onChange={e => setData('tahun_masuk', e.target.value)} placeholder="Tahun Masuk" className="glass-input text-sm w-full" />
                        <input value={data.tahun_lulus} onChange={e => setData('tahun_lulus', e.target.value)} placeholder="Tahun Lulus" className="glass-input text-sm w-full" />
                    </div>
                    <label className="flex items-center gap-2 text-xs text-on-surface-variant cursor-pointer">
                        <input type="checkbox" checked={data.is_current} onChange={e => setData('is_current', e.target.checked)} className="rounded" /> Masih kuliah di sini
                    </label>
                    <button type="submit" disabled={processing} className="px-5 py-2 bg-blue-500 text-white rounded-xl text-xs font-bold disabled:opacity-50">
                        {processing ? 'Menyimpan...' : '✓ Simpan'}
                    </button>
                </form>
            )}

            {items.length === 0 && !adding ? (
                <div className="p-8 text-center text-on-surface-variant text-sm">Belum ada data pendidikan</div>
            ) : (
                <div className="divide-y divide-white/20">
                    {items.map(p => (
                        <div key={p.id} className="p-5 flex items-start gap-4 hover:bg-white/20 transition-colors group">
                            <div className="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                <Icon name="school" className="text-blue-600 text-lg" />
                            </div>
                            <div className="flex-1 min-w-0">
                                <p className="font-bold text-on-surface text-sm">{p.institusi}</p>
                                <p className="text-xs text-on-surface-variant">{p.jenjang}{p.jurusan ? ` — ${p.jurusan}` : ''}</p>
                                <p className="text-[10px] text-on-surface-variant mt-0.5">
                                    {p.tahun_masuk ?? '?'} – {p.is_current ? <span className="text-emerald-600 font-bold">Sekarang</span> : (p.tahun_lulus ?? '?')}
                                </p>
                            </div>
                            <button onClick={() => remove(p.id)} className="opacity-0 group-hover:opacity-100 text-rose-400 hover:text-rose-600 transition-all">
                                <Icon name="delete" className="text-sm" />
                            </button>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}
