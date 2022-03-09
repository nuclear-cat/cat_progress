import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {ApiService} from "../../api/api.service";
import {ProjectPermissionsResponse} from "../../api/response/project-permissions-response";
import {ActivatedRoute} from "@angular/router";
import {forkJoin} from "rxjs";

@Component({
    selector: 'app-project-invite-page',
    templateUrl: './project-invite-page.component.html',
    styleUrls: ['./project-invite-page.component.scss']
})
export class ProjectInvitePageComponent implements OnInit {
    public title: string = 'Invite';
    public form!: FormGroup;
    public permissionsResponse!: ProjectPermissionsResponse;

    public constructor(
        private fb: FormBuilder,
        private apiService: ApiService,
        private route: ActivatedRoute,
    ) {
    }

    public ngOnInit(): void {
        const projectId = this.route.snapshot.paramMap.get('projectId') as string;

        const allowedProjectPermissions$ = this.apiService.getAllowedProjectPermissions();
        const project$ = this.apiService.getProject(projectId);

        forkJoin([
            allowedProjectPermissions$,
            project$,
        ]).subscribe(next => {
            this.permissionsResponse = next[0];

            this.form = this.fb.group({
                email: [null, [Validators.required, Validators.email]],
                permissions: this.fb.array(next[0].permissions.map((item) => {
                    return item.value;
                })),
            });
        });
    }

    public submitForm(): void {
        this.form.disable();
        const projectId: string = this.route.snapshot.paramMap.get('projectId') as string;
        const email: string = this.form.get('email')?.value;
        const permissions: string[] = this.form.get('permissions')?.value.filter((item: string | boolean) => item !== false);

        this.apiService.createInvite(projectId, {
            email: email,
            permissions: permissions,
        }).subscribe(next => {
            this.form.enable();
            this.form.reset();
        });
    }
}
