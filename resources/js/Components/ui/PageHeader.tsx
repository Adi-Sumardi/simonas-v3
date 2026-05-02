import { ReactNode } from 'react';
import { Icon } from '@/Components/ui/Icon';
import { Link } from '@inertiajs/react';

interface BreadcrumbItem {
    label: string;
    href?: string;
}

interface PageHeaderProps {
    title: string;
    subtitle?: string;
    breadcrumbs?: BreadcrumbItem[];
    actions?: ReactNode;
}

export function PageHeader({ title, subtitle, breadcrumbs, actions }: PageHeaderProps) {
    return (
        <header className="mb-6 lg:mb-10">
            {breadcrumbs && breadcrumbs.length > 0 && (
                <nav className="flex items-center gap-1.5 text-body-sm text-on-surface-variant mb-4 lg:mb-2 overflow-x-auto no-scrollbar whitespace-nowrap" aria-label="Breadcrumb">
                    {breadcrumbs.map((crumb, i) => (
                        <span key={i} className="flex items-center gap-1.5 flex-shrink-0">
                            {i > 0 && <Icon name="chevron_right" className="text-base opacity-50" />}
                            {crumb.href ? (
                                <Link href={crumb.href} className="hover:text-primary-container transition-colors">
                                    {crumb.label}
                                </Link>
                            ) : (
                                <span className="text-on-surface">{crumb.label}</span>
                            )}
                        </span>
                    ))}
                </nav>
            )}
            <div className="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                <div className="flex-1 min-w-0">
                    <h1 className="font-display text-2xl sm:text-display-md lg:text-display-lg text-primary leading-tight break-words">
                        {title}
                    </h1>
                    {subtitle && (
                        <p className="font-body text-body-md text-secondary mt-2 sm:mt-1 max-w-2xl">
                            {subtitle}
                        </p>
                    )}
                </div>
                {actions && (
                    <div className="flex items-center gap-3 flex-shrink-0 mt-2 sm:mt-0">
                        {actions}
                    </div>
                )}
            </div>
        </header>
    );
}
