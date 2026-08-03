import { Head, router, Link } from '@inertiajs/react';
import { useState } from 'react';
import { AppLayout } from '@/Layouts/AppLayout';
import { PageHeader } from '@/Components/ui/PageHeader';
import { Icon } from '@/Components/ui/Icon';

interface Member { val: string; cnt: number }
interface Group { suggested: string; members: Member[]; total_users: number }
interface Props {
    field: 'universitas' | 'prodi';
    groups: Group[];
    totalDistinct: number;
}

const FIELD_LABEL = { universitas: 'Universitas', prodi: 'Jurusan/Prodi' } as const;

export default function DataCleanup({ field, groups, totalDistinct }: Props) {
    return (
        <AppLayout>
            <Head title={`Bersihkan Duplikat ${FIELD_LABEL[field]}`} />
            <PageHeader
                title={`Bersihkan Duplikat ${FIELD_LABEL[field]}`}
                subtitle={`${totalDistinct} nama unik ditemukan, ${groups.length} kelompok kemungkinan duplikat disarankan. Ini isian bebas dari user, jadi banyak variasi ejaan/singkatan — cek tiap kelompok sebelum digabung.`}
                breadcrumbs={[
                    { label: 'Dashboard', href: '/dashboard' },
                    { label: 'Data Master', href: '/super/data-master' },
                    { label: `Bersihkan ${FIELD_LABEL[field]}` },
                ]}
            />

            <div className="flex gap-2 mb-6 p-1.5 glass-panel rounded-full w-fit">
                {(['universitas', 'prodi'] as const).map(f => (
                    <Link key={f} href={`/super/data-master/cleanup?field=${f}`}
                        className={`px-4 py-1.5 rounded-full text-xs font-bold transition-all ${field === f ? 'bg-on-surface text-white' : 'text-on-surface-variant hover:bg-white/60'}`}>
                        {FIELD_LABEL[f]}
                    </Link>
                ))}
            </div>

            {groups.length === 0 && (
                <div className="glass-card rounded-2xl py-12 flex flex-col items-center gap-2 text-on-surface-variant">
                    <Icon name="task_alt" className="text-4xl opacity-30" />
                    <p className="text-sm font-semibold">Tidak ada kelompok duplikat yang terdeteksi.</p>
                </div>
            )}

            <div className="space-y-4">
                {groups.map(g => <GroupCard key={g.members.map(m => m.val).join('|')} field={field} group={g} />)}
            </div>
        </AppLayout>
    );
}

function GroupCard({ field, group }: { field: 'universitas' | 'prodi'; group: Group }) {
    const [checked, setChecked] = useState<Record<string, boolean>>(
        Object.fromEntries(group.members.map(m => [m.val, true]))
    );
    const [canonical, setCanonical] = useState(group.suggested);
    const [processing, setProcessing] = useState(false);

    const selectedValues = group.members.filter(m => checked[m.val]).map(m => m.val);

    function toggle(val: string) {
        setChecked(prev => ({ ...prev, [val]: !prev[val] }));
    }

    function merge() {
        if (selectedValues.length < 2 || !canonical.trim()) return;
        setProcessing(true);
        router.post('/super/data-master/cleanup/merge', {
            field, values: selectedValues, canonical: canonical.trim(),
        }, { preserveScroll: true, onFinish: () => setProcessing(false) });
    }

    return (
        <div className="glass-card rounded-2xl p-4">
            <div className="flex flex-wrap gap-2 mb-3">
                {group.members.map(m => (
                    <button key={m.val} onClick={() => toggle(m.val)}
                        className={`flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold transition-colors border ${
                            checked[m.val]
                                ? 'bg-primary-container/10 border-primary-container text-primary-container'
                                : 'bg-surface-container/40 border-transparent text-on-surface-variant line-through opacity-60'
                        }`}>
                        <Icon name={checked[m.val] ? 'check_box' : 'check_box_outline_blank'} className="text-base" />
                        {m.val}
                        <span className="text-[10px] opacity-70">({m.cnt})</span>
                    </button>
                ))}
            </div>
            <div className="flex flex-wrap items-center gap-2">
                <span className="text-xs text-on-surface-variant whitespace-nowrap">Gabung jadi:</span>
                <input value={canonical} onChange={e => setCanonical(e.target.value)}
                    className="glass-input text-sm py-1.5 flex-1 min-w-[200px]" />
                <button onClick={merge} disabled={processing || selectedValues.length < 2 || !canonical.trim()}
                    className="text-xs font-bold px-4 py-2 rounded-lg bg-primary-container text-white hover:opacity-90 transition-opacity disabled:opacity-40 whitespace-nowrap">
                    Gabungkan {selectedValues.length} nama
                </button>
            </div>
        </div>
    );
}
