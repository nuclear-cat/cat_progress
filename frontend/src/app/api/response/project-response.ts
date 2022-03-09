export interface ProjectResponse {
    success: boolean;
    project: {
        id: string;
        title: string;
        description: string;
        color: string;
    }
}
