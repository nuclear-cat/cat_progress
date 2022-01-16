import {Component, EventEmitter, OnDestroy, OnInit, Output} from '@angular/core';
import {Subscription} from "rxjs";
import {MatDialog} from '@angular/material/dialog';
import {FormBuilder, FormGroup} from "@angular/forms";
import {ApiService} from "../../api/api.service";
import {OverviewResponse} from "../../api/response/overview-response";
import {TaskDialogComponent} from "../../task-dialog/task-dialog.component";
import * as moment from "moment";
import {NavService} from "../../services/nav-service";

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard-page.component.html',
  styleUrls: ['./dashboard-page.component.scss'],
})
export class DashboardPageComponent implements OnInit, OnDestroy {

  public overview!: OverviewResponse;
  public overviewSubscription!: Subscription;
  public taskCreatingMode = false;
  public addTaskForm!: FormGroup;
  public isLoading: boolean = true;
  public isHabitsLoading: boolean = false;
  public habitsDate: moment.Moment = moment().startOf('day');
  public habitsCompleteLoading: string[] = [];
  public title = 'Dashboard';

  @Output() public pageReady = new EventEmitter();

  constructor(
      private apiService: ApiService,
      public dialog: MatDialog,
      private formBuilder: FormBuilder,
      private navService: NavService,
  ) {
  }

  public ngOnInit(): void {
    this.loadOverview();
    // this.navService.title = 'Dashboard';
    this.pageReady.emit(true);
    this.addTaskForm = this.formBuilder.group({
      title: [null,],
    });
  }

  public ngOnDestroy(): void {
    if (this.overviewSubscription) {
      this.overviewSubscription.unsubscribe();
    }
  }

  public completeTask(id: string): void {
    this.apiService.completeTask(id).subscribe();

    const completedTask: any | undefined = this.overview.activeTasks.find(task => task.id === id);

    if (completedTask === undefined) {
      throw new Error();
    }

    this.overview.activeTasks = this.overview.activeTasks.filter(task => task !== completedTask);
    this.overview.completedTasks.unshift(completedTask);
  }


  public deleteTask(id: string): void {
    this.apiService.deleteTask(id).subscribe({
      complete: () => {
        this.loadOverview();
      }
    });
  }

  public addTaskSubmit(): void {
    this.addTaskForm.disable();

    if (!this.addTaskForm.get('title')?.value) {
      return;
    }

    this.apiService.createTask({
      title: this.addTaskForm.get('title')?.value,
      description: null,
    }).subscribe({
      complete: () => {
        this.addTaskForm.reset();
        this.addTaskForm.enable();

        this.loadOverview();
      }
    });
  }

  public completeHabit(habitId: string): void {
    this.apiService.completeHabit(habitId, this.habitsDate).subscribe({
      next: (next) => {
        this.loadOverview();
      }}
    );
  }

  public incompleteHabit(habitId: string, completionId: string): void {
    this.apiService.incompleteHabit(habitId, completionId).subscribe({
      next: (next) => {
        this.loadOverview();
      }}
    );
  }

  public incompleteTask(id: string): void {
    this.apiService.incompleteTask(id).subscribe();

    const uncompletedTask: any | undefined = this.overview.completedTasks.find(task => task.id === id);

    if (uncompletedTask === undefined) {
      throw new Error();
    }

    this.overview.completedTasks = this.overview.completedTasks.filter(task => task !== uncompletedTask);
    this.overview.activeTasks.unshift(uncompletedTask);
  }

  private loadOverview(): void {
    this.isLoading = true;
    this.overviewSubscription = this.apiService.getOverview(this.habitsDate)
        .subscribe(
            overview => {
              this.overview = overview;
              this.isLoading = false;
              this.isHabitsLoading = false;
            },
            error => {
              this.isLoading = false;
            },
        );
  }

  public openTaskDialog(task: any) {
    const dialogRef = this.dialog.open(TaskDialogComponent, {
      width: '100vw',
      data: {
        task: task,
      },
    });

    dialogRef.afterClosed().subscribe(result => {
      console.log(`Dialog result: ${result}`);
    });
  }

  public toggleTaskCreatingMode() {
    this.taskCreatingMode = !this.taskCreatingMode;
  }

  public incrementHabitsDay(): void {
    this.isHabitsLoading = true;
    this.habitsDate = this.habitsDate.add(1, 'day');
    this.loadOverview();
  }

  public decrementHabitsDay(): void {
    this.isHabitsLoading = true;
    this.habitsDate = this.habitsDate.subtract(1, 'day');
    this.loadOverview();
  }
}
