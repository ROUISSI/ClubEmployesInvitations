import {
    Component,
    OnInit,
    ViewChild,
    ElementRef,
    OnDestroy
} from '@angular/core'; import { first } from 'rxjs/operators';

import { User } from '@app/_models';
import { UserService } from '@app/_services';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Observable, Subscription, of } from 'rxjs';

import { HttpClient } from '@angular/common/http';
import {
    debounceTime,
    distinctUntilChanged,
    map,
    flatMap,
    switchMap,
    startWith
} from 'rxjs/operators';
import { Router } from '@angular/router';


@Component({ templateUrl: 'home.component.html' })
export class HomeComponent {
    loadingInvSent = false;
    loadingInvReceived = false;
    invitationsSent!: any;
    invitationsReceived!: any;
    searchForm!: FormGroup;
    inputStream$: Subscription = new Subscription;
    users$!: Observable<User[]>;
    searchedUsers!: User[];
    inputChange$!: Observable<string>;
    @ViewChild('userSearch') userSearch!: ElementRef<HTMLElement>;

    constructor(private userService: UserService, private formBuilder: FormBuilder) {


    }

    ngOnInit() {
        this.createSearchForm();
        this.initrequests();
    }

    loadSentInvitations() {
        this.loadingInvSent = true;
        this.userService.getSentInvitations().pipe(first()).subscribe(response => {
            this.loadingInvSent = false;
            this.invitationsSent = response;
        });
    }


    loadreceivedInvitations() {
        this.loadingInvReceived = true;
        this.userService.getReceivedInvitations().pipe(first()).subscribe(response => {
            this.loadingInvReceived = false;
            this.invitationsReceived = response;
        });
    }


    sendInvitation(idUser: any) {
        this.userService.sendInvitation(idUser).pipe(first()).subscribe(response => {
            this.initrequests()
        });
    }

    acceptInvitation(idInvitation: any) {
        this.userService.acceptInvitation(idInvitation).pipe(first()).subscribe(response => {
            this.initrequests()
        });
    }

    refuseInvitation(idInvitation: any) {
        this.userService.refuseInvitation(idInvitation).pipe(first()).subscribe(response => {
            this.initrequests()
        });
    }

    removeInvitation(idInvitation: any) {
        this.userService.removeInvitation(idInvitation).pipe(first()).subscribe(response => {
            this.initrequests()
        });
    }

    cancelInvitation(idInvitation: any) {
        this.userService.cancelInvitation(idInvitation).pipe(first()).subscribe(response => {
            this.initrequests()
        });
    }

    createSearchForm() {
        this.searchForm = this.formBuilder.group({
            userSearch: ['']
        });
    }

    beginSearch(): void {
        if (this.searchForm) {
            this.inputChange$ = this.searchForm.controls.userSearch.valueChanges;
            this.searchedUsers = [];
            this.inputStream$ = this.inputChange$
                .pipe(
                    map((e: string) => e),
                    debounceTime(400),
                    distinctUntilChanged(),
                    startWith(''),
                )
                .subscribe(c => {
                    if (this.searchForm.controls.userSearch.value) {
                        this.userService.searchBackendUsers(this.searchForm.controls.userSearch.value).pipe(first()).subscribe(response => {
                            this.searchedUsers = response;
                        });
                    } else {
                        this.searchedUsers = [];
                    }
                });
        }

    }

    initrequests() {
        this.beginSearch()
        this.loadSentInvitations()
        this.loadreceivedInvitations()
    }

    ngOnDestroy(): void {
        this.inputStream$.unsubscribe();
    }



}