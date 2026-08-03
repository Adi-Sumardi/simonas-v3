interface WatermarkOptions {
    lines: string[];
}

/**
 * Bakar teks watermark (jam + lokasi) ke pixel foto lewat canvas, supaya
 * tidak bisa dihapus/diedit user setelah difoto. Mengembalikan File JPEG baru.
 */
export function watermarkPhoto(file: File, options: WatermarkOptions): Promise<File> {
    return new Promise((resolve, reject) => {
        const img = new Image();
        const url = URL.createObjectURL(file);

        img.onload = () => {
            URL.revokeObjectURL(url);

            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            if (!ctx) return reject(new Error('Canvas tidak didukung'));

            ctx.drawImage(img, 0, 0);

            const fontSize = Math.max(16, Math.round(canvas.width * 0.028));
            const padding = Math.round(fontSize * 0.6);
            const lineHeight = Math.round(fontSize * 1.35);
            const barHeight = padding * 2 + lineHeight * options.lines.length;

            ctx.fillStyle = 'rgba(0, 0, 0, 0.55)';
            ctx.fillRect(0, canvas.height - barHeight, canvas.width, barHeight);

            ctx.fillStyle = '#ffffff';
            ctx.font = `700 ${fontSize}px -apple-system, BlinkMacSystemFont, sans-serif`;
            ctx.textBaseline = 'top';
            options.lines.forEach((line, i) => {
                ctx.fillText(line, padding, canvas.height - barHeight + padding + i * lineHeight);
            });

            canvas.toBlob(
                (blob) => {
                    if (!blob) return reject(new Error('Gagal membuat gambar'));
                    resolve(new File([blob], file.name.replace(/\.\w+$/, '.jpg'), { type: 'image/jpeg' }));
                },
                'image/jpeg',
                0.85,
            );
        };

        img.onerror = () => {
            URL.revokeObjectURL(url);
            reject(new Error('Gagal memuat foto'));
        };

        img.src = url;
    });
}
