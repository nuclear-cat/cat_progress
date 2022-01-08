import * as moment from "moment";

export interface CalendarResponse {
    success: boolean;
    weeks: {
        date: moment.Moment;
        habits: {
            id: string;
            title: string;
            description: string | null;
            completions: {
                id: string;
                completedAt: moment.Moment;
            }[];
        }[];
    }[][],
}
