import { Modal } from './Modal';
import { Icon } from './Icon';

interface ConfirmDialogProps {
    open: boolean;
    onClose: () => void;
    onConfirm: () => void;
    title?: string;
    message?: string;
    confirmText?: string;
    cancelText?: string;
    type?: 'danger' | 'warning' | 'info' | 'success';
    loading?: boolean;
}

export function ConfirmDialog({
    open,
    onClose,
    onConfirm,
    title = 'Konfirmasi',
    message = 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
    confirmText = 'Ya, Lanjutkan',
    cancelText = 'Batal',
    type = 'danger',
    loading = false
}: ConfirmDialogProps) {
    const typeStyles = {
        danger: {
            bg: 'bg-rose-50 dark:bg-rose-900/20',
            icon: 'text-rose-600 dark:text-rose-400',
            btn: 'bg-rose-600 hover:bg-rose-700 text-white',
            iconName: 'delete_forever'
        },
        warning: {
            bg: 'bg-amber-50 dark:bg-amber-900/20',
            icon: 'text-amber-600 dark:text-amber-400',
            btn: 'bg-amber-600 hover:bg-amber-700 text-white',
            iconName: 'warning'
        },
        info: {
            bg: 'bg-blue-50 dark:bg-blue-900/20',
            icon: 'text-blue-600 dark:text-blue-400',
            btn: 'bg-blue-600 hover:bg-blue-700 text-white',
            iconName: 'info'
        },
        success: {
            bg: 'bg-emerald-50 dark:bg-emerald-900/20',
            icon: 'text-emerald-600 dark:text-emerald-400',
            btn: 'bg-emerald-600 hover:bg-emerald-700 text-white',
            iconName: 'check_circle'
        }
    };

    const style = typeStyles[type];

    return (
        <Modal open={open} onClose={onClose} size="sm">
            <div className="p-6">
                <div className="flex flex-col items-center text-center">
                    <div className={`w-16 h-16 rounded-2xl ${style.bg} flex items-center justify-center mb-4 animate-in zoom-in duration-300`}>
                        <Icon name={style.iconName} className={`text-4xl ${style.icon}`} filled />
                    </div>
                    
                    <h3 className="text-xl font-bold text-on-surface mb-2 font-display">
                        {title}
                    </h3>
                    
                    <p className="text-sm text-on-surface-variant leading-relaxed px-4">
                        {message}
                    </p>
                </div>

                <div className="flex flex-col sm:flex-row gap-3 mt-8">
                    <button
                        type="button"
                        onClick={onClose}
                        disabled={loading}
                        className="flex-1 px-5 py-3 rounded-2xl font-bold text-sm bg-surface-container text-on-surface-variant hover:bg-black/10 transition-all disabled:opacity-50"
                    >
                        {cancelText}
                    </button>
                    <button
                        type="button"
                        onClick={onConfirm}
                        disabled={loading}
                        className={`flex-1 px-5 py-3 rounded-2xl font-bold text-sm ${style.btn} shadow-lg shadow-black/10 hover:shadow-xl hover:-translate-y-0.5 transition-all disabled:opacity-50 flex items-center justify-center gap-2`}
                    >
                        {loading && (
                            <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                        )}
                        {confirmText}
                    </button>
                </div>
            </div>
        </Modal>
    );
}
