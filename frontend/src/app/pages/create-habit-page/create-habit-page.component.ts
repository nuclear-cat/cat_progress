import {ChangeDetectorRef, Component, OnInit} from '@angular/core';
import {ApiService} from "../../api/api.service";
import {FormArray, FormBuilder, FormGroup, Validators} from "@angular/forms";
import {Weekday} from "../../enums/weekday";
import {CategoriesResponse} from "../../api/response/categories-response";
import {Router} from "@angular/router";

@Component({
    selector: 'app-create-habit-page',
    templateUrl: './../edit-habit-page/edit-habit-page.component.html',
    styleUrls: ['./create-habit-page.component.scss']
})
export class CreateHabitPageComponent implements OnInit {
    public form!: FormGroup;
    public weekdays: string[] = [];
    public isLoading = true;
    public categories: { id: string, title: string }[] = [];

    public title: string = 'Create habit';
    public submitButtonTitle = 'Create';

    public constructor(
        private formBuilder: FormBuilder,
        private apiService: ApiService,
        private router: Router,
        private changeDetectorRef: ChangeDetectorRef
    ) {
        for (let weekday in Weekday) {
            this.weekdays.push(weekday);
        }
    }

    public ngOnInit(): void {
        this.changeDetectorRef.detectChanges();

        this.apiService.getCategories().subscribe({
            next: (next: CategoriesResponse) => {
                this.categories = next.categories;
            }
        });

        this.form = this.formBuilder.group({
            title: [null, [Validators.required,]],
            description: [null, []],
            points: [1, [Validators.required,]],
            weekdays: this.formBuilder.array(this.weekdays.map(() => null)),
            category: [null, [Validators.required,]],
        });
    }

    public submitForm(): void {
        let selectedWeekdays = ((this.form.get('weekdays') as FormArray).value as []).map((item, index: number) => {
            if (item) {
                return this.weekdays[index];
            }

            return null;
        }).filter((item) => {
            return item !== null;
        }) as string[];

        this.apiService.createHabit({
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
