/**
 * Modal — Reusable modal via React Portal.
 * Renders directly into document.body so it's never clipped by overflow:hidden parents.
 *
 * Usage:
 *   <Modal open={open} onClose={close} title="Judul" size="md">
 *     ... content ...
 *   </Modal>
 */
import { useEffect, useRef } from 'react';
import { createPortal } from 'react-dom';
import { Icon } from '@/Components/ui/Icon';

type ModalSize = 'sm' | 'md' | 'lg' | 'xl' | 'full';

interface ModalProps {
    open: boolean;
    onClose: () => void;
    title?: React.ReactNode;
    /** Icon name (Material Symbols) shown left of title */
    icon?: string;
    children: React.ReactNode;
    /** Footer slot — rendered below a divider */
    footer?: React.ReactNode;
    size?: ModalSize;
    /** If true, clicking the backdrop does NOT close the modal */
    disableBackdropClose?: boolean;
    /** Extra classes for the inner panel */
    className?: string;
}

const SIZE_MAP: Record<ModalSize, string> = {
    sm:   'max-w-sm',
    md:   'max-w-lg',
    lg:   'max-w-2xl',
    xl:   'max-w-4xl',
    full: 'max-w-[95vw]',
};

export function Modal({
    open,
    onClose,
    title,
    icon,
    children,
    footer,
    size = 'md',
    disableBackdropClose = false,
    className = '',
}: ModalProps) {
    const panelRef = useRef<HTMLDivElement>(null);

    // Lock body scroll while open
    useEffect(() => {
        if (open) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
        return () => { document.body.style.overflow = ''; };
    }, [open]);

    // Close on Escape
    useEffect(() => {
        if (!open) return;
        function onKey(e: KeyboardEvent) { if (e.key === 'Escape') onClose(); }
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, [open, onClose]);

    if (!open) return null;

    return createPortal(
        <div
            className="fixed inset-0 z-[9999] flex items-center justify-center p-4 lg:pl-64 lg:pr-64"
            aria-modal="true"
            role="dialog"
        >
            {/* Backdrop */}
            <div
                className="absolute inset-0 bg-black/50 backdrop-blur-sm"
                onClick={disableBackdropClose ? undefined : onClose}
            />

            {/* Panel */}
            <div
                ref={panelRef}
                className={[
                    'relative w-full rounded-2xl shadow-2xl',
                    'bg-white/95 dark:bg-surface-container',
                    'max-h-[90vh] flex flex-col',
                    'animate-modal-in',
                    SIZE_MAP[size],
                    className,
                ].join(' ')}
                onClick={e => e.stopPropagation()}
            >
                {/* Header */}
                {(title !== undefined) && (
                    <div className="flex items-center justify-between px-6 py-4 border-b border-black/10 flex-shrink-0">
                        <h2 className="font-bold text-on-surface flex items-center gap-2 text-base">
                            {icon && <Icon name={icon} className="text-primary text-xl" filled />}
                            {title}
                        </h2>
                        <button
                            onClick={onClose}
                            aria-label="Tutup"
                            className="w-8 h-8 rounded-lg hover:bg-black/10 flex items-center justify-center transition-colors text-on-surface-variant"
                        >
                            <Icon name="close" className="text-base" />
                        </button>
                    </div>
                )}

                {/* Body (scrollable) */}
                <div className="flex-1 overflow-y-auto px-6 py-5 overscroll-contain">
                    {children}
                </div>

                {/* Footer */}
                {footer && (
                    <div className="flex-shrink-0 px-6 py-4 border-t border-black/10 flex items-center justify-end gap-3">
                        {footer}
                    </div>
                )}
            </div>
        </div>,
        document.body,
    );
}
