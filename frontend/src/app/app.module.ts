import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './app.component';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';
import {MatToolbarModule} from "@angular/material/toolbar";
import {MatSidenavModule} from "@angular/material/sidenav";
import {NavComponent} from './nav/nav.component';
import {LayoutModule} from '@angular/cdk/layout';
import {MatButtonModule} from '@angular/material/button';
import {MatIconModule} from '@angular/material/icon';
import {MatListModule} from '@angular/material/list';
import {MatGridListModule} from '@angular/material/grid-list';
import {MatCardModule} from '@angular/material/card';
import {MatMenuModule} from '@angular/material/menu';
import {CalendarPageComponent} from './pages/calendar-page/calendar-page.component';
import {DashboardPageComponent} from "./pages/dashboard-page/dashboard-page.component";
import {MatFormFieldModule} from "@angular/material/form-field";
import {MatInputModule} from "@angular/material/input";
import {ReactiveFormsModule} from "@angular/forms";
import {FlexModule} from "@angular/flex-layout";
import {HTTP_INTERCEPTORS, HttpClientModule} from "@angular/common/http";
import {MatExpansionModule} from "@angular/material/expansion";
import {MatDatepickerModule} from "@angular/material/datepicker";
import {MatCheckboxModule} from "@angular/material/checkbox";
import {DragDropModule} from "@angular/cdk/drag-drop";
import {TaskDialogComponent} from './task-dialog/task-dialog.component';
import {MatDialogModule, MatDialogRef} from "@angular/material/dialog";
import {MatProgressBarModule} from "@angular/material/progress-bar";
import {HabitsPageComponent} from './pages/habits-page/habits-page.component';
import {EditHabitPageComponent} from './pages/edit-habit-page/edit-habit-page.component';
import {CreateHabitPageComponent} from './pages/create-habit-page/create-habit-page.component';
import {MatSelectModule} from "@angular/material/select";
import {LoginPageComponent} from "./pages/login-page/login-page.component";
import {CategoriesPageComponent} from './pages/categories-page/categories-page.component';
import {EditCategoryPageComponent} from './pages/edit-category-page/edit-category-page.component';
import {CreateCategoryPageComponent} from './pages/create-category-page/create-category-page.component';
import {TokenInterceptor} from "./interceptors/token-interceptor";
import { RegistrationPageComponent } from './pages/registration-page/registration-page.component';
import { RegistrationInfoPageComponent } from './pages/registration-info-page/registration-info-page.component';
import { ServiceWorkerModule } from '@angular/service-worker';
import { environment } from '../environments/environment';

@NgModule({
    declarations: [
        AppComponent,
        NavComponent,
        DashboardPageComponent,
        CalendarPageComponent,
        LoginPageComponent,
        TaskDialogComponent,
        HabitsPageComponent,
        EditHabitPageComponent,
        CreateHabitPageComponent,
        CategoriesPageComponent,
        EditCategoryPageComponent,
        CreateCategoryPageComponent,
        RegistrationPageComponent,
        RegistrationInfoPageComponent,
    ],
    imports: [
        BrowserModule,
        AppRoutingModule,
        BrowserAnimationsModule,
        MatToolbarModule,
        MatSidenavModule,
        LayoutModule,
        MatButtonModule,
        MatIconModule,
        MatListModule,
        MatGridListModule,
        MatCardModule,
        MatMenuModule,
        MatFormFieldModule,
        MatInputModule,
        ReactiveFormsModule,
        FlexModule,
        HttpClientModule,
        MatExpansionModule,
        MatDatepickerModule,
        MatCheckboxModule,
        DragDropModule,
        MatDialogModule,
        MatProgressBarModule,
        MatSelectModule,
        ServiceWorkerModule.register('ngsw-worker.js', {
          enabled: environment.production,
          // Register the ServiceWorker as soon as the app is stable
          // or after 30 seconds (whichever comes first).
          registrationStrategy: 'registerWhenStable:30000'
        }),
    ],
    providers: [
        {
            provide: HTTP_INTERCEPTORS,
            multi: true,
            useClass: TokenInterceptor,
        },
        {
            provide: MatDialogRef,
            useValue: {}
        },
    ],
    bootstrap: [AppComponent]
})
export class AppModule { }
