import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {ApiService} from "../../api/api.service";
import {Observable} from "rxjs";
import {Injectable} from "@angular/core";
import {CategoriesResponse} from "../../api/response/categories-response";

@Injectable({
    providedIn: 'root'
})
export class CategoriesPageResolver implements Resolve<CategoriesResponse> {
    public constructor(
        private apiService: ApiService,
    ) {
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<CategoriesResponse> {
        return this.apiService.getCategories();
    }
}
