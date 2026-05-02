interface ProgressDonutProps {
    value: number;      // 0-100
    size?: number;      // px, default 128
    strokeWidth?: number;
    label?: string;     // center bottom label
    className?: string;
}

export function ProgressDonut({
    value,
    size = 128,
    strokeWidth = 8,
    label,
    className = '',
}: ProgressDonutProps) {
    const r = (size - strokeWidth) / 2;
    const circumference = 2 * Math.PI * r;
    const offset = circumference - (value / 100) * circumference;
    const cx = size / 2;
    const cy = size / 2;

    return (
        <div className={`relative inline-block ${className}`} style={{ width: size, height: size }}>
            <svg
                width={size}
                height={size}
                viewBox={`0 0 ${size} ${size}`}
                className="transform -rotate-90"
                aria-hidden
            >
                {/* Track */}
                <circle
                    cx={cx} cy={cy} r={r}
                    fill="transparent"
                    stroke="currentColor"
                    strokeWidth={strokeWidth}
                    className="text-surface-container-high"
                />
                {/* Progress */}
                <circle
                    cx={cx} cy={cy} r={r}
                    fill="transparent"
                    stroke="currentColor"
                    strokeWidth={strokeWidth}
                    strokeDasharray={circumference}
                    strokeDashoffset={offset}
                    strokeLinecap="round"
                    className="text-primary-container transition-all duration-700"
                />
            </svg>
            {/* Center label */}
            <div className="absolute inset-0 flex flex-col items-center justify-center">
                <span 
                    className="font-extrabold text-primary-container leading-none"
                    style={{ fontSize: `${Math.max(10, size * 0.2)}px` }}
                >
                    {value}%
                </span>
                {label && size > 80 && (
                    <span className="text-[10px] uppercase font-bold text-secondary text-center leading-tight mt-0.5 px-2">
                        {label}
                    </span>
                )}
            </div>
        </div>
    );
}
