import * as moment from "moment";

export interface OverviewResponse {
    success: boolean;
    projects: {
        id: string;
        title: string;
        description: string | null;
        color: string;
    }[];
    habits: {
        id: string;
        title: string;
        description: string | null;
        completions: {
            id: string,
            completedAt: moment.Moment;
            type: string;
        }[];
    }[];
    activeTasks: {
        id: string;
        title: string;
        description: string | null;
        createdAt: moment.Moment;
        project: {
            id: string,
            title: string,
            description: string | null,
            color: string | null,
        } | null;
        creator: {
            id: string,
            name: string,
            avatarSrc: string,
        },
    }[];
    completedTasks: {
        id: string;
        title: string;
        description: string | null;
    }[];
}
