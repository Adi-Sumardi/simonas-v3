import { useEffect } from 'react';

interface Reminder {
    id: number | string;
    title: string;
    date: string;
    time: string;
    type: string;
    completed?: boolean;
    lastRemindedAt?: number;
    missedNotified?: boolean;
}

export function ReminderManager() {
    useEffect(() => {
        const checkReminders = () => {
            const data = localStorage.getItem('simonas_reminders');
            if (!data) return;

            let reminders: Reminder[] = JSON.parse(data);
            const now = new Date();
            const nowTime = now.getTime();
            let changed = false;

            reminders = reminders.map(r => {
                // Parse event time
                const [y, m, d] = r.date.split('-').map(Number);
                const [hh, mm] = r.time.split(':').map(Number);
                const eventDate = new Date(y, m - 1, d, hh, mm);
                const eventTime = eventDate.getTime();
                const diff = eventTime - nowTime;

                // 1. Logic for SHALAT reminders
                if (r.type === 'shalat' && !r.completed) {
                    
                    // A. Periodic reminder every 5 minutes if past due
                    if (diff <= 0) {
                        const fiveMin = 5 * 60 * 1000;
                        const lastReminded = r.lastRemindedAt || 0;
                        
                        // If more than 5 mins since last reminder
                        if (nowTime - lastReminded >= fiveMin) {
                            if (Notification.permission === 'granted') {
                                new Notification('Pengingat Sholat', {
                                    body: `Anda belum ceklis sholat ${r.title}. Ayo segera sholat!`,
                                    icon: '/favicon.ico',
                                });
                                changed = true;
                                return { ...r, lastRemindedAt: nowTime };
                            }
                        }
                    }

                    // B. Missed notification (e.g. 2 hours after or if next prayer is coming?)
                    // Let's use a 90 minute window for "Missed" notification
                    const missedWindow = 90 * 60 * 1000;
                    if (diff < -missedWindow && !r.missedNotified) {
                        if (Notification.permission === 'granted') {
                            new Notification('Peringatan Sholat', {
                                body: `Waktu sholat ${r.title} telah terlewat. Anda tidak sholat tepat waktu.`,
                                icon: '/favicon.ico',
                            });
                            changed = true;
                            return { ...r, missedNotified: true };
                        }
                    }
                }

                // 2. Logic for REGULAR reminders (once at start)
                if (r.type !== 'shalat') {
                    if (diff <= 0 && diff > -30000 && !r.lastRemindedAt) {
                        if (Notification.permission === 'granted') {
                            new Notification('Pengingat Kegiatan', {
                                body: `Kegiatan "${r.title}" dimulai sekarang!`,
                                icon: '/favicon.ico',
                            });
                            changed = true;
                            return { ...r, lastRemindedAt: nowTime };
                        }
                    }
                }

                return r;
            });

            if (changed) {
                localStorage.setItem('simonas_reminders', JSON.stringify(reminders));
            }
        };

        const interval = setInterval(checkReminders, 30000); // Check every 30s
        return () => clearInterval(interval);
    }, []);

    return null;
}
