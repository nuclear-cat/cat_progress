import {Component, OnInit} from '@angular/core';
import {ApiService} from "../../api/api.service";
import {CategoriesResponse} from "../../api/response/categories-response";
import {NavService} from "../../services/nav-service";
import {CategoryColorsResponse} from "../../api/response/category-colors-response";

@Component({
  selector: 'app-categories-page',
  templateUrl: './categories-page.component.html',
  styleUrls: ['./categories-page.component.scss']
})
export class CategoriesPageComponent implements OnInit {

  public categoriesResponse!: CategoriesResponse;
  public colors: string[] = [];
  public title: string = 'Categories';

  public constructor(
      private apiService: ApiService,
      public navService: NavService,
  ) {
  }

  public ngOnInit(): void {
    this.navService.title = 'Categories';

    this.apiService.getCategoryColors().subscribe({
      next: (next: CategoryColorsResponse) => {
        this.colors = next.colors;
      }
    });

    this.apiService.getCategories().subscribe({
      next: (next: CategoriesResponse) => {
        this.categoriesResponse = next;
      },
      error: (error) => {
      },
    })
  }

  public deleteCategory(id: string): void {
    this.apiService.deleteCategory(id).subscribe();

    this.categoriesResponse.categories = this.categoriesResponse.categories.filter(item => item.id !== id);
  }

  public changeColor(categoryId: string, color: string): void {
    this.apiService.changeCategoryColor(categoryId, color).subscribe();

    for (let i = 0; i < this.categoriesResponse.categories.length ; i++) {
      if (this.categoriesResponse.categories[i].id === categoryId) {
        this.categoriesResponse.categories[i].color = color;
      }
    }
  }
}
