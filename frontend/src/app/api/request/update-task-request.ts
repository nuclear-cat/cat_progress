export interface UpdateTaskRequest {
    title: string;
    description: string | null;
    projectId: string | null;
}
