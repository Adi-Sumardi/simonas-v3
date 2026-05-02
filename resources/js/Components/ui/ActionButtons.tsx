import { Icon } from './Icon';

interface ActionButtonsProps {
    onEdit?: () => void;
    onDelete?: () => void;
    onView?: () => void;
    className?: string;
    variant?: 'ghost' | 'filled' | 'glass';
}

export function ActionButtons({
    onEdit,
    onDelete,
    onView,
    className = '',
    variant = 'glass'
}: ActionButtonsProps) {
    const baseClass = "w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200";
    
    const variants = {
        ghost: "text-on-surface-variant hover:bg-black/5",
        filled: "bg-surface-container text-on-surface-variant hover:bg-primary-container hover:text-primary",
        glass: "bg-white/50 backdrop-blur-sm border border-white/30 text-on-surface-variant shadow-sm"
    };

    const vClass = variants[variant];

    return (
        <div className={`flex items-center gap-1.5 ${className}`}>
            {onView && (
                <button 
                    onClick={(e) => { e.stopPropagation(); onView(); }}
                    className={`${baseClass} ${vClass} hover:text-blue-600 hover:bg-blue-50 hover:border-blue-200`}
                    title="Lihat Detail"
                >
                    <Icon name="visibility" className="text-sm" />
                </button>
            )}
            
            {onEdit && (
                <button 
                    onClick={(e) => { e.stopPropagation(); onEdit(); }}
                    className={`${baseClass} ${vClass} hover:text-amber-600 hover:bg-amber-50 hover:border-amber-200`}
                    title="Edit Data"
                >
                    <Icon name="edit" className="text-sm" />
                </button>
            )}

            {onDelete && (
                <button 
                    onClick={(e) => { e.stopPropagation(); onDelete(); }}
                    className={`${baseClass} ${vClass} hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200`}
                    title="Hapus Data"
                >
                    <Icon name="delete" className="text-sm" />
                </button>
            )}
        </div>
    );
}
