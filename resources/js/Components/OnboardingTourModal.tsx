import { useState } from 'react';
import { Modal } from '@/Components/ui/Modal';
import { Button } from '@/Components/ui/Button';
import { Icon } from '@/Components/ui/Icon';
import { FEATURES_BY_ROLE, Role } from '@/lib/dashboardFeatures';

interface Props {
    open: boolean;
    onClose: () => void;
    role: Role;
}

export function OnboardingTourModal({ open, onClose, role }: Props) {
    const slides = FEATURES_BY_ROLE[role] ?? FEATURES_BY_ROLE.mahasiswa;
    const [index, setIndex] = useState(0);
    const isLast = index === slides.length - 1;
    const slide = slides[index];

    function handleClose() {
        setIndex(0);
        onClose();
    }

    function next() {
        if (isLast) {
            handleClose();
        } else {
            setIndex(i => i + 1);
        }
    }

    return (
        <Modal open={open} onClose={handleClose} title="Kenalan dengan SIMONAS" icon="explore" size="sm">
            <div className="flex flex-col items-center text-center py-2">
                <div className="w-16 h-16 rounded-2xl bg-primary-container flex items-center justify-center mb-4 shadow-glow">
                    <Icon name={slide.icon} className="text-3xl text-white" filled />
                </div>
                <h3 className="font-bold text-lg text-on-surface mb-2">{slide.title}</h3>
                <p className="text-sm text-on-surface-variant leading-relaxed mb-6">{slide.desc}</p>

                <div className="flex items-center gap-1.5 mb-6">
                    {slides.map((_, i) => (
                        <div key={i} className={`h-1.5 rounded-full transition-all ${i === index ? 'w-6 bg-primary-container' : 'w-1.5 bg-surface-container'}`} />
                    ))}
                </div>

                <div className="flex items-center gap-3 w-full">
                    {!isLast && (
                        <button onClick={handleClose} className="text-sm font-bold text-on-surface-variant px-4 py-2.5 hover:text-on-surface transition-colors">
                            Lewati
                        </button>
                    )}
                    <Button fullWidth onClick={next}>
                        {isLast ? 'Mulai Pakai SIMONAS' : 'Lanjut'}
                        <Icon name={isLast ? 'check' : 'arrow_forward'} />
                    </Button>
                </div>
            </div>
        </Modal>
    );
}
