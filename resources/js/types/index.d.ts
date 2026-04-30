export type Role = 'super' | 'admin' | 'mentor' | 'mahasiswa' | 'alumni';

export interface User {
    id: number;
    name: string;
    email: string;
    role: Role;
    avatar?: string | null;
    asrama?: string | null;
}

export interface Flash {
    success?: string | null;
    error?: string | null;
}

export interface SharedProps {
    auth: {
        user: User | null;
    };
    flash: Flash;
    errors: Record<string, string>;
}
