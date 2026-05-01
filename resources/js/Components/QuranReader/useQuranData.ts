import { useState, useEffect, useRef } from 'react';

// ─── Types ────────────────────────────────────────────────────────────────────
export interface SurahItem {
    nomor: number;
    nama: string;
    namaLatin: string;
    jumlahAyat: number;
    arti: string;
    tempatTurun: string;
}

export interface AyatItem {
    nomorAyat: number;
    teksArab: string;
    teksLatin: string;
    teksIndonesia: string;
    audio: Record<string, string>;
}

export interface SurahDetail extends SurahItem {
    deskripsi: string;
    audioFull: Record<string, string>;
    ayat: AyatItem[];
    suratSelanjutnya: { nomor: number; namaLatin: string; jumlahAyat: number } | false;
    suratSebelumnya: { nomor: number; namaLatin: string; jumlahAyat: number } | false;
}

// ─── Cache ────────────────────────────────────────────────────────────────────
const SURAH_LIST_KEY = 'eq_surah_list_v2';
const SURAH_LIST_TTL = 7 * 24 * 60 * 60 * 1000; // 7 hari

let surahListMemCache: SurahItem[] | null = null;
const surahDetailMemCache: Record<number, SurahDetail> = {};

const BASE_URL = 'https://equran.id/api/v2';

// ─── useSurahList ─────────────────────────────────────────────────────────────
export function useSurahList() {
    const [list, setList]       = useState<SurahItem[]>(surahListMemCache ?? []);
    const [loading, setLoading] = useState(!surahListMemCache);
    const [error, setError]     = useState<string | null>(null);

    useEffect(() => {
        if (surahListMemCache) { setList(surahListMemCache); setLoading(false); return; }

        // Try localStorage cache first
        try {
            const cached = localStorage.getItem(SURAH_LIST_KEY);
            if (cached) {
                const { data, ts } = JSON.parse(cached);
                if (Date.now() - ts < SURAH_LIST_TTL) {
                    surahListMemCache = data;
                    setList(data);
                    setLoading(false);
                    return;
                }
            }
        } catch {}

        fetch(`${BASE_URL}/surat`)
            .then(r => r.json())
            .then(res => {
                const data: SurahItem[] = res.data;
                surahListMemCache = data;
                try { localStorage.setItem(SURAH_LIST_KEY, JSON.stringify({ data, ts: Date.now() })); } catch {}
                setList(data);
            })
            .catch(() => setError('Gagal memuat daftar surah. Periksa koneksi internet.'))
            .finally(() => setLoading(false));
    }, []);

    return { list, loading, error };
}

// ─── useSurahDetail ───────────────────────────────────────────────────────────
export function useSurahDetail(nomor: number) {
    const [detail, setDetail]   = useState<SurahDetail | null>(surahDetailMemCache[nomor] ?? null);
    const [loading, setLoading] = useState(!surahDetailMemCache[nomor]);
    const [error, setError]     = useState<string | null>(null);
    const prevNomor             = useRef(nomor);

    useEffect(() => {
        if (prevNomor.current !== nomor) {
            prevNomor.current = nomor;
            if (!surahDetailMemCache[nomor]) {
                setDetail(null);
                setLoading(true);
                setError(null);
            }
        }

        if (surahDetailMemCache[nomor]) {
            setDetail(surahDetailMemCache[nomor]);
            setLoading(false);
            return;
        }

        setLoading(true);
        fetch(`${BASE_URL}/surat/${nomor}`)
            .then(r => r.json())
            .then(res => {
                const data: SurahDetail = res.data;
                surahDetailMemCache[nomor] = data;
                setDetail(data);
            })
            .catch(() => setError('Gagal memuat surah. Periksa koneksi internet.'))
            .finally(() => setLoading(false));
    }, [nomor]);

    return { detail, loading, error };
}
