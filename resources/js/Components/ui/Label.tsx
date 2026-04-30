import { LabelHTMLAttributes } from 'react';
import { cn } from '@/lib/utils';

export function Label({ className, ...rest }: LabelHTMLAttributes<HTMLLabelElement>) {
    return (
        <label
            className={cn('block text-body-sm font-semibold text-on-surface mb-2', className)}
            {...rest}
        />
    );
}
