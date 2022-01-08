import {NgModule} from '@angular/core';
import {RouterModule, Routes} from '@angular/router';
import {CalendarPageComponent} from "./pages/calendar-page/calendar-page.component";
import {DashboardPageComponent} from "./pages/dashboard-page/dashboard-page.component";
import {LoginPageComponent} from "./pages/login-page/login-page.component";
import {AuthGuard} from "./auth.guard";
import {HabitsPageComponent} from "./pages/habits-page/habits-page.component";
import {CreateHabitPageComponent} from "./pages/create-habit-page/create-habit-page.component";
import {EditHabitPageComponent} from "./pages/edit-habit-page/edit-habit-page.component";
import {CategoriesPageComponent} from "./pages/categories-page/categories-page.component";
import {EditCategoryPageComponent} from "./pages/edit-category-page/edit-category-page.component";
import {CreateCategoryPageComponent} from "./pages/create-category-page/create-category-page.component";
import {RegistrationPageComponent} from "./pages/registration-page/registration-page.component";
import {RegistrationInfoPageComponent} from "./pages/registration-info-page/registration-info-page.component";

const routes: Routes = [
  {path: '', component: DashboardPageComponent, canActivate: [AuthGuard],},
  {path: 'calendar', component: CalendarPageComponent, canActivate: [AuthGuard],},
  {path: 'habits', component: HabitsPageComponent, canActivate: [AuthGuard],},
  {path: 'create-habit', component: CreateHabitPageComponent, canActivate: [AuthGuard],},
  {path: 'edit-habit/:habitId', component: EditHabitPageComponent, canActivate: [AuthGuard],},
  {path: 'categories', component: CategoriesPageComponent, canActivate: [AuthGuard],},
  {path: 'edit-category/:categoryId', component: EditCategoryPageComponent, canActivate: [AuthGuard],},
  {path: 'create-category', component: CreateCategoryPageComponent, canActivate: [AuthGuard],},
  {path: 'registration', component: RegistrationPageComponent,},
  {path: 'registration-info', component: RegistrationInfoPageComponent,},
  {path: 'login', component: LoginPageComponent,},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {
}
