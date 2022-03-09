import {Component, OnInit} from '@angular/core';
import {ApiService} from "../../api/api.service";
import {MatDialog} from "@angular/material/dialog";
import {CropImageDialogComponent} from "../../dialogs/crop-image-dialog/crop-image-dialog.component";
import {ImageCroppedEvent} from "ngx-image-cropper";
import {ProfileResponse} from "../../api/response/profile-response";
import {FormBuilder, FormGroup} from "@angular/forms";
import {ActivatedRoute} from "@angular/router";
import {ProfileService} from "../../services/profile.service";

@Component({
    selector: 'app-edit-profile-page',
    templateUrl: './edit-profile-page.component.html',
    styleUrls: ['./edit-profile-page.component.scss']
})
export class EditProfilePageComponent implements OnInit {
    public title: string = 'Profile';
    public profileResponse: ProfileResponse | null = null;
    public editProfileForm!: FormGroup;
    public isImageLoading: boolean = false;

    public constructor(
        private apiService: ApiService,
        public dialog: MatDialog,
        private formBuilder: FormBuilder,
        public route: ActivatedRoute,
        public profileService: ProfileService,
    ) {
    }

    public ngOnInit(): void {
        this.route.data.subscribe(data => {
            this.profileResponse = data['profile'];

            this.editProfileForm = this.formBuilder.group({
                name: [this.profileResponse?.profile.name,],
            });
        });
    }

    public submitForm(): void {
        this.editProfileForm.disable();

        this.apiService.updateProfile({
            name: this.editProfileForm.get('name')?.value,
        }).subscribe(next => {
            this.editProfileForm.enable();

        });
    }

    public changeAvatar(event: Event): void {
        const target = event.target as HTMLInputElement;

        if (!target.files) {
            return;
        }

        const file = target.files[0];
        const dialogRef = this.dialog.open(CropImageDialogComponent, {
            data: {
                file: file,
            },
            maxWidth: '500px'
        });


        dialogRef.afterClosed().subscribe(result => {
            if (result === undefined) {
                target.value = '';
                return;
            }

            const event: ImageCroppedEvent = result;

            const formData = new FormData();
            formData.append('image', file, file.name);

            formData.append('crop_position[x1]', event.cropperPosition.x1.toString());
            formData.append('crop_position[x2]', event.cropperPosition.x2.toString());
            formData.append('crop_position[y1]', event.cropperPosition.y1.toString());
            formData.append('crop_position[y2]', event.cropperPosition.y2.toString());

            formData.append('image_position[x1]', event.imagePosition.x1.toString());
            formData.append('image_position[x2]', event.imagePosition.x2.toString());
            formData.append('image_position[y1]', event.imagePosition.y1.toString());
            formData.append('image_position[y2]', event.imagePosition.y2.toString());

            formData.append('width', event.width.toString());
            formData.append('height', event.height.toString());
            this.isImageLoading = true;

            this.apiService.changeProfileAvatar(formData).subscribe(next => {
                target.value = '';
                this.profileResponse!.profile.avatarSrc = next.avatarSrc;
                this.profileService.update();
                this.isImageLoading = false;
            });
        });
    }
}
