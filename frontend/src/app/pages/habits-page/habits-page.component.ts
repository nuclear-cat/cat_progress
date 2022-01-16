import {Component, OnInit} from '@angular/core';
import {HabitsResponse} from "../../api/response/habits-response";
import {ApiService} from "../../api/api.service";
import {NavService} from "../../services/nav-service";

@Component({
  selector: 'app-habits-page',
  templateUrl: './habits-page.component.html',
  styleUrls: ['./habits-page.component.scss']
})
export class HabitsPageComponent implements OnInit {

  public displayedColumns: string[] = ['title', 'description', 'action',];
  public title: string = 'Habits';

  public habitsResponse!: HabitsResponse;

  public constructor(
      private apiService: ApiService,
      public navService: NavService,
  ) {
  }

  public ngOnInit(): void {
    this.navService.title = 'Habits';

    this.apiService.getHabits().subscribe({
      next: (next) => {
        this.habitsResponse = next;
      },
      error: (error) => {
      },
    });
  }

  public deleteHabit(id: string): void {
    this.apiService.deleteHabit(id).subscribe();

    this.habitsResponse.habits = this.habitsResponse.habits.filter(item => item.id !== id);
  }
}
