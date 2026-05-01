import { useState } from 'react';
import { useForm, router } from '@inertiajs/react';
import { Icon } from '@/Components/ui/Icon';

interface Sertifikat { id: number; tipe: string; nama: string; penerbit: string|null; tahun: string|null; nomor_id: string|null; url: string|null }

const TIPE_META: Record<string, { label: string; icon: string; color: string }> = {
    sertifikat:  { label: 'Sertifikat',  icon: 'verified',      color: 'bg-blue-100 text-blue-600' },
    gelar:       { label: 'Gelar',       icon: 'school',        color: 'bg-purple-100 text-purple-600' },
    penghargaan: { label: 'Penghargaan', icon: 'emoji_events',  color: 'bg-amber-100 text-amber-600' },
    lisensi:     { label: 'Lisensi',     icon: 'badge',         color: 'bg-emerald-100 text-emerald-600' },
};

export function ProfilSertifikat({ items }: { items: Sertifikat[] }) {
    const [adding, setAdding] = useState(false);
    const { data, setData, post, processing, reset } = useForm({
        tipe: 'sertifikat', nama: '', penerbit: '', tahun: '', nomor_id: '', url: '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post('/alumni/profil/sertifikat', { onSuccess: () => { reset(); setAdding(false); } });
    }
    function remove(id: number) {
        if (!confirm('Hapus item ini?')) return;
        router.delete(`/alumni/profil/sertifikat/${id}`, { preserveScroll: true });
    }

    return (
        <div className="glass-card rounded-2xl overflow-hidden">
            <div className="px-6 py-4 border-b border-white/30 flex items-center justify-between">
                <h3 className="font-bold text-on-surface flex items-center gap-2">
                    <Icon name="verified" className="text-amber-600" filled /> Sertifikat & Penghargaan
                </h3>
                <button onClick={() => setAdding(!adding)} className="flex items-center gap-1 text-xs font-bold text-emerald-600 hover:underline">
                    <Icon name={adding ? 'close' : 'add'} className="text-sm" /> {adding ? 'Batal' : 'Tambah'}
                </button>
            </div>

            {adding && (
                <form onSubmit={submit} className="p-5 border-b border-white/20 bg-surface-container/30 space-y-3">
                    <div className="flex flex-wrap gap-1.5">
                        {Object.entries(TIPE_META).map(([k, m]) => (
                            <button type="button" key={k} onClick={() => setData('tipe', k)}
                                className={`flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold border transition-all ${data.tipe === k ? 'bg-emerald-500 text-white border-emerald-600' : 'border-white/30 text-on-surface-variant'}`}>
                                <Icon name={m.icon} className="text-xs" /> {m.label}
                            </button>
                        ))}
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <input value={data.nama} onChange={e => setData('nama', e.target.value)} placeholder="Nama Sertifikat / Gelar *" className="glass-input text-sm w-full" required />
                        <input value={data.penerbit} onChange={e => setData('penerbit', e.target.value)} placeholder="Penerbit / Institusi" className="glass-input text-sm w-full" />
                        <input value={data.tahun} onChange={e => setData('tahun', e.target.value)} placeholder="Tahun" className="glass-input text-sm w-full" />
                        <input value={data.url} onChange={e => setData('url', e.target.value)} placeholder="URL Credential (opsional)" className="glass-input text-sm w-full" />
                    </div>
                    <button type="submit" disabled={processing} className="px-5 py-2 bg-emerald-500 text-white rounded-xl text-xs font-bold disabled:opacity-50">
                        {processing ? 'Menyimpan...' : '✓ Simpan'}
                    </button>
                </form>
            )}

            {items.length === 0 && !adding ? (
                <div className="p-8 text-center text-on-surface-variant text-sm">Belum ada sertifikat</div>
            ) : (
                <div className="divide-y divide-white/20">
                    {items.map(s => {
                        const m = TIPE_META[s.tipe] ?? TIPE_META.sertifikat;
                        return (
                            <div key={s.id} className="p-5 flex items-start gap-4 hover:bg-white/20 transition-colors group">
                                <div className={`w-10 h-10 rounded-xl ${m.color} flex items-center justify-center flex-shrink-0`}>
                                    <Icon name={m.icon} className="text-lg" />
                                </div>
                                <div className="flex-1 min-w-0">
                                    <div className="flex items-center gap-2">
                                        <p className="font-bold text-on-surface text-sm">{s.nama}</p>
                                        <span className="text-[10px] font-black px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant">{m.label}</span>
                                    </div>
                                    <p className="text-xs text-on-surface-variant">{[s.penerbit, s.tahun].filter(Boolean).join(' · ')}</p>
                                    {s.url && <a href={s.url} target="_blank" rel="noopener" className="text-[10px] font-bold text-blue-600 hover:underline mt-0.5 inline-block">Lihat Credential →</a>}
                                </div>
                                <button onClick={() => remove(s.id)} className="opacity-0 group-hover:opacity-100 text-rose-400 hover:text-rose-600 transition-all">
                                    <Icon name="delete" className="text-sm" />
                                </button>
                            </div>
                        );
                    })}
                </div>
            )}
        </div>
    );
}
