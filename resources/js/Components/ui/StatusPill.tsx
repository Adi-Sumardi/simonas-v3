import { ReactNode } from 'react';

type PillVariant = 'success' | 'warning' | 'danger' | 'info' | 'neutral';

interface StatusPillProps {
    variant?: PillVariant;
    children: ReactNode;
    className?: string;
}

const variantMap: Record<PillVariant, string> = {
    success: 'status-pill-success',
    warning: 'status-pill-warning',
    danger:  'status-pill-danger',
    info:    'status-pill-info',
    neutral: 'status-pill-neutral',
};

// Map hafalan score to variant
export const hafalanScoreVariant: Record<string, PillVariant> = {
    memtas:           'success',
    layak_ulang:      'warning',
    perlu_perbaikan:  'danger',
};

export const hafalanScoreLabel: Record<string, string> = {
    memtas:           'MEMTAS',
    layak_ulang:      'LAYAK ULANG',
    perlu_perbaikan:  'PERLU PERBAIKAN',
};

export function StatusPill({ variant = 'neutral', children, className = '' }: StatusPillProps) {
    return (
        <span className={`${variantMap[variant]} ${className}`}>
            {children}
        </span>
    );
}
