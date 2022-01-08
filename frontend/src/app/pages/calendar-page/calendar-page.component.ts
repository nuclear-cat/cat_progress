import {Component, OnInit} from '@angular/core';
import * as moment from "moment";
import {Moment} from "moment";
import {CalendarResponse} from "../../api/response/calendar-response";
import {ApiService} from "../../api/api.service";

@Component({
  selector: 'app-calendar-page',
  templateUrl: './calendar-page.component.html',
  styleUrls: ['./calendar-page.component.scss'],
})
export class CalendarPageComponent implements OnInit {

  public selectedDate: Moment = moment();
  public calendar!: CalendarResponse;
  public loadingHabits: {
    habitId: string,
    date: moment.Moment,
  }[] = [];

  public constructor(private apiService: ApiService) {
  }

  public ngOnInit(): void {
    this.apiService.getCalendar().subscribe((next: CalendarResponse) => {
      this.calendar = next;
    });
  }

  public completeHabit(habitId: string, date: moment.Moment): void {
    this.loadingHabits.push({habitId: habitId, date: date,});
    console.log(this.loadingHabits );

    this.apiService.completeHabit(habitId, date).subscribe({
      next: () => {
        this.apiService.getCalendar().subscribe((next: CalendarResponse) => {
          this.calendar = next;
          this.loadingHabits = this.loadingHabits.filter(item => {
            return item.habitId !== habitId && item.date != date;
          });
        });
      }
    });
  }

  public incompleteHabit(habitId: string, completionId: string, date: moment.Moment,): void {
    this.loadingHabits.push({habitId: habitId, date: date,});

    this.apiService.incompleteHabit(habitId, completionId).subscribe({
      next: () => {
        this.apiService.getCalendar().subscribe((next: CalendarResponse) => {
          this.calendar = next;
        });
      }
    });
  }

  public isLoading(habitId: string, date: moment.Moment): boolean {
    const el = this.loadingHabits.find(element => {
      return element.habitId === habitId && element.date == date;
    });

    return el !== undefined;
  }
}
