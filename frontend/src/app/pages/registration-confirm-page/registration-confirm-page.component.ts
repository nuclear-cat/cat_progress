import {Component, OnInit} from '@angular/core';
import {ApiService} from "../../api/api.service";
import {ActivatedRoute} from "@angular/router";

@Component({
    selector: 'app-registration-confirm-page',
    templateUrl: './registration-confirm-page.component.html',
    styleUrls: ['./registration-confirm-page.component.scss']
})
export class RegistrationConfirmPageComponent implements OnInit {
    public isLoading: boolean = true;
    public isSuccess: boolean = false;

    public constructor(
        private apiService: ApiService,
        private route: ActivatedRoute,
    ) {
    }

    public ngOnInit(): void {
        const requestId = this.route.snapshot.paramMap.get('requestId') as string;
        const requestToken = this.route.snapshot.paramMap.get('requestToken') as string;

        this.apiService.confirmRegistration(requestId, requestToken).subscribe(next => {
            this.isLoading = false;
            this.isSuccess = next.success;
        });
    }
}
