import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {ApiService} from "../../api/api.service";
import {Observable} from "rxjs";
import {Injectable} from "@angular/core";
import * as moment from "moment";
import {OverviewResponse} from "../../api/response/overview-response";

@Injectable({
    providedIn: 'root'
})
export class DashboardPageResolver implements Resolve<OverviewResponse> {
    public constructor(
        private apiService: ApiService,
    ) {
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<OverviewResponse> {
        const dateString = route.paramMap.get('date');

        if (dateString) {
            const date = moment(dateString, 'YYYY-MM-DD')
            return this.apiService.getOverview(date);
        }

        return this.apiService.getOverview(moment());
    }
}
