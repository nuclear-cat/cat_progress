import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {AuthGuard} from "./auth.guard";
import {NavComponent} from "./nav/nav.component";
import {DashboardPageComponent} from "./pages/dashboard-page/dashboard-page.component";
import {CalendarPageComponent} from "./pages/calendar-page/calendar-page.component";
import {HabitsPageComponent} from "./pages/habits-page/habits-page.component";
import {CreateHabitPageComponent} from "./pages/create-habit-page/create-habit-page.component";
import {EditHabitPageComponent} from "./pages/edit-habit-page/edit-habit-page.component";
import {CategoriesPageComponent} from "./pages/categories-page/categories-page.component";
import {EditCategoryPageComponent} from "./pages/edit-category-page/edit-category-page.component";
import {CreateCategoryPageComponent} from "./pages/create-category-page/create-category-page.component";
import {RegistrationPageComponent} from "./pages/registration-page/registration-page.component";
import {RegistrationInfoPageComponent} from "./pages/registration-info-page/registration-info-page.component";
import {LoginPageComponent} from "./pages/login-page/login-page.component";
import {CalendarPageResolver} from "./pages/calendar-page/calendar-page.resolver";
import {DashboardPageResolver} from "./pages/dashboard-page/dashboard-page.resolver";
import {CategoriesPageResolver} from "./pages/categories-page/categories-page.resolver";
import {HabitsPageResolver} from "./pages/habits-page/habits-page.resolver";
import {ProjectsPageComponent} from "./pages/projects-page/projects-page.component";
import {ProjectsPageResolver} from "./pages/projects-page/projects-page.resolver";
import {EditProjectPageComponent} from "./pages/edit-project-page/edit-project-page.component";
import {CreateProjectPageComponent} from "./pages/create-project-page/create-project-page.component";
import {RegistrationConfirmPageComponent} from "./pages/registration-confirm-page/registration-confirm-page.component";
import {ProjectInvitePageComponent} from "./pages/project-invite-page/project-invite-page.component";
import {
    ProjectInviteConfirmPageComponent
} from "./pages/project-invite-confirm-page/project-invite-confirm-page.component";
import {ProjectPageComponent} from "./pages/project-page/project-page.component";
import {EditProfilePageComponent} from "./pages/edit-profile-page/edit-profile-page.component";
import {EditProfilePageResolver} from "./pages/edit-profile-page/edit-profile-page.resolver";
import {ProjectPageResolver} from "./pages/project-page/project-page.resolver";

const routes: Routes = [
    {
        path: '', component: NavComponent, canActivate: [AuthGuard], children: [
            {
                path: '',
                redirectTo: '/',
                pathMatch: 'full'
            },
            {
        path: '',
        component: DashboardPageComponent,
        canActivate: [AuthGuard],
        resolve: {
          overview: DashboardPageResolver,
        },
      },
      {
        path: 'dashboard/:date',
        component: DashboardPageComponent,
        canActivate: [AuthGuard],
        resolve: {
          overview: DashboardPageResolver,
        },
      },
      {
        path: 'calendar',
        component: CalendarPageComponent,
        canActivate: [AuthGuard],
        resolve: {
          calendar: CalendarPageResolver,
        },
      },
      {
        path: 'habits',
        component: HabitsPageComponent,
        canActivate: [AuthGuard],
        resolve: {
          habits: HabitsPageResolver,
        },
      },
      {
        path: 'create-habit',
        component: CreateHabitPageComponent,
        canActivate: [AuthGuard],
      },
      {
        path: 'edit-habit/:habitId',
        component: EditHabitPageComponent,
        canActivate: [AuthGuard],
      },
      {
        path: 'categories',
        component: CategoriesPageComponent,
        canActivate: [AuthGuard],
        resolve: {
          categories: CategoriesPageResolver,
        },
      },
            {
                path: 'edit-category/:categoryId',
                component: EditCategoryPageComponent,
                canActivate: [AuthGuard],
            },
            {
                path: 'create-category',
                component: CreateCategoryPageComponent,
                canActivate: [AuthGuard],
            },
            {
                path: 'projects',
                component: ProjectsPageComponent,
                canActivate: [AuthGuard],
                resolve: {
                    projects: ProjectsPageResolver,
                },
            },
            {
                path: 'project/:projectId/edit',
                component: EditProjectPageComponent,
                canActivate: [AuthGuard],
            },
            {
                path: 'create-project',
                component: CreateProjectPageComponent,
                canActivate: [AuthGuard],
            },
            {
                path: 'project/:projectId/invite',
                component: ProjectInvitePageComponent,
                canActivate: [AuthGuard],
            },
            {
                path: 'project/:projectId',
                component: ProjectPageComponent,
                canActivate: [AuthGuard],
                resolve: {
                    project: ProjectPageResolver,
                },
            },
            {
                path: 'project/:projectId/invite/:inviteId/confirm/:token',
                component: ProjectInviteConfirmPageComponent,
                canActivate: [AuthGuard],
            },
            {
                path: 'profile/edit',
                component: EditProfilePageComponent,
                canActivate: [AuthGuard],
                resolve: {
                    profile: EditProfilePageResolver,
                },
            },
        ],
    },

    {path: 'registration', component: RegistrationPageComponent,},
    {path: 'registration-info', component: RegistrationInfoPageComponent,},
    {path: 'login', component: LoginPageComponent,},
    {path: 'registration-confirm/:requestId/:requestToken', component: RegistrationConfirmPageComponent,},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {
}
