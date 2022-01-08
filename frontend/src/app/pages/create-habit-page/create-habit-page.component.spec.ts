import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CreateHabitPageComponent } from './create-habit-page.component';

describe('CreateHabitPageComponent', () => {
  let component: CreateHabitPageComponent;
  let fixture: ComponentFixture<CreateHabitPageComponent>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [ CreateHabitPageComponent ]
    })
    .compileComponents();
  });

  beforeEach(() => {
    fixture = TestBed.createComponent(CreateHabitPageComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
