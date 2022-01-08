import {Component, Inject, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {MAT_DIALOG_DATA, MatDialogRef} from "@angular/material/dialog";
import {ApiService} from "../api/api.service";

@Component({
  selector: 'app-task-dialog',
  templateUrl: './task-dialog.component.html',
  styleUrls: ['./task-dialog.component.scss']
})
export class TaskDialogComponent implements OnInit {
  public form!: FormGroup;
  public error: string | null = null;

  public constructor(
      private fb: FormBuilder,
      private dialogRef: MatDialogRef<TaskDialogComponent>,
      @Inject(MAT_DIALOG_DATA) public data: { task: any },
      private apiService: ApiService,
  ) {
  }

  public ngOnInit(): void {
    this.form = this.fb.group({
      title: [this.data.task.title, [Validators.required,]],
      description: [this.data.task.description, []],
    });
  }

  public submitForm(): void {
    this.form.disable();
    this.error = null;

    if (this.form.invalid) {
      return;
    }

    this.apiService.updateTask(this.data.task.id, {
      title: this.form.get('title')?.value,
      description: this.form.get('description')?.value,
    }).subscribe({
      complete() {

      },
    });

    this.dialogRef.close();
  }

}
