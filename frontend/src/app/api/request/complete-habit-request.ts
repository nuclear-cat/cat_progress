import * as moment from "moment";

export interface CompleteHabitRequest {
    date: moment.Moment;
    completionType: string;
}
