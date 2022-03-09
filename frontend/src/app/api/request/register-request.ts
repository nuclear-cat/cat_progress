export interface RegisterRequest {
    name: string;
    email: string;
    password: string;
    timezone: string;
    target: string | null;
}
