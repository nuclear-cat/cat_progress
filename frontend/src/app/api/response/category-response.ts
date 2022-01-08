export interface CategoryResponse {
    success: boolean;
    category: {
        id: string;
        title: string;
        description: string;
        color: string;
    }
}
