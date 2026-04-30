interface ProgressBarProps {
    value: number;  // 0-100
    label?: string;
    showValue?: boolean;
    color?: 'primary' | 'success' | 'warning' | 'error';
    className?: string;
}

const colorMap = {
    primary: 'bg-primary-container shadow-[0_0_8px_rgba(37,99,235,0.4)]',
    success:  'bg-success',
    warning:  'bg-warning',
    error:    'bg-error',
};

export function ProgressBar({
    value,
    label,
    showValue = false,
    color = 'primary',
    className = '',
}: ProgressBarProps) {
    const clamped = Math.min(100, Math.max(0, value));
    return (
        <div className={className}>
            {(label || showValue) && (
                <div className="flex justify-between items-center mb-1.5">
                    {label && <span className="text-body-sm text-on-surface-variant">{label}</span>}
                    {showValue && <span className="text-label-caps font-bold text-primary-container">{clamped}%</span>}
                </div>
            )}
            <div className="w-full bg-surface-container rounded-full h-2 overflow-hidden">
                <div
                    className={`h-2 rounded-full transition-all duration-700 ${colorMap[color]}`}
                    style={{ width: `${clamped}%` }}
                />
            </div>
        </div>
    );
}
