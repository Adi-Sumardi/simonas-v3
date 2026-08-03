import { useMemo, useState } from 'react';
import { ComposableMap, Geographies, Geography } from 'react-simple-maps';
import { Icon } from '@/Components/ui/Icon';
import geoData from '@/data/indonesia-provinces.geo.json';
import { GEO_STATE_TO_PROVINCE, PROVINCE_MERGE_INTO_PARENT } from '@/lib/provinceGeoMap';

interface ProvinceCount { name: string; cnt: number }
interface RegencyCount { province: string; kota: string; cnt: number }
interface Props {
    provinces: ProvinceCount[];
    regencies: RegencyCount[];
}

function resolveProvinceKey(name: string): string {
    return PROVINCE_MERGE_INTO_PARENT[name] ?? name;
}

function colorForRatio(ratio: number): string {
    // 0 -> abu-abu netral, 1 -> biru penuh (selaras warna primary aplikasi)
    if (ratio <= 0) return '#e2e8f0';
    const from = { r: 199, g: 210, b: 254 }; // indigo-200
    const to = { r: 30, g: 58, b: 138 };     // blue-900
    const r = Math.round(from.r + (to.r - from.r) * ratio);
    const g = Math.round(from.g + (to.g - from.g) * ratio);
    const b = Math.round(from.b + (to.b - from.b) * ratio);
    return `rgb(${r},${g},${b})`;
}

export function IndonesiaMap({ provinces, regencies }: Props) {
    const [selected, setSelected] = useState<string | null>(null);

    const countByProvince = useMemo(() => {
        const map: Record<string, number> = {};
        for (const p of provinces) {
            const key = resolveProvinceKey(p.name);
            map[key] = (map[key] ?? 0) + p.cnt;
        }
        return map;
    }, [provinces]);

    const maxCount = useMemo(() => Math.max(1, ...Object.values(countByProvince)), [countByProvince]);

    const regenciesForSelected = useMemo(() => {
        if (!selected) return [];
        return regencies
            .filter(r => resolveProvinceKey(r.province) === selected)
            .sort((a, b) => b.cnt - a.cnt);
    }, [selected, regencies]);

    const selectedTotal = selected ? (countByProvince[selected] ?? 0) : 0;
    const selectedLabel = selected
        ? selected.charAt(0) + selected.slice(1).toLowerCase()
        : null;

    return (
        <div className="glass-card rounded-2xl p-4 mb-6">
            <div className="flex items-center justify-between mb-3">
                <div>
                    <h3 className="font-display text-headline-sm text-on-surface">Sebaran Wilayah Asal</h3>
                    <p className="text-xs text-on-surface-variant">Klik provinsi untuk lihat rincian per kota/kabupaten</p>
                </div>
                {selected && (
                    <button onClick={() => setSelected(null)}
                        className="text-xs font-bold px-3 py-1.5 rounded-lg bg-surface-container text-on-surface-variant hover:bg-white/60 transition-colors flex items-center gap-1">
                        <Icon name="close" className="text-sm" /> Tutup Rincian
                    </button>
                )}
            </div>

            <div className="flex flex-col lg:flex-row gap-4">
                <div className="flex-1 min-w-0">
                    <ComposableMap
                        projection="geoMercator"
                        projectionConfig={{ center: [118, -2], scale: 1100 }}
                        style={{ width: '100%', height: 'auto', maxHeight: 420 }}
                    >
                        <Geographies geography={geoData as unknown as object}>
                            {({ geographies }) =>
                                geographies.map(geo => {
                                    const stateName = geo.properties.state as string;
                                    const provinceName = GEO_STATE_TO_PROVINCE[stateName];
                                    const key = provinceName ? resolveProvinceKey(provinceName) : null;
                                    const cnt = key ? (countByProvince[key] ?? 0) : 0;
                                    const ratio = cnt / maxCount;

                                    return (
                                        <Geography
                                            key={geo.rsmKey}
                                            geography={geo}
                                            onClick={() => key && cnt > 0 && setSelected(key === selected ? null : key)}
                                            style={{
                                                default: {
                                                    fill: colorForRatio(ratio),
                                                    stroke: '#fff',
                                                    strokeWidth: 0.5,
                                                    outline: 'none',
                                                    cursor: cnt > 0 ? 'pointer' : 'default',
                                                },
                                                hover: {
                                                    fill: cnt > 0 ? '#2563eb' : colorForRatio(ratio),
                                                    stroke: '#fff',
                                                    strokeWidth: 0.5,
                                                    outline: 'none',
                                                },
                                                pressed: {
                                                    fill: '#1e3a8a',
                                                    stroke: '#fff',
                                                    strokeWidth: 0.5,
                                                    outline: 'none',
                                                },
                                            }}
                                        />
                                    );
                                })
                            }
                        </Geographies>
                    </ComposableMap>
                </div>

                <div className="lg:w-64 flex-shrink-0">
                    {selected ? (
                        <div>
                            <p className="text-xs font-black uppercase tracking-wider text-on-surface-variant mb-1">{selectedLabel}</p>
                            <p className="text-2xl font-black text-primary-container mb-3">{selectedTotal} orang</p>
                            <div className="space-y-1.5 max-h-64 overflow-y-auto">
                                {regenciesForSelected.map(r => (
                                    <div key={r.kota} className="flex items-center justify-between text-xs">
                                        <span className="text-on-surface-variant">{r.kota}</span>
                                        <span className="font-bold text-on-surface">{r.cnt}</span>
                                    </div>
                                ))}
                                {regenciesForSelected.length === 0 && (
                                    <p className="text-xs text-on-surface-variant italic">Tidak ada data kota tercatat.</p>
                                )}
                            </div>
                        </div>
                    ) : (
                        <div>
                            <p className="text-xs font-black uppercase tracking-wider text-on-surface-variant mb-2">Provinsi Teratas</p>
                            <div className="space-y-1.5">
                                {provinces.slice(0, 8).map(p => {
                                    const label = p.name.charAt(0) + p.name.slice(1).toLowerCase();
                                    return (
                                        <button key={p.name} onClick={() => setSelected(resolveProvinceKey(p.name))}
                                            className="w-full flex items-center justify-between text-xs hover:bg-white/50 rounded-lg px-1.5 py-1 transition-colors">
                                            <span className="text-on-surface-variant">{label}</span>
                                            <span className="font-bold text-on-surface">{p.cnt}</span>
                                        </button>
                                    );
                                })}
                                {provinces.length === 0 && (
                                    <p className="text-xs text-on-surface-variant italic">Belum ada data wilayah.</p>
                                )}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
