export type Role = 'mahasiswa' | 'mentor' | 'super' | 'alumni';

export interface FeatureSlide {
    icon: string;
    title: string;
    desc: string;
}

export const FEATURES_BY_ROLE: Record<Role, FeatureSlide[]> = {
    mahasiswa: [
        { icon: 'task_alt', title: 'Aktivitas', desc: 'Catat kegiatan Akademik, Leadership, Karakter Islami & Kreativitas kamu di sini.' },
        { icon: 'menu_book', title: 'Hafalan Quran', desc: 'Lapor setoran hafalan & pantau progres surah yang sudah dihafal.' },
        { icon: 'emoji_events', title: 'Leaderboard', desc: 'Lihat peringkat aktivitas kamu dibanding warga lain tiap bulan.' },
        { icon: 'calendar_month', title: 'Kalender', desc: 'Jadwal kegiatan asrama & pengingat kegiatan pribadi kamu.' },
        { icon: 'badge', title: 'Profil & Portfolio', desc: 'Kelola data diri, riwayat, dan portfolio yang bisa dibagikan.' },
    ],
    mentor: [
        { icon: 'groups', title: 'Mentees', desc: 'Pantau daftar warga bimbingan kamu dan progres aktivitas mereka.' },
        { icon: 'menu_book', title: 'Review Hafalan', desc: 'Uji & nilai setoran hafalan Quran warga bimbingan.' },
        { icon: 'query_stats', title: 'Analisis Mentor', desc: 'Lihat ringkasan perkembangan setiap mentee dari waktu ke waktu.' },
        { icon: 'calendar_month', title: 'Kalender', desc: 'Jadwal sesi mentoring & kegiatan asrama.' },
        { icon: 'badge', title: 'Profil', desc: 'Kelola data diri kamu sebagai mentor.' },
    ],
    super: [
        { icon: 'groups', title: 'Manajemen Warga', desc: 'Kelola data warga, mentor, alumni, dan hak akses (role & permission).' },
        { icon: 'event', title: 'Kegiatan', desc: 'Buat & kelola kegiatan asrama.' },
        { icon: 'emoji_events', title: 'Leaderboard', desc: 'Pantau peringkat aktivitas seluruh warga.' },
        { icon: 'summarize', title: 'Laporan', desc: 'Lihat rekap & statistik performa asrama, export ke PDF.' },
        { icon: 'settings', title: 'Pengaturan', desc: 'Atur komponen penilaian, target harian, asrama, dan konfigurasi sistem.' },
    ],
    alumni: [
        { icon: 'forum', title: 'Hub Alumni', desc: 'Berbagi cerita, foto, dan terhubung dengan sesama alumni.' },
        { icon: 'work', title: 'Lowongan Kerja', desc: 'Lihat & bagikan info lowongan kerja untuk sesama alumni.' },
        { icon: 'badge', title: 'Profil & Portfolio', desc: 'Kelola data diri, riwayat karier, dan portfolio kamu.' },
    ],
};
