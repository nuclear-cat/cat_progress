import {Component, OnInit} from '@angular/core';
import {ApiService} from "../../api/api.service";
import {CategoriesResponse} from "../../api/response/categories-response";

@Component({
  selector: 'app-categories-page',
  templateUrl: './categories-page.component.html',
  styleUrls: ['./categories-page.component.scss']
})
export class CategoriesPageComponent implements OnInit {

  public categoriesResponse!: CategoriesResponse;

  public constructor(private apiService: ApiService) {
  }

  public ngOnInit(): void {
    this.apiService.getCategories().subscribe({
      next: (next: CategoriesResponse) => {
        this.categoriesResponse = next;
      },
      error: (error) => {
      },
    })
  }

  public deleteCategory(id: string) {
    this.apiService.deleteCategory(id).subscribe();

    this.categoriesResponse.categories = this.categoriesResponse.categories.filter(item => item.id !== id);
  }
}
