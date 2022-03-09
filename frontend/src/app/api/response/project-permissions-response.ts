export interface ProjectPermissionsResponse {
    success: boolean;
    permissions: {
        title: string,
        value: string,
    }[],
}
