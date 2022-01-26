import {Component, OnInit} from '@angular/core';
import * as moment from "moment";
import {Moment} from "moment";
import {CalendarResponse} from "../../api/response/calendar-response";
import {ApiService} from "../../api/api.service";
import {ActivatedRoute} from "@angular/router";
import {Weekday} from "../../enums/weekday";
import {HabitCompletionType} from "../../enums/habit-completion-type";

@Component({
  selector: 'app-calendar-page',
  templateUrl: './calendar-page.component.html',
  styleUrls: ['./calendar-page.component.scss'],
})
export class CalendarPageComponent implements OnInit {
  public currentDate: Moment = moment().startOf('day');
  public selectedMonth: Moment = moment().startOf('month');
  public calendar!: CalendarResponse;
  public weekdays: string[] = Object.keys(Weekday);

  public loadingHabits: {
    habitId: string,
    date: moment.Moment,
  }[] = [];
  public title: string = 'Calendar';

  public constructor(
      private apiService: ApiService,
      public route: ActivatedRoute,
  ) {
  }

  public ngOnInit(): void {
    this.route.data.subscribe(data => {
      this.calendar = data['calendar'];
    });
  }

  public completeHabit(habitId: string, date: moment.Moment): void {
    this.loadingHabits.push({habitId: habitId, date: date,});

    this.apiService.completeHabit(habitId, {
      date: date,
      completionType: HabitCompletionType.Success,
    }).subscribe({
      next: () => {
        this.apiService.getCalendar(this.selectedMonth).subscribe((next: CalendarResponse) => {
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
        this.apiService.getCalendar(this.selectedMonth).subscribe((next: CalendarResponse) => {
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

  public isDateCurrent(date: moment.Moment): boolean {
    return date.format('DD MM') === this.currentDate.format('DD MM');
  }
}
