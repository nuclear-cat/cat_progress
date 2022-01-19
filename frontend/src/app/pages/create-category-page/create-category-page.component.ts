import {Component, OnInit} from '@angular/core';
import {ApiService} from "../../api/api.service";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {CategoryColorsResponse} from "../../api/response/category-colors-response";
import {Router} from "@angular/router";

@Component({
  selector: 'app-create-category-page',
  templateUrl: './../edit-category-page/edit-category-page.component.html',
  styleUrls: ['./create-category-page.component.scss']
})
export class CreateCategoryPageComponent implements OnInit {
  public form!: FormGroup;
  public title: string = 'Create category';
  public submitButtonTitle: string = 'Create';
  public colors: string[] = [];
  public selectedColor!: string;

  public constructor(
      private apiService: ApiService,
      private formBuilder: FormBuilder,
      private router: Router,
  ) {
  }

  public ngOnInit(): void {

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

  public submitForm() {
    this.apiService.createCategory({
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
