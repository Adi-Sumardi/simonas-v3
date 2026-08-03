import { Head, router } from '@inertiajs/react';
import { useRef, useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Pagination } from '@/Components/ui/Pagination';
import { Icon } from '@/Components/ui/Icon';
import { Modal } from '@/Components/ui/Modal';
import { watermarkPhoto } from '@/lib/watermarkPhoto';

interface KegiatanData {
    id: number;
    nama_kegiatan: string;
    waktu: string;
    tempat: string;
    jenis_kegiatan: string;
    keterangan: string;
    wajib_absen: boolean;
    sudah_absen: boolean;
    waktu_absen: string | null;
}
interface Props {
    kegiatan: { data: KegiatanData[]; current_page: number; last_page: number; total: number; per_page: number };
}

type Step = 'lokasi' | 'selfie' | 'foto_lokasi' | 'review' | 'done';

export default function MahasiswaKegiatan({ kegiatan }: Props) {
    const [absenTarget, setAbsenTarget] = useState<KegiatanData | null>(null);
    const [step, setStep] = useState<Step>('lokasi');
    const [coords, setCoords] = useState<{ lat: number; lng: number } | null>(null);
    const [geoError, setGeoError] = useState('');
    const [selfieFile, setSelfieFile] = useState<File | null>(null);
    const [lokasiFile, setLokasiFile] = useState<File | null>(null);
    const [submitting, setSubmitting] = useState(false);
    const [submitError, setSubmitError] = useState('');

    const selfieInputRef = useRef<HTMLInputElement>(null);
    const lokasiInputRef = useRef<HTMLInputElement>(null);

    function openAbsen(k: KegiatanData) {
        setAbsenTarget(k);
        setStep('lokasi');
        setCoords(null);
        setGeoError('');
        setSelfieFile(null);
        setLokasiFile(null);
        setSubmitError('');
        requestLocation();
    }

    function closeModal() {
        setAbsenTarget(null);
    }

    function requestLocation() {
        setGeoError('');
        if (!navigator.geolocation) {
            setGeoError('Perangkat kamu tidak mendukung deteksi lokasi.');
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                setCoords({ lat: pos.coords.latitude, lng: pos.coords.longitude });
                setStep('selfie');
            },
            () => setGeoError('Gagal mendapatkan lokasi. Aktifkan izin lokasi lalu coba lagi.'),
            { enableHighAccuracy: true, timeout: 10000 },
        );
    }

    function watermarkLines(): string[] {
        const now = new Date().toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'medium' });
        const loc = coords ? `${coords.lat.toFixed(6)}, ${coords.lng.toFixed(6)}` : '-';
        return [absenTarget?.nama_kegiatan ?? '', now, loc];
    }

    async function handleSelfieCapture(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (!file) return;
        const stamped = await watermarkPhoto(file, { lines: watermarkLines() });
        setSelfieFile(stamped);
        setStep('foto_lokasi');
    }

    async function handleLokasiCapture(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0];
        if (!file) return;
        const stamped = await watermarkPhoto(file, { lines: watermarkLines() });
        setLokasiFile(stamped);
        setStep('review');
    }

    function submit() {
        if (!absenTarget || !selfieFile || !lokasiFile || !coords) return;
        setSubmitting(true);
        setSubmitError('');

        const formData = new FormData();
        formData.append('file_selfie', selfieFile);
        formData.append('file_lokasi', lokasiFile);
        formData.append('latitude', String(coords.lat));
        formData.append('longitude', String(coords.lng));

        router.post(`/mahasiswa/kegiatan/${absenTarget.id}/checkin`, formData, {
            preserveScroll: true,
            onSuccess: () => { setStep('done'); },
            onError: () => setSubmitError('Gagal mengirim absensi, coba lagi.'),
            onFinish: () => setSubmitting(false),
        });
    }

    return (
        <AppLayout>
            <Head title="Kegiatan" />
            <PageHeader
                title="Kegiatan"
                subtitle="Daftar kegiatan asrama & kampus, lengkap dengan absensi kehadiran"
                breadcrumbs={[{ label: 'Dashboard', href: '/dashboard' }, { label: 'Kegiatan' }]}
            />

            {kegiatan.data.length === 0 ? (
                <div className="glass-card rounded-2xl py-16 flex flex-col items-center gap-2 text-on-surface-variant">
                    <Icon name="event_busy" className="text-4xl opacity-20" />
                    <p className="text-sm font-semibold">Belum ada kegiatan</p>
                </div>
            ) : (
                <div className="space-y-3 mb-6">
                    {kegiatan.data.map(k => {
                        const eventDate = new Date(k.waktu);
                        return (
                            <div key={k.id} className="glass-card rounded-2xl p-4 flex items-center gap-4">
                                <div className="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                                    <Icon name="event" className="text-xl text-purple-600" filled />
                                </div>
                                <div className="flex-1 min-w-0">
                                    <p className="font-bold text-on-surface text-sm truncate">{k.nama_kegiatan}</p>
                                    <p className="text-xs text-on-surface-variant">
                                        {eventDate.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })} &middot; {k.tempat}
                                    </p>
                                </div>
                                {k.wajib_absen && (
                                    k.sudah_absen ? (
                                        <span className="text-xs font-bold px-3 py-2 rounded-lg bg-emerald-50 text-emerald-600 flex items-center gap-1 flex-shrink-0">
                                            <Icon name="check_circle" className="text-sm" filled /> Sudah Absen
                                        </span>
                                    ) : (
                                        <button onClick={() => openAbsen(k)} className="btn-primary px-3 py-2 rounded-lg text-xs font-bold flex items-center gap-1 flex-shrink-0">
                                            <Icon name="photo_camera" className="text-sm" /> Absen
                                        </button>
                                    )
                                )}
                            </div>
                        );
                    })}
                </div>
            )}

            <Pagination
                currentPage={kegiatan.current_page}
                totalPages={kegiatan.last_page}
                totalItems={kegiatan.total}
                perPage={kegiatan.per_page}
                onPageChange={(page) => router.get('/mahasiswa/kegiatan', { page }, { preserveState: true })}
                onPerPageChange={(perPage) => router.get('/mahasiswa/kegiatan', { per_page: perPage }, { preserveState: true })}
            />

            <Modal open={!!absenTarget} onClose={closeModal} title={`Absen: ${absenTarget?.nama_kegiatan ?? ''}`} icon="photo_camera" size="sm" disableBackdropClose>
                <div className="space-y-4">
                    {step === 'lokasi' && (
                        <div className="text-center py-6">
                            <Icon name="location_on" className="text-4xl text-primary-container mb-3" />
                            <p className="text-sm text-on-surface-variant mb-4">Mendeteksi lokasi kamu...</p>
                            {geoError && (
                                <>
                                    <p className="text-xs text-rose-500 mb-3">{geoError}</p>
                                    <button onClick={requestLocation} className="btn-primary px-4 py-2 rounded-lg text-xs font-bold">Coba Lagi</button>
                                </>
                            )}
                        </div>
                    )}

                    {step === 'selfie' && (
                        <div className="text-center py-4">
                            <Icon name="face" className="text-4xl text-primary-container mb-3" />
                            <p className="text-sm font-bold text-on-surface mb-1">Ambil Foto Selfie</p>
                            <p className="text-xs text-on-surface-variant mb-4">Wajib pakai kamera depan langsung, waktu & lokasi otomatis ditempel di foto.</p>
                            <button onClick={() => selfieInputRef.current?.click()} className="btn-primary px-5 py-3 rounded-xl text-sm font-bold flex items-center gap-2 mx-auto">
                                <Icon name="photo_camera" /> Buka Kamera Depan
                            </button>
                            <input ref={selfieInputRef} type="file" accept="image/*" capture="user" className="hidden" onChange={handleSelfieCapture} />
                        </div>
                    )}

                    {step === 'foto_lokasi' && (
                        <div className="text-center py-4">
                            <Icon name="photo_camera_back" className="text-4xl text-primary-container mb-3" />
                            <p className="text-sm font-bold text-on-surface mb-1">Ambil Foto Lokasi Kegiatan</p>
                            <p className="text-xs text-on-surface-variant mb-4">Wajib pakai kamera belakang langsung, arahkan ke suasana kegiatan.</p>
                            <button onClick={() => lokasiInputRef.current?.click()} className="btn-primary px-5 py-3 rounded-xl text-sm font-bold flex items-center gap-2 mx-auto">
                                <Icon name="photo_camera" /> Buka Kamera Belakang
                            </button>
                            <input ref={lokasiInputRef} type="file" accept="image/*" capture="environment" className="hidden" onChange={handleLokasiCapture} />
                        </div>
                    )}

                    {step === 'review' && selfieFile && lokasiFile && (
                        <div>
                            <div className="grid grid-cols-2 gap-3 mb-4">
                                <div>
                                    <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">Selfie</p>
                                    <img src={URL.createObjectURL(selfieFile)} className="rounded-xl w-full aspect-square object-cover" />
                                </div>
                                <div>
                                    <p className="text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-1">Lokasi</p>
                                    <img src={URL.createObjectURL(lokasiFile)} className="rounded-xl w-full aspect-square object-cover" />
                                </div>
                            </div>
                            {submitError && <p className="text-xs text-rose-500 mb-3 text-center">{submitError}</p>}
                            <button onClick={submit} disabled={submitting} className="btn-primary w-full py-3 rounded-xl text-sm font-bold flex items-center justify-center gap-2">
                                {submitting ? 'Mengirim...' : 'Kirim Absensi'}
                                {!submitting && <Icon name="check" />}
                            </button>
                        </div>
                    )}

                    {step === 'done' && (
                        <div className="text-center py-6">
                            <div className="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                                <Icon name="check_circle" className="text-3xl text-emerald-600" filled />
                            </div>
                            <p className="font-bold text-on-surface mb-1">Absensi Berhasil!</p>
                            <p className="text-xs text-on-surface-variant mb-4">Kehadiran kamu sudah tercatat.</p>
                            <button onClick={() => { closeModal(); router.reload({ only: ['kegiatan'] }); }} className="btn-primary px-5 py-2.5 rounded-xl text-sm font-bold">
                                Selesai
                            </button>
                        </div>
                    )}
                </div>
            </Modal>
        </AppLayout>
    );
}
