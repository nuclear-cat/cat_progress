import {Component, OnInit} from '@angular/core';
import {FormArray, FormBuilder, FormGroup, Validators} from "@angular/forms";
import {ApiService} from "../../api/api.service";
import {ActivatedRoute, Router} from "@angular/router";
import {Weekday} from "../../enums/weekday";
import {Subscription} from "rxjs";
import {CategoriesResponse} from "../../api/response/categories-response";
import {HabitResponse} from "../../api/response/habit-response";

@Component({
  selector: 'app-edit-habit-page',
  templateUrl: './edit-habit-page.component.html',
  styleUrls: ['./edit-habit-page.component.scss']
})
export class EditHabitPageComponent implements OnInit {

  public form!: FormGroup;
  public weekdays: string[] = [];
  public isLoading = true;
  public categoriesResponse!: CategoriesResponse;
  public habitSub?: Subscription;
  public categoriesSub?: Subscription;
  public title: string = 'Edit habit';
  public submitButtonTitle = 'Update';

  public constructor(
      private formBuilder: FormBuilder,
      private apiService: ApiService,
      private router: Router,
      private route: ActivatedRoute,
  ) {
    for (let weekday in Weekday) {
      this.weekdays.push(weekday);
    }
  }

  public ngOnInit(): void {
    const habitId = this.route.snapshot.paramMap.get('habitId') as string;

    this.categoriesSub = this.apiService.getCategories().subscribe({
      next: (next: CategoriesResponse) => {
        this.categoriesResponse = next;
      },
    });

    this.habitSub = this.apiService.getHabit(habitId).subscribe({
      next: (next: HabitResponse) => {
        this.form = this.formBuilder.group({
          title: [next.habit.title, [Validators.required,]],
          description: [next.habit.description, []],
          points: [next.habit.points, [Validators.required,]],
          weekdays: this.formBuilder.array(this.weekdays.map((weekdayItem: string) => {
            return next.habit.weekdays.find(weekday => weekday === weekdayItem) !== undefined;
          })),
          category: [next.habit.category.id, [Validators.required,]],
        });
      },
    });
  }

  public submitForm() {
    let selectedWeekdays = ((this.form.get('weekdays') as FormArray).value as []).map((item, index: number) => {
      if (item) {
        return this.weekdays[index];
      }

      return null;
    }).filter((item) => {
      return item !== null;
    }) as string[];

    const habitId = this.route.snapshot.paramMap.get('habitId') as string;

    this.apiService.updateHabit(habitId, {
      title: this.form.get('title')?.value,
      description: this.form.get('description')?.value,
      weekdays: selectedWeekdays,
      categoryId: this.form.get('category')?.value,
      points: this.form.get('points')?.value,
    }).subscribe({
      next: () => {
        this.router.navigate(['/habits']);
      }
    });
  }
}
