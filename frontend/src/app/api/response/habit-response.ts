export interface HabitResponse {
    success: boolean;
    habit: {
        id: string;
        title: string;
        description: string | null;
        points: number;
        weekdays: string[];
        category: {
            id: string;
            title: string;
            description: string | null;
        }
    }
}
