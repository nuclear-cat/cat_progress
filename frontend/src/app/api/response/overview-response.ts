import * as moment from "moment";

export interface OverviewResponse {
    success: boolean;
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
    }[];
    completedTasks: {
        id: string;
        title: string;
        description: string | null;
    }[];
}
