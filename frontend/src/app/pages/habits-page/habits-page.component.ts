import {Component, OnInit} from '@angular/core';
import {HabitsResponse} from "../../api/response/habits-response";
import {ApiService} from "../../api/api.service";

@Component({
  selector: 'app-habits-page',
  templateUrl: './habits-page.component.html',
  styleUrls: ['./habits-page.component.scss']
})
export class HabitsPageComponent implements OnInit {

  public habitsResponse!: HabitsResponse;

  public constructor(private apiService: ApiService) {
  }

  public ngOnInit(): void {
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
