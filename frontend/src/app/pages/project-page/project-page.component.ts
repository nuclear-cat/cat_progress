import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {ProjectResponse} from "../../api/response/project-response";

@Component({
    selector: 'app-project-page',
    templateUrl: './project-page.component.html',
    styleUrls: ['./project-page.component.scss']
})
export class ProjectPageComponent implements OnInit {
    public projectResponse!: ProjectResponse;

    public constructor(
        public route: ActivatedRoute,
    ) {
    }

    public ngOnInit(): void {
        this.route.data.subscribe(data => {
            this.projectResponse = data['project'];
        });
    }
}
