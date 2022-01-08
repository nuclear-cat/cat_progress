export interface HabitsResponse {
    success: boolean;
    habits: {
        id: string;
        title: string;
        description: string | null;
    }[];
}
