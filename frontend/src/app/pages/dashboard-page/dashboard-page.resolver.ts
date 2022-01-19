import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {ApiService} from "../../api/api.service";
import {Observable} from "rxjs";
import {Injectable} from "@angular/core";
import * as moment from "moment";
import {Moment} from "moment";
import {OverviewResponse} from "../../api/response/overview-response";

@Injectable({
    providedIn: 'root'
})
export class DashboardPageResolver implements Resolve<OverviewResponse> {
    public habitsDate: Moment = moment();

    public constructor(
        private apiService: ApiService,
    ) {
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<OverviewResponse> {
        return this.apiService.getOverview(this.habitsDate);
    }
}
