import { ReactNode } from 'react';
import { Icon } from '@/Components/ui/Icon';

interface EmptyStateProps {
    icon?: string;
    title: string;
    description?: string;
    action?: ReactNode;
    className?: string;
}

export function EmptyState({ icon = 'inbox', title, description, action, className = '' }: EmptyStateProps) {
    return (
        <div className={`flex flex-col items-center justify-center py-16 text-center ${className}`}>
            <div className="w-20 h-20 rounded-2xl bg-primary-fixed/50 flex items-center justify-center mb-4">
                <Icon name={icon} className="text-4xl text-primary-container/60" />
            </div>
            <h3 className="font-display text-title-sm text-on-surface mb-2">{title}</h3>
            {description && (
                <p className="text-body-sm text-on-surface-variant max-w-sm mb-6">{description}</p>
            )}
            {action && action}
        </div>
    );
}
