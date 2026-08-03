import { useEffect, useState } from 'react';

export function useProvinces() {
    const [provinces, setProvinces] = useState<string[]>([]);

    useEffect(() => {
        fetch('/wilayah/provinces')
            .then(r => r.json())
            .then((data: Record<string, string>) => setProvinces(Object.values(data)));
    }, []);

    return provinces;
}

export function useRegencies(province: string) {
    const [regencies, setRegencies] = useState<string[]>([]);

    useEffect(() => {
        if (!province) {
            setRegencies([]);
            return;
        }
        fetch(`/wilayah/regencies?province=${encodeURIComponent(province)}`)
            .then(r => r.json())
            .then(setRegencies);
    }, [province]);

    return regencies;
}
