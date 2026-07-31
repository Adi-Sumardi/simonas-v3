const ADMIN_WHATSAPP = '6285121379697';

export function adminWhatsappUrl(message: string): string {
    return `https://wa.me/${ADMIN_WHATSAPP}?text=${encodeURIComponent(message)}`;
}

export const ERROR_REPORT_WHATSAPP_URL = adminWhatsappUrl(
    [
        'Ada error di Aplikasi SIMONAS',
        '- Jelaskan errornya:',
        '- Menu apa:',
        '- Screenshoot errornya:',
    ].join('\n')
);
