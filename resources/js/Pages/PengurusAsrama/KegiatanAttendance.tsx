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
    waktu_absen: string;
    latitude: number | null;
    longitude: number | null;
    selfie_url: string | null;
    lokasi_url: string | null;
}
interface KegiatanData { id: number; nama_kegiatan: string; waktu: string; asrama: string | null }
interface Props { kegiatan: KegiatanData; items: AttendanceItem[]; asramas: string[]; warga: { id: number; name: string }[] }

export default function KegiatanAttendance({ kegiatan, items, warga }: Props) {
    const [preview, setPreview] = useState<AttendanceItem | null>(null);
    const [showManual, setShowManual] = useState(false);
    const manualForm = useForm({ user_id: '' });

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

    const asramaBreakdown = items.reduce<Record<string, number>>((acc, it) => {
        const key = it.asrama ?? '-';
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
                subtitle={`Total ${items.length} warga hadir · ${new Date(kegiatan.waktu).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`}
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Kegiatan', href: '/pengurus-asrama/kegiatan' }, { label: 'Absensi' }]}
                actions={
                    <button onClick={() => setShowManual(true)} className="btn-primary flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold">
                        <Icon name="person_add" className="text-lg" /> Catat Manual
                    </button>
                }
            />

            <div className="flex flex-wrap gap-2 mb-6">
                {Object.entries(asramaBreakdown).map(([asrama, count]) => (
                    <span key={asrama} className="text-xs font-bold px-3 py-1.5 rounded-full bg-blue-50 text-blue-600">
                        {asrama}: {count} warga
                    </span>
                ))}
            </div>

            {items.length === 0 ? (
                <div className="glass-card rounded-2xl py-16 flex flex-col items-center gap-2 text-on-surface-variant">
                    <Icon name="how_to_reg" className="text-4xl opacity-20" />
                    <p className="text-sm font-semibold">Belum ada yang absen</p>
                </div>
            ) : (
                <div className="glass-card rounded-2xl overflow-hidden">
                    <table className="w-full text-sm">
                        <thead>
                            <tr className="border-b border-white/40 bg-surface-container/30">
                                <th className="text-left px-5 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Warga</th>
                                <th className="text-left px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Asrama</th>
                                <th className="text-left px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Waktu Absen</th>
                                <th className="text-center px-4 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Foto</th>
                                <th className="text-right px-5 py-3 text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {items.map(it => (
                                <tr key={it.id} className="border-b border-white/20 hover:bg-white/20 transition-colors">
                                    <td className="px-5 py-3 font-semibold text-on-surface">{it.user.name}</td>
                                    <td className="px-4 py-3 text-on-surface-variant">{it.asrama ?? '-'}</td>
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

            <Modal open={showManual} onClose={() => setShowManual(false)} title="Catat Kehadiran Manual" icon="person_add">
                <form onSubmit={submitManual} className="space-y-4">
                    <div>
                        <label className="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-1.5 block">Warga</label>
                        <select value={manualForm.data.user_id} onChange={e => manualForm.setData('user_id', e.target.value)} className="glass-input w-full text-sm py-2">
                            <option value="">— Pilih Warga —</option>
                            {warga.map(w => <option key={w.id} value={w.id}>{w.name}</option>)}
                        </select>
                        {manualForm.errors.user_id && <p className="text-xs text-rose-500 mt-1">{manualForm.errors.user_id}</p>}
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
