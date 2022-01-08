export interface UpdateHabitRequest {
    title: string;
    description: string | null;
    weekdays: string[];
    categoryId: string;
    points: number;
}
