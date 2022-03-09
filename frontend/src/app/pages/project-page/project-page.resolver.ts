import {ActivatedRouteSnapshot, Resolve, RouterStateSnapshot} from "@angular/router";
import {ApiService} from "../../api/api.service";
import {Observable} from "rxjs";
import {Injectable} from "@angular/core";
import {ProjectResponse} from "../../api/response/project-response";

@Injectable({
    providedIn: 'root'
})
export class ProjectPageResolver implements Resolve<ProjectResponse> {
    public constructor(
        private apiService: ApiService,
    ) {
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<ProjectResponse> {
        const projectId = route.paramMap.get('projectId') as string;

        return this.apiService.getProject(projectId);
    }
}
