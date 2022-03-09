import {Component, OnInit} from '@angular/core';
import {ApiService} from "../../api/api.service";
import {CategoriesResponse} from "../../api/response/categories-response";
import {ColorsResponse} from "../../api/response/colors-response";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-categories-page',
  templateUrl: './categories-page.component.html',
  styleUrls: ['./categories-page.component.scss']
})
export class CategoriesPageComponent implements OnInit {
  public displayedColumns: string[] = ['color', 'title', 'description', 'action',];
  public categoriesResponse!: CategoriesResponse;
  public colors: string[] = [];
  public title: string = 'Categories';

  public constructor(
      private apiService: ApiService,
      public route: ActivatedRoute,
  ) {
  }

  public ngOnInit(): void {
      this.route.data.subscribe(data => {
          this.categoriesResponse = data['categories'];
      });

      this.apiService.getColors().subscribe({
          next: (next: ColorsResponse) => {
              this.colors = next.colors;
          }
      });
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
