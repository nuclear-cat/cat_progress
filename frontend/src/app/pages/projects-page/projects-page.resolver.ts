import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {ApiService} from "../../api/api.service";
import {Observable} from "rxjs";
import {Injectable} from "@angular/core";
import {ProjectsResponse} from "../../api/response/projects-response";

@Injectable({
    providedIn: 'root'
})
export class ProjectsPageResolver implements Resolve<ProjectsResponse> {
    public constructor(
        private apiService: ApiService,
    ) {
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<ProjectsResponse> {
        return this.apiService.getProjects();
    }
}
