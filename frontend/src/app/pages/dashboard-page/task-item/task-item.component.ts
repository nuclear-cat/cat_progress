import {Component, ElementRef, EventEmitter, HostListener, Input, OnInit, Output, ViewChild} from '@angular/core';
import * as moment from "moment";

@Component({
  selector: 'app-task-item',
  templateUrl: './task-item.component.html',
  styleUrls: ['./task-item.component.scss']
})
export class TaskItemComponent implements OnInit {
  @Input() public id!: string;
  @Input() public title!: string;
  @Input() public creatorName!: string;
  @Input() public avatarSrc: string | null = null;
  @Input() public description: string | null = null;
  @Input() public projectTitle: string | null = null;
  @Input() public projectColor: string | null = null;
  @Input() public createdAt!: moment.Moment;
  @Input() public completedAt: moment.Moment | null = null;
  @Input() public isEdit: boolean = false;

  @Output() public onComplete: EventEmitter<any> = new EventEmitter();
  @Output() public onDelete: EventEmitter<any> = new EventEmitter();
  @Output() public onOpenTaskDialog: EventEmitter<any> = new EventEmitter();
  @Output() public onEditStarted: EventEmitter<any> = new EventEmitter();
  @Output() public onEditClose: EventEmitter<any> = new EventEmitter();
  @Output() public onSave: EventEmitter<any> = new EventEmitter();

  @ViewChild('editTitleField') editTitleField!: ElementRef;

  public editableTitle: string = this.title;

  public constructor(private eRef: ElementRef) {
  }

  public ngOnInit(): void {
  }

  public completeClick(id: string): void {
    this.onComplete.emit([id]);
  }

  public deleteClick(id: string): void {
    this.onDelete.emit([id]);
  }

  public openTaskDialogClick(task: any): void {
    this.onOpenTaskDialog.emit([task]);
  }

  public editStart(): void {
    this.isEdit = true;
    this.onEditStarted.emit([this.isEdit]);
    this.editableTitle = this.title;

    setTimeout(() => {
      this.editTitleField.nativeElement.focus();
    }, 0);
  }

  saveTitle($event: KeyboardEvent) {
    if ($event.code === 'Enter') {
      this.isEdit = false;
      this.title = this.editableTitle;
      this.onSave.emit([this.isEdit]);
    }
  }

  @HostListener('document:keydown.escape', ['$event'])
  public closeEdit(event: KeyboardEvent): void {
    this.isEdit = false;
    this.onEditClose.emit([this.isEdit]);
  }
}
