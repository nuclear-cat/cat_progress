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
import {ColorsResponse} from "./response/colors-response";
import {UpdateCategoryRequest} from "./request/update-category-request";
import {CategoryResponse} from "./response/category-response";
import {AuthResponse} from "./response/auth-response";
import {RegisterRequest} from "./request/register-request";
import {CompleteHabitRequest} from "./request/complete-habit-request";
import {ProjectsResponse} from "./response/projects-response";
import {ProjectResponse} from "./response/project-response";
import {UpdateProjectRequest} from "./request/update-project-request";
import {ProjectPermissionsResponse} from "./response/project-permissions-response";
import {InviteRequest} from "./request/invite-request";
import {ProfileResponse} from "./response/profile-response";
import {UpdateProfileRequest} from "./request/update-profile-request";

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

    public getOverview(habitsDate: moment.Moment, taskProjectId: string | null = null): Observable<OverviewResponse> {
        return this.httpClient.get<{
            success: boolean,
            projects: [{ id: string, title: string, description: string | null, color: string, }],
            active_tasks: [{
                id: string,
                title: string,
                description: string | null,
                project: any | null,
                created_at: string,
                creator: any,
            }],
            completed_tasks: [{ id: string, title: string, description: string | null, }],
            today_habits: [{ id: string, title: string, description: string | null, completions: any[], projects: any[], }],
        }>(environment.apiBaseUrl + '/api/v1/overview?habits_date='
            + encodeURIComponent(habitsDate.format('YYYY-MM-DD HH:mm:ss.sssZ'))
            + '&task_project_id=' + (taskProjectId ? taskProjectId : ''),
        ).pipe(map(response => {
            return {
                success: response.success,
                projects: response.projects.map(item => {
                    return {
                        id: item.id,
                        title: item.title,
                        description: item.description,
                        color: item.color,
                    };
                }),
                activeTasks: response.active_tasks.map(item => {
                    return {
                        id: item.id,
                        title: item.title,
                        description: item.description,
                        project: item.project ? {
                            id: item.project.id,
                            title: item.project.title,
                            description: item.project.description,
                            color: item.project.color,
                        } : null,
                        createdAt: moment(item.created_at),
                        creator: {
                            id: item.creator.id,
                            name: item.creator.name,
                            avatarSrc: item.creator.avatar_src,
                        },
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
                                type: completionItem.type,
                            };
                        }),
                    };
                }),
            };
        }));
    }

    public getCalendar(date: moment.Moment): Observable<CalendarResponse> {
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
                        type: string,
                    }[],
                    category: {
                        id: string,
                        title: string,
                        color: string,
                    }
                }[],
            }[][],
        }>(environment.apiBaseUrl + '/api/v1/habit/calendar/' + date.format('YYYY-MM-DD')).pipe(map(response => {
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
                                            type: completionItem.type,
                                        };
                                    }),
                                    category: {
                                        id: habitItem.category.id,
                                        title: habitItem.category.title,
                                        color: habitItem.category.color,
                                    },
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
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/task/' + id + '/update', {
            title: request.title,
            description: request.description,
            project_id: request.projectId,
        }, {});
    }

    public createTask(request: UpdateTaskRequest): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/task/create', {
            title: request.title,
            description: request.description,
            project_id: request.projectId,
        }, {});
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

    public getProjects(): Observable<ProjectsResponse> {
        return this.httpClient.get<any>(environment.apiBaseUrl + '/api/v1/project/list');
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

    public getColors(): Observable<ColorsResponse> {
        return this.httpClient.get<ColorsResponse>(environment.apiBaseUrl + '/api/v1/color/list');
    }

    public getAllowedProjectPermissions(): Observable<ProjectPermissionsResponse> {
        return this.httpClient.get<ProjectPermissionsResponse>(environment.apiBaseUrl + '/api/v1/project/allowed_permissions');
    }

    public createCategory(request: UpdateCategoryRequest): Observable<{ success: boolean, id: string }> {
        return this.httpClient.post<{ success: boolean, id: string }>(environment.apiBaseUrl + '/api/v1/category/create', {
            title: request.title,
            description: request.description,
            color: request.color,
        }, {});
    }

    public createProject(request: UpdateProjectRequest): Observable<{ success: boolean, id: string }> {
        return this.httpClient.post<{ success: boolean, id: string }>(environment.apiBaseUrl + '/api/v1/project/create', {
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

    public updateProject(id: string, request: UpdateProjectRequest): Observable<any> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/project/' + id + '/update', {
            title: request.title,
            description: request.description,
            color: request.color,
        }, {});
    }

    public changeCategoryColor(id: string, color: string): Observable<any> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/category/' + id + '/change_color', {
            color: color,
        }, {});
    }

    public changeProjectColor(id: string, color: string): Observable<any> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/project/' + id + '/change_color', {
            color: color,
        }, {});
    }

    public getCategory(id: string): Observable<CategoryResponse> {
        return this.httpClient.get<CategoryResponse>(environment.apiBaseUrl + '/api/v1/category/' + id);
    }

    public getProject(id: string): Observable<ProjectResponse> {
        return this.httpClient.get<ProjectResponse>(environment.apiBaseUrl + '/api/v1/project/' + id);
    }

    public deleteCategory(id: string): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/category/' + id + '/delete', {});
    }

    public deleteProject(id: string): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/project/' + id + '/delete', {});
    }

    public completeHabit(id: string, request: CompleteHabitRequest): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/habit/' + id + '/complete', {
            completed_at: request.date.format('YYYY-MM-DD'),
            completion_type: request.completionType,
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
            target: request.target,
        }, {});
    }

    public confirmRegistration(requestId: string, requestToken: string): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/register/confirm', {
            id: requestId,
            token: requestToken,
        });
    }

    public createInvite(projectId: string, request: InviteRequest): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/project/' + projectId + '/invite/create', {
            email: request.email,
            permissions: request.permissions,
        }, {});
    }

    public confirmInvite(projectId: string, inviteId: string, inviteToken: string): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/project/' + projectId + '/invite/' + inviteId + '/confirm', {
            token: inviteToken,
        }, {});
    }

    public getProfile(): Observable<ProfileResponse> {
        return this.httpClient.get<{
            success: boolean,
            profile: {
                id: string;
                name: string;
                email: string;
                avatar_src: string | null;
            },
        }>(environment.apiBaseUrl + '/api/v1/profile').pipe(map(response => {
            return {
                success: response.success,
                profile: {
                    id: response.profile.id,
                    name: response.profile.name,
                    email: response.profile.email,
                    avatarSrc: response.profile.avatar_src,
                },
            };
        }));
    }

    public changeProfileAvatar(formData: FormData): Observable<{ success: boolean, avatarSrc: string }> {
        return this.httpClient.post<{
            success: boolean,
            avatar_src: string,
        }>(environment.apiBaseUrl + '/api/v1/profile/change_avatar', formData).pipe(map(response => {
            return {
                success: response.success,
                avatarSrc: response.avatar_src,
            };
        }));
    }

    public updateProfile(request: UpdateProfileRequest): Observable<{ success: boolean }> {
        return this.httpClient.post<{ success: boolean }>(environment.apiBaseUrl + '/api/v1/profile/update', {
            name: request.name,
        }, {});
    }
}
