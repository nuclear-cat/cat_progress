import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {ApiService} from "../../api/api.service";
import {CalendarResponse} from "../../api/response/calendar-response";
import {Observable} from "rxjs";
import {Injectable} from "@angular/core";
import * as moment from "moment";
import {Moment} from "moment";

@Injectable({
    providedIn: 'root'
})
export class CalendarPageResolver implements Resolve<CalendarResponse> {
    public selectedDate: Moment = moment();

    public constructor(
        private apiService: ApiService,
    ) {
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<CalendarResponse> {
        return this.apiService.getCalendar(this.selectedDate);
    }
}
