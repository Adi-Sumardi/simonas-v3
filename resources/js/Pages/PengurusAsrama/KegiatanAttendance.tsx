import { Head, Link, router, useForm } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';

interface AttendanceItem {
    id: number;
    user: { id: number; name: string; avatar: string | null; asrama: string | null };
    asrama: string | null;
    status: string;
    keterangan: string | null;
    waktu_absen: string;
    latitude: number | null;
    longitude: number | null;
    selfie_url: string | null;
    lokasi_url: string | null;
}
interface RosterItem {
    user_id: number;
    name: string;
    avatar: string | null;
    attendance_id: number | null;
    status: string | null;
    keterangan: string | null;
    waktu_absen: string | null;
}
interface KegiatanData { id: number; nama_kegiatan: string; waktu: string; asrama: string | null }
interface Props {
    kegiatan: KegiatanData;
    items: AttendanceItem[];
    roster: RosterItem[];
    warga: { id: number; name: string; avatar: string | null }[];
    isPutri: boolean;
}

const STATUS_META: Record<string, { label: string; color: string }> = {
    hadir: { label: 'Hadir', color: 'bg-emerald-50 text-emerald-600' },
    izin:  { label: 'Izin',  color: 'bg-blue-50 text-blue-600' },
    sakit: { label: 'Sakit', color: 'bg-amber-50 text-amber-600' },
    alpa:  { label: 'Alpa',  color: 'bg-rose-50 text-rose-600' },
    haid:  { label: 'Haid (Izin)', color: 'bg-purple-50 text-purple-600' },
};
const BELUM = { label: 'Belum Absen', color: 'bg-zinc-100 text-zinc-500' };

export default function KegiatanAttendance({ kegiatan, items, roster, warga, isPutri }: Props) {
    const [preview, setPreview] = useState<AttendanceItem | null>(null);
    const [showManual, setShowManual] = useState(false);
    const manualForm = useForm({ user_id: '', status: 'hadir', keterangan: '' });

    const statusOptions = isPutri
        ? ['hadir', 'izin', 'sakit', 'alpa', 'haid']
        : ['hadir', 'izin', 'sakit', 'alpa'];

    function openManual(prefill?: RosterItem) {
        manualForm.setData({
            user_id: prefill ? String(prefill.user_id) : '',
            status: prefill?.status ?? 'hadir',
            keterangan: prefill?.keterangan ?? '',
        });
        setShowManual(true);
    }

    function submitManual(e: React.FormEvent) {
        e.preventDefault();
        manualForm.post(`/pengurus-asrama/kegiatan/${kegiatan.id}/attendance`, {
            preserveScroll: true,
            onSuccess: () => { manualForm.reset(); setShowManual(false); },
        });
    }

    function deleteAttendance(id: number) {
        if (!confirm('Hapus data kehadiran ini?')) return;
        router.delete(`/pengurus-asrama/kegiatan/${kegiatan.id}/attendance/${id}`, { preserveScroll: true });
    }

    const statusBreakdown = roster.reduce<Record<string, number>>((acc, r) => {
        const key = r.status ?? 'belum';
        acc[key] = (acc[key] ?? 0) + 1;
        return acc;
    }, {});

    return (
        <AppLayout>
            <Head title={`Absensi: ${kegiatan.nama_kegiatan}`} />
            <div className="mb-6">
                <Link href="/pengurus-asrama/kegiatan" className="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary-container transition-colors">
                    <Icon name="arrow_back" className="text-lg" /> Kembali ke Kegiatan
                </Link>
            </div>
            <PageHeader
                title={`Absensi: ${kegiatan.nama_kegiatan}`}
                subtitle={`${roster.length} warga terdaftar · ${new Date(kegiatan.waktu).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`}
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Kegiatan', href: '/pengurus-asrama/kegiatan' }, { label: 'Absensi' }]}
                actions={
                    <button onClick={() => openManual()} className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="person_add" className="text-lg" /> Catat Manual
                    </button>
                }
            />

            {/* Rekap status */}
            <div className="flex flex-wrap gap-2 mb-6">
                {(['hadir', 'izin', 'sakit', 'alpa', ...(isPutri ? ['haid'] : [])] as const).map(key => (
                    <span key={key} className={`text-xs font-bold px-3 py-1.5 rounded-full ${STATUS_META[key].color}`}>
                        {STATUS_META[key].label}: {statusBreakdown[key] ?? 0}
                    </span>
                ))}
                <span className={`text-xs font-bold px-3 py-1.5 rounded-full ${BELUM.color}`}>
                    {BELUM.label}: {statusBreakdown['belum'] ?? 0}
                </span>
            </div>

            {/* Rekap roster lengkap */}
            <div className="glass-card rounded-2xl overflow-hidden mb-8">
                <div className="px-5 py-3 border-b border-white/40 bg-surface-container/20">
                    <h3 className="font-bold text-on-surface text-sm">Rekap Kehadiran — Seluruh Warga</h3>
                    <p className="text-[10px] text-on-surface-variant mt-0.5">
                        {isPutri ? 'Termasuk kategori izin "Haid" untuk asrama putri.' : 'Status hadir / izin / sakit / alpa per warga.'}
                    </p>
                </div>
                <div className="overflow-x-auto">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-white/40 bg-surface-container/10">
                                <th className="text-left px-5 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Warga</th>
                                <th className="text-left px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Status</th>
                                <th className="text-left px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Keterangan</th>
                                <th className="text-left px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Waktu</th>
                                <th className="text-right px-5 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {roster.map(r => {
                                const meta = r.status ? STATUS_META[r.status] : BELUM;
                                return (
                                    <tr key={r.user_id} className="border-b border-white/20 hover:bg-white/20 transition-colors">
                                        <td className="px-5 py-3 font-semibold text-on-surface">{r.name}</td>
                                        <td className="px-4 py-3">
                                            <span className={`text-xs font-bold px-2.5 py-1 rounded-full ${meta.color}`}>{meta.label}</span>
                                        </td>
                                        <td className="px-4 py-3 text-on-surface-variant text-xs">{r.keterangan || '-'}</td>
                                        <td className="px-4 py-3 text-on-surface-variant text-xs">{r.waktu_absen || '-'}</td>
                                        <td className="px-5 py-3 text-right">
                                            <button onClick={() => openManual(r)} className="text-xs font-bold px-3 py-1.5 rounded-lg bg-primary-container/10 text-primary-container hover:bg-primary-container/20 transition-colors">
                                                {r.attendance_id ? 'Ubah' : 'Catat'}
                                            </button>
                                        </td>
                                    </tr>
                                );
                            })}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Log kehadiran dengan bukti foto (self check-in) */}
            <h3 className="font-bold text-on-surface text-sm uppercase tracking-wide mb-3">Log Check-in (dengan bukti foto)</h3>
            {items.length === 0 ? (
                <div className="glass-card rounded-2xl py-16 flex flex-col items-center gap-2 text-on-surface-variant">
                    <Icon name="how_to_reg" className="text-4xl opacity-20" />
                    <p className="text-sm font-semibold">Belum ada yang check-in mandiri</p>
                </div>
            ) : (
                <div className="glass-card rounded-2xl overflow-hidden">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-white/40 bg-surface-container/30">
                                <th className="text-left px-5 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Warga</th>
                                <th className="text-left px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Status</th>
                                <th className="text-left px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Waktu Absen</th>
                                <th className="text-center px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Foto</th>
                                <th className="text-right px-5 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {items.map(it => (
                                <tr key={it.id} className="border-b border-white/20 hover:bg-white/20 transition-colors">
                                    <td className="px-5 py-3 font-semibold text-on-surface">{it.user.name}</td>
                                    <td className="px-4 py-3">
                                        <span className={`text-xs font-bold px-2.5 py-1 rounded-full ${STATUS_META[it.status]?.color ?? BELUM.color}`}>
                                            {STATUS_META[it.status]?.label ?? it.status}
                                        </span>
                                    </td>
                                    <td className="px-4 py-3 text-on-surface-variant">{it.waktu_absen}</td>
                                    <td className="px-4 py-3 text-center">
                                        {it.selfie_url ? (
                                            <button onClick={() => setPreview(it)} className="text-primary-container hover:underline text-xs font-bold">Lihat Foto</button>
                                        ) : '-'}
                                    </td>
                                    <td className="px-5 py-3 text-right">
                                        <button onClick={() => deleteAttendance(it.id)} className="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-100 inline-flex items-center justify-center transition-colors">
                                            <Icon name="delete" className="text-sm" />
                                        </button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}

            <Modal open={!!preview} onClose={() => setPreview(null)} title={preview?.user.name} icon="photo_camera" size="lg">
                {preview && (
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">Selfie</p>
                            {preview.selfie_url ? <img src={preview.selfie_url} className="rounded-xl w-full" /> : <p className="text-xs text-on-surface-variant">Tidak ada foto</p>}
                        </div>
                        <div>
                            <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">Lokasi</p>
                            {preview.lokasi_url ? <img src={preview.lokasi_url} className="rounded-xl w-full" /> : <p className="text-xs text-on-surface-variant">Tidak ada foto</p>}
                        </div>
                        {preview.latitude && preview.longitude && (
                            <a
                                href={`https://www.google.com/maps?q=${preview.latitude},${preview.longitude}`}
                                target="_blank" rel="noopener"
                                className="col-span-2 text-xs text-primary-container hover:underline flex items-center gap-1"
                            >
                                <Icon name="location_on" className="text-sm" /> Lihat titik lokasi di Maps
                            </a>
                        )}
                    </div>
                )}
            </Modal>

            <Modal open={showManual} onClose={() => setShowManual(false)} title="Catat Kehadiran" icon="person_add">
                <form onSubmit={submitManual} className="space-y-4">
                    <div>
                        <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">Warga</label>
                        <select value={manualForm.data.user_id} onChange={e => manualForm.setData('user_id', e.target.value)} className="glass-input w-full text-sm py-2">
                            <option value="">— Pilih Warga —</option>
                            {warga.map(w => <option key={w.id} value={w.id}>{w.name}</option>)}
                        </select>
                        {manualForm.errors.user_id && <p className="text-xs text-rose-500 mt-1">{manualForm.errors.user_id}</p>}
                    </div>
                    <div>
                        <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">Status</label>
                        <select value={manualForm.data.status} onChange={e => manualForm.setData('status', e.target.value)} className="glass-input w-full text-sm py-2">
                            {statusOptions.map(s => <option key={s} value={s}>{STATUS_META[s].label}</option>)}
                        </select>
                        {manualForm.errors.status && <p className="text-xs text-rose-500 mt-1">{manualForm.errors.status}</p>}
                    </div>
                    <div>
                        <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">Keterangan (opsional)</label>
                        <input
                            type="text"
                            value={manualForm.data.keterangan}
                            onChange={e => manualForm.setData('keterangan', e.target.value)}
                            placeholder="Contoh: sakit demam, izin pulang, dll."
                            className="glass-input w-full text-sm py-2"
                        />
                    </div>
                    <div className="flex gap-3 pt-2">
                        <button type="submit" disabled={manualForm.processing} className="btn-primary flex-1 py-3 rounded-xl font-bold text-sm">
                            {manualForm.processing ? 'Menyimpan...' : 'Simpan'}
                        </button>
                        <button type="button" onClick={() => setShowManual(false)} className="px-6 py-3 rounded-xl font-bold text-sm bg-zinc-100 text-on-surface-variant">Batal</button>
                    </div>
                </form>
            </Modal>
        </AppLayout>
    );
}
