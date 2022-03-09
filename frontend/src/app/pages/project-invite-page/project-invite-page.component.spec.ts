import {ComponentFixture, TestBed} from '@angular/core/testing';

import {ProjectInvitePageComponent} from './project-invite-page.component';

describe('ProjectInvitePageComponent', () => {
    let component: ProjectInvitePageComponent;
    let fixture: ComponentFixture<ProjectInvitePageComponent>;

    beforeEach(async () => {
        await TestBed.configureTestingModule({
            declarations: [ProjectInvitePageComponent]
        })
            .compileComponents();
    });

    beforeEach(() => {
        fixture = TestBed.createComponent(ProjectInvitePageComponent);
        component = fixture.componentInstance;
        fixture.detectChanges();
    });

    it('should create', () => {
        expect(component).toBeTruthy();
    });
});
