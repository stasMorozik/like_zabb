import { Either, left, right } from "@sweet-monads/either";
import { ajax } from 'rxjs/ajax';
import { catchError, of, Subject, Observable, switchMap } from 'rxjs';
import { AuthenticationUseCase } from '../use-cases/authentication';
import { notAuthFetch } from './shared/not-auth.fetch';

export namespace AuthenticationAdapters {
  export class Emiter implements AuthenticationUseCase.Ports.Emiter {
    constructor(
      private readonly _subject: Subject<Either<Error, boolean>>
    ){}

    emit(either: Either<Error, boolean>): void {
      this._subject.next(either)
    }
  }

  export class Api implements AuthenticationUseCase.Ports.Api {
    fetch(dto: AuthenticationUseCase.Dtos.Data): Observable<Either<Error, boolean>> {
      return notAuthFetch<boolean>({
        url: '/api/users/authenticate',
        method: 'POST',
        body: dto
      })
    }
  }
}
