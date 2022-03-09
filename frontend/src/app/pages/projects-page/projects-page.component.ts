import {Component, OnInit} from '@angular/core';
import {ApiService} from "../../api/api.service";
import {ProjectsResponse} from "../../api/response/projects-response";
import {ActivatedRoute} from "@angular/router";
import {ColorsResponse} from "../../api/response/colors-response";

@Component({
    selector: 'app-projects-page',
    templateUrl: './projects-page.component.html',
    styleUrls: ['./projects-page.component.scss']
})
export class ProjectsPageComponent implements OnInit {
    public title = 'Projects';
    public projectsResponse!: ProjectsResponse;
    public displayedColumns: string[] = ['color', 'title', 'description', 'action',];
    public colors: string[] = [];

    public constructor(
        private apiService: ApiService,
        public route: ActivatedRoute,
    ) {
    }

    public ngOnInit(): void {
        this.route.data.subscribe(data => {
            this.projectsResponse = data['projects'];
        });

        this.apiService.getColors().subscribe({
            next: (next: ColorsResponse) => {
                this.colors = next.colors;
            }
        });
    }

    public deleteProject(id: string): void {
        this.apiService.deleteProject(id).subscribe();
        this.projectsResponse.projects = this.projectsResponse.projects.filter(item => item.id !== id);
    }

    public changeColor(projectId: string, color: string): void {
        this.apiService.changeProjectColor(projectId, color).subscribe();

        for (let i = 0; i < this.projectsResponse.projects.length; i++) {
            if (this.projectsResponse.projects[i].id === projectId) {
                this.projectsResponse.projects[i].color = color;
            }
        }
    }
}
