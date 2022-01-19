import {Component, OnInit} from '@angular/core';
import {HabitsResponse} from "../../api/response/habits-response";
import {ApiService} from "../../api/api.service";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-habits-page',
  templateUrl: './habits-page.component.html',
  styleUrls: ['./habits-page.component.scss']
})
export class HabitsPageComponent implements OnInit {
  public displayedColumns: string[] = ['title', 'description', 'weekdays', 'action',];
  public title: string = 'Habits';

  public habitsResponse!: HabitsResponse;

  public constructor(
      private apiService: ApiService,
      public route: ActivatedRoute,
  ) {
  }

  public ngOnInit(): void {
    this.route.data.subscribe(data => {
      this.habitsResponse = data['habits'];
    });
  }

  public deleteHabit(id: string): void {
    this.apiService.deleteHabit(id).subscribe();

    this.habitsResponse.habits = this.habitsResponse.habits.filter(item => item.id !== id);
  }
}
