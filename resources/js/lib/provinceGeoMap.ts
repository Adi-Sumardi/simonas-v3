// Peta nama "state" di file GeoJSON (bahasa Inggris, batas lama sebelum
// pemekaran Papua 2022 & pembentukan Kalimantan Utara 2012) ke nama provinsi
// baku yang dipakai di tabel `provinces` (lihat WilayahSeeder).
export const GEO_STATE_TO_PROVINCE: Record<string, string> = {
    'Special Region of Aceh': 'ACEH',
    'West Sumatera': 'SUMATERA BARAT',
    'Special Region of Yogyakarta': 'DI YOGYAKARTA',
    'North Sumatera': 'SUMATERA UTARA',
    'Bangka-Belitung Islands': 'KEPULAUAN BANGKA BELITUNG',
    'Special Region of West Papua': 'PAPUA BARAT',
    'East Java': 'JAWA TIMUR',
    'West Kalimantan': 'KALIMANTAN BARAT',
    'South Kalimantan': 'KALIMANTAN SELATAN',
    'East Kalimantan': 'KALIMANTAN TIMUR',
    'Riau Islands': 'KEPULAUAN RIAU',
    'Lampung': 'LAMPUNG',
    'Maluku': 'MALUKU',
    'North Maluku': 'MALUKU UTARA',
    'West Nusa Tenggara': 'NUSA TENGGARA BARAT',
    'East Nusa Tenggara': 'NUSA TENGGARA TIMUR',
    'Papua': 'PAPUA',
    'Riau': 'RIAU',
    'South Sulawesi': 'SULAWESI SELATAN',
    'Bengkulu': 'BENGKULU',
    'Central Sulawesi': 'SULAWESI TENGAH',
    'North Sulawesi': 'SULAWESI UTARA',
    'Southeast Sulawesi': 'SULAWESI TENGGARA',
    'Bali': 'BALI',
    'Banten': 'BANTEN',
    'Gorontalo': 'GORONTALO',
    'Jakarta Special Capital Region': 'DKI JAKARTA',
    'Jambi': 'JAMBI',
    'West Jawa': 'JAWA BARAT',
    'Central Jawa': 'JAWA TENGAH',
    'Central Kalimantan': 'KALIMANTAN TENGAH',
    'West Sulawesi': 'SULAWESI BARAT',
    'South Sumatera': 'SUMATERA SELATAN',
};

// Provinsi hasil pemekaran yang belum punya batas wilayah sendiri di GeoJSON
// (pemekaran Papua 2022, Kalimantan Utara 2012) — datanya digabung secara
// visual ke provinsi induk di peta, supaya tidak hilang dari choropleth.
export const PROVINCE_MERGE_INTO_PARENT: Record<string, string> = {
    'PAPUA SELATAN': 'PAPUA',
    'PAPUA TENGAH': 'PAPUA',
    'PAPUA PEGUNUNGAN': 'PAPUA',
    'PAPUA BARAT DAYA': 'PAPUA BARAT',
    'KALIMANTAN UTARA': 'KALIMANTAN TIMUR',
};
