import { useState, useEffect, createContext, useContext, ReactNode } from 'react';
import { usePage } from '@inertiajs/react';
import { Icon } from './Icon';
import { createPortal } from 'react-dom';

type ToastType = 'success' | 'error' | 'info' | 'warning';

interface ToastMessage {
    id: number;
    type: ToastType;
    message: string;
}

interface ToastContextType {
    toast: (message: string, type?: ToastType) => void;
}

const ToastContext = createContext<ToastContextType | undefined>(undefined);

export function useToast() {
    const context = useContext(ToastContext);
    if (!context) throw new Error('useToast must be used within a ToastProvider');
    return context;
}

export function ToastProvider({ children }: { children: ReactNode }) {
    const [toasts, setToasts] = useState<ToastMessage[]>([]);
    const { flash } = usePage().props as any;

    const addToast = (message: string, type: ToastType = 'success') => {
        const id = Date.now();
        setToasts(prev => [...prev, { id, type, message }]);
        setTimeout(() => removeToast(id), 5000);
    };

    const removeToast = (id: number) => {
        setToasts(prev => prev.filter(t => t.id !== id));
    };

    // Watch for Inertia flash messages
    useEffect(() => {
        if (flash?.success) addToast(flash.success, 'success');
        if (flash?.error)   addToast(flash.error, 'error');
        if (flash?.message) addToast(flash.message, 'info');
    }, [flash]);

    return (
        <ToastContext.Provider value={{ toast: addToast }}>
            {children}
            {createPortal(
                <div className="fixed top-4 right-4 z-[99999] flex flex-col gap-3 w-full max-w-[400px] pointer-events-none px-4">
                    {toasts.map(t => (
                        <ToastItem key={t.id} toast={t} onRemove={() => removeToast(t.id)} />
                    ))}
                </div>,
                document.body
            )}
        </ToastContext.Provider>
    );
}

function ToastItem({ toast, onRemove }: { toast: ToastMessage; onRemove: () => void }) {
    const [visible, setVisible] = useState(false);

    useEffect(() => {
        const timer = setTimeout(() => setVisible(true), 10);
        return () => clearTimeout(timer);
    }, []);

    const config = {
        success: { 
            title: 'Berhasil', 
            icon: 'check_circle', 
            color: 'text-emerald-600', 
            bg: 'bg-emerald-50', 
            iconBg: 'bg-emerald-600',
            border: 'border-emerald-100' 
        },
        error: { 
            title: 'Gagal', 
            icon: 'error', 
            color: 'text-rose-600', 
            bg: 'bg-rose-50', 
            iconBg: 'bg-rose-600',
            border: 'border-rose-100' 
        },
        info: { 
            title: 'Info', 
            icon: 'info', 
            color: 'text-blue-600', 
            bg: 'bg-blue-50', 
            iconBg: 'bg-blue-600',
            border: 'border-blue-100' 
        },
        warning: { 
            title: 'Peringatan', 
            icon: 'warning', 
            color: 'text-amber-600', 
            bg: 'bg-amber-50', 
            iconBg: 'bg-amber-600',
            border: 'border-amber-100' 
        },
    }[toast.type];

    return (
        <div 
            className={`
                relative pointer-events-auto flex items-center gap-4 p-3 pr-10 rounded-2xl 
                bg-white border ${config.border} shadow-[0_10px_40px_rgb(0,0,0,0.08)]
                transition-all duration-700 cubic-bezier(0.16, 1, 0.3, 1)
                ${visible ? 'translate-x-0 opacity-100 scale-100' : 'translate-x-full opacity-0 scale-90'}
            `}
        >
            {/* Icon Circle */}
            <div className={`w-10 h-10 rounded-full ${config.iconBg} flex items-center justify-center flex-shrink-0 shadow-lg shadow-black/5 animate-in zoom-in duration-500 delay-100`}>
                <Icon name={config.icon} className="text-white text-xl" filled />
            </div>
            
            <div className="flex flex-col">
                <h4 className={`text-[13px] font-black uppercase tracking-widest ${config.color} leading-none`}>
                    {config.title}
                </h4>
                <p className="text-[13px] text-slate-700 font-bold mt-1 leading-tight">
                    {toast.message}
                </p>
            </div>

            {/* Close Button */}
            <button 
                onClick={(e) => {
                    e.stopPropagation();
                    onRemove();
                }}
                className="absolute top-1/2 -translate-y-1/2 right-2 w-7 h-7 rounded-full flex items-center justify-center text-slate-300 hover:text-slate-900 hover:bg-slate-100 transition-all"
            >
                <Icon name="close" className="text-base" />
            </button>
        </div>
    );
}
