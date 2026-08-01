import { Icon } from '@/Components/ui/Icon';

interface PaginationProps {
    currentPage: number;
    totalPages: number;
    totalItems: number;
    perPage: number;
    onPageChange: (page: number) => void;
    onPerPageChange: (perPage: number) => void;
    perPageOptions?: number[];
}

export function Pagination({
    currentPage, totalPages, totalItems, perPage,
    onPageChange, onPerPageChange,
    perPageOptions = [10, 25, 50, 100],
}: PaginationProps) {
    const from = totalItems === 0 ? 0 : (currentPage - 1) * perPage + 1;
    const to   = Math.min(currentPage * perPage, totalItems);

    // Smart page numbers: always show first, last, current ± 1, and ellipsis
    function pageNumbers(): (number | '...')[] {
        if (totalPages <= 7) return Array.from({ length: totalPages }, (_, i) => i + 1);
        const pages: (number | '...')[] = [1];
        if (currentPage > 3) pages.push('...');
        for (let p = Math.max(2, currentPage - 1); p <= Math.min(totalPages - 1, currentPage + 1); p++) pages.push(p);
        if (currentPage < totalPages - 2) pages.push('...');
        pages.push(totalPages);
        return pages;
    }

    if (totalItems === 0) return null;

    return (
        <div className="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 py-3 border-t border-white/40 bg-white/10">
            {/* Info + per-page */}
            <div className="flex items-center gap-3 text-sm text-on-surface-variant">
                <span>
                    Menampilkan{' '}
                    <span className="font-black text-on-surface">{from}–{to}</span>
                    {' '}dari{' '}
                    <span className="font-black text-on-surface">{totalItems}</span>
                    {' '}data
                </span>
                <div className="flex items-center gap-1.5">
                    <span className="text-xs">Per halaman:</span>
                    <select
                        value={perPage}
                        onChange={e => onPerPageChange(Number(e.target.value))}
                        className="glass-input text-xs py-1 px-2 min-w-0 w-16"
                    >
                        {perPageOptions.map(n => <option key={n} value={n}>{n}</option>)}
                    </select>
                </div>
            </div>

            {/* Page buttons */}
            {totalPages > 1 && (
                <div className="flex items-center gap-1">
                    {/* Prev */}
                    <button
                        onClick={() => onPageChange(currentPage - 1)}
                        disabled={currentPage === 1}
                        className="w-8 h-8 rounded-lg flex items-center justify-center transition-all disabled:opacity-30 disabled:cursor-not-allowed hover:bg-white/60"
                    >
                        <Icon name="chevron_left" className="text-xl text-on-surface-variant" />
                    </button>

                    {pageNumbers().map((p, i) =>
                        p === '...' ? (
                            <span key={`ellipsis-${i}`} className="w-8 h-8 flex items-center justify-center text-xs text-on-surface-variant select-none">…</span>
                        ) : (
                            <button
                                key={p}
                                onClick={() => onPageChange(p as number)}
                                className={`w-8 h-8 rounded-lg text-xs font-bold transition-all ${
                                    currentPage === p
                                        ? 'bg-primary-container text-white shadow-md shadow-blue-500/20'
                                        : 'text-on-surface-variant hover:bg-white/60'
                                }`}
                            >
                                {p}
                            </button>
                        )
                    )}

                    {/* Next */}
                    <button
                        onClick={() => onPageChange(currentPage + 1)}
                        disabled={currentPage === totalPages}
                        className="w-8 h-8 rounded-lg flex items-center justify-center transition-all disabled:opacity-30 disabled:cursor-not-allowed hover:bg-white/60"
                    >
                        <Icon name="chevron_right" className="text-xl text-on-surface-variant" />
                    </button>
                </div>
            )}
        </div>
    );
}
