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
        <header className="mb-10">
            {breadcrumbs && breadcrumbs.length > 0 && (
                <nav className="flex items-center gap-1.5 text-body-sm text-on-surface-variant mb-2" aria-label="Breadcrumb">
                    {breadcrumbs.map((crumb, i) => (
                        <span key={i} className="flex items-center gap-1.5">
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
            <div className="flex items-start justify-between gap-4">
                <div>
                    <h1 className="font-display text-display-lg text-primary leading-tight">{title}</h1>
                    {subtitle && (
                        <p className="font-body text-body-md text-secondary mt-1">{subtitle}</p>
                    )}
                </div>
                {actions && (
                    <div className="flex items-center gap-3 flex-shrink-0">{actions}</div>
                )}
            </div>
        </header>
    );
}
