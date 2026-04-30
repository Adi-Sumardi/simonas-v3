import { InputHTMLAttributes, forwardRef, ReactNode } from 'react';
import { cn } from '@/lib/utils';

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
    leftIcon?: ReactNode;
    rightIcon?: ReactNode;
    error?: string;
}

export const Input = forwardRef<HTMLInputElement, InputProps>(
    ({ leftIcon, rightIcon, error, className, ...rest }, ref) => (
        <div className="w-full">
            <div
                className={cn(
                    'glass-input flex items-center gap-2 w-full',
                    error && 'border-error focus-within:border-error focus-within:ring-error/30',
                    className,
                )}
            >
                {leftIcon && <span className="text-on-surface-variant flex-shrink-0">{leftIcon}</span>}
                <input
                    ref={ref}
                    className="flex-1 bg-transparent border-0 outline-none text-on-surface placeholder:text-on-surface-variant/50 py-1"
                    {...rest}
                />
                {rightIcon && <span className="text-on-surface-variant flex-shrink-0">{rightIcon}</span>}
            </div>
            {error && <p className="mt-1 text-body-sm text-error">{error}</p>}
        </div>
    ),
);
Input.displayName = 'Input';
