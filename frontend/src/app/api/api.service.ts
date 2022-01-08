import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Observable} from "rxjs";
import {environment} from "../../environments/environment";
import {map} from "rxjs/operators";
import {UpdateTaskRequest} from "./request/update-task-request";
import {OverviewResponse} from "./response/overview-response";
import {CalendarResponse} from "./response/calendar-response";
import * as moment from "moment";
import {HabitsResponse} from "./response/habits-response";
import {CategoriesResponse} from "./response/categories-response";
import {UpdateHabitRequest} from "./request/update-habit-request";
import {HabitResponse} from "./response/habit-response";
import {CategoryColorsResponse} from "./response/category-colors-response";
import {UpdateCategoryRequest} from "./request/update-category-request";
import {CategoryResponse} from "./response/category-response";
import {AuthResponse} from "./response/auth-response";
import {RegisterRequest} from "./request/register-request";

@Injectable({
    providedIn: 'root'
})
export class ApiService {

    constructor(private httpClient: HttpClient) {
    }

    public login(email: string, password: string): Observable<AuthResponse> {
        return this.httpClient.post<AuthResponse>(environment.apiBaseUrl + '/api/v1/login', {
            email: email,
            password: password,
            device_info: navigator.userAgent,
        });
    }

    public refresh(refreshToken: string) {
        return this.httpClient.post<AuthResponse>(environment.apiBaseUrl + '/api/v1/refresh', {
            refresh_token: refreshToken,
            device_info: navigator.userAgent,
        });
    }

    public getOverview(habitsDate: moment.Moment): Observable<OverviewResponse> {
        return this.httpClient.get<{
            success: boolean,
            active_tasks: [{ id: string, title: string, description: string | null }],
            completed_tasks: [{ id: string, title: string, description: string | null }],
            today_habits: [{ id: string, title: string, description: string | null, completions: any[] }],
        }>(environment.apiBaseUrl + '/api/v1/overview?habits_date='
            + encodeURIComponent(habitsDate.format('YYYY-MM-DD HH:mm:ss.sssZ'))
        ).pipe(map(response => {
            return {
                success: response.success,
                activeTasks: response.active_tasks.map(item => {
                    return {
                        id: item.id,
                        title: item.title,
                        description: item.description,
                    };
                }),
                completedTasks: response.completed_tasks.map(item => {
                    return {
                        id: item.id,
                        title: item.title,
                        description: item.description,
                    };
                }),
                habits: response.today_habits.map(habitItem => {
                    return {
                        id: habitItem.id,
                        title: habitItem.title,
                        description: habitItem.description,
                        completions: habitItem.completions.map(completionItem => {
                            return {
                                id: completionItem.id,
                                completedAt: moment(completionItem.completed_at),
                            };
                        }),
                    };
                }),
            };
        }));
    }

    public getCalendar(): Observable<CalendarResponse> {
        return this.httpClient.get<{
            success: boolean,
            weeks: {
                date: moment.Moment,
                habits: {
                    id: string,
                    title: string,
                    description: string | null,
                    completions: {
                        id: string,
                        completed_at: moment.Moment,
                    }[],
                }[],
            }[][],
        }>(environment.apiBaseUrl + '/api/v1/habit/calendar/2021-12-01').pipe(map(response => {
            return {
                success: response.success,
                weeks: response.weeks.map(weekItem => {
                    return weekItem.map(dayItem => {
                        return {
                            date: moment(dayItem.date),
                            habits: dayItem.habits.map(habitItem => {
                                return {
                                    id: habitItem.id,
                                    title: habitItem.title,
                                    description: habitItem.description,
                                    completions: habitItem.completions.map(completionItem => {
                                        return {
                                            id: completionItem.id,
                                            completedAt: completionItem.completed_at,
                                        };
                                    }),
                                };
                            }),
                        };
                    });
                }),
            };
        }));
    }

    public completeTask(id: string): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/task/' + id + '/complete', null, {});
    }

    public incompleteTask(id: string): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/task/' + id + '/incomplete', null, {});
    }

    public updateTask(id: string, request: UpdateTaskRequest): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/task/' + id + '/update', request, {});
    }

    public createTask(request: UpdateTaskRequest): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/task/create', request, {});
    }

    public deleteTask(id: string): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/task/' + id + '/delete', null, {});
    }

    public getHabits(): Observable<HabitsResponse> {
        return this.httpClient.get<{
            success: boolean,
            habits: {
                id: string,
                title: string,
                description: string | null,
            }[],
        }>(environment.apiBaseUrl + '/api/v1/habit/list');
    }

    public getCategories(): Observable<CategoriesResponse> {
        return this.httpClient.get<any>(environment.apiBaseUrl + '/api/v1/category/list');
    }

    public createHabit(request: UpdateHabitRequest): Observable<any> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/habit/create', {
            title: request.title,
            description: request.description,
            weekdays: request.weekdays,
            category_id: request.categoryId,
            points: request.points,
        }, {});
    }

    public updateHabit(id: string, request: UpdateHabitRequest): Observable<any> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/habit/' + id + '/update', {
            title: request.title,
            description: request.description,
            weekdays: request.weekdays,
            category_id: request.categoryId,
            points: request.points,
        }, {});
    }

    public getHabit(id: string): Observable<HabitResponse> {
        return this.httpClient.get<HabitResponse>(environment.apiBaseUrl + '/api/v1/habit/' + id);
    }

    public deleteHabit(id: string): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/habit/' + id + '/delete', {});
    }

    public getCategoryColors() {
        return this.httpClient.get<CategoryColorsResponse>(environment.apiBaseUrl + '/api/v1/category/color/list');
    }

    public createCategory(request: UpdateCategoryRequest): Observable<{ success: boolean, id: string }> {
        return this.httpClient.post<{ success: boolean, id: string }>(environment.apiBaseUrl + '/api/v1/category/create', {
            title: request.title,
            description: request.description,
            color: request.color,
        }, {});
    }

    public updateCategory(id: string, request: UpdateCategoryRequest): Observable<any> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/category/' + id + '/update', {
            title: request.title,
            description: request.description,
            color: request.color,
        }, {});
    }

    public getCategory(id: string): Observable<CategoryResponse> {
        return this.httpClient.get<CategoryResponse>(environment.apiBaseUrl + '/api/v1/category/' + id);
    }

    public deleteCategory(id: string): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/category/' + id + '/delete', {});
    }

    public completeHabit(id: string, date: moment.Moment): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/habit/' + id + '/complete', {
            completed_at: date.format('YYYY-MM-DD'),
        }, {});
    }

    public incompleteHabit(habitId: string, completionId: string): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/habit/' + habitId + '/incomplete', {
            completion_id: completionId,
        }, {});
    }

    public register(request: RegisterRequest): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/register', {
            name: request.name,
            email: request.email,
            password: request.password,
            timezone: request.timezone,
        }, {});
    }
}
