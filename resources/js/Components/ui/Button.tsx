import { ButtonHTMLAttributes, forwardRef } from 'react';
import { cn } from '@/lib/utils';

type Variant = 'primary' | 'secondary' | 'ghost' | 'destructive';
type Size = 'sm' | 'md' | 'lg';

interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
    variant?: Variant;
    size?: Size;
    fullWidth?: boolean;
}

const variants: Record<Variant, string> = {
    primary: 'bg-primary-container text-on-primary hover:shadow-glow',
    secondary: 'bg-white/60 backdrop-blur border-2 border-primary-container text-primary-container hover:bg-white/80',
    ghost: 'bg-transparent text-on-surface-variant hover:bg-surface-container',
    destructive: 'bg-error text-white hover:shadow-[0_8px_24px_rgba(186,26,26,0.35)]',
};

const sizes: Record<Size, string> = {
    sm: 'px-3 py-1.5 text-body-sm',
    md: 'px-4 py-2 text-body-md',
    lg: 'px-6 py-3 text-body-md',
};

export const Button = forwardRef<HTMLButtonElement, ButtonProps>(
    ({ variant = 'primary', size = 'md', fullWidth, className, ...rest }, ref) => (
        <button
            ref={ref}
            className={cn(
                'rounded-lg font-medium transition-all inline-flex items-center justify-center gap-2',
                'disabled:opacity-50 disabled:cursor-not-allowed',
                variants[variant],
                sizes[size],
                fullWidth && 'w-full',
                className,
            )}
            {...rest}
        />
    ),
);
Button.displayName = 'Button';
