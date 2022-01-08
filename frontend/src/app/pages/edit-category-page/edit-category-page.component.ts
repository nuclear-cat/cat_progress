import { Component, OnInit } from '@angular/core';
import {ApiService} from "../../api/api.service";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {CategoryColorsResponse} from "../../api/response/category-colors-response";
import {ActivatedRoute, Router} from "@angular/router";
import {HabitResponse} from "../../api/response/habit-response";
import {CategoryResponse} from "../../api/response/category-response";

@Component({
  selector: 'app-edit-category-page',
  templateUrl: './edit-category-page.component.html',
  styleUrls: ['./edit-category-page.component.scss']
})
export class EditCategoryPageComponent implements OnInit {
  public form!: FormGroup;
  public title: string = 'Edit category';
  public submitButtonTitle: string = 'Update';
  public colors: string[] = [];
  public selectedColor!: string;

  public constructor(
      private apiService: ApiService,
      private formBuilder: FormBuilder,
      private route: ActivatedRoute,
      private router: Router,
  ) {
  }

  public ngOnInit(): void {
    const categoryId = this.route.snapshot.paramMap.get('categoryId') as string;

    this.apiService.getCategory(categoryId).subscribe({
      next: (next: CategoryResponse) => {

        this.form = this.formBuilder.group({
          title: [next.category.title, [Validators.required,]],
          description: [next.category.description, []],
          color: [next.category.color, []],
        });

        this.selectedColor = next.category.color;
      },
    });

    this.apiService.getCategoryColors().subscribe({
      next: (next: CategoryColorsResponse) => {
        this.colors = next.colors;
      }
    });

    this.form = this.formBuilder.group({
      title: [null, [Validators.required,]],
      description: [null, []],
      color: [null, []],
    });
  }

  public selectColor(color: string): void {
    this.selectedColor = color;
  }

  public submitForm(): void {
    const categoryId = this.route.snapshot.paramMap.get('categoryId') as string;

    this.apiService.updateCategory(categoryId, {
      title: this.form.get('title')?.value,
      description: this.form.get('description')?.value,
      color: this.selectedColor,
    }).subscribe({
      next: () => {
        this.router.navigate(['/categories']);
      }
    });
  }
}
