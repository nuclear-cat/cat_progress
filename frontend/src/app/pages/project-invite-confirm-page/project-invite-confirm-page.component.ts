import {Component, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {ApiService} from "../../api/api.service";

@Component({
    selector: 'app-project-invite-confirm-page',
    templateUrl: './project-invite-confirm-page.component.html',
    styleUrls: ['./project-invite-confirm-page.component.scss']
})
export class ProjectInviteConfirmPageComponent implements OnInit {
    public title: string = 'Invitation confirmation';

    public constructor(
        private route: ActivatedRoute,
        private apiService: ApiService,
    ) {
    }

    public ngOnInit(): void {
        const projectId = this.route.snapshot.paramMap.get('projectId') as string;
        const inviteId = this.route.snapshot.paramMap.get('inviteId') as string;
        const inviteToken = this.route.snapshot.paramMap.get('token') as string;

        this.apiService.confirmInvite(projectId, inviteId, inviteToken).subscribe(next => {
            console.log('-------------------');
            console.log(next);
        });
    }
}
