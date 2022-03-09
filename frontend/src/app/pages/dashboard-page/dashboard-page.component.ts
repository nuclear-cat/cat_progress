import {Component, EventEmitter, OnDestroy, OnInit, Output, ViewChild} from '@angular/core';
import {Subscription} from "rxjs";
import {MatDialog} from '@angular/material/dialog';
import {FormBuilder, FormGroup} from "@angular/forms";
import {ApiService} from "../../api/api.service";
import {OverviewResponse} from "../../api/response/overview-response";
import {TaskDialogComponent} from "../../dialogs/task-dialog/task-dialog.component";
import * as moment from "moment";
import {ActivatedRoute} from "@angular/router";
import {HabitCompletionType} from "../../enums/habit-completion-type";
import {CdkPortal} from "@angular/cdk/portal";
import {CdkDragDrop, moveItemInArray} from "@angular/cdk/drag-drop";

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
    public isHabitsLoading: boolean = false;
    public habitsDate: moment.Moment = moment().startOf('day');
    public habitsCompleteLoading: string[] = [];
    public completionType = HabitCompletionType;
    @Output() public pageReady = new EventEmitter();
    public selectedProjectId: string | null = null;

    @ViewChild(CdkPortal)
    public portalContent!: CdkPortal;
    public editTasks: string[] = [];

    constructor(
        private apiService: ApiService,
        public dialog: MatDialog,
        private formBuilder: FormBuilder,
        public route: ActivatedRoute,
    ) {
    }

    public ngOnInit(): void {
        this.route.paramMap.subscribe();

        this.route.data.subscribe(data => {
            this.overview = data['overview'];
        });

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
        projectId: this.selectedProjectId,
    }).subscribe({
      complete: () => {
        this.addTaskForm.reset();
        this.addTaskForm.enable();

        this.loadOverview();
      }
    });
  }

  public completeHabit(habitId: string, completionType: string): void {
    this.apiService.completeHabit(habitId, {
      date: moment(),
      completionType: completionType,
    }).subscribe({
          next: () => {
            this.loadOverview();
          }
        }
    );
  }

  public incompleteHabit(habitId: string, completionId: string): void {
    this.apiService.incompleteHabit(habitId, completionId).subscribe({
      next: () => {
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

    public selectProject(id: string | null): void {
        this.selectedProjectId = id;
        this.loadOverview();
    }

    public openTaskDialog(taskId: string) {

        console.log(taskId);

        const task = this.overview.activeTasks.filter(item => item.id == taskId)[0];

        const dialogRef = this.dialog.open(TaskDialogComponent, {
            width: '100vw',
            data: {
                task: task,
            },
        });

        dialogRef.afterClosed().subscribe(result => {
            if (result.success) {
                this.loadOverview();
            }
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

    private loadOverview(): void {
        this.overviewSubscription = this.apiService.getOverview(this.habitsDate, this.selectedProjectId)
            .subscribe(
                overview => {
                    this.overview = overview;
                    this.isHabitsLoading = false;
                },
            );
    }

    public editTaskStart(id: string) {
        this.editTasks.push(id);
    }

    public editTaskClose(id: string): void {
        this.editTasks = this.editTasks.filter(function (value: string) {
            return value !== id
        });
    }

    public resortActiveTasks($event: CdkDragDrop<any[]>): void {
        moveItemInArray(this.overview.activeTasks, $event.previousIndex, $event.currentIndex);
    }
}
