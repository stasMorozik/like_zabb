import { Either, left, right } from "@sweet-monads/either";
import { ajax, AjaxResponse } from 'rxjs/ajax';
import { catchError, of, Subject, Observable, switchMap } from 'rxjs';
import { AuthorizationUseCase } from '../use-cases/authorization';
import { authFetch } from './shared/auth.fetch';

export namespace AuthorizationAdapters {
  export class Emiter implements AuthorizationUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error, AuthorizationUseCase.Dtos.User>>
    ){}

    emit(either: Either<Error, AuthorizationUseCase.Dtos.User>): void {
      this._subject.next(either)
    }
  }

  export class Api implements AuthorizationUseCase.Ports.Api {
    fetch(): Observable<Either<Error, AuthorizationUseCase.Dtos.User>> {
      return authFetch<AuthorizationUseCase.Dtos.User>({
        url: '/api/users/authorize',
        method: 'GET'
      })
    }
  }
}
