import { useState } from 'react';
import { useForm, router } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';

interface Pekerjaan { id: number; jabatan: string; perusahaan: string; lokasi: string|null; tahun_mulai: string|null; tahun_selesai: string|null; is_current: boolean; deskripsi: string|null }

export function ProfilPekerjaan({ items }: { items: Pekerjaan[] }) {
    const [adding, setAdding] = useState(false);
    const { data, setData, post, processing, reset } = useForm({
        jabatan: '', perusahaan: '', lokasi: '', tahun_mulai: '', tahun_selesai: '', is_current: false, deskripsi: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/alumni/profil/pekerjaan', { onSuccess: () => { reset(); setAdding(false); } });
    }
    function remove(id: number) {
        if (!confirm('Hapus pekerjaan ini?')) return;
        router.delete(`/alumni/profil/pekerjaan/${id}`, { preserveScroll: true });
    }

    return (
        <div className="glass-card rounded-2xl overflow-hidden">
            <div className="px-6 py-4 border-b border-white/30 flex items-center justify-between">
                <h3 className="font-bold text-on-surface flex items-center gap-2">
                    <Icon name="work" className="text-emerald-600" filled /> Pengalaman Kerja
                </h3>
                <button onClick={() => setAdding(!adding)} className="flex items-center gap-1 text-xs font-bold text-emerald-600 hover:underline">
                    <Icon name={adding ? 'close' : 'add'} className="text-sm" /> {adding ? 'Batal' : 'Tambah'}
                </button>
            </div>

            {adding && (
                <form onSubmit={submit} className="p-5 border-b border-white/20 bg-surface-container/30 space-y-3">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input value={data.jabatan} onChange={e => setData('jabatan', e.target.value)} placeholder="Jabatan / Posisi *" className="glass-input text-sm w-full" required />
                        <input value={data.perusahaan} onChange={e => setData('perusahaan', e.target.value)} placeholder="Perusahaan / Instansi *" className="glass-input text-sm w-full" required />
                        <input value={data.lokasi} onChange={e => setData('lokasi', e.target.value)} placeholder="Lokasi (Jakarta, Remote, dll)" className="glass-input text-sm w-full" />
                        <div className="flex gap-2">
                            <input value={data.tahun_mulai} onChange={e => setData('tahun_mulai', e.target.value)} placeholder="Tahun Mulai" className="glass-input text-sm w-full" />
                            <input value={data.tahun_selesai} onChange={e => setData('tahun_selesai', e.target.value)} placeholder="Tahun Selesai" className="glass-input text-sm w-full" disabled={data.is_current} />
                        </div>
                    </div>
                    <textarea value={data.deskripsi} onChange={e => setData('deskripsi', e.target.value)} placeholder="Deskripsi pekerjaan (opsional)" className="glass-input text-sm w-full resize-none" rows={2} />
                    <label className="flex items-center gap-2 text-xs text-on-surface-variant cursor-pointer">
                        <input type="checkbox" checked={data.is_current} onChange={e => setData('is_current', e.target.checked)} className="rounded" /> Masih bekerja di sini
                    </label>
                    <button type="submit" disabled={processing} className="px-5 py-2 bg-emerald-500 text-white rounded-xl text-xs font-bold disabled:opacity-50">
                        {processing ? 'Menyimpan...' : '✓ Simpan'}
                    </button>
                </form>
            )}

            {items.length === 0 && !adding ? (
                <div className="p-8 text-center text-on-surface-variant text-sm">Belum ada data pekerjaan</div>
            ) : (
                <div className="divide-y divide-white/20">
                    {items.map(p => (
                        <div key={p.id} className="p-5 flex items-start gap-4 hover:bg-white/20 transition-colors group">
                            <div className="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0">
                                <Icon name="work" className="text-emerald-600 text-lg" />
                            </div>
                            <div className="flex-1 min-w-0">
                                <p className="font-bold text-on-surface text-sm">{p.jabatan}</p>
                                <p className="text-xs text-on-surface-variant">{p.perusahaan}{p.lokasi ? ` · ${p.lokasi}` : ''}</p>
                                <p className="text-[10px] text-on-surface-variant mt-0.5">
                                    {p.tahun_mulai ?? '?'} – {p.is_current ? <span className="text-emerald-600 font-bold">Sekarang</span> : (p.tahun_selesai ?? '?')}
                                </p>
                                {p.deskripsi && <p className="text-xs text-on-surface-variant mt-1 line-clamp-2">{p.deskripsi}</p>}
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
