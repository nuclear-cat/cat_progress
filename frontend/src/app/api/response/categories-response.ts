export interface CategoriesResponse {
    success: boolean;
    categories: {
        id: string;
        title: string;
        description: string | null;
        color: string;
    }[];
}
