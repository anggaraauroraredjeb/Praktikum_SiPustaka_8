export type AppUser = {
    id: number;
    name: string;
    email: string;
    role: 'admin' | 'user';
};

export type SharedProps = {
    auth: { user: AppUser | null };
    navigation: { label: string; href: string }[];
    flash: { success?: string | null };
    errors: Record<string, string>;
};