export type RoleName = 'super' | 'admin' | 'mentor' | 'mahasiswa' | 'alumni' | 'pengurus_asrama';

export interface User {
    id: number;
    name: string;
    email: string;
    role: RoleName;          // primary role (legacy column)
    roles: RoleName[];       // all Spatie roles (multi-role support)
    avatar?: string;
    nim?: string;
    asrama?: string;
    angkatan?: string;
}

export interface PageProps {
    auth: {
        user: User;
    };
    permissions: string[];
    flash?: {
        success?: string;
        error?: string;
    };
    [key: string]: unknown;
}

// Mahasiswa types
export interface AktivitasItem {
    id: number;
    jenis: string;
    deskripsi: string;
    created_at: string;
    icon?: string;
    image_url?: string;
}

export interface HafalanData {
    target_juz: number;
    current_juz: number;
    current_ayah: number;
    total_ayah: number;
    streak_days: number;
    last_tasmi_at: string | null;
    progress_percent: number;
    current_surah?: string;
    current_page?: number;
}

export interface HafalanLog {
    id: number;
    mentor_name: string;
    surah: string;
    ayat_start: number;
    ayat_end: number;
    halaman_start?: number;
    halaman_end?: number;
    score: 'memtas' | 'layak_ulang' | 'perlu_perbaikan';
    notes: string | null;
    tested_at: string;
}

export interface LeaderboardEntry {
    rank: number;
    user_id: number;
    name: string;
    asrama: string;
    avatar?: string;
    points: number;
    badge?: string;
    target?: number;
    terpenuhi?: boolean;
}

// Mentor types
export interface Mentee {
    id: number;
    name: string;
    nim: string;
    asrama: string;
    kelas: string;
    avatar?: string;
    score: number;
    status: 'active' | 'review_needed';
}

export interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    links: Array<{ url: string | null; label: string; active: boolean }>;
}
