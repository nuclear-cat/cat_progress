export interface ProjectsResponse {
    success: boolean;
    projects: {
        id: string;
        title: string;
        description: string | null;
        color: string;
    }[];
}
