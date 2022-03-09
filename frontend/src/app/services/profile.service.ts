import {Injectable} from "@angular/core";
import {ApiService} from "../api/api.service";
import {ProfileResponse} from "../api/response/profile-response";
import {Observable} from "rxjs";

@Injectable({
    providedIn: 'root'
})
export class ProfileService {
    public profileResponse: Observable<ProfileResponse> = this.apiService.getProfile();

    public constructor(
        private apiService: ApiService,
    ) {
    }

    public update() {
        this.profileResponse = this.apiService.getProfile();
    }
}
