import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {ApiService} from "../../api/api.service";
import {Observable} from "rxjs";
import {Injectable} from "@angular/core";
import {ProfileResponse} from "../../api/response/profile-response";

@Injectable({
    providedIn: 'root'
})
export class EditProfilePageResolver implements Resolve<ProfileResponse> {
    public constructor(
        private apiService: ApiService,
    ) {
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<ProfileResponse> {
        return this.apiService.getProfile();
    }
}
