import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {ApiService} from "../../api/api.service";
import {Observable} from "rxjs";
import {Injectable} from "@angular/core";
import {HabitsResponse} from "../../api/response/habits-response";

@Injectable({
    providedIn: 'root'
})
export class HabitsPageResolver implements Resolve<HabitsResponse> {
    public constructor(
        private apiService: ApiService,
    ) {
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<HabitsResponse> {
        return this.apiService.getHabits();
    }
}
