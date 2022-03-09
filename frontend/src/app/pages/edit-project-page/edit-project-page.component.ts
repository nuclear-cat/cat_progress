import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, Validators} from "@angular/forms";
import {ApiService} from "../../api/api.service";
import {ActivatedRoute, Router} from "@angular/router";
import {ColorsResponse} from "../../api/response/colors-response";
import {ProjectResponse} from "../../api/response/project-response";

@Component({
    selector: 'app-edit-project-page',
    templateUrl: './edit-project-page.component.html',
    styleUrls: ['./edit-project-page.component.scss']
})
export class EditProjectPageComponent implements OnInit {

    public form!: FormGroup;
    public title: string = 'Edit project';
    public colors: string[] = [];
    public selectedColor!: string;

    public constructor(
        private apiService: ApiService,
        private formBuilder: FormBuilder,
        private route: ActivatedRoute,
        private router: Router,
    ) {
    }

    public ngOnInit(): void {
        const projectId = this.route.snapshot.paramMap.get('projectId') as string;

        this.apiService.getProject(projectId).subscribe({
            next: (next: ProjectResponse) => {

                this.form = this.formBuilder.group({
                    title: [next.project.title, [Validators.required,]],
                    description: [next.project.description, []],
                    color: [next.project.color, []],
                });

                this.selectedColor = next.project.color;
            },
        });

        this.apiService.getColors().subscribe({
            next: (next: ColorsResponse) => {
                this.colors = next.colors;
            }
        });

        this.form = this.formBuilder.group({
            title: [null, [Validators.required,]],
            description: [null, []],
            color: [null, []],
        });
    }

    public selectColor(color: string): void {
        this.selectedColor = color;
    }

    public submitForm(): void {
        const projectId = this.route.snapshot.paramMap.get('projectId') as string;

        this.apiService.updateProject(projectId, {
            title: this.form.get('title')?.value,
            description: this.form.get('description')?.value,
            color: this.selectedColor,
        }).subscribe({
            next: () => {
                this.router.navigate(['/projects']);
            }
        });
    }
}
