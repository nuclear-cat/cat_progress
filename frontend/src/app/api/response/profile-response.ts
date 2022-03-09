export interface ProfileResponse {
    success: boolean;
    profile: {
        id: string;
        name: string;
        email: string;
        avatarSrc: string | null;
    }
}
