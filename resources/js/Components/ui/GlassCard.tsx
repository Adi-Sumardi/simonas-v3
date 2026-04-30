import { ReactNode } from 'react';

interface GlassCardProps {
    children: ReactNode;
    className?: string;
    as?: 'div' | 'section' | 'article';
}

export function GlassCard({ children, className = '', as: Tag = 'div' }: GlassCardProps) {
    return (
        <Tag className={`glass-card p-md ${className}`}>
            {children}
        </Tag>
    );
}
